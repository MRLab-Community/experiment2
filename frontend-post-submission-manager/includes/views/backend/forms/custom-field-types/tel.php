<?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
<div class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Min Length', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" name="<?php echo esc_attr($field_name_prefix); ?>[min_length]" value="<?php echo (!empty($field_details['min_length'])) ? intval($field_details['min_length']) : ''; ?>"/>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Max Length', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" name="<?php echo esc_attr($field_name_prefix); ?>[max_length]" value="<?php echo (!empty($field_details['max_length'])) ? intval($field_details['max_length']) : ''; ?>"/>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Pattern', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[pattern]" value="<?php echo (!empty($field_details['pattern'])) ? esc_attr($field_details['pattern']) : ''; ?>"/>
            <p class="description"><?php esc_html_e('Please enter the pattern in which you want the telephone number to validate.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
</div>