<div class="fpsm-field-wrap">
    <label><?php esc_html_e('Show on form', 'frontend-post-submission-manager'); ?></label>
    <div class="fpsm-field">
        <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[show_on_form]" value="1"
            <?php echo (!empty($field_details['show_on_form'])) ? 'checked="checked"' : ''; ?>
            class="fpsm-checkbox-toggle-trigger"
            data-toggle-class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?>" />
    </div>
</div>
<div
    class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Required', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="<?php echo esc_attr($field_name_prefix); ?>[required]" value="1"
                <?php echo (!empty($field_details['required'])) ? 'checked="checked"' : ''; ?>
                class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-required-message" />
        </div>
    </div>
    <div
        class="fpsm-field-wrap fpsm-required-message <?php echo (empty($field_details['required'])) ? 'fpsm-display-none' : ''; ?>">
        <label><?php esc_html_e('Required Error Message', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[required_error_message]"
                value="<?php echo (!empty($field_details['required_error_message'])) ? esc_attr($field_details['required_error_message']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Field Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[field_label]"
                value="<?php echo (!empty($field_details['field_label'])) ? esc_attr($field_details['field_label']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Field Note', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <textarea
                name="<?php echo esc_attr($field_name_prefix); ?>[field_note]"><?php echo (!empty($field_details['field_note'])) ? $fpsm_library_obj->sanitize_html($field_details['field_note']) : ''; ?></textarea>
            <p class="description">
                <?php esc_html_e('This note will show just below the field. Pleaes leave blank if you don\'t want to display the field note.', 'frontend-post-submission-manager'); ?>
            </p>
        </div>
    </div>
</div>