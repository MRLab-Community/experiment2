<div class="fpsm-each-dropdown">
    <# if(data.field_type== 'radio'){ #>
    <label>
        <input type="radio" name="form_details[form][fields][{{data.field_key}}][checked_radio]" class="fpsm-checked-radio-ref"/><?php esc_html_e('Checked', 'frontend-post-submission-manager'); ?>
        <input type="hidden" name="form_details[form][fields][{{data.field_key}}][checked][]" value="0" class="fpsm-checked-radio-val"/>
    </label>
    <# } #>
    <input type="text" name="form_details[form][fields][{{data.field_key}}][options][]" placeholder="<?php esc_html_e('Option', 'frontend-post-submission-manager'); ?>"/>
    <input type="text" name="form_details[form][fields][{{data.field_key}}][values][]" placeholder="<?php esc_html_e('Value', 'frontend-post-submission-manager'); ?>"/>
    <span class="dashicons dashicons-trash fpsm-delete-dropdown-trigger"></span>
</div>