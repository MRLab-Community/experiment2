<div class="fpsm-field-wrap">
    <label><?php esc_html_e('Open in new tab', 'frontend-post-submission-manager'); ?></label>
    <div class="fpsm-field">
        <input type="checkbox" name="<?php echo esc_attr($field_name_prefix) ?>[open_in_new_tab]" value="1" <?php echo (!empty($field_details['open_in_new_tab'])) ? 'checked="checked"' : ''; ?>/>
        <p class="description"><?php esc_html_e('Please check if you want to open the link in new tab.', 'frontend-post-submission-manager'); ?></p>
    </div>
</div>
