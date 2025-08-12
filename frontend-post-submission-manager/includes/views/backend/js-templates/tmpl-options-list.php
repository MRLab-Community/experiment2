<div class="fpsm-field">
    <div class="fpsm-dropdown-list-wrap fpsm-field-{{data.field_type}}">

        <?php
        for ($i = 1; $i <= 2; $i++) {
            ?>
            <div class="fpsm-each-dropdown">
                <# if(data.field_type== 'radio'){ #>
                <label>
                    <input type="radio" name="<?php echo esc_attr($field_name_prefix); ?>[checked_radio]" class="fpsm-checked-radio-ref"/><?php esc_html_e('Checked', 'frontend-post-submission-manager'); ?>
                    <input type="hidden" name="<?php echo esc_attr($field_name_prefix); ?>[checked][]" value="0" class="fpsm-checked-radio-val"/>
                </label>
                <# } #>
                <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[options][]" placeholder="<?php esc_html_e('Option', 'frontend-post-submission-manager'); ?>"/>
                <input type="text" name="<?php echo esc_attr($field_name_prefix); ?>[values][]" placeholder="<?php esc_html_e('Value', 'frontend-post-submission-manager'); ?>"/>
                <span class="dashicons dashicons-trash fpsm-delete-dropdown-trigger"></span>
            </div>
            <?php
        }
        ?>
    </div>
    <input type="button" class="button-secondary fpsm-add-option-trigger" value="<?php esc_html_e('Add Option', 'frontend-post-submission-manager'); ?>" data-field-key="{{data.field_key}}" data-field-type='{{data.field_type}}'/>
</div>