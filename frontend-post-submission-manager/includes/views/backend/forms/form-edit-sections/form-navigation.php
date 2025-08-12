
<div class="fpsm-shortcode-wrapper">
    <div class="fpsm-field-wrap fpsm-shortcode-common">
        <label><?php esc_html_e('Form Shortcode', 'frontend-post-submission-manager') ?></label>
        <div class="fpsm-field">
            <span class="fpsm-shortcode-preview">[fpsm alias="<?php echo esc_attr($form_row->form_alias); ?>"]</span>
            <span class="fpsm-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
        </div>
    </div>
    <?php if ($form_row->form_type == 'login_require') { ?>
        <div class="fpsm-field-wrap fpsm-shortcode-common">
            <label><?php esc_html_e('Dashboard Shortcode', 'frontend-post-submission-manager') ?></label>
            <div class="fpsm-field">
                <span class="fpsm-shortcode-preview">[fpsm_dashboard alias="<?php echo esc_attr($form_row->form_alias); ?>"]</span>
                <span class="fpsm-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
            </div>
        </div>
    <?php } ?>
</div>

<div class="fpsm-form-nav-wrap">
    <ul class="fpsm-form-nav">
        <li><a href="javascript:void(0);" class="fpsm-nav-item fpsm-active-nav" data-tab="basic"><span class="dashicons dashicons-admin-generic"></span><?php esc_html_e('Basic', 'frontend-post-submission-manager'); ?></a></li>
        <?php if ($form_row->form_type == 'login_require') { ?>
            <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="login"><span class="dashicons dashicons-lock"></span><?php esc_html_e('Login', 'frontend-post-submission-manager'); ?></a></li>
            <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="dashboard"><span class="dashicons dashicons-dashboard"></span><?php esc_html_e('Dashboard', 'frontend-post-submission-manager'); ?></a></li>
        <?php } ?>
        <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="form"><span class="dashicons dashicons-feedback"></span><?php esc_html_e('Form', 'frontend-post-submission-manager'); ?></a></li>
        <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="layout"><span class="dashicons dashicons-layout"></span><?php esc_html_e('Layout', 'frontend-post-submission-manager'); ?></a></li>
        <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="notification"><span class="dashicons dashicons-email"></span><?php esc_html_e('Notification', 'frontend-post-submission-manager'); ?></a></li>
        <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="security"><span class="dashicons dashicons-shield"></span><?php esc_html_e('Security', 'frontend-post-submission-manager'); ?></a></li>
        <li><a href="javascript:void(0);" class="fpsm-nav-item" data-tab="customize"><span class="dashicons dashicons-admin-customizer"></span><?php esc_html_e('Customize', 'frontend-post-submission-manager'); ?></a></li>
                <?php
                /**
                 * Fires while building the nav
                 *
                 * @since 1.0.0
                 */
                do_action('fpsm_form_nav');
                ?>
    </ul>
</div>