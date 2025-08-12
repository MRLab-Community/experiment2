<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Post Image', 'frontend-post-submission-manager'); ?></h3>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
        <div class="fpsm-show-fields-ref-<?php echo esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Upload Button Label', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_button_label]" value="<?php echo (!empty($field_details['upload_button_label'])) ? esc_attr($field_details['upload_button_label']) : ''; ?>" />
                </div>
            </div>
            <?php
            $post_image_uploader_type = (!empty($field_details['uploader_type'])) ? $field_details['uploader_type'] : 'custom';
            if ($form_row->form_type == 'login_require') {
            ?>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Uploader Type', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <select name="<?php echo esc_attr($field_name_prefix); ?>[uploader_type]" class="fpsm-toggle-trigger" data-toggle-class="fpsm-post-image-uploader-type-ref">

                            <option value="custom" <?php selected($post_image_uploader_type, 'custom'); ?>><?php esc_html_e('Custom Uploader', 'frontend-post-submission-manager'); ?></option>
                            <option value="wp_media_uploader" <?php selected($post_image_uploader_type, 'wp_media_uploader'); ?>><?php esc_html_e('WP Media Uploader', 'frontend-post-submission-manager'); ?></option>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('File Extension Error Message', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[file_extension_error_message]" value="<?php echo (!empty($field_details['file_extension_error_message'])) ? esc_attr($field_details['file_extension_error_message']) : ''; ?>" />
                </div>
            </div>
            <div class="fpsm-post-image-uploader-type-ref <?php echo ($post_image_uploader_type != 'custom') ? 'fpsm-display-none' : ''; ?>" data-toggle-ref="custom">
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Upload File Size Limit', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="number" min="1" name="<?php echo esc_attr($field_name_prefix); ?>[upload_file_size_limit]" value="<?php echo (!empty($field_details['upload_file_size_limit'])) ? intval($field_details['upload_file_size_limit']) : ''; ?>" />
                        <p class="description"><?php esc_html_e('Please enter the max size of the file being uploaded in MB. Default is 5 MB.', 'frontend-post-submission-manager'); ?></p>
                        <?php
                        $max_upload_filesize = ini_get('upload_max_filesize');
                        ?>
                        <p class="description"><?php esc_html_e(sprintf("Please note that the number shouldn't exceed %s. If you want to allow more than %s then please update the value in your server's php.ini file.", $max_upload_filesize, $max_upload_filesize), 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Max Size Error Message', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[max_size_error_message]" value="<?php echo (!empty($field_details['max_size_error_message'])) ? esc_attr($field_details['max_size_error_message']) : ''; ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>