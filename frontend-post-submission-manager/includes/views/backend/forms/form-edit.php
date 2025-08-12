<?php
if (empty($_GET['form_id'])) {
    return;
}
defined('ABSPATH') or die('No script kiddies please!!');
global $fpsm_library_obj;
$form_id = intval($_GET['form_id']);
$form_row = $fpsm_library_obj->get_form_row_by_id($form_id);
if (empty($form_row)) {
    return;
}
$form_details = (!empty($form_row->form_details)) ? $form_row->form_details : '';
$form_details = maybe_unserialize($form_details);
?>
<div class="wrap fpsm-wrap fpsm-clearfix">
    <div class="fpsm-header fpsm-clearfix">
        <h1 class="fpsm-floatLeft"><?php esc_html_e('Frontend Post Submission Manager', 'frontend-post-submission-manager'); ?></h1>

        <div class="fpsm-add-wrap">
            <a href="javascript:void(0);" class="fpsm-button-primary fpsm-form-save" data-form='fpsm-edit-form'><?php esc_html_e('Save', 'frontend-post-submission-manager'); ?></a>
            <a href="<?php echo site_url() . '?fpsm_form_preview=true&fpsm_form_alias=' . esc_attr($form_row->form_alias) . '&_wpnonce=' . wp_create_nonce('fpsm_preview_nonce'); ?>" class="fpsm-button-primary btn-preview" target="_blank">
                <?php esc_html_e('Preview', 'frontend-post-submission-manager'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=fpsm'); ?>" class="fpsm-button-primary btn-cancel"><?php esc_html_e('Cancel', 'frontend-post-submission-manager'); ?></a>
        </div>


    </div>

    <?php
    /**
     * Form Navigation
     */
    include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-navigation.php');
    ?>
    <form class="fpsm-form-wrap fpsm-edit-form">
        <input type="hidden" name="form_id" value="<?php echo intval($form_id); ?>"/>
        <input type="hidden" name="post_type" value="<?php echo (!empty($form_row->post_type)) ? esc_attr($form_row->post_type) : 'post'; ?>"/>
        <input type="hidden" name="form_type" value="<?php echo (!empty($form_row->form_type)) ? esc_attr($form_row->form_type) : 'login_require'; ?>"/>
        <?php
        /**
         * Fires on start of the form sections
         *
         * @since 1.0.0
         *
         * @param array $form_row
         *
         */
        do_action('fpsm_form_sections_start', $form_row);
        ?>
        <?php
        /**
         * Basic Settings
         */
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/basic-settings.php');
        ?>
        <?php
        if ($form_row->form_type == 'login_require') {
            /**
             * Login Form Settings
             */
            include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/login-form-settings.php');

            /**
             * Dashboard Settings
             */
            include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/dashboard-settings.php');
        }
        ?>
        <?php
        /**
         * Form Fields Settings
         */
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-field-settings.php');
        ?>
        <?php
        /**
         * Layout Settings
         */
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/layout-settings.php');
        ?>
        <?php
        /**
         * Notification Settings
         */
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/notification-settings.php');
        ?>
        <?php
        /**
         * Security Settings
         */
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/security-settings.php');
        ?>
        <?php
        /**
         * Customize Settings
         */
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/customize-settings.php');
        ?>
        <?php
        /**
         * Fires on end of the form sections
         *
         * @since 1.0.0
         *
         * @param array $form_row
         *
         */
        do_action('fpsm_form_sections_end', $form_row);
        ?>

    </form>

</div>