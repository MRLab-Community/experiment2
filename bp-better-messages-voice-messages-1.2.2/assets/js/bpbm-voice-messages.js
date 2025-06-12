/* eslint-disable */

function pad2(n) {
    n |= 0;
    return n < 10 ? `0${n}` : `${Math.min(n, 99)}`;
}

function inlineWorker() {
    // TODO(Kagami): Cache compiled module in IndexedDB? It works in FF
    // and Edge, see: https://github.com/mdn/webassembly-examples/issues/4
    // Though gzipped WASM module currently weights ~70kb so it should be
    // perfectly cached by the browser itself.
    function fetchAndInstantiate(url, imports) {
        if (!WebAssembly.instantiateStreaming) return fetchAndInstantiateFallback(url, imports);
        const req = fetch(url, {credentials: "same-origin"});
        return WebAssembly.instantiateStreaming(req, imports).catch(err => {
            // https://github.com/Kagami/vmsg/issues/11
            if (err.message && err.message.indexOf("Argument 0 must be provided and must be a Response") > 0) {
                return fetchAndInstantiateFallback(url, imports);
            } else {
                throw err;
            }
        });
    }

    function fetchAndInstantiateFallback(url, imports) {
        return new Promise((resolve, reject) => {
            const req = new XMLHttpRequest();
            req.open("GET", url);
            req.responseType = "arraybuffer";
            req.onload = () => {
                resolve(WebAssembly.instantiate(req.response, imports));
            };
            req.onerror = reject;
            req.send();
        });
    }

    // Must be in sync with emcc settings!
    const TOTAL_STACK = 5 * 1024 * 1024;
    const TOTAL_MEMORY = 16 * 1024 * 1024;
    const WASM_PAGE_SIZE = 64 * 1024;
    let memory = null;
    let dynamicTop = TOTAL_STACK;
    // TODO(Kagami): Grow memory?
    function sbrk(increment) {
        const oldDynamicTop = dynamicTop;
        dynamicTop += increment;
        return oldDynamicTop;
    }
    // TODO(Kagami): LAME calls exit(-1) on internal error. Would be nice
    // to provide custom DEBUGF/ERRORF for easier debugging. Currenty
    // those functions do nothing.
    function exit(status) {
        postMessage({type: "internal-error", data: status});
    }

    let FFI = null;
    let ref = null;
    let pcm_l = null;
    function vmsg_init(rate) {
        ref = FFI.vmsg_init(rate);
        if (!ref) return false;
        const pcm_l_ref = new Uint32Array(memory.buffer, ref, 1)[0];
        pcm_l = new Float32Array(memory.buffer, pcm_l_ref);
        return true;
    }
    function vmsg_encode(data) {
        pcm_l.set(data);
        return FFI.vmsg_encode(ref, data.length) >= 0;
    }
    function vmsg_flush() {
        if (FFI.vmsg_flush(ref) < 0) return null;
        const mp3_ref = new Uint32Array(memory.buffer, ref + 4, 1)[0];
        const size = new Uint32Array(memory.buffer, ref + 8, 1)[0];
        const mp3 = new Uint8Array(memory.buffer, mp3_ref, size);
        const blob = new Blob([mp3], {type: "audio/mpeg"});
        FFI.vmsg_free(ref);
        ref = null;
        pcm_l = null;
        return blob;
    }

    // https://github.com/brion/min-wasm-fail
    function testSafariWebAssemblyBug() {
        const bin = new Uint8Array([0,97,115,109,1,0,0,0,1,6,1,96,1,127,1,127,3,2,1,0,5,3,1,0,1,7,8,1,4,116,101,115,116,0,0,10,16,1,14,0,32,0,65,1,54,2,0,32,0,40,2,0,11]);
        const mod = new WebAssembly.Module(bin);
        const inst = new WebAssembly.Instance(mod, {});
        // test storing to and loading from a non-zero location via a parameter.
        // Safari on iOS 11.2.5 returns 0 unexpectedly at non-zero locations
        return (inst.exports.test(4) !== 0);
    }

    onmessage = (e) => {
        const msg = e.data;
        switch (msg.type) {
            case "init":
                const { wasmURL, shimURL } = msg.data;
                Promise.resolve().then(() => {
                    if (self.WebAssembly && !testSafariWebAssemblyBug()) {
                        delete self.WebAssembly;
                    }
                    if (!self.WebAssembly) {
                        importScripts(shimURL);
                    }
                    memory = new WebAssembly.Memory({
                        initial: TOTAL_MEMORY / WASM_PAGE_SIZE,
                        maximum: TOTAL_MEMORY / WASM_PAGE_SIZE,
                    });
                    return {
                        memory: memory,
                        pow: Math.pow,
                        exit: exit,
                        powf: Math.pow,
                        exp: Math.exp,
                        sqrtf: Math.sqrt,
                        cos: Math.cos,
                        log: Math.log,
                        sin: Math.sin,
                        sbrk: sbrk,
                    };
                }).then(Runtime => {
                    return fetchAndInstantiate(wasmURL, {env: Runtime})
                }).then(wasm => {
                    FFI = wasm.instance.exports;
                    postMessage({type: "init", data: null});
                }).catch(err => {
                    postMessage({type: "init-error", data: err.toString()});
                });
                break;
            case "start":
                if (!vmsg_init(msg.data)) return postMessage({type: "error", data: "vmsg_init"});
                break;
            case "data":
                if (!vmsg_encode(msg.data)) return postMessage({type: "error", data: "vmsg_encode"});
                break;
            case "stop":
                const blob = vmsg_flush();
                if (!blob) return postMessage({type: "error", data: "vmsg_flush"});
                postMessage({type: "stop", data: blob});
                break;
        }
    };
}

class Recorder {
    constructor(opts = {}, onStop) {
        // Can't use relative URL in blob worker, see:
        // https://stackoverflow.com/a/22582695
        this.wasmURL = new URL(opts.wasmURL || "/static/js/vmsg.wasm", location).href;
        this.shimURL = new URL(opts.shimURL || "/static/js/wasm-polyfill.js", location).href;
        this.onStop = onStop;
        this.pitch = opts.pitch || 0;
        this.audioCtx = null;
        this.gainNode = null;
        this.pitchFX = null;
        this.encNode = null;
        this.worker = null;
        this.workerURL = null;
        this.blob = null;
        this.blobURL = null;
        this.resolve = null;
        this.reject = null;
        this.stream = null;
        Object.seal(this);
    }

    close() {
        if (this.encNode) this.encNode.disconnect();
        if (this.encNode) this.encNode.onaudioprocess = null;
        if (this.audioCtx) this.audioCtx.close();
        if (this.worker) this.worker.terminate();
        if (this.workerURL) URL.revokeObjectURL(this.workerURL);
        if (this.blobURL) URL.revokeObjectURL(this.blobURL);
    }

    initAudio() {
        const getUserMedia = navigator.mediaDevices && navigator.mediaDevices.getUserMedia
            ? function(constraints) {
                return navigator.mediaDevices.getUserMedia(constraints);
            }
            : function(constraints) {
                const oldGetUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
                if (!oldGetUserMedia) {
                    return Promise.reject(new Error("getUserMedia is not implemented in this browser"));
                }
                return new Promise(function(resolve, reject) {
                    oldGetUserMedia.call(navigator, constraints, resolve, reject);
                });
            };

        return getUserMedia({audio: true}).then(stream => {
            const audioCtx = this.audioCtx = new (window.AudioContext
                || window.webkitAudioContext)();

            this.stream = stream;

            const sourceNode = audioCtx.createMediaStreamSource(stream);
            const gainNode = this.gainNode = (audioCtx.createGain
                || audioCtx.createGainNode).call(audioCtx);
            gainNode.gain.value = 1;
            sourceNode.connect(gainNode);

            const pitchFX = this.pitchFX = new Jungle(audioCtx);
            pitchFX.setPitchOffset(this.pitch);

            const encNode = this.encNode = (audioCtx.createScriptProcessor
                || audioCtx.createJavaScriptNode).call(audioCtx, 0, 1, 1);
            pitchFX.output.connect(encNode);

            gainNode.connect(this.pitch === 0 ? encNode : pitchFX.input);
        });
    }

    initWorker() {
        if (!this.audioCtx) throw new Error("missing audio initialization");
        // https://stackoverflow.com/a/19201292
        const blob = new Blob(
            ["(", inlineWorker.toString(), ")()"],
            {type: "application/javascript"});
        const workerURL = this.workerURL = URL.createObjectURL(blob);
        const worker = this.worker = new Worker(workerURL);
        const { wasmURL, shimURL } = this;
        worker.postMessage({type: "init", data: {wasmURL, shimURL}});
        return new Promise((resolve, reject) => {
            worker.onmessage = (e) => {
                const msg = e.data;
                switch (msg.type) {
                    case "init":
                        resolve();
                        break;
                    case "init-error":
                        reject(new Error(msg.data));
                        break;
                    // TODO(Kagami): Error handling.
                    case "error":
                    case "internal-error":
                        console.error("Worker error:", msg.data);
                        if (this.reject) this.reject(msg.data);
                        break;
                    case "stop":
                        this.blob = msg.data;
                        this.blobURL = URL.createObjectURL(msg.data);
                        if (this.onStop) this.onStop();
                        if (this.resolve) this.resolve(this.blob);
                        break;
                }
            }
        });
    }

    startRecording() {
        if (!this.audioCtx) throw new Error("missing audio initialization");
        if (!this.worker) throw new Error("missing worker initialization");
        this.blob = null;
        if (this.blobURL) URL.revokeObjectURL(this.blobURL);
        this.blobURL = null;
        this.worker.postMessage({type: "start", data: this.audioCtx.sampleRate});
        this.encNode.onaudioprocess = (e) => {
            const samples = e.inputBuffer.getChannelData(0);
            this.worker.postMessage({type: "data", data: samples});
        };
        this.encNode.connect(this.audioCtx.destination);
    }

    stopRecording() {
        const resultP = new Promise((resolve, reject) => {
            if (this.encNode) {
                this.encNode.disconnect();
                this.encNode.onaudioprocess = null;
            }

            this.resolve = resolve;
            this.reject = reject;
        });

        if (this.worker) {
            this.worker.postMessage({type: "stop", data: null});
        } else {
            return Promise.resolve(this.blob);
        }

        return resultP;
    }
}

class Form {
    constructor(opts = {}, resolve, reject) {
        this.recorder = new Recorder(opts, this.onStop.bind(this));
        this.recorderEl = opts.recorder;
        this.resolve = resolve;
        this.reject = reject;
        this.backdrop = null;
        this.popup = null;
        this.recordBtn = null;
        this.stopBtn = null;
        this.timer = null;
        this.audio = null;
        this.saveBtn = null;
        this.send = null;
        this.tid = 0;
        this.start = 0;
        Object.seal(this);

        this.recorder.initAudio()
            .then(() => this.showRecorder())
            .then(() => this.recorder.initWorker())
            .then(() => this.startRecording())
            .catch((err) => this.drawError(err));
    }

    showRecorder(){
        var form = this;
        this.recorderEl.addClass('active');
        const audio = this.audio = new Audio();
        audio.autoplay = true;

        this.recorderEl.find('.bpbm-record-voice-delete').on('click', function(){
            form.send = false;
            form.recorderEl.removeClass('active');
            form.stopRecording();
        });

        this.recorderEl.find('.bpbm-record-voice-send').on('click', function(){
            form.send = true;
            form.recorderEl.removeClass('active');
            form.stopRecording();
        });

    }

    drawTime(msecs) {
        const secs = Math.round(msecs / 1000);
        this.recorderEl.find('.bpbm-record-voice-time').text( pad2(secs / 60) + ":" + pad2(secs % 60) );
    }

    drawError(err) {
        BBPMShowError(err);
    }

    finish(blob) {
        if (this.audio) this.audio.pause();
        if (this.tid) clearTimeout(this.tid);
        this.recorder.close();
        if (blob) {
            this.resolve(blob);
        } else {
            this.reject(new Error("No record made"));
        }
    }

    onStop() {
        this.recorder.stream.getTracks().forEach(function(track) {
            track.stop();
        });

        if( this.send ) {
            this.finish(this.recorder.blob);
        } else {
            this.finish(false);
        }
    }

    startRecording() {
        this.audio.pause();
        this.start = Date.now();
        this.updateTime();
        this.recorder.startRecording();
    }

    stopRecording() {
        clearTimeout(this.tid);
        this.tid = 0;
        this.recorder.stopRecording();
    }

    updateTime() {
        // NOTE(Kagami): We can do this in `onaudioprocess` but that would
        // run too often and create unnecessary DOM updates.
        this.drawTime(Date.now() - this.start);
        this.tid = setTimeout(() => this.updateTime(), 300);
    }
}

let shown = false;

/**
 * Record a new voice message.
 *
 * @param {Object=} opts - Options
 * @param {string=} opts.wasmURL - URL of the module
 *                                 ("/static/js/vmsg.wasm" by default)
 * @param {string=} opts.shimURL - URL of the WebAssembly polyfill
 *                                 ("/static/js/wasm-polyfill.js" by default)
 * @param {number=} opts.pitch - Initial pitch shift ([-1, 1], 0 by default)
 * @return {Promise.<Blob>} A promise that contains recorded blob when fulfilled.
 */
function record(opts) {
    return new Promise((resolve, reject) => {
        if (shown) throw new Error("Record form is already opened");
        shown = true;
        new Form(opts, resolve, reject);
        // Use `.finally` once it's available in Safari and Edge.
    }).then(result => {
        shown = false;
        return result;
    }, err => {
        shown = false;
        throw err;
    });
}

/**
 * All available public items.
 */

// Borrowed from and slightly modified:
// https://github.com/cwilso/Audio-Input-Effects/blob/master/js/jungle.js

// Copyright 2012, Google Inc.
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are
// met:
//
//     * Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer.
//     * Redistributions in binary form must reproduce the above
// copyright notice, this list of conditions and the following disclaimer
// in the documentation and/or other materials provided with the
// distribution.
//     * Neither the name of Google Inc. nor the names of its
// contributors may be used to endorse or promote products derived from
// this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

const delayTime = 0.100;
const fadeTime = 0.050;
const bufferTime = 0.100;

function createFadeBuffer(context, activeTime, fadeTime) {
    var length1 = activeTime * context.sampleRate;
    var length2 = (activeTime - 2*fadeTime) * context.sampleRate;
    var length = length1 + length2;
    var buffer = context.createBuffer(1, length, context.sampleRate);
    var p = buffer.getChannelData(0);

    var fadeLength = fadeTime * context.sampleRate;

    var fadeIndex1 = fadeLength;
    var fadeIndex2 = length1 - fadeLength;

    // 1st part of cycle
    for (var i = 0; i < length1; ++i) {
        var value;

        if (i < fadeIndex1) {
            value = Math.sqrt(i / fadeLength);
        } else if (i >= fadeIndex2) {
            value = Math.sqrt(1 - (i - fadeIndex2) / fadeLength);
        } else {
            value = 1;
        }

        p[i] = value;
    }

    // 2nd part
    for (var i = length1; i < length; ++i) {
        p[i] = 0;
    }

    return buffer;
}

function createDelayTimeBuffer(context, activeTime, fadeTime, shiftUp) {
    var length1 = activeTime * context.sampleRate;
    var length2 = (activeTime - 2*fadeTime) * context.sampleRate;
    var length = length1 + length2;
    var buffer = context.createBuffer(1, length, context.sampleRate);
    var p = buffer.getChannelData(0);

    // 1st part of cycle
    for (var i = 0; i < length1; ++i) {
        if (shiftUp)
            // This line does shift-up transpose
            p[i] = (length1-i)/length;
        else
            // This line does shift-down transpose
            p[i] = i / length1;
    }

    // 2nd part
    for (var i = length1; i < length; ++i) {
        p[i] = 0;
    }

    return buffer;
}

function Jungle(context) {
    this.context = context;
    // Create nodes for the input and output of this "module".
    var input = (context.createGain || context.createGainNode).call(context);
    var output = (context.createGain || context.createGainNode).call(context);
    this.input = input;
    this.output = output;

    // Delay modulation.
    var mod1 = context.createBufferSource();
    var mod2 = context.createBufferSource();
    var mod3 = context.createBufferSource();
    var mod4 = context.createBufferSource();
    this.shiftDownBuffer = createDelayTimeBuffer(context, bufferTime, fadeTime, false);
    this.shiftUpBuffer = createDelayTimeBuffer(context, bufferTime, fadeTime, true);
    mod1.buffer = this.shiftDownBuffer;
    mod2.buffer = this.shiftDownBuffer;
    mod3.buffer = this.shiftUpBuffer;
    mod4.buffer = this.shiftUpBuffer;
    mod1.loop = true;
    mod2.loop = true;
    mod3.loop = true;
    mod4.loop = true;

    // for switching between oct-up and oct-down
    var mod1Gain = (context.createGain || context.createGainNode).call(context);
    var mod2Gain = (context.createGain || context.createGainNode).call(context);
    var mod3Gain = (context.createGain || context.createGainNode).call(context);
    mod3Gain.gain.value = 0;
    var mod4Gain = (context.createGain || context.createGainNode).call(context);
    mod4Gain.gain.value = 0;

    mod1.connect(mod1Gain);
    mod2.connect(mod2Gain);
    mod3.connect(mod3Gain);
    mod4.connect(mod4Gain);

    // Delay amount for changing pitch.
    var modGain1 = (context.createGain || context.createGainNode).call(context);
    var modGain2 = (context.createGain || context.createGainNode).call(context);

    var delay1 = (context.createDelay || context.createDelayNode).call(context);
    var delay2 = (context.createDelay || context.createDelayNode).call(context);
    mod1Gain.connect(modGain1);
    mod2Gain.connect(modGain2);
    mod3Gain.connect(modGain1);
    mod4Gain.connect(modGain2);
    modGain1.connect(delay1.delayTime);
    modGain2.connect(delay2.delayTime);

    // Crossfading.
    var fade1 = context.createBufferSource();
    var fade2 = context.createBufferSource();
    var fadeBuffer = createFadeBuffer(context, bufferTime, fadeTime);
    fade1.buffer = fadeBuffer
    fade2.buffer = fadeBuffer;
    fade1.loop = true;
    fade2.loop = true;

    var mix1 = (context.createGain || context.createGainNode).call(context);
    var mix2 = (context.createGain || context.createGainNode).call(context);
    mix1.gain.value = 0;
    mix2.gain.value = 0;

    fade1.connect(mix1.gain);
    fade2.connect(mix2.gain);

    // Connect processing graph.
    input.connect(delay1);
    input.connect(delay2);
    delay1.connect(mix1);
    delay2.connect(mix2);
    mix1.connect(output);
    mix2.connect(output);

    // Start
    var t = context.currentTime + 0.050;
    var t2 = t + bufferTime - fadeTime;
    mod1.start(t);
    mod2.start(t2);
    mod3.start(t);
    mod4.start(t2);
    fade1.start(t);
    fade2.start(t2);

    this.mod1 = mod1;
    this.mod2 = mod2;
    this.mod1Gain = mod1Gain;
    this.mod2Gain = mod2Gain;
    this.mod3Gain = mod3Gain;
    this.mod4Gain = mod4Gain;
    this.modGain1 = modGain1;
    this.modGain2 = modGain2;
    this.fade1 = fade1;
    this.fade2 = fade2;
    this.mix1 = mix1;
    this.mix2 = mix2;
    this.delay1 = delay1;
    this.delay2 = delay2;

    this.setDelay(delayTime);
}

Jungle.prototype.setDelay = function(delayTime) {
    this.modGain1.gain.setTargetAtTime(0.5*delayTime, 0, 0.010);
    this.modGain2.gain.setTargetAtTime(0.5*delayTime, 0, 0.010);
};

Jungle.prototype.setPitchOffset = function(mult) {
    if (mult>0) { // pitch up
        this.mod1Gain.gain.value = 0;
        this.mod2Gain.gain.value = 0;
        this.mod3Gain.gain.value = 1;
        this.mod4Gain.gain.value = 1;
    } else { // pitch down
        this.mod1Gain.gain.value = 1;
        this.mod2Gain.gain.value = 1;
        this.mod3Gain.gain.value = 0;
        this.mod4Gain.gain.value = 0;
    }
    this.setDelay(delayTime*Math.abs(mult));
};

(function ($) {

    var currentlyPlaying = false;

    function initVoiceMessages() {
        BPBMJQ('.bp-messages-wrap .reply').each(function () {
            switchMediaButton(BPBMJQ(this));
        });

        BPBMJQ('.bpbm-waveform:not(.initialized)').each(function () {
            var waveform = BPBMJQ(this);
            var wrap = waveform.closest('.bp-messages-wrap');
            var stack = waveform.closest('.messages-stack');
            var voiceMessage = waveform.closest('.bpbm-voice-message');
            var playBtn = voiceMessage.find('.bpbm-voice-message-play');

            waveform.addClass('initialized');
            waveform.closest('.message-content').addClass('has-iframe');

            var cursorColor = '#333';
            var progressColor = '#555';
            var waveColor = '#999';

            if (wrap.hasClass('bpbm-template-modern') && stack.hasClass('incoming')) {
                cursorColor = '#fff';
                progressColor = '#fff';
                waveColor = '#e3e3e3';
            }

            var wavesurfer = WaveSurfer.create({
                container        : this,
                height           : 30,
                'cursorColor'    : cursorColor,
                'progressColor'  : progressColor,
                'waveColor'      : waveColor,
                responsive       : true,
                ignoreSilenceMode: true
            });

            voiceMessage.find('.bpbm-voice-message-play').click(function () {
                wavesurfer.playPause();
            });

            wavesurfer.on('pause', function () {
                currentlyPlaying = false;
                playBtn.html('<i class="far fa-play-circle"></i>');
            });

            wavesurfer.on('play', function () {
                if( currentlyPlaying !== false ){
                    currentlyPlaying.stop();
                }

                currentlyPlaying = wavesurfer;
                playBtn.html('<i class="far fa-pause-circle"></i>');
            });

            wavesurfer.load(voiceMessage.attr('data-message'));

        });
    }

    BPBMJQ(document).on('bp-better-messages-message-render-end bp-better-messages-message-sent-end', function (event) {
        initVoiceMessages();
    });

    BPBMJQ(document).on('bp-better-messages-reinit-end', function (event) {
        if( currentlyPlaying !== false ){
            console.log(currentlyPlaying.mediaContainer);
        }
        initVoiceMessages();
    });

    BPBMJQ(document).on('click', '.bp-emojionearea i.emojibtn', function () {
        var editor = BPBMJQ(this).closest('.bp-emojionearea');
        var reply = editor.closest('.reply');
        switchMediaButton(reply);
    });

    BPBMJQ(document).on('change keyup beforepaste paste', '.bp-messages-wrap .reply .bp-emojionearea-editor, .bp-messages-wrap .reply textarea', function () {
        var editor = BPBMJQ(this);
        var reply = editor.closest('.reply');

        switchMediaButton(reply);
    });

    function switchMediaButton(reply) {
        $('.bp-better-messages-mini .chats .chat:not(.voice-enabled)').addClass('voice-enabled');
        var editor        = reply.find('.bp-emojionearea-editor:visible');
        var textEditor    = reply.find('textarea[name="message"]:visible');

        var mobileEditor  = reply.find('.bm-mobile-area');

        var showVoiceButton = true;

        var content = '';

        if (editor.length > 0 && typeof editor.html() !== 'undefined') {
            content = editor.html().replace('<br>', '').replace('<p></p>', '').trim();
        } else if(mobileEditor.length > 0 && typeof mobileEditor.html() !== 'undefined') {
            content = mobileEditor.html().replace('<br>', '').replace('<p></p>', '').trim();
        }else if (textEditor.length > 0) {
            content = textEditor.val().trim();
        }

        var filesBtn = reply.find('.upload-btn .count');
        if( filesBtn.length > 0 ){
            if( ! filesBtn.hasClass('count-0') ){
                showVoiceButton = false;
            }
        }

        if( content !== '' ){
            showVoiceButton = false;
        }

        var voice_button = reply.find('.bpbm-record-voice-button');

        if (showVoiceButton) {
            voice_button.addClass('active');
        } else {
            voice_button.removeClass('active');
        }
    }

    BPBMJQ(document).on('click', '.bpbm-record-voice-button', function (event) {
        var button = BPBMJQ(this);
        var reply = button.closest('.reply');

        reply.addClass('voice-message-recording');
        var recorder = reply.find('.bpbm-record-voice-timer');

        var thread_id = reply.find('input[name="thread_id"]').val();
        var nonce = reply.find('input[name="_wpnonce"]').val();

        record({
                recorder: recorder,
                wasmURL: BP_Messages_Voice_Messages['url']
            }
        ).then(function(blob) {
            reply.removeClass('voice-message-recording');
            var fd = new FormData();
            fd.append('thread_id', thread_id);
            fd.append('_wpnonce', nonce);
            fd.append('data', blob);

            BPBMJQ.ajax({
                type: 'POST',
                url: BP_Messages['ajaxUrl'] + '?action=bp_better_messages_send_voice_message',
                data: fd,
                processData: false,
                contentType: false
            }).done(function (data) {
                $(document).trigger("bp-better-messages-refresh-thread");
            });
        }, function(error){
            reply.removeClass('voice-message-recording');
        });
    });
})(BPBMJQ)