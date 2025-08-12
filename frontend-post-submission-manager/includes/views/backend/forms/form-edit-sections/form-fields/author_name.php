<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span><?php esc_html_e('Author Name', 'frontend-post-submission-manager'); ?></h3>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/common-fields.php');
        $field_details['field_type'] = 'author_name';
        include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/post-display-fields.php');
        ?>
    </div>
</div>