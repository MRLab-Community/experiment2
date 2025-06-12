<?php

/*
    @wordpress-plugin
    Plugin Name: Better Messages - Voice Messages
    Plugin URI: https://www.wordplus.org
    Description: Voice Messages addon for BuddyPress and WordPress
    Version: 1.2.2
    Update URI: https://api.freemius.com
    Author: WordPlus
    Author URI: https://www.wordplus.org
    License: GPL2
    Text Domain: bp-better-messages-voice-messages
    Domain Path: /languages
*/
if ( !class_exists( 'BP_Better_Messages_Voice_Messages' ) && !function_exists( 'bbmvm_fs' ) ) {
    class BP_Better_Messages_Voice_Messages {
        public $realtime;

        public $version = '1.2.2';

        public $path;

        public $url;

        public static function instance() {
            // Store the instance locally to avoid private static replication
            static $instance = null;
            // Only run these methods if they haven't been run previously
            if ( null === $instance ) {
                $instance = new BP_Better_Messages_Voice_Messages();
                $instance->setup_vars();
                if ( bbmvm_fs()->is_paying() ) {
                    if ( function_exists( 'Better_Messages' ) ) {
                        $instance->setup_actions();
                    }
                } else {
                    add_action( 'admin_notices', array($instance, 'admin_notice') );
                }
            }
            // Always return the instance
            return $instance;
            // The last metroid is in captivity. The galaxy is at peace.
        }

        public function admin_notice() {
            $new_license = bbmvm_fs()->get_upgrade_url();
            $activate_license = bbmvm_fs()->get_activation_url();
            echo '<div class="notice notice-error">';
            echo '<p><b>BP Better Messages Voice Messages</b> missing license. To get new license press <a href="' . $new_license . '">here</a>. To activate existing license press <a href="' . $activate_license . '">here</a>.</p>';
            echo '</div>';
        }

        public function setup_vars() {
            $this->path = plugin_dir_path( __FILE__ );
            $this->url = plugin_dir_url( __FILE__ );
        }

        public function setup_actions() {
            if ( version_compare( Better_Messages()->version, '2.6.0' ) >= 0 ) {
                add_action(
                    'better_messages_register_script_dependencies',
                    array($this, 'load_script_dependencies'),
                    10,
                    1
                );
            } else {
                if ( version_compare( Better_Messages()->version, '2.3.6' ) >= 0 ) {
                    // Check if Better Messages 2.3.6 or higher
                    add_action( 'wp_enqueue_scripts', array($this, 'load_scripts_new') );
                } else {
                    add_action( 'wp_enqueue_scripts', array($this, 'load_scripts') );
                }
            }
            add_action( 'bp_better_messages_before_reply_send', array($this, 'add_voice_message_button') );
            add_filter(
                'bp_better_messages_after_format_message',
                array($this, 'after_format_message'),
                9,
                4
            );
            add_action( 'rest_api_init', array($this, 'rest_api_init') );
            add_filter(
                'better_messages_rest_message_meta',
                array($this, 'files_message_meta'),
                11,
                4
            );
            add_action( 'wp_ajax_bp_better_messages_send_voice_message', array($this, 'send_voice_message') );
            add_action( 'wp_ajax_better_messages_wasm_fallback', array($this, 'wasm_fallback') );
            add_action( 'wp_ajax_nopriv_better_messages_wasm_new_fallback', array($this, 'wasm_new_fallback') );
            add_action( 'wp_ajax_better_messages_wasm_new_fallback', array($this, 'wasm_new_fallback') );
        }

        public function wasm_fallback() {
            header( 'Content-Type: application/wasm' );
            header( 'Content-Disposition: attachment; filename="bpbm-vmsg.wasm"' );
            echo file_get_contents( $this->path . 'assets/js/bpbm-vmsg.wasm' );
            exit;
        }

        public function wasm_new_fallback() {
            header( 'Content-Type: application/wasm' );
            header( 'Content-Disposition: attachment; filename="encoder.wasm"' );
            header( 'Access-Control-Allow-Origin: *' );
            echo file_get_contents( $this->path . 'assets/js/encoder.wasm' );
            exit;
        }

        public function rest_api_init() {
            if ( function_exists( 'Better_Messages_Rest_Api' ) ) {
                register_rest_route( 'better-messages/v1', '/thread/(?P<id>\\d+)/sendVoice', array(
                    'methods'             => 'POST',
                    'callback'            => array($this, 'send_voice_message_v2'),
                    'permission_callback' => array(Better_Messages_Rest_Api(), 'can_reply'),
                    'args'                => array(
                        'id' => array(
                            'validate_callback' => function ( $param, $request, $key ) {
                                return is_numeric( $param );
                            },
                        ),
                    ),
                ) );
            }
        }

        public function send_voice_message_v2( WP_REST_Request $request ) {
            $thread_id = $request->get_param( 'id' );
            $meta = $request->get_param( 'meta' );
            add_filter( 'upload_dir', array($this, 'upload_dir') );
            add_filter(
                'upload_mimes',
                array($this, 'upload_mimes'),
                10,
                2
            );
            $errors = array();
            $message = '<!-- BPBM-VOICE-MESSAGE -->';
            $args = array(
                'content'    => $message,
                'thread_id'  => $thread_id,
                'error_type' => 'wp_error',
                'return'     => 'message_id',
                'meta_data'  => [],
            );
            if ( $meta && is_string( $meta ) ) {
                $meta = json_decode( $meta, true );
                if ( is_array( $meta ) && count( $meta ) > 0 ) {
                    $args['meta_data'] = $meta;
                }
            }
            // These files need to be included as dependencies when on the front end.
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            $_FILES['data']['name'] = $this->random_string( 20 ) . '.mp3';
            add_filter( 'intermediate_image_sizes', '__return_empty_array' );
            $attachment_id = media_handle_upload( 'data', 0 );
            remove_filter( 'intermediate_image_sizes', '__return_empty_array' );
            add_post_meta(
                $attachment_id,
                'bp-better-messages-attachment',
                true,
                true
            );
            add_post_meta(
                $attachment_id,
                'bp-better-messages-thread-id',
                $thread_id,
                true
            );
            add_post_meta(
                $attachment_id,
                'bp-better-messages-upload-time',
                time(),
                true
            );
            do_action_ref_array( 'bp_better_messages_before_message_send', array(&$args, &$errors) );
            if ( empty( $errors ) ) {
                $args['meta_data']['bpbm_voice_messages'] = $attachment_id;
                $message_id = Better_Messages()->functions->new_message( $args );
                add_post_meta(
                    $attachment_id,
                    'bp-better-messages-message-id',
                    $message_id,
                    true
                );
                Better_Messages()->functions->messages_mark_thread_read( $thread_id );
            }
            remove_filter( 'upload_dir', array($this, 'upload_dir') );
            remove_filter( 'upload_mimes', array($this, 'upload_mimes'), 10 );
        }

        public function files_message_meta(
            $meta,
            $message_id,
            $thread_id,
            $content
        ) {
            $is_voice_message = strpos( $content, '<!-- BPBM-VOICE-MESSAGE -->', 0 ) === 0;
            if ( !$is_voice_message ) {
                return $meta;
            }
            if ( !isset( $meta['files'] ) ) {
                $meta['files'] = [];
            }
            $attachment_id = Better_Messages()->functions->get_message_meta( $message_id, 'bpbm_voice_messages', true );
            $attachment = get_post( $attachment_id );
            if ( !$attachment ) {
                return $meta;
            }
            $url = wp_get_attachment_url( $attachment_id );
            $file = [
                'id'       => $attachment->ID,
                'thumb'    => wp_get_attachment_image_url( $attachment->ID, array(200, 200) ),
                'url'      => $url,
                'mimeType' => $attachment->post_mime_type,
            ];
            $path = get_attached_file( $attachment_id );
            $size = filesize( $path );
            $ext = pathinfo( $url, PATHINFO_EXTENSION );
            $name = get_post_meta( $attachment_id, 'bp-better-messages-original-name', true );
            if ( empty( $name ) ) {
                $name = wp_basename( $url );
            }
            $file['name'] = $name;
            $file['size'] = $size;
            $file['ext'] = $ext;
            $meta['files'][] = $file;
            return $meta;
        }

        public function send_voice_message() {
            add_filter( 'upload_dir', array($this, 'upload_dir') );
            add_filter(
                'upload_mimes',
                array($this, 'upload_mimes'),
                10,
                2
            );
            $thread_id = intval( $_POST['thread_id'] );
            $errors = array();
            if ( !wp_verify_nonce( $_POST['_wpnonce'], 'sendMessage_' . $thread_id ) ) {
                $errors[] = __( 'Security error while sending message', 'bp-better-messages-voice-messages' );
            } else {
                $message = '<!-- BPBM-VOICE-MESSAGE -->';
                $args = array(
                    'content'    => $message,
                    'thread_id'  => $thread_id,
                    'error_type' => 'wp_error',
                );
                if ( !apply_filters(
                    'bp_better_messages_can_send_message',
                    BP_Messages_Thread::check_access( $thread_id ),
                    get_current_user_id(),
                    $thread_id
                ) ) {
                    $errors[] = __( 'You can`t reply to this thread.', 'bp-better-messages-voice-messages' );
                }
                if ( count( $errors ) === 0 ) {
                    // These files need to be included as dependencies when on the front end.
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';
                    $_FILES['data']['name'] = $this->random_string( 20 ) . '.mp3';
                    global $bpbm_uploaded_voice;
                    add_filter( 'intermediate_image_sizes', '__return_empty_array' );
                    $attachment_id = media_handle_upload( 'data', 0 );
                    remove_filter( 'intermediate_image_sizes', '__return_empty_array' );
                    add_post_meta(
                        $attachment_id,
                        'bp-better-messages-attachment',
                        true,
                        true
                    );
                    add_post_meta(
                        $attachment_id,
                        'bp-better-messages-thread-id',
                        $thread_id,
                        true
                    );
                    add_post_meta(
                        $attachment_id,
                        'bp-better-messages-upload-time',
                        time(),
                        true
                    );
                    $bpbm_uploaded_voice = $attachment_id;
                    do_action_ref_array( 'bp_better_messages_before_message_send', array(&$args, &$errors) );
                    if ( empty( $errors ) ) {
                        add_action( 'messages_message_sent', array($this, 'on_message_sent'), 1 );
                        messages_new_message( $args );
                        remove_action( 'messages_message_sent', array($this, 'on_message_sent'), 1 );
                        messages_mark_thread_read( $thread_id );
                    }
                }
            }
            remove_filter( 'upload_dir', array($this, 'upload_dir') );
            remove_filter( 'upload_mimes', array($this, 'upload_mimes'), 10 );
            exit;
        }

        public function on_message_sent( $message ) {
            global $bpbm_uploaded_voice;
            bp_messages_add_meta( $message->id, 'bpbm_voice_messages', $bpbm_uploaded_voice );
            add_post_meta(
                $bpbm_uploaded_voice,
                'bp-better-messages-message-id',
                $message->id,
                true
            );
        }

        public function random_string( $length ) {
            $key = '';
            $keys = array_merge( range( 0, 9 ), range( 'a', 'z' ) );
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand( $keys )];
            }
            return $key;
        }

        public function upload_dir( $dir ) {
            $dirName = apply_filters( 'bp_better_messages_upload_dir_name', 'bp-better-messages/voice-message' );
            return array(
                'path'   => $dir['basedir'] . '/' . $dirName,
                'url'    => $dir['baseurl'] . '/' . $dirName,
                'subdir' => '/' . $dirName,
            ) + $dir;
        }

        public function upload_mimes( $mimes, $user ) {
            return array(
                'mp3|mp4|m4a|m4b' => 'audio/mpeg',
                'webm'            => 'audio/webm',
            );
        }

        public function after_format_message(
            $message,
            $message_id,
            $context,
            $user_id
        ) {
            $is_voice_message = strpos( $message, '<!-- BPBM-VOICE-MESSAGE -->', 0 ) === 0;
            if ( $is_voice_message ) {
                if ( $context === 'stack' ) {
                    if ( class_exists( 'Better_Messages' ) ) {
                        $attachment_id = Better_Messages()->functions->get_message_meta( $message_id, 'bpbm_voice_messages', true );
                    } else {
                        if ( function_exists( 'bp_messages_get_meta' ) ) {
                            $attachment_id = bp_messages_get_meta( $message_id, 'bpbm_voice_messages', true );
                        } else {
                            return $message;
                        }
                    }
                    $attachment_url = wp_get_attachment_url( $attachment_id );
                    $html = '<div class="bpbm-voice-message" data-message="' . esc_url( $attachment_url ) . '">';
                    $html .= '<div class="bpbm-voice-message-play"><i class="far fa-play-circle"></i></div>';
                    $html .= '<div class="bpbm-waveform"></div>';
                    $html .= '</div>';
                    return $html;
                } else {
                    if ( $context === 'site' ) {
                        return '<i class="fas fa-microphone"></i> ' . __( 'Voice Message', 'bp-better-messages-voice-messages' );
                    } else {
                        if ( $context === 'email' || $context === 'mobile_app' ) {
                            return __( 'Voice Message', 'bp-better-messages-voice-messages' );
                        }
                    }
                }
            }
            return $message;
        }

        private $scripts_loaded = false;

        public function load_script_dependencies( $context = '' ) {
            if ( $this->scripts_loaded ) {
                return;
            }
            $this->scripts_loaded = true;
            if ( !Better_Messages()->functions->is_user_authorized() ) {
                return false;
            }
            add_filter( 'better_messages_script_dependencies', function ( $deps ) {
                $deps[] = 'bm-voice-messages';
                return $deps;
            } );
            add_filter( 'better_messages_style_dependencies', function ( $deps ) {
                $deps[] = 'bpbm-voice-messages-css';
                return $deps;
            } );
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            wp_register_style(
                'bpbm-voice-messages-css',
                plugins_url( 'assets/css/bpbm-voice-messages.min.css', __FILE__ ),
                [],
                $this->version
            );
            $path = plugin_dir_path( __FILE__ ) . 'assets/js/vm' . $suffix . '.js';
            $script_version = $this->version;
            $is_dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
            if ( $is_dev ) {
                $script_version .= '.' . filemtime( $path );
            }
            wp_register_script(
                'bm-voice-messages',
                plugins_url( 'assets/js/vm' . $suffix . '.js', __FILE__ ),
                [],
                $script_version
            );
            $worker = $this->url . 'assets/js/worker' . $suffix . '.js?v=' . $script_version;
            $wasm = $this->url . 'assets/js/encoder.wasm';
            if ( $context === 'mobile-app' || defined( 'BETTER_MESSAGES_WASM_FALLBACK' ) && BETTER_MESSAGES_WASM_FALLBACK === true ) {
                $wasm = add_query_arg( [
                    'action' => 'better_messages_wasm_new_fallback',
                ], admin_url( 'admin-ajax.php' ) );
            }
            $script_variables = [
                'wasm'   => $wasm,
                'worker' => $worker,
            ];
            wp_localize_script( 'bm-voice-messages', 'BM_Voice_Messages', $script_variables );
            wp_enqueue_script( 'bm-voice-messages' );
        }

        public function load_scripts_new() {
            if ( !Better_Messages()->functions->is_user_authorized() ) {
                return false;
            }
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            wp_enqueue_style(
                'bpbm-voice-messages-css',
                plugins_url( 'assets/css/bpbm-voice-messages.min.css', __FILE__ ),
                [],
                $this->version
            );
            wp_register_script(
                'bm-voice-messages',
                plugins_url( 'assets/js/vm' . $suffix . '.js', __FILE__ ),
                ['better-messages'],
                $this->version
            );
            $worker = $this->url . 'assets/js/worker' . $suffix . '.js';
            $wasm = $this->url . 'assets/js/encoder.wasm';
            if ( defined( 'BETTER_MESSAGES_WASM_FALLBACK' ) && BETTER_MESSAGES_WASM_FALLBACK === true ) {
                $wasm = add_query_arg( [
                    'action' => 'better_messages_wasm_new_fallback',
                ], admin_url( 'admin-ajax.php' ) );
            }
            $script_variables = [
                'wasm'   => $wasm,
                'worker' => $worker,
            ];
            wp_localize_script( 'bm-voice-messages', 'BM_Voice_Messages', $script_variables );
            wp_enqueue_script( 'bm-voice-messages' );
        }

        public function load_scripts() {
            if ( !is_user_logged_in() ) {
                return false;
            }
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            wp_enqueue_style(
                'bpbm-voice-messages-css',
                plugins_url( 'assets/css/bpbm-voice-messages.css', __FILE__ ),
                [],
                $this->version
            );
            wp_register_script(
                'bpbm-wavesurfer-js',
                plugins_url( 'assets/js/wavesurfer' . $suffix . '.js', __FILE__ ),
                [],
                $this->version
            );
            $url = $this->url . 'assets/js/bpbm-vmsg.wasm';
            if ( defined( 'BETTER_MESSAGES_WASM_FALLBACK' ) && BETTER_MESSAGES_WASM_FALLBACK === true ) {
                $url = add_query_arg( [
                    'action' => 'better_messages_wasm_fallback',
                ], admin_url( 'admin-ajax.php' ) );
            }
            $script_variables = [
                'url' => $url,
            ];
            if ( function_exists( 'Better_Messages_Rest_Api' ) ) {
                // For version 2
                wp_register_script(
                    'bm-voice-messages-js',
                    plugins_url( 'assets/js/bm-voice-messages' . $suffix . '.js', __FILE__ ),
                    ['better-messages', 'bpbm-wavesurfer-js'],
                    $this->version
                );
                wp_localize_script( 'bm-voice-messages-js', 'BP_Messages_Voice_Messages', $script_variables );
                wp_enqueue_script( 'bm-voice-messages-js' );
            } else {
                // For version 1
                wp_register_script(
                    'bpbm-voice-messages-js',
                    plugins_url( 'assets/js/bpbm-voice-messages' . $suffix . '.js', __FILE__ ),
                    ['bp_messages_js', 'bpbm-wavesurfer-js'],
                    $this->version
                );
                wp_localize_script( 'bpbm-voice-messages-js', 'BP_Messages_Voice_Messages', $script_variables );
                wp_enqueue_script( 'bpbm-voice-messages-js' );
            }
        }

        public function add_voice_message_button() {
            echo '<span class="bpbm-record-voice-timer"><span class="bpbm-record-voice-delete"><i class="far fa-times-circle"></i></span><span class="bpbm-record-voice-time">00:00</span><span class="bpbm-record-voice-send"><i class="far fa-check-circle"></i></span></span>';
            echo '<button type="button" class="bpbm-record-voice-button" title="' . __( 'Record Voice Message', 'bp-better-messages-voice-messages' ) . '"><i class="fas fa-microphone" aria-hidden="true"></i></button>';
        }

    }

    function BP_Better_Messages_Voice_Messages() {
        return BP_Better_Messages_Voice_Messages::instance();
    }

    function bbmvm_fs() {
        global $bbmvm_fs;
        if ( !isset( $bbmvm_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_8659_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_8659_MULTISITE', true );
            }
            // Include Freemius SDK.
            if ( file_exists( dirname( dirname( __FILE__ ) ) . '/bp-better-messages/inc/freemius/start.php' ) ) {
                // Try to load SDK from parent plugin folder.
                require_once dirname( dirname( __FILE__ ) ) . '/bp-better-messages/inc/freemius/start.php';
            } else {
                if ( file_exists( dirname( dirname( __FILE__ ) ) . '/bp-better-messages-websocket/inc/freemius/start.php' ) ) {
                    // Try to load SDK from premium parent plugin folder.
                    require_once dirname( dirname( __FILE__ ) ) . '/bp-better-messages-websocket/inc/freemius/start.php';
                } else {
                    require_once dirname( __FILE__ ) . '/inc/freemius/start.php';
                }
            }
            $bbmvm_fs = fs_dynamic_init( array(
                'id'               => '8659',
                'slug'             => 'bp-better-messages-voice-messages',
                'premium_slug'     => 'bp-better-messages-voice-messages',
                'type'             => 'plugin',
                'public_key'       => 'pk_745a013c686d96e42b595ae8af577',
                'is_premium'       => true,
                'is_premium_only'  => true,
                'has_paid_plans'   => true,
                'is_org_compliant' => false,
                'parent'           => array(
                    'id'         => '1557',
                    'slug'       => 'bp-better-messages',
                    'public_key' => 'pk_8af54172153e9907893f32a4706e2',
                    'name'       => 'BP Better Messages',
                ),
                'menu'             => array(
                    'first-path' => 'plugins.php',
                    'support'    => false,
                ),
                'is_live'          => true,
            ) );
        }
        return $bbmvm_fs;
    }

    function bbmvm_fs_is_parent_active_and_loaded() {
        // Check if the parent's init SDK method exists.
        return function_exists( 'bpbm_fs' );
    }

    function bbmvm_fs_is_parent_active() {
        $active_plugins = get_option( 'active_plugins', array() );
        if ( is_multisite() ) {
            $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
            $active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
        }
        foreach ( $active_plugins as $basename ) {
            if ( 0 === strpos( $basename, 'bp-better-messages/' ) || 0 === strpos( $basename, 'bp-better-messages-websocket/' ) ) {
                return true;
            }
        }
        return false;
    }

    function bbmvm_fs_init() {
        if ( bbmvm_fs_is_parent_active_and_loaded() ) {
            // Init Freemius.
            bbmvm_fs();
            // Signal that the add-on's SDK was initiated.
            do_action( 'bbmvm_fs_loaded' );
            // Parent is active, add your init code here.
        } else {
            // Parent is inactive, add your error handling here.
        }
    }

    add_action( 'plugins_loaded', 'BP_Better_Messages_Voice_Messages_Init', 30 );
    function BP_Better_Messages_Voice_Messages_Init() {
        if ( bbmvm_fs_is_parent_active_and_loaded() ) {
            // If parent already included, init add-on.
            bbmvm_fs_init();
            BP_Better_Messages_Voice_Messages();
        } else {
            if ( bbmvm_fs_is_parent_active() ) {
                // Init add-on only after the parent is loaded.
                add_action( 'bbm_fs_loaded', 'bbmvm_fs_init' );
                BP_Better_Messages_Voice_Messages();
            } else {
                // Even though the parent is not activated, execute add-on for activation / uninstall hooks.
                bbmvm_fs_init();
            }
        }
    }

}