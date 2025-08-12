<div class="fpsm-field">
    <div class="fpsm-dropdown-list-wrap fpsm-field-<?php echo esc_attr($field_type); ?>">
        <?php
        if (!empty($field_details['options'])) {
            $option_count = 0;
            foreach ($field_details['options'] as $option) {
                ?>
                <div class="fpsm-each-dropdown">
                    <?php if ($field_type == 'radio') { ?>
                        <label>
                            <input type="radio" name="<?php echo esc_attr($field_name_prefix); ?>[checked_radio]" class="fpsm-checked-radio-ref" <?php checked($field_details['checked'][$option_count], 1); ?>/><?php esc_html_e('Checked', 'frontend-post-submission-manager'); ?>
                            <input type="hidden" name="<?php echo esc_attr($field_name_prefix); ?>[checked][]" value="<?php echo intval($field_details['checked'][$option_count]); ?>" class="fpsm-checked-radio-val"/>
                        </label>
                    <?php } ?>
                    <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[options][]" placeholder="<?php esc_html_e('Option 1', 'frontend-post-submission-manager'); ?>" value="<?php echo esc_attr($option); ?>"/>
                    <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[values][]" placeholder="<?php esc_html_e('Value 1', 'frontend-post-submission-manager'); ?>" value="<?php echo esc_attr($field_details['values'][$option_count]); ?>"/>
                    <span class="dashicons dashicons-trash fpsm-delete-dropdown-trigger"></span>
                </div>
                <?php
                $option_count++;
            }
        }
        ?>
    </div>
    <input type="button" class="button-secondary fpsm-add-option-trigger" value="<?php esc_html_e('Add Option', 'frontend-post-submission-manager'); ?>" data-field-key="<?php echo esc_attr($field_key); ?>" data-field-type="<?php echo esc_attr($field_type); ?>"/>
</div>