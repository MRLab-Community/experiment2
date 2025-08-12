<?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
<div class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Editor Type', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <?php
            $editor_type = (!empty($field_details['editor_type'])) ? $field_details['editor_type'] : 'simple';
            ?>
            <select name="<?php echo esc_attr($field_name_prefix); ?>[editor_type]" class="fpsm-editor-type">
                <option value="rich" <?php selected($editor_type, 'rich'); ?>><?php esc_html_e('Rich Text Editor', 'frontend-post-submission-manager'); ?></option>
                <option value="visual" <?php selected($editor_type, 'visual'); ?>><?php esc_html_e('Visual Text Editor', 'frontend-post-submission-manager'); ?></option>
                <option value="html" <?php selected($editor_type, 'html'); ?>><?php esc_html_e('HTML Text Editor', 'frontend-post-submission-manager'); ?></option>
            </select>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Editor Height', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" name="<?php echo esc_attr($field_name_prefix); ?>[editor_height]" value="<?php echo (!empty($field_details['editor_height'])) ? esc_attr($field_details['editor_height']) : ''; ?>" min="1" />
            <p class="description"><?php esc_html_e('Please enter the height of the editor in px if you want to increase or decrease the default height.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Min Character Limit', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" min="0" name="<?php echo esc_attr($field_name_prefix); ?>[min_character_limit]" value="<?php echo (!empty($field_details['min_character_limit'])) ? intval($field_details['min_character_limit']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Max Character Limit', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="number" min="0" name="<?php echo esc_attr($field_name_prefix); ?>[character_limit]" value="<?php echo (!empty($field_details['character_limit'])) ? intval($field_details['character_limit']) : ''; ?>" />
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Character Limit Error Message', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[form][fields][<?php echo esc_attr($field_key) ?>][character_limit_error_message]" value="<?php echo (!empty($field_details['character_limit_error_message'])) ? esc_attr($field_details['character_limit_error_message']) : ''; ?>" />
        </div>
    </div>
</div>