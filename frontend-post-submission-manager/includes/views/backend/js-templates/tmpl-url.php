<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span>{{data.label}} <span class="fpsm-field-type-label">- <?php esc_html_e('URL', 'frontend-post-submission-manager'); ?></span></h3>
        <a href="javascript:void(0);" class="fpsm-field-remove-trigger"><span class="dashicons dashicons-trash"></span></a>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-common-fields.php'); ?>
        <div class="fpsm-show-fields-ref-{{data.meta_key}} fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Character Limit', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="number" min="0" name="form_details[form][fields][{{data.field_key}}][character_limit]"/>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e('Character Limit Error Message', 'frontend-post-submission-manager'); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="form_details[form][fields][{{data.field_key}}][character_limit_error_message]"/>
                </div>
            </div>
        </div>
    </div>
</div>