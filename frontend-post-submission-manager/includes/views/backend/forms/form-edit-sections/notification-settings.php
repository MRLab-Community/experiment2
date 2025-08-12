<div class="fpsm-settings-each-section fpsm-display-none" data-tab="notification">
    <div class="fpsm-each-form-field">
        <div class="fpsm-field-head fpsm-clearfix">
            <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Admin Notification', 'frontend-post-submission-manager'); ?></h3>
        </div>
        <div class="fpsm-field-body fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Enable', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="form_details[notification][admin][enable]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-show-fields-ref-admin-notification" <?php echo (!empty($form_details['notification']['admin']['enable'])) ? 'checked="checked"' : ''; ?>>
                </div>
            </div>
            <div class="fpsm-show-fields-ref-admin-notification <?php echo (empty($form_details['notification']['admin']['enable'])) ? 'fpsm-display-none' : ''; ?>">
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Notification Emails', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field fpsm-checkbox-toggle">
                        <input type="text" name="form_details[notification][admin][notification_emails]" value="<?php echo (!empty($form_details['notification']['admin']['notification_emails'])) ? esc_attr($form_details['notification']['admin']['notification_emails']) : '' ?>">
                        <p class="description"><?php esc_html_e('Please enter the emails in which you want to receive notifications separated by comma. If kept blank, it will go to admin email.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Subject', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field fpsm-checkbox-toggle">
                        <input type="text" name="form_details[notification][admin][subject]" value="<?php echo (!empty($form_details['notification']['admin']['subject'])) ? esc_attr($form_details['notification']['admin']['subject']) : '' ?>">
                        <p class="description"><?php esc_html_e('You can use [post_title] and [author_name] to replace it with submitted post title and author name in the subject while receiving the post admin notification.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap fpsm-required-message ">
                    <label><?php esc_html_e('From name', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][admin][from_name]" value="<?php echo (!empty($form_details['notification']['admin']['from_name'])) ? esc_attr($form_details['notification']['admin']['from_name']) : ''; ?>">
                        <p class="description"><?php esc_html_e('You can use [author_name] as the form name to replace it with the post submitted author name. Please note that the form should contain Author Name field if the form is guest form.', 'frontend-post-submission-manager'); ?></p>

                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('From Email', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][admin][from_email]" value="<?php echo (!empty($form_details['notification']['admin']['from_email'])) ? esc_attr($form_details['notification']['admin']['from_email']) : ''; ?>">
                        <p class="description"><?php esc_html_e('You can use [author_email] as the form email to replace it with the post submitted author email. Please note that the form should contain Author Email field if the form is guest form.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Message', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <textarea name="form_details[notification][admin][notification_message]"><?php echo (!empty($form_details['notification']['admin']['notification_message'])) ? $fpsm_library_obj->output_converting_br($form_details['notification']['admin']['notification_message']) : $fpsm_library_obj->default_admin_notification(); ?></textarea>
                        <p class="description"><?php esc_html_e('Please use [post_title],[post_admin_link] to replace it with the submitted post title and post admin link in the post submission admin notification email message.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="fpsm-each-form-field">
        <div class="fpsm-field-head fpsm-clearfix">
            <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Post Publish Notification', 'frontend-post-submission-manager'); ?></h3>
        </div>
        <div class="fpsm-field-body fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Enable', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="form_details[notification][post_publish][enable]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-show-fields-ref-post-notification" <?php echo (!empty($form_details['notification']['post_publish']['enable'])) ? 'checked="checked"' : ''; ?>>
                </div>
            </div>
            <div class="fpsm-show-fields-ref-post-notification <?php echo (empty($form_details['notification']['post_publish']['enable'])) ? 'fpsm-display-none' : ''; ?>">
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Subject', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field fpsm-checkbox-toggle">
                        <input type="text" name="form_details[notification][post_publish][subject]" value="<?php echo (!empty($form_details['notification']['post_publish']['subject'])) ? esc_attr($form_details['notification']['post_publish']['subject']) : '' ?>">
                        <p class="description"><?php esc_html_e('You can use [post_title] and [author_name] to replace it with submitted post title and author name in the subject while sending the post publish notification.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap fpsm-required-message ">
                    <label><?php esc_html_e('From name', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][post_publish][from_name]" value="<?php echo (!empty($form_details['notification']['post_publish']['from_name'])) ? esc_attr($form_details['notification']['post_publish']['from_name']) : ''; ?>">
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('From Email', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][post_publish][from_email]" value="<?php echo (!empty($form_details['notification']['post_publish']['from_email'])) ? esc_attr($form_details['notification']['post_publish']['from_email']) : ''; ?>">
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Message', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <textarea name="form_details[notification][post_publish][notification_message]"><?php echo (!empty($form_details['notification']['post_publish']['notification_message'])) ? $fpsm_library_obj->output_converting_br($form_details['notification']['post_publish']['notification_message']) : $fpsm_library_obj->default_publish_notification(); ?></textarea>
                        <p class="description"><?php esc_html_e('Please use [author_name],[post_title],[post_link] to replace with the approved post\'s author name, post title and post frontend link in the post publish email message.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="fpsm-each-form-field">
        <div class="fpsm-field-head fpsm-clearfix">
            <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Post Submit Notification', 'frontend-post-submission-manager'); ?></h3>
        </div>
        <div class="fpsm-field-body fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Enable', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="form_details[notification][post_submit][enable]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-show-fields-ref-post-notification" <?php echo (!empty($form_details['notification']['post_submit']['enable'])) ? 'checked="checked"' : ''; ?>>
                </div>
            </div>
            <div class="fpsm-show-fields-ref-post-notification <?php echo (empty($form_details['notification']['post_submit']['enable'])) ? 'fpsm-display-none' : ''; ?>">
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Subject', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field fpsm-checkbox-toggle">
                        <input type="text" name="form_details[notification][post_submit][subject]" value="<?php echo (!empty($form_details['notification']['post_submit']['subject'])) ? esc_attr($form_details['notification']['post_submit']['subject']) : '' ?>">
                        <p class="description"><?php esc_html_e('You can use [post_title] and [author_name] to replace it with submitted post title and author name in the subject while sending the post submit notification.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap fpsm-required-message ">
                    <label><?php esc_html_e('From name', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][post_submit][from_name]" value="<?php echo (!empty($form_details['notification']['post_submit']['from_name'])) ? esc_attr($form_details['notification']['post_submit']['from_name']) : ''; ?>">
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('From Email', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][post_submit][from_email]" value="<?php echo (!empty($form_details['notification']['post_submit']['from_email'])) ? esc_attr($form_details['notification']['post_submit']['from_email']) : ''; ?>">
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Message', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <textarea name="form_details[notification][post_submit][notification_message]"><?php echo (!empty($form_details['notification']['post_submit']['notification_message'])) ? $fpsm_library_obj->output_converting_br($form_details['notification']['post_submit']['notification_message']) : $fpsm_library_obj->default_submit_notification(); ?></textarea>
                        <p class="description"><?php esc_html_e('Please use [author_name] & [post_title] to replace with the submitted post\'s author name and  post title in the post submit email message.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="fpsm-each-form-field">
        <div class="fpsm-field-head fpsm-clearfix">
            <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Post Trash Notification', 'frontend-post-submission-manager'); ?></h3>
        </div>
        <div class="fpsm-field-body fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Enable', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="form_details[notification][post_trash][enable]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-show-fields-ref-trash-notification" <?php echo (!empty($form_details['notification']['post_trash']['enable'])) ? 'checked="checked"' : ''; ?>>
                </div>
            </div>
            <div class="fpsm-show-fields-ref-trash-notification <?php echo (empty($form_details['notification']['post_trash']['enable'])) ? 'fpsm-display-none' : ''; ?>">
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Subject', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field fpsm-checkbox-toggle">
                        <input type="text" name="form_details[notification][post_trash][subject]" value="<?php echo (!empty($form_details['notification']['post_trash']['subject'])) ? esc_attr($form_details['notification']['post_trash']['subject']) : '' ?>">
                        <p class="description"><?php esc_html_e('You can use [post_title] and [author_name] to replace it with submitted post title and author name in the subject while sending the post trash notification.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
                <div class="fpsm-field-wrap fpsm-required-message ">
                    <label><?php esc_html_e('From name', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][post_trash][from_name]" value="<?php echo (!empty($form_details['notification']['post_trash']['from_name'])) ? esc_attr($form_details['notification']['post_trash']['from_name']) : ''; ?>">
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('From Email', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <input type="text" name="form_details[notification][post_trash][from_email]" value="<?php echo (!empty($form_details['notification']['post_trash']['from_email'])) ? esc_attr($form_details['notification']['post_trash']['from_email']) : ''; ?>">
                    </div>
                </div>
                <div class="fpsm-field-wrap">
                    <label><?php esc_html_e('Message', 'frontend-post-submission-manager'); ?></label>
                    <div class="fpsm-field">
                        <textarea name="form_details[notification][post_trash][notification_message]"><?php echo (!empty($form_details['notification']['post_trash']['notification_message'])) ? $fpsm_library_obj->output_converting_br($form_details['notification']['post_trash']['notification_message']) : $fpsm_library_obj->default_trash_notification(); ?></textarea>
                        <p class="description"><?php esc_html_e('Please use [author_name] and [post_title] to replace with the rejected post\'s author name and post title in the email message.', 'frontend-post-submission-manager'); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>