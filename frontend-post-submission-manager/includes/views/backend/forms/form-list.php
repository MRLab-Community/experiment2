<?php
defined('ABSPATH') or die('No script kiddies please!!');
?>
<div class="wrap fpsm-wrap">
    <div class="fpsm-header">
        <h1 class="fpsm-floatLeft">
            <?php esc_html_e('Frontend Post Submission Manager', 'frontend-post-submission-manager'); ?>
        </h1>
        <div class="fpsm-add-wrap">
            <a
                href="<?php echo admin_url('admin.php?page=fpsm-add-new-form'); ?>"><input
                    type="button" class="fpsm-button-primary"
                    value="<?php esc_html_e('Add New Form', 'frontened-post-submission-manager'); ?>" /></a>
        </div>

    </div>

    <div class="fpsm-grid-wrap">
        <div class="fpsm-title-wrap">
            <h2><?php esc_html_e('Form Lists', 'frontend-post-submission-manager'); ?>
            </h2>

        </div>
        <table class="wp-list-table widefat fixed fpsm-form-lists-table">
            <thead>
                <tr>
                    <th>
                        <?php esc_html_e('Form Title', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th>
                        <?php esc_html_e('Shortcode', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th>
                        <?php esc_html_e('Post Type', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th>
                        <?php esc_html_e('Form Type', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th>
                        <?php esc_html_e('Status', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th>
                        <?php esc_html_e('Action', 'frontend-post-submission-manager'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                $form_table = FPSM_FORM_TABLE;
                $form_rows = $wpdb->get_results("select * from $form_table order by form_title asc");
                if (!empty($form_rows)) {
                    foreach ($form_rows as $form_row) {
                        ?>
                <tr>
                    <td><a
                            href="<?php echo admin_url('admin.php?page=fpsm&form_id=' . intval($form_row->form_id) . '&action=edit_form'); ?>"><?php echo esc_html($form_row->form_title); ?></a>
                    </td>
                    <td>
                        <div class="fpsm-shortcode-display">
                            <span class="fpsm-shortcode-preview">[fpsm alias="<?php echo esc_html(($form_row->form_alias)); ?>"]</span>
                            <span class="fpsm-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
                        </div>
                        <?php if ($form_row->form_type == 'login_require') {
                            ?>
                        <div class="fpsm-shortcode-display">
                            <span class="fpsm-shortcode-preview">[fpsm_dashboard alias="<?php echo esc_html(($form_row->form_alias)); ?>"]</span>
                            <span class="fpsm-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
                        </div>
                        <?php
                        } ?>
                    </td>
                    <td><?php echo esc_html($form_row->post_type); ?>
                    </td>
                    <td><?php
                                $form_type_label = array('login_require' => esc_html__('Login Require Form'), 'guest' => esc_html__('Guest Form', 'frontend-post-submission-manager'));
                        echo esc_html($form_type_label[$form_row->form_type]); ?>
                    </td>
                    <td><?php echo (!empty($form_row->form_status)) ? esc_html__('Active', 'frontend-post-submission-manager') : esc_html__('Inactive', 'frontend-post-submission-manager'); ?>
                    </td>
                    <td>
                        <a class="fpsm-edit"
                            href="<?php echo admin_url('admin.php?page=fpsm&form_id=' . intval($form_row->form_id) . '&action=edit_form'); ?>"
                            title="<?php esc_html_e('Edit Form', 'frontend-post-submission-manager'); ?>"><?php esc_html_e('Edit', 'frontend-post-submission-manager'); ?></a>
                        <a class="fpsm-copy fpsm-form-copy" href="javascript:void(0);"
                            data-form-id="<?php echo intval($form_row->form_id); ?>"
                            title="<?php esc_html_e('Copy Form', 'frontend-post-submission-manager'); ?>"><?php esc_html_e('Copy', 'frontend-post-submission-manager'); ?></a>
                        <a class="fpsm-preview"
                            href="<?php echo site_url() . '?fpsm_form_preview=true&fpsm_form_alias=' . esc_attr($form_row->form_alias) . '&_wpnonce=' . wp_create_nonce('fpsm_preview_nonce'); ?>"
                            target="_blank"
                            title="<?php esc_html_e('Preview', 'frontend-post-submission-manager'); ?>"><?php esc_html_e('Preview', 'frontend-post-submission-manager'); ?></a>
                        <a class="fpsm-delete fpsm-form-delete" href="javascript:void(0)"
                            data-form-id="<?php echo intval($form_row->form_id); ?>"
                            title="<?php esc_html_e('Delete Form', 'frontend-post-submission-manager'); ?>"><?php esc_html_e('Delete', 'frontend-post-submission-manager'); ?></a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    ?>
                <tr>
                    <td colspan="5"><?php esc_html_e('No forms added yet.', 'frontend-post-submission-manager'); ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?php esc_html_e('Form Title', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th><?php esc_html_e('Shortcode', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th><?php esc_html_e('Post Type', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th><?php esc_html_e('Form Type', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th><?php esc_html_e('Status', 'frontend-post-submission-manager'); ?>
                    </th>
                    <th><?php esc_html_e('Action', 'frontend-post-submission-manager'); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>