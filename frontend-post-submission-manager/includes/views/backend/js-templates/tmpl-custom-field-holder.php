<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span>{{data.label}} <span class="fpsm-field-type-label">- <?php echo esc_html( $custom_field_type_label ); ?></span></h3>
        <a href="javascript:void(0);" class="fpsm-field-remove-trigger"><span class="dashicons dashicons-trash"></span></a>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <input type="hidden" name="<?php echo esc_attr( $field_name_prefix ); ?>[field_type]" value="<?php echo esc_attr( $custom_field_type ); ?>"/>
        <?php
        if ( file_exists( FPSM_PATH . '/includes/views/backend/forms/custom-field-types/' . $custom_field_type . '.php' ) ) {
            include(FPSM_PATH . '/includes/views/backend/forms/custom-field-types/' . $custom_field_type . '.php');
            include(FPSM_PATH . '/includes/views/backend/forms/form-edit-sections/form-fields/post-display-fields.php');
        }
        ?>
    </div>
</div>