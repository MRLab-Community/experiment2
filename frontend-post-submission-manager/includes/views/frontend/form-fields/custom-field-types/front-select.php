<div class="fpsm-select-field">
    <select name="<?php echo esc_attr($field_key); ?>">
        <?php
        if (!empty($field_details['options'])) {
            foreach ($field_details['options'] as $option_count => $option) {
                ?>
                <option value="<?php echo esc_attr($field_details['values'][$option_count]) ?>" <?php echo ($custom_field_saved_value == $field_details['values'][$option_count]) ? 'selected="selected"' : ''; ?>><?php echo esc_html($option); ?></option>
                <?php
            }
        }
        ?>
    </select>
</div>