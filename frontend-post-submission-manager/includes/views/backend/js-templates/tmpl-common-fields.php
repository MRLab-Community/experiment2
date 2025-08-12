<input type="hidden" name="form_details[form][fields][{{data.field_key}}][field_type]" value="{{data.field_type}}"/>
<div class="fpsm-field-wrap">
    <label><?php esc_html_e('Show on form', 'frontend-post-submission-manager'); ?></label>
    <div class="fpsm-field">
        <input type="checkbox" name="form_details[form][fields][{{data.field_key}}][show_on_form]" value="1"  class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-show-fields-ref-{{data.meta_key}}"/>
    </div>
</div>
<div class="fpsm-show-fields-ref-{{data.meta_key}} fpsm-display-none">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Required', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[form][fields][{{data.field_key}}][required]" value="1" class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-required-message"/>
        </div>
    </div>
    <div class="fpsm-field-wrap fpsm-required-message <?php echo (empty($field_details['required'])) ? 'fpsm-display-none' : ''; ?>">
        <label><?php esc_html_e('Required Error Message', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[form][fields][{{data.field_key}}][required_error_message]"/>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Field Label', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[form][fields][{{data.field_key}}][field_label]" value="{{data.label}}"/>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Field Note', 'frontend-post-submission-manager'); ?></label>
        <div class="fpsm-field">
            <input type="text" name="form_details[form][fields][{{data.field_key}}][field_note]"/>
            <p class="description"><?php esc_html_e('This note will show just below the field. Pleaes leave blank if you don\'t want to display the field note.', 'frontend-post-submission-manager'); ?></p>
        </div>
    </div>
</div>