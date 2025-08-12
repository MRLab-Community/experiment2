<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span>{{data.label}} <span class="fpsm-field-type-label">- <?php esc_html_e('File Uploader', 'frontend-post-submission-manager'); ?></span></h3>
        <a href="javascript:void(0);" class="fpsm-field-remove-trigger"><span class="dashicons dashicons-trash"></span></a>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-common-fields.php'); ?>
        <div class="fpsm-show-fields-ref-{{data.meta_key}} fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Upload Button Label', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_button_label]"/>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('File Extensions', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <?php
                    global $fpsm_library_obj;
                    $mime_types = get_allowed_mime_types();
                    if (!empty($mime_types)) {
                        foreach ($mime_types as $mime_type => $mime_type_label) {
                            ?>
                            <label class="fpsm-each-extension"><input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[file_extensions][]" value="<?php echo esc_attr($mime_type); ?>" class="fpsm-disable-checkbox-toggle"/><span><?php echo esc_html($mime_type); ?></label>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Upload File Size Limit', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="number" min="1" name="<?php echo esc_attr($field_name_prefix); ?>[upload_file_size_limit]"/>
                    <p class="description"><?php esc_html_e('Please enter the max size of the file being uploaded in MB. Default is 5 MB.', 'frontend-post-submission-manager'); ?></p>
                    <?php
                    $max_upload_filesize = ini_get('upload_max_filesize');
                    ?>
                    <p class="description"><?php esc_html_e(sprintf("Please note that the number shouldn't exceed %s. If you want to allow more than %s then please update the value in your server's php.ini file.", $max_upload_filesize, $max_upload_filesize), 'frontend-post-submission-manager'); ?></p>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Upload File Size Error Message', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_filesize_error_message]"/>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Multiple Upload', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="<?php echo esc_attr($field_name_prefix) ?>[multiple_upload]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-multiple-upload-fields"/>
                    <p class="description"><?php esc_html_e('Please check if you want to enable the multiple file upload.', 'frontend-post-submission-manager'); ?></p>
                </div>
            </div>
            <div class="fpsm-multiple-upload-fields fpsm-display-none">
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Allowed Number of Files', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="number" min="1" name="<?php echo esc_attr($field_name_prefix); ?>[max_number_uploads]"/>
                        <p class="description"><?php esc_html_e("Please enter the maximum number of files you want to allow for the upload. Please leave blank if you don't want to set any limitation.", 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Upload Limit Error Message', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_limit_error_message]"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>