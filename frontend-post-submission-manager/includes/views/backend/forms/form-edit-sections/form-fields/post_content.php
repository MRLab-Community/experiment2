<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Post Content', 'frontend-post-submission-manager'); ?></h3>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
        <div class="fpsm-show-fields-ref-<?php echo esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Editor Type', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <?php
                    $editor_type = (!empty($field_details['editor_type'])) ? $field_details['editor_type'] : 'simple';
                    ?>
                    <select name="<?php echo esc_attr($field_name_prefix); ?>[editor_type]" class="fpsm-editor-type">
                        <option value="simple" <?php selected($editor_type, 'simple'); ?>><?php esc_html_e('Simple Textarea', 'frontend-post-submission-manager'); ?></option>
                        <option value="rich" <?php selected($editor_type, 'rich'); ?>><?php esc_html_e('Rich Text Editor', 'frontend-post-submission-manager'); ?></option>
                        <option value="visual" <?php selected($editor_type, 'visual'); ?>><?php esc_html_e('Visual Text Editor', 'frontend-post-submission-manager'); ?></option>
                        <option value="html" <?php selected($editor_type, 'html'); ?>><?php esc_html_e('HTML Text Editor', 'frontend-post-submission-manager'); ?></option>
                    </select>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Editor Height', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="number" name="<?php echo esc_attr($field_name_prefix); ?>[editor_height]" value="<?php echo (!empty($field_details['editor_height'])) ? esc_attr($field_details['editor_height']) : ''; ?>" min="1" />
                    <p class="description"><?php esc_html_e('Please enter the height of the editor in px if you want to increase or decrease the default height.', 'frontend-post-submission-manager'); ?></p>
                </div>
            </div>
            <?php
            $media_ref_editors = array('rich', 'visual');
            if ($form_row->form_type == 'login_require') {
            ?>
                <div class="fpsm-field-wrap fpsm-editor-type-ref <?php echo (!in_array($editor_type, $media_ref_editors)) ? 'fpsm-display-none' : '' ?>">
                    <label><?php esc_html_e('Media Upload', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[media_upload]" value="1" <?php echo (!empty($field_details['media_upload'])) ? 'checked="checked"' : ''; ?> />
                        <p class="description"><?php esc_html_e('Please check if you want to enable the direct media upload to the post content for logged in users.', 'frontend-post-submission-manager'); ?></p>
                        <p class="description"><?php echo __(sprintf('Please note that media upload button only shows if logged in user role has the upload_files capabilities. Please check %s here %s for an easy reference.', '<a href="https://wordpress.org/support/article/roles-and-capabilities/#capability-vs-role-table" target="_blank">', '</a>'), 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="fpsm-editor-type-ref <?php echo (!in_array($editor_type, $media_ref_editors)) ? 'fpsm-display-none' : '' ?>">

                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Custom Media Upload Button', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[custom_media_upload_button]" <?php echo (!empty($field_details['custom_media_upload_button'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-custom-media-ref" />
                        <p class="description"><?php esc_html_e('Please check this if you want to enable our custom media uploader which will allow the user roles who doesn\'t have the upload_files capabilities.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-custom-media-ref <?php echo (empty($field_details['custom_media_upload_button'])) ? 'fpsm-display-none' : ''; ?>">
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
                        <label><?php esc_html_e('Upload File Size Error Message', 'frontend-post-submission-manager'); ?></label>
                        <div class="fpsm-field">
                            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[upload_filesize_error_message]" value="<?php echo (!empty($field_details['upload_filesize_error_message'])) ? esc_attr($field_details['upload_filesize_error_message']) : ''; ?>" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Min Character Limit', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="number" min="0" name="<?php echo esc_attr($field_name_prefix); ?>[min_character_limit]" value="<?php echo (!empty($field_details['min_character_limit'])) ? intval($field_details['min_character_limit']) : ''; ?>" />
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Max Character Limit', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="number" min="0" name="<?php echo esc_attr($field_name_prefix); ?>[character_limit]" value="<?php echo (!empty($field_details['character_limit'])) ? intval($field_details['character_limit']) : ''; ?>" />
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Character Limit Error Message', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="form_details[form][fields][<?php echo esc_attr($field_key) ?>][character_limit_error_message]" value="<?php echo (!empty($field_details['character_limit_error_message'])) ? esc_attr($field_details['character_limit_error_message']) : ''; ?>" />
                </div>
            </div>
        </div>
    </div>

</div>