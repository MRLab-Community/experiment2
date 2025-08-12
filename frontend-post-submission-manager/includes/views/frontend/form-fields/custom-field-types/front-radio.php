<?php
$display_type = $field_details['display_type'];
$display_class = 'fpsm-radio-' . $display_type;
?>
<div class="fpsm-checkbox-list-wrap <?php echo esc_attr($display_class); ?>">
    <?php
    if (!empty($field_details['options'])) {
    ?>

        <?php
        foreach ($field_details['options'] as $option_count => $option) {
            $saved_checked = '';
            if (!empty($custom_field_saved_value)) {
                $saved_checked = ($custom_field_saved_value == $field_details['values'][$option_count]) ? 'checked="checked"' : '';
            }
            if (empty($saved_checked)) {
                $saved_checked = (!empty($field_details['checked'][$option_count])) ? "checked='checked'" : '';
                
            }
        ?>
            <div class="fpsm-radio">
                <input type="radio" name="<?php echo esc_attr($field_key); ?>" value="<?php echo esc_attr($field_details['values'][$option_count]); ?>" <?php echo esc_attr($saved_checked); ?> />
                <label for="<?php echo esc_attr($field_key); ?>"><?php echo esc_html($option); ?></label>
            </div>
        <?php
        }
        ?>

    <?php
    }
    ?>
</div>