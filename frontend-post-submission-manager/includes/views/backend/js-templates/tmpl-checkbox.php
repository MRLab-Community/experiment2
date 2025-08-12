<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span>{{data.label}} <span class="fpsm-field-type-label">- <?php esc_html_e('Checkbox', 'frontend-post-submission-manager'); ?></span></h3>
        <a href="javascript:void(0);" class="fpsm-field-remove-trigger"><span class="dashicons dashicons-trash"></span></a>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-common-fields.php'); ?>
        <div class="fpsm-show-fields-ref-{{data.meta_key}} fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Checkbox Lists', 'frontend-post-submission-manager'); ?></label>
                <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-options-list.php'); ?>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Save Format', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <select name="<?php echo esc_attr($field_name_prefix); ?>[save_format]">
                        <option value="comma"><?php esc_html_e('Comma Separated', 'frontend-post-submission-manager'); ?></option>
                        <option value="array"><?php esc_html_e('Array Format', 'frontend-post-submission-manager'); ?></option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>