
<?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
<div class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Radio Button Lists', 'frontend-post-submission-manager'); ?></label>
        <?php include(FPSM_PATH . '/includes/views/backend/forms/custom-field-types/options-list.php'); ?>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Display Type', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <select name="<?php echo esc_attr($field_name_prefix) ?>[display_type]">
                <?php
                $display_type = (!empty($field_details['display_type'])) ? $field_details['display_type'] : 'inline';
                ?>
                <option value="inline" <?php selected($display_type, 'inline'); ?>><?php esc_html_e('Inline', 'frontend-post-submission-manager'); ?></option>
                <option value="block" <?php selected($display_type, 'block'); ?>><?php esc_html_e('Block', 'frontend-post-submission-manager'); ?></option>
            </select>
        </div>
    </div>
</div>
