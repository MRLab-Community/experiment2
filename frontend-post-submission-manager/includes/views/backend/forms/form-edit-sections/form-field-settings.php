<?php
$form_settings = (!empty($form_details['form'])) ? $form_details['form'] : array();
$default_fields = $fpsm_library_obj->get_default_fields($form_row->post_type,$form_row->form_type);
?>
<div class="fpsm-settings-each-section fpsm-display-none fpsm-clearfix" data-tab="form">
    <div class="fpsm-form-fields-wrap ">
        <div class="fpsm-form-fields-list">
            <h3 class="fpsm-form-settings-heading">
                <?php esc_html_e('Form Fields', 'frontend-post-submission-manager'); ?>
            </h3>
            <div class="fpsm-sortable">
                <?php
                $post_type = $form_row->post_type;
                $form_type = $form_row->form_type;
                $form_fields = (!empty($form_settings['fields'])) ? $form_settings['fields'] : $fpsm_library_obj->get_default_fields($post_type, $form_type);
                $form_fields = array_merge($default_fields,$form_fields);
                foreach ($form_fields as $field_key => $field_details) {
                    $field_file = $fpsm_library_obj->generate_field_file($field_key);
                    if (file_exists(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/' . $field_file)) {
                        $field_name_prefix = "form_details[form][fields][$field_key]";
                        $show_hide_toggle_class = $field_key;
                        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/' . $field_file);
                    }
                }
                ?>
            </div>
        </div>
        <div class="fpsm-form-other-settings">

            <?php
            if ($form_row->form_type == 'login_require' || $form_row->form_type == 'guest') {
            ?>
                <h3 class="fpsm-form-settings-heading">
                    <?php esc_html_e('Post Status Specific Submit Buttons', 'frontend-post-submission-manager'); ?>
                </h3>

                <div class="fpsm-dynamic-post-status-wrap fpsm-sortable">
                    <?php
                    $post_statuses = get_post_statuses();
                    if (empty($form_details['form']['post_status'])) {
                        $default_post_status = (!empty($basic_settings['post_status'])) ? $basic_settings['post_status'] : 'draft';
                        $default_submit_label = (!empty($form_details['form']['submit_button_label'])) ? $form_details['form']['submit_button_label'] : '';
                        $form_details['form']['post_status'][$default_post_status]['enable'] = 1;
                        $form_details['form']['post_status'][$default_post_status]['label'] = $default_submit_label;
                    }
                    foreach ($post_statuses as $post_status => $post_status_label) {
                        $field_name_prefix = "form_details[form][post_status][$post_status]";
                        $post_status_details = (!empty($form_details['form']['post_status'][$post_status])) ? $form_details['form']['post_status'][$post_status] : array(); ?>
                        <div class="fpsm-each-form-field">
                            <div class="fpsm-field-head fpsm-clearfix ui-sortable-handle">
                                <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php echo esc_html($post_status_label); ?>
                                </h3>
                            </div>
                            <div class="fpsm-field-body fpsm-display-none">
                                <div class="fpsm-field-wrap">
                                    <label><?php esc_html_e('Enable', 'frontend-post-submission-manager'); ?></label>
                                    <div class="fpsm-field">
                                        <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[enable]" value="1" <?php echo (!empty($post_status_details['enable'])) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-show-fields-ref-<?php echo esc_attr($post_status); ?>" />
                                    </div>
                                </div>
                                <div class="fpsm-show-fields-ref-<?php echo esc_attr($post_status); ?> <?php echo (empty($post_status_details['enable'])) ? 'fpsm-display-none' : ''; ?>">
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Label', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <input type="text" name="<?php echo esc_attr($field_name_prefix) ?>[label]" value="<?php echo (!empty($post_status_details['label'])) ? esc_attr($post_status_details['label']) : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Update Label', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <input type="text" name="<?php echo esc_attr($field_name_prefix) ?>[update_label]" value="<?php echo (!empty($post_status_details['update_label'])) ? esc_attr($post_status_details['update_label']) : ''; ?>" />
                                            <p class="description"><?php esc_html_e('Please enter the label you want to display when the post is edited. If kept blank, label will be displayed.', 'frontend-post-submission-manager'); ?></p>
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Background Color', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <input type="text" name="<?php echo esc_attr($field_name_prefix) ?>[background_color]" value="<?php echo (!empty($post_status_details['background_color'])) ? esc_attr($post_status_details['background_color']) : ''; ?>" class="fpsm-color-picker" />
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Success Message', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <textarea name="<?php echo esc_attr($field_name_prefix) ?>[success_message]"><?php echo (!empty($post_status_details['success_message'])) ? wp_kses_post($post_status_details['success_message']) : ''; ?></textarea>
                                            <p class="description">
                                                <?php esc_html_e('Please note that if not kept blank, this message will override the form success message from basic settings.', 'frontend-post-submission-manager'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Disable Admin Notification', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[disable_admin_notification]" value="1" <?php echo (!empty($post_status_details['disable_admin_notification'])) ? 'checked="checked"' : ''; ?> />
                                            <p class="description">
                                                <?php esc_html_e('Please check if you want to disable admin email notification configured in the notification settings when users submits post with this post status button.', 'frontend-post-submission-manager'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Disable Post Submit Notification', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[disable_post_submit_notification]" value="1" <?php echo (!empty($post_status_details['disable_post_submit_notification'])) ? 'checked="checked"' : ''; ?> />
                                            <p class="description">
                                                <?php esc_html_e('Please check if you want to disable post submit email notification configured in the notification settings when users submits posts with this post status button', 'frontend-post-submission-manager'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Disable this button for', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <?php
                                            $disable_button_for = (!empty($post_status_details['disable_button_for'])) ? $post_status_details['disable_button_for'] : [];
                                            $user_roles = $fpsm_library_obj->get_user_roles();
                                            if (!empty($user_roles)) {
                                                foreach ($user_roles as $user_role_key => $user_role_details) {
                                            ?>
                                                    <label class="fpsm-display-block"><input class="fpsm-disable-checkbox-toggle" type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[disable_button_for][]" value="<?php echo esc_attr($user_role_key); ?>" <?php echo (in_array($user_role_key, $disable_button_for)) ? 'checked="checked"' : ''; ?> /><?php echo esc_html($user_role_details['name']) ?></label>
                                            <?php
                                                }
                                            }
                                            ?>

                                            <p class="description">
                                                <?php esc_html_e('Please check if you want to hide this button for specific user roles.', 'frontend-post-submission-manager'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="fpsm-field-wrap">
                                        <label><?php esc_html_e('Hide after Post Publish', 'frontend-post-submission-manager'); ?></label>
                                        <div class="fpsm-field">
                                            <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[hide_after_post_publish]" value="1" <?php echo (!empty($post_status_details['hide_after_post_publish'])) ? 'checked="checked"' : ''; ?> />
                                            <p class="description">
                                                <?php esc_html_e('Please check if you want to hide this submit button after post has been published.', 'frontend-post-submission-manager'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php
                                    if ($post_status == 'draft') {
                                    ?>


                                        <div class="fpsm-field-wrap">
                                            <label><?php esc_html_e('Disable Field Required Check', 'frontend-post-submission-manager'); ?></label>
                                            <div class="fpsm-field">
                                                <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[disable_field_required_check]" value="1" <?php echo (!empty($post_status_details['disable_field_required_check'])) ? 'checked="checked"' : ''; ?> />
                                                <p class="description">
                                                    <?php esc_html_e('Please check if you want to disable the field required validation on draft save.', 'frontend-post-submission-manager'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="fpsm-field-wrap">
                                            <label><?php esc_html_e('Auto Draft', 'frontend-post-submission-manager'); ?></label>
                                            <div class="fpsm-field">
                                                <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[auto_draft]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class='fpsm-auto-draft-save-time' <?php echo (!empty($post_status_details['auto_draft'])) ? 'checked="checked"' : ''; ?> />
                                                <p class="description">
                                                    <?php esc_html_e('Please check if you want to enable the auto draft save. Please note that this will only work if you haven\'t enabled google reCaptcha in the form.', 'frontend-post-submission-manager'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="fpsm-field-wrap fpsm-auto-draft-save-time <?php echo (empty($post_status_details['auto_draft_save_time'])) ? 'fpsm-display-none' : ''; ?>">
                                            <label><?php esc_html_e('Auto Draft Save Time', 'frontend-post-submission-manager'); ?></label>
                                            <div class="fpsm-field">
                                                <input type="text" name="<?php echo esc_attr($field_name_prefix) ?>[auto_draft_save_time]" value="<?php echo (!empty($post_status_details['auto_draft_save_time'])) ? esc_attr($post_status_details['auto_draft_save_time']) : ''; ?>" />
                                                <p class="description">
                                                    <?php esc_html_e('Please enter the time interval in seconds in which the draft shall be auto saved.', 'frontend-post-submission-manager'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="fpsm-field-wrap">
                                            <label><?php esc_html_e('Background Save', 'frontend-post-submission-manager'); ?></label>
                                            <div class="fpsm-field">
                                                <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[background_save]" value="1" <?php echo (!empty($post_status_details['background_save'])) ? 'checked="checked"' : ''; ?> />
                                                <p class="description">
                                                    <?php esc_html_e('Please check if you want to run the auto save in the background without displaying the draft save message.', 'frontend-post-submission-manager'); ?>
                                                </p>
                                            </div>
                                        </div>

                                    <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    } ?>

                </div>

            <?php
            }
            ?>
            <h3 class="fpsm-form-settings-heading">
                <?php esc_html_e('Other Settings', 'frontend-post-submission-manager'); ?>
            </h3>
            <?php
            if ($form_row->form_type == 'guest') {
            ?>

                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Submit Button Label', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[form][submit_button_label]" value="<?php echo (!empty($form_settings['submit_button_label'])) ? esc_attr($form_settings['submit_button_label']) : ''; ?>" />
                    </div>
                </div>
            <?php
            }
            if ($form_row->form_type == 'login_require') {
            ?>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Back Button Label', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[form][back_button_label]" value="<?php echo (!empty($form_settings['back_button_label'])) ? esc_attr($form_settings['back_button_label']) : '' ?>" />
                        <p class="description">
                            <?php esc_html_e('Please enter the label for the back to dashboard link in the post edit form. Please leave blank if you don\'t want to display the back to dashboard link. ', 'frontend-post-submission-manager'); ?>
                        </p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/custom-field-add-form.php'); ?>
</div>