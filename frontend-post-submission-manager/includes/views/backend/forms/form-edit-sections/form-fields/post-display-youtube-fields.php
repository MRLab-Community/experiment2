<div class="fpsm-field-wrap">
    <label><?php esc_html_e('Embed Width', 'frontend-post-submission-manager'); ?></label>
    <div class="fpsm-field">
        <input type="text" name="<?php echo esc_attr($field_name_prefix) ?>[embed_width]" value="<?php echo (!empty($field_details['embed_width'])) ? esc_attr($field_details['embed_width']) : ''; ?>" />
        <p class="description"><?php esc_html_e('Please enter the width of the youtube embed to be displayed. It can be either in px or %', 'frontend-post-submission-manager'); ?></p>
    </div>
</div>
<div class="fpsm-field-wrap">
    <label><?php esc_html_e('Embed Height', 'frontend-post-submission-manager'); ?></label>
    <div class="fpsm-field">
        <input type="text" name="<?php echo esc_attr($field_name_prefix) ?>[embed_height]" value="<?php echo (!empty($field_details['embed_height'])) ? esc_attr($field_details['embed_height']) : ''; ?>"/>
        <p class="description"><?php esc_html_e('Please enter the height of the youtube embed to be displayed. It can be either in px or %', 'frontend-post-submission-manager'); ?></p>
    </div>
</div>
