<?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
<div class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Upload Button Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_button_label]" value="<?php echo (!empty($field_details['upload_button_label'])) ? esc_attr($field_details['upload_button_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('File Extensions', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field fpsm-extension-list">
            <?php
            global $fpsm_library_obj;
            $mime_types = get_allowed_mime_types();
            $selected_mime_types = (!empty($field_details['file_extensions'])) ? $field_details['file_extensions'] : array();
            if (!empty($mime_types)) {
                foreach ($mime_types as $mime_type => $mime_type_label) {
            ?>
                    <label class="fpsm-each-extension"><input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[file_extensions][]" value="<?php echo esc_attr($mime_type); ?>" class="fpsm-disable-checkbox-toggle" <?php echo (in_array($mime_type, $selected_mime_types)) ? 'checked="checked"' : ''; ?> /><span><?php echo esc_html($mime_type); ?></label>
            <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('File Extension Error Message', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[file_extension_error_message]" value="<?php echo (!empty($field_details['file_extension_error_message'])) ? esc_attr($field_details['file_extension_error_message']) : ''; ?>" />
            <p class="description"><?php esc_html_e('Please use {file} and {extensions} to replace uploaded file name and allowed extension in the displayed message. For example: {file} has invalid extension. Only {extensions} are allowed.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Upload File Size Limit', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" name="<?php echo esc_attr($field_name_prefix); ?>[upload_file_size_limit]" value="<?php echo (!empty($field_details['upload_file_size_limit'])) ? esc_attr($field_details['upload_file_size_limit']) : ''; ?>" />
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
            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_filesize_error_message]" value="<?php echo (!empty($field_details['upload_filesize_error_message'])) ? esc_attr($field_details['upload_filesize_error_message']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Multiple Upload', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="<?php echo esc_attr($field_name_prefix) ?>[multiple_upload]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-multiple-upload-fields" <?php echo (!empty($field_details['multiple_upload'])) ? 'checked="checked"' : ''; ?> />
            <p class="description"><?php esc_html_e('Please check if you want to enable the multiple file upload.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-multiple-upload-fields <?php echo (empty($field_details['multiple_upload'])) ? 'fpsm-display-none' : ''; ?>">
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Allowed Number of Files', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="number" min="1" name="<?php echo esc_attr($field_name_prefix); ?>[max_number_uploads]" value="<?php echo (!empty($field_details['max_number_uploads'])) ? intval($field_details['max_number_uploads']) : ''; ?>" />
                <p class="description"><?php esc_html_e("Please enter the maximum number of files you want to allow for the upload. Please leave blank if you don't want to set any limitation.", 'frontend-post-submission-manager'); ?></p>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e('Upload Limit Error Message', 'frontend-post-submission-manager'); ?></label>
            <div class="fpsm-field">
                <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_limit_error_message]" value="<?php echo (!empty($field_details['upload_limit_error_message'])) ? esc_attr($field_details['upload_limit_error_message']) : ''; ?>" />
            </div>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Attach to Post', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="<?php echo esc_attr($field_name_prefix) ?>[attach_to_post]" value="1" <?php echo (!empty($field_details['attach_to_post'])) ? 'checked="checked"' : ''; ?> />
            <p class="description"><?php esc_html_e('Please check if you want the media files to attach to the submitted post.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
</div>