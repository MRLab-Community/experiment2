<div class="fpsm-date-picker">
    <?php
    if (!empty($field_details['string_format']) && !empty($custom_field_saved_value)) {
        $custom_field_saved_value = date('Y-m-d', $custom_field_saved_value);
    }
    ?>
    <input autocomplete="off" type="text" name="<?php echo esc_attr($field_key); ?>" class="fpsm-front-datepicker" data-date-format="<?php echo esc_attr($field_details['date_format']); ?>" data-date-value="<?php echo esc_attr($custom_field_saved_value); ?>"/>
</div>