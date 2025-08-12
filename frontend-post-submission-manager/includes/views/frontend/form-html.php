<?php
defined('ABSPATH') or die('No script kiddies please!!');
$form_template = (!empty($form_details['layout']['template'])) ? $form_details['layout']['template'] : 'template-1';
$form_alias_class = 'fpsm-alias-' . $form_row->form_alias;
if (!empty($edit_post)) {
    $post_status = get_post_status($edit_post->ID);
    $current_page_url = $fpsm_library_obj->get_current_page_url();
    if (empty($form_details['dashboard']['disable_post_edit_status'])) {
        if (!empty($form_details['dashboard']['disable_post_edit'])) {
            $disabled_post_edit_status = array('publish');
        } else {
            $disabled_post_edit_status = array();
        }
    } else {
        $disabled_post_edit_status = $form_details['dashboard']['disable_post_edit_status'];
    }
    $post_edit_flag = (in_array($post_status, $disabled_post_edit_status)) ? false : true;
    $user_meta = get_userdata($current_user_id);
    $user_roles = $user_meta->roles;
    if (!empty($form_details['dashboard']['list_all_administrator']) && in_array('administrator', $user_roles)) {
        $post_edit_flag = true;
    }

    if (!$post_edit_flag) {
?>
        <h2><?php esc_html_e('You are not allowed to edit already submitted post', 'frontend-post-submission-manager'); ?>
        </h2>
        <script>
            setTimeout(function() {
                window.location = '<?php echo esc_url($current_page_url); ?>';
            }, 2000);
        </script>
<?php
        die();
    }
}
?>
<form method="post" class="fpsm-front-form <?php echo (!empty($post_edit_flag)) ? 'fpsm-edit-form' : 'fpsm-add-form'; ?> fpsm-<?php echo esc_attr($form_template); ?> <?php echo esc_attr($form_alias_class); ?>" data-alias="<?php echo esc_attr($form_row->form_alias); ?>">
    <?php if (empty($form_details['customize']['hide_form_title'])) { ?>
        <h2 class="fpsm-form-title"><?php echo esc_html($form_row->form_title); ?>
        </h2><?php } ?>

    <input type="hidden" name="form_alias" value="<?php echo esc_attr($form_row->form_alias); ?>" />
    <input type="hidden" name="post_id" value="<?php echo (!empty($edit_post->ID)) ? intval($edit_post->ID) : 0; ?>" class="fpsm-edit-post-id" />
    <input type="hidden" name="previous_post_status" value="<?php echo (empty($edit_post->ID)) ? '' : get_post_status($edit_post->ID); ?>" class="fpsm-previous-post-status" />
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'edit_post' && is_user_logged_in()) {
    ?>
        <input type="hidden" name="action" value="edit_post" />
        <input type="hidden" name="dashboard_url" value="<?php echo esc_url($fpsm_library_obj->get_current_page_url()); ?>" />
    <?php
    }
    ?>
    <?php
    /**
     * Fires at the start of form
     *
     * @since 1.0.0
     */
    do_action('fpsm_form_start', $form_row);
    if (!empty($form_details['form']['fields'])) {
        foreach ($form_details['form']['fields'] as $field_key => $field_details) {
            $field_file = $fpsm_library_obj->generate_field_file($field_key);
            if (file_exists(FPSM_PATH . '/includes/views/frontend/form-fields/front-' . $field_file)) {
                // If field is enabled from the backend
                if (!empty($field_details['show_on_form'])) {
                    $field_class = $fpsm_library_obj->generate_field_class($field_key);
                    if ($fpsm_library_obj->is_taxonomy_key($field_key)) {
                        $field_type = $field_details['field_type'];
                        $field_type_class = ' fpsm-taxonomy-' . $field_type;
                    } elseif ($fpsm_library_obj->is_custom_field_key($field_key)) {
                        $field_type = $field_details['field_type'];

                        $field_type_class = ' fpsm-custom-field-' . $field_type;
                    } else {
                        $field_type_class = '';
                    }
                    $is_hidden = (!empty($field_type) && $field_type == 'hidden') ? true : false;
                    if ($is_hidden) {
                        include(FPSM_PATH . '/includes/views/frontend/form-fields/custom-field-types/front-hidden.php');
                    } else {
    ?>

                        <div class="fpsm-field-wrap<?php echo esc_attr($field_type_class); ?> <?php echo esc_attr($field_class); ?>" data-field-key="<?php echo esc_attr($field_key); ?>">
                            <label><?php echo (!empty($field_details['field_label'])) ? wp_kses_post($field_details['field_label']) : ''; ?></label>
                            <div class="fpsm-field">
                                <?php
                                include(FPSM_PATH . '/includes/views/frontend/form-fields/front-' . $field_file);
                                if (!empty($field_details['field_note'])) {
                                ?>
                                    <div class="fpsm-field-note"><?php echo $fpsm_library_obj->sanitize_html($field_details['field_note']); ?>
                                    </div>
                                <?php
                                } ?>
                            </div>
                            <div class="fpsm-error"></div>
                        </div>
            <?php
                    }
                    /**
                     * Triggers after displaying each field
                     * 
                     * @param string $field_key
                     * @param mixed $field_details
                     * @param mixed $form_row
                     * 
                     * @since 1.3.2
                     */
                    do_action('fpsm_after_form_field', $field_key, $field_details, $form_row);
                }
            }
        }
    }
    /**
     * Captcha
     */
    if (!empty($form_details['security']['frontend_form_captcha'])) {
        $site_key = (!empty($form_details['security']['site_key'])) ? $form_details['security']['site_key'] : '';
        if (!empty($site_key)) {
            ?>

            <div class="fpsm-field-wrap fpsm-captcha-field" data-field-key="captcha">
                <label><?php echo (!empty($form_details['security']['captcha_label'])) ? esc_attr($form_details['security']['captcha_label']) : ''; ?></label>
                <div class="fpsm-field">
                    <div data-field-key="security">
                        <script type="text/javascript" src="//www.google.com/recaptcha/api.js"></script>
                        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($site_key); ?>">
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    /**
     * Fires at the end of form
     *
     * @since 1.0.0
     */
    do_action('fpsm_form_end', $form_row);
    ?>
    <div class="fpsm-field-wrap fpsm-has-submit-btn">
        <div class="fpsm-field">
            <?php
            $default_post_status = (!empty($form_details['basic']['post_status'])) ? $form_details['basic']['post_status'] : 'draft';
            if (empty($form_details['form']['post_status'])) {
                $default_submit_label = (!empty($form_details['form']['submit_button_label'])) ? $form_details['form']['submit_button_label'] : '';
                $form_details['form']['post_status'][$default_post_status]['enable'] = 1;
                $form_details['form']['post_status'][$default_post_status]['label'] = $default_submit_label;
                $form_details['form']['post_status'][$default_post_status]['disable_button_for'] = [];
            }

            foreach ($form_details['form']['post_status'] as $form_post_button_status => $form_post_button_details) {

                if (!empty($form_post_button_details['enable'])) {
                    $button_label = (!empty($form_post_button_details['label'])) ? $form_post_button_details['label'] : esc_attr__('Submit', 'frontend-post-submission-manager');
                    $button_update_label = (!empty($form_post_button_details['update_label'])) ? $form_post_button_details['update_label'] : $button_label;
                    /**
                     * Filters the submit button label before printing
                     *
                     * @param string $button_label
                     * @param string $form_post_button_status
                     * @param array $form_post_button_details
                     *
                     * @since 1.2.1
                     */

                    $button_value = (!empty($_GET['action']) && !empty($_GET['post_id'])) ? $button_update_label : $button_label;
                    $button_value = apply_filters('fpsm_submit_label', $button_value, $form_post_button_status, $form_post_button_details);
                    $disable_button = false;
                    /**
                     * Checking if the current user role is restricted from accessing this button
                     */
                    if (is_user_logged_in()) {
                        $disable_button_roles = (!empty($form_post_button_details['disable_button_for'])) ? $form_post_button_details['disable_button_for'] : [];
                        $user = wp_get_current_user();
                        $user_role = $user->roles[0];
                        if (in_array($user_role, $disable_button_roles)) {
                            $disable_button = true;
                        }
                    }
                    if (!empty($edit_post->ID)) {
                        $post_status = get_post_status($edit_post->ID);
                        if (!empty($post_status) && $post_status == 'publish' && !empty($form_post_button_details['hide_after_post_publish'])) {
                            $disable_button = true;
                        }
                    }
                    if (!$disable_button) {
            ?>

                        <input
                            type="submit"
                            value="<?php echo esc_attr($button_value); ?>"
                            data-post-status="<?php echo esc_attr($form_post_button_status); ?>"
                            class="fpsm-submit-<?php echo esc_attr($form_post_button_status); ?> <?php echo (!empty($form_post_button_details['auto_draft'])) ? 'fpsm-auto-draft' : ''; ?>"
                            <?php if (!empty($form_post_button_details['auto_draft'])) {
                            ?> data-auto-save-time="<?php echo esc_attr($form_post_button_details['auto_draft_save_time']) ?>"
                            data-background-save="<?php echo (!empty($form_post_button_details['background_save'])) ? 1 : 0 ?>"
                            <?php
                            }
                            if (!empty($form_post_button_details['background_color'])) {
                            ?>style="background-color: <?php echo esc_attr($form_post_button_details['background_color']); ?>"
                            <?php
                            } ?> />

            <?php
                    }
                }
            }
            ?>
            <input type="hidden" name="dynamic_post_status" value="<?php echo esc_attr($default_post_status); ?>" class="fpsm-default-post-status" />
            <img src="<?php echo FPSM_URL . '/assets/images/ajax-loader-front.gif'; ?>" class="fpsm-ajax-loader" />
        </div>
    </div>
    <div class="fpsm-form-message fpsm-display-none"></div>
    <?php if (!empty($form_details['form']['back_button_label']) && isset($_GET['action'], $_GET['post_id'])) { ?>
        <a class="fpsm-back-dashboard" href="<?php echo esc_url($fpsm_library_obj->get_current_page_url()); ?>"><?php echo esc_html($form_details['form']['back_button_label']); ?></a>
    <?php } ?>
</form>
<?php
include(FPSM_PATH . '/includes/cores/form-customize.php');
