var file_uploader_fields = {};

jQuery(document).ready(function ($) {
    "use strict";
    /**
     * @type object
     */
    var translation_strings = fpsm_js_obj.translation_strings;
    function initialize_uploaders() {
        $('.fpsm-file-uploader').each(function () {
            var form_alias = $(this).closest('form').data('alias');
            var selector = $(this);
            var attr_element_id = $(this).attr('id');
            var arr_element_id = attr_element_id.split('-');
            var uploader_name = arr_element_id[2];
            var extensions = $(this).data('extensions');
            var extensions_error = $(this).data('extensions-error-message');
            var extensions_array = extensions.split('|');
            var sizeLimit = $(this).data('file-size-limit');
            var sizeLimit_error = $(this).data('upload-filesize-error-message');
            sizeLimit = parseInt(sizeLimit) * 1000 * 1000;
            var multiple_upload = $(this).data('multiple');
            var limit_flag = 0;
            var upload_limit = $(this).data('multiple-upload-limit');
            var uploader_label = $(this).data('upload-label');
            var upload_limit_message = $(this).data('multiple-upload-error-message');
            var field_name = $(this).data('field-name');
            file_uploader_fields[uploader_name] = new qq.FileUploader({
                element: document.getElementById(attr_element_id),
                action: fpsm_js_obj.ajax_url,
                params: {
                    action: 'fpsm_file_upload_action',
                    _wpnonce: fpsm_js_obj.ajax_nonce,
                    form_alias: form_alias,
                    field_name: field_name
                },
                debug: false,
                allowedExtensions: extensions_array,
                sizeLimit: sizeLimit,
                minSizeLimit: 50,
                uploadButtonText: $(this).data('label'),
                onSubmit: function (id, fileName) {

                    selector.closest('.fpsm-field-wrap').find('.fpsm-error').html('');
                    if (multiple_upload == true && upload_limit != -1) {
                        var upload_count = selector.parent().find('.fpsm-upload-count').val();
                        var current_upload_count = upload_count;
                        upload_count++;
                        selector.closest('.fpsm-field').find('.fpsm-upload-count').val(upload_count);
                        if (upload_count > upload_limit) {
                            upload_limit_message = (upload_limit_message) ? upload_limit_message : 'Maximum number of files allowed is ' + upload_limit;
                            selector.closest('.fpsm-field-wrap').find('.fpsm-error').html(upload_limit_message);
                            selector.closest('.fpsm-field').find('.fpsm-upload-count').val(current_upload_count);
                            return false;
                        }
                    } else {
                        // Just to delete the file that has been already uploaded
                        if (selector.closest('.fpsm-field').find('.fpsm-media-delete-button').length > 0) {
                            selector.closest('.fpsm-field').find('.fpsm-media-delete-button').click();
                        }


                    }
                },
                onProgress: function (id, fileName, loaded, total) {

                },
                onComplete: function (id, fileName, responseJSON) {
                    if (responseJSON.success) {

                        if (selector.hasClass('fpsm-custom-media-upload-button')) {
                            switch (responseJSON.media_type) {
                                case 'image':
                                    var insert_content_html = '<img class="alignnone size-full wp-image-' + responseJSON.media_id + '" src="' + responseJSON.media_full_url + '" alt="' + responseJSON.media_name + '"/>';
                                    break;
                                case 'video':
                                    var insert_content_html = '[video width="100%" height="500" ' + responseJSON.media_extension + '="' + responseJSON.media_full_url + '"][/video]';
                                    break;
                                case 'audio':
                                    var insert_content_html = '[audio ' + responseJSON.media_extension + '="' + responseJSON.media_full_url + '"][/audio]';
                                    break;
                                case 'others':
                                    var insert_content_html = '<a href="' + responseJSON.media_full_url + '">' + responseJSON.media_name + '</a>';
                                    break;
                            }
                            var tinymce_id = selector.data('tinymce-id');
                            // tinyMCE.get('fpsm_login_require_form').triggerSave();
                            tinyMCE.get(tinymce_id).execCommand('mceInsertContent', false, insert_content_html);
                        } else {
                            var data = { media_url: responseJSON.media_url, media_id: responseJSON.media_id, media_name: responseJSON.media_name, media_key: responseJSON.media_key }

                            var file_preview_template = wp.template('upload-preview');
                            if (multiple_upload) {
                                var media_id = selector.closest('.fpsm-field').find('.fpsm-media-id').val();
                                if (media_id != '') {
                                    var media_id_array = media_id.split(',');
                                    media_id_array.push(responseJSON.media_id);
                                    var media_id = media_id_array.join(',');
                                } else {
                                    media_id = responseJSON.media_id;
                                }

                                selector.closest('.fpsm-field').find('.fpsm-media-id').val(media_id);
                                selector.closest('.fpsm-field').find('.fpsm-file-preview-wrap').append(file_preview_template(responseJSON));
                            } else {
                                selector.closest('.fpsm-field').find('.fpsm-media-id').val(responseJSON.media_id);
                                selector.closest('.fpsm-field').find('.fpsm-file-preview-wrap').html(file_preview_template(responseJSON));
                            }
                        }

                    } else {

                        console.log(responseJSON);
                    }


                },
                onCancel: function (id, fileName) { },
                onError: function (id, fileName, xhr) { },
                messages: {
                    typeError: (extensions_error == '') ? translation_strings.typeError : extensions_error,
                    sizeError: (sizeLimit_error != '') ? sizeLimit_error : translation_strings.sizeError,
                    minSizeError: translation_strings.minSizeError,
                    emptyError: translation_strings.emptyError,
                    onLeave: translation_strings.onLeave,
                },
                showMessage: function (message) {
                    alert(message);
                },
                multiple: multiple_upload
            });
        });
    }
    /**
     * Scrolls to the first error of the form
     *
     */
    function fpsm_scroll_to_error(form) {
        form.find('.fpsm-error').each(function () {
            var in_selector = $(this);
            if (in_selector.is(':visible') && in_selector.html() != '') {
                $('html,body').animate({
                    scrollTop: in_selector.closest('.fpsm-field-wrap').offset().top - 100
                },
                    'slow');
                return false;
            }
            ;
        });

    }

    /**
     * Reset forms
     */
    function fpsm_reset_form(form) {
        form[0].reset();
        form.find('.fpsm-file-preview-wrap').html('');
        form.find('.fpsm-media-id').val('');
        form.find('.fpsm-upload-count').val(0);
        form.find('.fpsm-error').html('').hide();
        if (form.find('#g-recaptcha-response').length > 0) {
            grecaptcha.reset();
        }
    }

    $('body').on('click', '.fpsm-media-delete-button', function () {
        var selector = $(this);
        var media_id = $(this).data('media-id');
        var media_key = $(this).data('media-key');
        var edit = $(this).data('edit');
        if (edit == 'no') {
            $.ajax({
                type: 'post',
                url: fpsm_js_obj.ajax_url,
                data: {
                    _wpnonce: fpsm_js_obj.ajax_nonce,
                    media_id: media_id,
                    media_key: media_key,
                    action: 'fpsm_media_delete_action'
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        media_id = media_id.toString();
                        var upload_count = selector.closest('.fpsm-field').find('.fpsm-upload-count').val();
                        upload_count--;
                        selector.closest('.fpsm-field').find('.fpsm-upload-count').val(upload_count);
                        var pre_saved_value = selector.closest('.fpsm-field').find('.fpsm-media-id').val();
                        var pre_saved_value_array = pre_saved_value.split(',');
                        if (pre_saved_value_array.length > 1) {
                            console.log(pre_saved_value_array.indexOf(media_id));
                            pre_saved_value_array.splice(pre_saved_value_array.indexOf(media_id), 1);
                            pre_saved_value = pre_saved_value_array.join(',');
                            selector.closest('.fpsm-field').find('.fpsm-media-id').val(pre_saved_value);
                        } else {
                            selector.closest('.fpsm-field').find('.fpsm-media-id').val('');
                        }
                        selector.closest('.fpsm-file-preview-row').remove();
                    } else {
                        alert(res.message);
                    }
                }
            });
        } else {
            media_id = media_id.toString();
            var upload_count = selector.closest('.fpsm-field').find('.fpsm-upload-count').val();
            upload_count--;
            selector.closest('.fpsm-field').find('.fpsm-upload-count').val(upload_count);
            var pre_saved_value = selector.closest('.fpsm-field').find('.fpsm-media-id').val();
            var pre_saved_value_array = pre_saved_value.split(',');
            if (pre_saved_value_array.length > 1) {
                console.log(pre_saved_value_array.indexOf(media_id));
                pre_saved_value_array.splice(pre_saved_value_array.indexOf(media_id), 1);
                pre_saved_value = pre_saved_value_array.join(',');
                selector.closest('.fpsm-field').find('.fpsm-media-id').val(pre_saved_value);
            } else {
                selector.closest('.fpsm-field').find('.fpsm-media-id').val('');
            }
            selector.closest('.fpsm-file-preview-row').remove();
        }
    });

    $('.fpsm-auto-complete-field').each(function () {
        var available_tags = $(this).next('.fpsm-available-tags').val();
        available_tags = available_tags.split(',');
        $(this).autocomplete({
            source: available_tags
        });
    });

    $('body').on('keypress', '.fpsm-auto-complete-field', function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var tag = $(this).val();
            if (tag == '') {
                return;
            }
            var added_tags = $(this).parent().find('.fpsm-auto-complete-values').val();
            if (added_tags == '') {
                added_tags = [];
            } else {
                added_tags = added_tags.split(',');
            }

            if (added_tags.indexOf(tag) == -1) {
                added_tags.push(tag);
                added_tags = added_tags.join(',');
                $(this).parent().find('.fpsm-auto-complete-values').val(added_tags);
                var tag_html = '<div class="fpsm-each-tag"><span class="fpsm-tag-text">' + tag + '</span><span class="fpsm-tag-remove-trigger"><i class="fas fa-times-circle"></i></span></div>';
                $(this).parent().find('.fpsm-auto-complete-tags').append(tag_html);
                $(this).val('');
                $(".ui-autocomplete").hide();
            }
            event.stopPropagation();
            return false;

        }
    });

    $('body').on('click', '.fpsm-tag-remove-trigger', function () {
        var tag = $(this).parent().find('.fpsm-tag-text').html();
        var added_tags = $(this).closest('.fpsm-field').find('.fpsm-auto-complete-values').val();
        added_tags = added_tags.split(',');
        var tag_index = added_tags.indexOf(tag)
        added_tags.splice(tag_index, 1)
        added_tags = added_tags.join(',');
        $(this).closest('.fpsm-field').find('.fpsm-auto-complete-values').val(added_tags);
        $(this).closest('.fpsm-each-tag').remove();

    });

    $('body').on('submit', '.fpsm-front-form', function (e) {
        e.preventDefault();

        // Checking this to prevent form from submitting while file upload is on progress
        if ($(this).find('.qq-upload-list li').length > 0) {
            console.log('File upload is on progress');
            return false;
        }
        var selector = $(this);
        var auto_save = ($(this).data('auto-save')) ? $(this).data('auto-save') : 'no';
        // If auto complete textfield is filled but auto complete is not done
        selector.find('.fpsm-auto-complete-field').each(function () {
            var filled_tag = $(this).val();
            if (filled_tag != '') {
                var auto_complete_value = $(this).closest('.fpsm-field').find('.fpsm-auto-complete-values').val();
                if (auto_complete_value == '') {
                    auto_complete_value = [];
                } else {
                    auto_complete_value = auto_complete_value.split(',');
                }
                if (auto_complete_value.indexOf(filled_tag) == -1) {
                    auto_complete_value.push(filled_tag);
                    auto_complete_value = auto_complete_value.join(',');
                    $(this).parent().find('.fpsm-auto-complete-values').val(auto_complete_value);
                }
            }
        });
        var form_data = selector.serialize();
        $.ajax({
            type: 'post',
            url: fpsm_js_obj.ajax_url,
            data: {
                action: 'fpsm_form_process',
                form_data: form_data,
                _wpnonce: fpsm_js_obj.ajax_nonce
            },
            beforeSend: function (xhr) {
                if (auto_save == 'no') {
                    selector.find('.fpsm-form-message').slideUp();
                    selector.find('.fpsm-error').slideUp();
                    selector.find('.fpsm-ajax-loader').show();
                }
            },
            success: function (data, textStatus, jqXHR) {
                selector.find('.fpsm-ajax-loader').hide();
                selector.data('auto-save', 'no');
                data = $.parseJSON(data);
                if (data.status == 200) {
                    if (auto_save == 'no') {
                        selector.find('.fpsm-form-message').removeClass('fpsm-form-error').addClass('fpsm-form-success').html(data.message).slideDown('slow');
                    }
                    if (!data.draft_post_id) {
                        fpsm_reset_form(selector);
                        if (data.redirect_url) {
                            if (data.redirect_delay) {
                                setTimeout(function () {
                                    window.location = data.redirect_url;
                                    exit;
                                }, data.redirect_delay);
                            } else {
                                window.location = data.redirect_url;
                                exit;
                            }

                        }
                    } else {
                        selector.find('.fpsm-edit-post-id').val(data.draft_post_id);
                        if (data.redirect_url) {
                            if (data.redirect_delay) {
                                setTimeout(function () {
                                    window.location = data.redirect_url;
                                    exit;
                                }, data.redirect_delay);
                            } else {
                                window.location = data.redirect_url;
                                exit;
                            }
                        }
                        if (selector.find('#g-recaptcha-response').length > 0) {
                            grecaptcha.reset();
                        }
                    }
                } else {
                    if (auto_save == 'no') {
                        selector.find('.fpsm-form-message').removeClass('fpsm-form-success').addClass('fpsm-form-error').html(data.message).slideDown('slow', function () {
                            var error_details = data.error_details;
                            for (var field_key in error_details) {
                                if (selector.find('[data-field-key="' + field_key + '"] .fpsm-error').length > 0) {
                                    selector.find('[data-field-key="' + field_key + '"] .fpsm-error').html(error_details[field_key]).slideDown('slow');
                                } else {
                                    selector.find('[data-field-key="' + field_key + '"]').append('<div class="fpsm-error">' + error_details[field_key] + '</div>');
                                }

                            }
                            if (selector.find('#g-recaptcha-response').length > 0) {
                                grecaptcha.reset();
                            }
                            fpsm_scroll_to_error(selector);
                        });

                    }
                }
            }
        });
    });
    initialize_uploaders();

    $('.fpsm-each-term-checkbox label').on('click', function () {
        $(this).toggleClass('checked');
    });

    $('.fpsm-front-datepicker').each(function () {
        var date_format = $(this).data('date-format');
        var date_value = $(this).data('date-value');
        $(this).datepicker({
            dateFormat: date_format,
        });
        if (date_value != '') {
            var date_value_break = date_value.split('-');
            var year = parseInt(date_value_break[0]);
            var month = parseInt(date_value_break[1]) - 1;
            var day = parseInt(date_value_break[2]);
            $(this).datepicker('setDate', new Date(year, month, day));
        }


    });


    /**
     * Clear error
     */

    $('body').on('keyup', '.fpsm-front-form input[type="text"], .fpsm-front-form textarea', function () {
        $(this).closest('.fpsm-field-wrap').find('.fpsm-error').slideUp('fast');
    });
    $('body').on('click', '.fpsm-front-form input[type="checkbox"], .fpsm-front-form select,.fpsm-front-form input[type="radio"]', function () {

        $(this).closest('.fpsm-field-wrap').find('.fpsm-error').slideUp('fast');
    });

    /**
     * Delete Post
     */
    $('body').on('click', '.fpsm-delete-post', function () {
        var selector = $(this);
        var warning_message = $(this).data('warning-message');
        if (confirm(warning_message)) {
            var delete_key = $(this).data('delete-key');
            var post_id = $(this).data('post-id');
            $.ajax({
                url: fpsm_js_obj.ajax_url,
                type: 'post',
                data: {
                    action: 'fpsm_post_delete_action',
                    _wpnonce: fpsm_js_obj.ajax_nonce,
                    delete_key: delete_key,
                    post_id: post_id
                },
                beforeSend: function (xhr) {
                    selector.closest('.fpsm-dashboard-row').addClass('fpsm-delete-loading');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        selector.closest('.fpsm-dashboard-row').fadeOut(500, function () {
                            $(this).remove();
                        });
                    } else {
                        alert(res.message);
                    }
                }
            });
        }
    });
    if (!fpsm_js_obj.fpsm_settings.disable_are_you_sure_js) {
        $('.fpsm-front-form').areYouSure(
            {
                message: translation_strings.are_your_sure
            }
        );
    }

    /**
     * Enter key form submit disable
     */
    $('.fpsm-front-form').each(function () {
        if ($(this).find('input[type="submit"]').length > 1) {
            $(this).on('keypress', ':input:not(textarea):not([type=submit])',
                function (event) {
                    if (event.which == '13') {
                        event.preventDefault();
                    }
                });
        }
    });

    $('body').on('click', '.fpsm-front-form input[type="submit"]', function () {
        var dynamic_post_status = $(this).data('post-status');
        $(this).closest('form').find('input[name="dynamic_post_status"]').val(dynamic_post_status);
    });

    $('.fpsm-auto-draft').each(function () {
        var selector = $(this);
        var time_interval = $(this).data('auto-save-time');
        var post_status = $(this).closest('form').find('.fpsm-previous-post-status').val();
        if (time_interval != '' && (post_status == '' || post_status == 'draft')) {
            setInterval(function () {
                if (selector.closest('form').hasClass('dirty')) {
                    console.log('Auto save calling');
                    if (selector.data('background-save') == 1) {
                        selector.closest('form').data('auto-save', 'yes');
                    }
                    selector.click();
                }
            }, parseInt(time_interval) * 1000);
        }
    });

    $('body').on('change', '.fpsm-dashboard-post-status-filter', function () {
        $(this).closest('form').submit();
    });

    $('.fpsm-file-preview-wrap').sortable({
        placeholder: "fpsm-sortable-placeholder",
        forcePlaceholderSize: true,
        stop: function (event, ui) {
            var media_id_array = [];
            ui.item.closest('.fpsm-file-preview-wrap').find('.fpsm-file-preview-row').each(function () {
                var media_id = $(this).data('media-id');
                media_id_array.push(media_id);

            });
            var media_ids = media_id_array.join();
            ui.item.closest('.fpsm-field').find('.fpsm-media-id').val(media_ids);
        }
    });

    /**
     * WP Media Uploader for Featured Image
     */
    var frame; // variable for the wp.media file_frame

    // attach a click event (or whatever you want) to some element on your page
    $('body').on('click', '.fpsm-wp-media-uploader', function (event) {
        var selector = $(this);
        var media_select_label = $(this).data('media-select-label');
        var media_title = $(this).data('media-title');
        var extension_error_message = $(this).data('extension-error');

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            //  title: media_title,
            button: {
                //    text: media_select_label
            },
            multiple: false,
            library: { type: 'image' }
        });


        // When an image is selected in the media frame...
        frame.on('select', function () {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            if (attachment.type != 'image') {
                alert(extension_error_message);
                frame.open();
            } else {
                var data = { media_url: attachment.url, media_id: attachment.id, media_name: attachment.name, media_size: attachment.filesizeHumanReadable, hideDelete: 1 }
                var file_preview_template = wp.template('upload-preview');
                selector.closest('.fpsm-field').find('.fpsm-media-id').val(attachment.id);
                selector.closest('.fpsm-field').find('.fpsm-file-preview-wrap').html(file_preview_template(data));
            }
        });

        // Finally, open the modal on click
        frame.open();
    });



});