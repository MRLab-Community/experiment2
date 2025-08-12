
<?php include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php'); ?>
<div class="fpsm-show-fields-ref-<?php echo (!empty($show_hide_toggle_class)) ? esc_attr($show_hide_toggle_class) : esc_attr($field_key); ?> <?php echo (empty($field_details['show_on_form'])) ? 'fpsm-display-none' : ''; ?>">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e('Dropdown Lists', 'frontend-post-submission-manager'); ?></label>
        <?php include(FPSM_PATH . '/includes/views/backend/forms/custom-field-types/options-list.php'); ?>
    </div>
</div>
