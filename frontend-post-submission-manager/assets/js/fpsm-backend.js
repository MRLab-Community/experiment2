jQuery(document).ready(function ($) {
    "use strict";
    /**
     *
     * @type object
     */
    var notice_timeout;

    /**
     * @type object
     */
    var translation_strings = fpsm_backend_obj.translation_strings;
    /**
     * Generates required notice
     *
     * @param {string} info_text
     * @param {string} info_type
     *
     */
    function fpsm_generate_info(info_text, info_type) {
        clearTimeout(notice_timeout);
        switch (info_type) {
            case 'error':
                var info_html = '<p class="fpsm-error">' + info_text + '</p>';
                break;
            case 'info':
                var info_html = '<p class="fpsm-info">' + info_text + '</p>';
                break;
            case 'ajax':
                var info_html = '<p class="fpsm-ajax"><img src="' + fpsm_backend_obj.plugin_url + '/assets/images/ajax-loader.gif" class="fpsm-ajax-loader"/>' + info_text + '</p>';
            default:
                break;

        }
        $('.fpsm-form-message').html(info_html).show();
        if (info_type != 'ajax') {
            notice_timeout = setTimeout(function () {
                $('.fpsm-form-message').slideUp(1000);
            }, 5000);
        }

    }

    /**
     * Performs clipboard copy action
     * 
     * @param {object} element
     * @returns null
     */
    function fpsm_copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function fpsm_title_to_alias(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/,:;";
        var to = "aaaaeeeeiiiioooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 _]/g, '') // remove invalid chars
            .replace(/\s+/g, '_') // collapse whitespace and replace by _
            .replace(/_+/g, '_'); // collapse dashes

        return str;
    }

    /**
     * Initialize checkbox as toggle switch
     * 
     * @since 1.0.0
     */

    function initialize_checkbox_toggle() {

        $('.fpsm-field input[type="checkbox"]').each(function () {
            if (!($(this).parent().hasClass('fpsm-checkbox-toggle')) && !($(this).hasClass('fpsm-disable-checkbox-toggle'))) {
                var input_name = $(this).attr('name');
                $(this).parent().addClass('fpsm-checkbox-toggle');
                $('<label></label>').insertAfter($(this));
            }
        });
    }

    /**
     * 
     * Check if string has white space
     * 
     * @since 1.0.0
     * 
     */
    function fpsm_hasWhiteSpace(s) {
        return s.indexOf(' ') >= 0;
    }

    /**
     * Check if string has special characters
     * 
     * @since 1.0.0
     */
    function fpsm_has_special_characters(string) {
        var format = /[!@#$%^&*()+\=\[\]{};':"\\|,.<>\/?]+/;

        if (format.test(string)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Initialize checkbox as toggle on page load
     * 
     * @since 1.0.0
     */
    initialize_checkbox_toggle();

    $('body').on('submit', '.fpsm-add-form', function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: 'post',
            url: fpsm_backend_obj.ajax_url,
            data: {
                action: 'fpsm_form_add_action',
                _wpnonce: fpsm_backend_obj.ajax_nonce,
                form_data: form_data
            },
            beforeSend: function (xhr) {
                fpsm_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    fpsm_generate_info(res.message, 'info');
                    if (res.redirect_url) {
                        window.location = res.redirect_url;
                        exit;
                    }
                } else {
                    fpsm_generate_info(res.message, 'error');
                }
            }
        });

    });

    /**
     * Settings section show hide
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-nav-item', function () {
        var tab = $(this).data('tab');
        $('.fpsm-nav-item').removeClass('fpsm-active-nav');
        $(this).addClass('fpsm-active-nav');
        $('.fpsm-settings-each-section').hide();
        $('.fpsm-settings-each-section[data-tab="' + tab + '"]').show();

    });

    $('body').on('submit', '.fpsm-edit-form', function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: 'post',
            url: fpsm_backend_obj.ajax_url,
            data: {
                action: 'fpsm_form_edit_action',
                _wpnonce: fpsm_backend_obj.ajax_nonce,
                form_data: form_data
            },
            beforeSend: function (xhr) {
                fpsm_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    fpsm_generate_info(res.message, 'info');
                    if (res.redirect_url) {
                        window.location = res.redirect_url;
                        exit;
                    }
                } else {
                    fpsm_generate_info(res.message, 'error');
                }
            }
        });

    });

    /**
     * Global Settings save
     * 
     * @since 1.0.0
     */
    $('body').on('submit', '.fpsm-settings-form', function (e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            type: 'post',
            url: fpsm_backend_obj.ajax_url,
            data: {
                action: 'fpsm_settings_save_action',
                _wpnonce: fpsm_backend_obj.ajax_nonce,
                form_data: form_data
            },
            beforeSend: function (xhr) {
                fpsm_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    fpsm_generate_info(res.message, 'info');

                } else {
                    fpsm_generate_info(res.message, 'error');
                }
            }
        });

    });

    /**
     * Shortcode clipboard copy
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-clipboard-copy', function () {
        var copy_element = $(this).parent().find('.fpsm-shortcode-preview').select();
        fpsm_copyToClipboard(copy_element);
        fpsm_generate_info(translation_strings.clipboad_copy_message, 'info');
    });



    /**
     * Show hide toggle for Select and Radio
     * 
     * @since 1.0.0
     */
    $('body').on('change', '.fpsm-toggle-trigger', function () {

        var toggle_ref = $(this).val();
        var toggle_class = $(this).data('toggle-class');
        $('.' + toggle_class).hide();
        $('.' + toggle_class).each(function () {
            var toggle_refs = $(this).data('toggle-ref') ? $(this).data('toggle-ref').split('|') : [];
            if (toggle_refs.includes(toggle_ref)) {
                $(this).show();
            }
        });

    });

    $('body').on('click', '.fpsm-checkbox-toggle-trigger', function () {
        var toggle_class = $(this).data('toggle-class');
        var toggle_type = ($(this).data('toggle-type')) ? $(this).data('toggle-type') : 'on';
        switch (toggle_type) {
            case 'on':
                if ($(this).is(':checked')) {
                    $('.' + toggle_class).show();
                    $('.' + toggle_class).removeClass('fpsm-display-none');

                } else {
                    $('.' + toggle_class).hide();
                    $('.' + toggle_class).addClass('fpsm-display-none');
                }
                break;
            case 'off':
                if ($(this).is(':checked')) {
                    $('.' + toggle_class).addClass('fpsm-display-none');
                    $('.' + toggle_class).hide();
                } else {
                    $('.' + toggle_class).removeClass('fpsm-display-none');
                    $('.' + toggle_class).show();

                }
                break;
        }

    });

    $('body').on('click', '.fpsm-field-title', function () {
        $(this).closest('.fpsm-each-form-field').find('.fpsm-field-body').slideToggle(500);
        if ($(this).find('span.dashicons').hasClass('dashicons-arrow-up')) {
            $(this).find('span.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
        } else {
            $(this).find('span.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
        }
    });

    $('body').on('click', '.fpsm-form-save', function () {
        var form = $(this).data('form');
        $('.' + form).submit();
    });

    $('body').on('click', '.fpsm-form-delete', function () {
        if (confirm(translation_strings.delete_form_confirm)) {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            $.ajax({
                type: 'post',
                url: fpsm_backend_obj.ajax_url,
                data: {
                    action: 'fpsm_form_delete_action',
                    _wpnonce: fpsm_backend_obj.ajax_nonce,
                    form_id: form_id
                },
                beforeSend: function (xhr) {
                    fpsm_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        selector.closest('tr').fadeOut(500);
                        fpsm_generate_info(res.message, 'info');
                    } else {
                        fpsm_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });

    $('.fpsm-sortable').sortable({
        placeholder: "fpsm-sortable-placeholder",
        forcePlaceholderSize: true,
        handle: '.fpsm-field-head'
    });
    $('.fpsm-dropdown-list-wrap').sortable({
        placeholder: "fpsm-sortable-placeholder",
        forcePlaceholderSize: true
    });

    /**
     * Custom field adder
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-custom-field-add-trigger', function () {
        var custom_field_label = $('#fpsm-custom-field-label').val();
        var custom_field_meta_key = $('#fpsm-custom-field-meta-key').val();
        var custom_field_key = '_custom_field|' + custom_field_meta_key;
        if (custom_field_label == '' || custom_field_meta_key == '') {
            fpsm_generate_info(translation_strings.custom_field_error, 'error');
        } else {
            if (fpsm_hasWhiteSpace(custom_field_meta_key) || fpsm_has_special_characters(custom_field_meta_key)) {
                fpsm_generate_info(translation_strings.custom_field_space_error, 'error');
                return;
            }
            if ($('.fpsm-show-fields-ref-' + custom_field_meta_key).length > 0) {
                fpsm_generate_info(translation_strings.custom_field_key_available_error, 'error');
                return;
            }
            var field_type = $('#fpsm-custom-field-type').val();
            var data = { label: custom_field_label, field_key: custom_field_key, meta_key: custom_field_meta_key, field_type: field_type };
            var field_template = wp.template('custom-' + field_type);
            $('.fpsm-form-fields-wrap > .fpsm-form-fields-list > .fpsm-sortable').append(field_template(data));
            initialize_checkbox_toggle();
            $('#fpsm-custom-field-label').val('');
            $('#fpsm-custom-field-meta-key').val('');
            $('body,html').animate({
                scrollTop: $('.fpsm-form-fields-list .fpsm-each-form-field').last().offset().top + 100
            }, 'slow');
            $('.fpsm-form-fields-list .fpsm-each-form-field h3.fpsm-field-title').last().click();
            $('.fpsm-sortable').sortable({
                placeholder: "fpsm-sortable-placeholder",
                forcePlaceholderSize: true
            });
            $('.fpsm-dropdown-list-wrap').sortable({
                placeholder: "fpsm-sortable-placeholder",
                forcePlaceholderSize: true
            });

        }


    });

    /**
     * Custom field remover
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-field-remove-trigger', function () {
        if (confirm(translation_strings.custom_field_delete_confirm)) {
            $(this).closest('.fpsm-each-form-field').remove();

        }
    });

    /**
     * Select dropdown option adder
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-add-option-trigger', function () {
        var selector = $(this);
        var field_key = $(this).data('field-key');
        var field_type = $(this).data('field-type');
        var data = { field_key: field_key, field_type: field_type };
        var option_template = wp.template('option');
        selector.closest('.fpsm-field').find('.fpsm-dropdown-list-wrap').append(option_template(data));
        selector.closest('.fpsm-field').find('.fpsm-each-dropdown').last().find('input[type="text"]').first().focus();
    });

    /**
     * Option delete trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-delete-dropdown-trigger', function () {
        if (confirm(translation_strings.option_delete_confirm)) {
            $(this).closest('.fpsm-each-dropdown').remove();

        }
    });

    /**
     * Radio button checked trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-checked-radio-ref', function () {
        $(this).closest('.fpsm-dropdown-list-wrap').find('.fpsm-checked-radio-val').val(0);
        $(this).next('input[type="hidden"]').val(1);
    });

    /**
     * Editor change options toggle
     * 
     * @since 1.0.0
     */

    $('body').on('change', '.fpsm-editor-type', function () {
        var media_editors = ['visual', 'rich'];
        var editor_type = $(this).val();
        if (media_editors.indexOf(editor_type) != -1) {
            $(this).closest('.fpsm-each-form-field').find('.fpsm-editor-type-ref').show();
        } else {
            $(this).closest('.fpsm-each-form-field').find('.fpsm-editor-type-ref').hide();
        }
    });

    /**
     * Media remove backend
     */
    $('body').on('click', '.fpsm-media-remove-button', function () {
        var media_id = $(this).data('media-id');
        var media_id = media_id.toString();
        var pre_saved_value = $(this).closest('.fpsm-file-preview-wrap').find('.fpsm-fileuploader-value').val();
        var pre_saved_value_array = pre_saved_value.split(',');
        if (pre_saved_value_array.length > 1) {
            pre_saved_value_array.splice(pre_saved_value_array.indexOf(media_id), 1);
            pre_saved_value = pre_saved_value_array.join(',');
            $(this).closest('.fpsm-file-preview-wrap').find('.fpsm-fileuploader-value').val(pre_saved_value);
        } else {
            $(this).closest('.fpsm-file-preview-wrap').find('.fpsm-fileuploader-value').val('');
        }
        $(this).closest('.fpsm-file-preview-row').remove();
    });

    $('.fpsm-front-datepicker').each(function () {
        var date_format = $(this).data('date-format');
        $(this).datepicker({
            dateFormat: date_format
        });
    });

    $('body').on('click', '.fpsm-custom-field-type-trigger-btn', function () {
        var field_type = $(this).data('field-type');
        $('#fpsm-custom-field-type option').removeAttr('selected');
        $('#fpsm-custom-field-type option[value="' + field_type + '"]').prop('selected', true);
        $('.fpsm-custom-field-type-trigger-btn').removeClass('btn-selected');
        $(this).addClass('btn-selected');
    });
    $('.fpsm-edit-form').areYouSure(
        {
            message: translation_strings.are_your_sure
        }
    );

    $('body').on('change', '.fpsm-form-template', function () {
        var template = $(this).val();
        $('.fpsm-form-template-preview-img').hide();
        $('.fpsm-form-template-preview-img[data-template-id="' + template + '"]').show();
        if (template == 'template-7' || template == 'template-12' || template == 'template-18' || template == 'template-22') {
            $('.fpsm-label-background-ref').show();
        } else {
            $('.fpsm-label-background-ref').hide();

        }
    });
    $('body').on('change', '.fpsm-custom-field-template-trigger', function () {
        var template = $(this).val();
        $('.fpsm-post-template-preview-img').hide();
        $('.fpsm-post-template-preview-img[data-template-id="' + template + '"]').show();

    });

    $('.fpsm-color-picker').wpColorPicker();

    /**
     * Open Media Uploader
     */
    $('body').on('click', '.fpsm-media-uploader', function () {

        var selector = $(this);

        var image = wp.media({
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
            .on('select', function (e) {
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                // console.log(uploaded_image);
                var image_url = uploaded_image.toJSON().url;
                var image_id = uploaded_image.toJSON().id;
                // Let's assign the url value to the input field
                selector.parent().find('input[type="text"]').val(image_url);
                selector.parent().find('input[type="hidden"]').val(image_id);
                selector.parent().find('.fpsm-media-preview').html('<img src="' + uploaded_image.toJSON().sizes.thumbnail.url + '"/>');
            });
    });
    /**
     * Open Media Uploader
     */
    $('body').on('click', '.fpsm-metabox-fileuploader', function () {

        var selector = $(this);

        var image = wp.media({
            title: 'Upload File',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
            .on('select', function (e) {
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image

                var uploaded_obj = uploaded_image.toJSON();
                if (uploaded_obj.type == 'image') {
                    if (uploaded_obj.sizes.thumbnail) {
                        var media_icon = uploaded_obj.sizes.thumbnail.url;
                    } else {
                        var media_icon = uploaded_obj.sizes.full.url;
                    }

                } else {
                    var media_icon = uploaded_obj.icon;

                }
                var media_id = uploaded_obj.id;
                var media_edit_url = fpsm_backend_obj.admin_url + 'upload.php?item=' + media_id;
                var media_url = uploaded_obj.url;
                var media_name = uploaded_obj.name;
                var media_size = uploaded_obj.filesizeHumanReadable;
                var data = { media_id, media_icon, media_edit_url, media_url, media_name, media_size };
                var preview_template = wp.template('fpsm-metabox-file-preview');
                selector.parent().find('.fpsm-uploaded-files-list').append(preview_template(data));
                var pre_saved_value = selector.parent().find('.fpsm-fileuploader-value').val();
                if (pre_saved_value != '') {
                    pre_saved_value = pre_saved_value + ',' + media_id;
                } else {
                    pre_saved_value = media_id;
                }

                selector.parent().find('.fpsm-fileuploader-value').val(pre_saved_value);
            });
    });

    $('body').on('keyup', 'input[name="form_title"]', function () {
        var form_title = $(this).val();
        var form_alias = fpsm_title_to_alias(form_title);
        if ($('input[name="form_alias"]').attr('readonly') != 'readonly') {
            $('input[name="form_alias"]').val(form_alias);

        }
    });

    $('body').on('click', '.fpsm-alias-force-edit', function () {
        $(this).parent().find('input[type="text"]').removeAttr('readonly');
    });

    /**
     * Form Copy
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.fpsm-form-copy', function () {
        if (confirm(translation_strings.copy_form_confirm)) {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            $.ajax({
                type: 'post',
                url: fpsm_backend_obj.ajax_url,
                data: {
                    action: 'fpsm_form_copy_action',
                    form_id: form_id,
                    _wpnonce: fpsm_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    fpsm_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        fpsm_generate_info(res.message, 'info');
                        fpsm_generate_info(res.message, 'info');
                        if (res.redirect_url) {
                            window.location = res.redirect_url;
                            exit;
                        }
                    } else {
                        fpsm_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });


});