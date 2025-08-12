<input type="hidden" name="<?php echo esc_attr( $field_name_prefix ); ?>[show_on_form]" value="1"/>
<div class="fpsm-field-wrap">
    <label><?php esc_html_e( 'Field Label', 'frontend-post-submission-manager' ); ?></label>
    <div class="fpsm-field">
        <input type="text" name="<?php echo esc_attr( $field_name_prefix ); ?>[field_label]" value="<?php echo (!empty( $field_details['field_label'] )) ? esc_attr( $field_details['field_label'] ) : ''; ?>"/>
        <p class="description"><?php esc_html_e( 'This is just for the reference of the field here. It won\'t show in the form itself.', 'frontend-post-submission-manager' ); ?></p>
    </div>
</div>
<div class="fpsm-field-wrap">
    <label><?php esc_html_e( 'Hidden Field Type', 'frontend-post-submission-manager' ); ?></label>
    <div class="fpsm-field">
        <select name="<?php echo esc_attr( $field_name_prefix ); ?>[hidden_field_type]" class="fpsm-toggle-trigger" data-toggle-class="fpsm-hidden-field-ref">
            <?php
            $hidden_field_type = (!empty( $field_details['hidden_field_type'] )) ? $field_details['hidden_field_type'] : 'current_page_url';
            ?>
            <option value="current_page_url" <?php selected( $hidden_field_type, 'current_page_url' ); ?>><?php esc_html_e( 'Current Page URL', 'frontend-post-submission-manager' ); ?></option>
            <option value="user_ip" <?php selected( $hidden_field_type, 'user_ip' ); ?>><?php esc_html_e( 'User Public IP', 'frontend-post-submission-manager' ); ?></option>
            <option value="request" <?php selected( $hidden_field_type, 'request' ); ?>><?php esc_html_e( 'URL/$_REQUEST Parameter', 'frontend-post-submission-manager' ); ?></option>
            <option value="prefilled" <?php selected( $hidden_field_type, 'prefilled' ); ?>><?php esc_html_e( 'Pre Filled Value', 'frontend-post-submission-manager' ); ?></option>
        </select>
    </div>
</div>
<div class="fpsm-field-wrap fpsm-hidden-field-ref <?php echo ($hidden_field_type != 'request') ? 'fpsm-display-none' : ''; ?>" data-toggle-ref="request">
    <label><?php esc_html_e( 'URL/$_REQUEST Parameter Key', 'frontend-post-submission-manager' ); ?></label>
    <div class="fpsm-field">
        <input type="text" name="<?php echo esc_attr( $field_name_prefix ); ?>[url_parameter_key]" value="<?php echo (!empty( $field_details['url_parameter_key'] )) ? esc_attr( $field_details['url_parameter_key'] ) : ''; ?>"/>
    </div>
</div>
<div class="fpsm-field-wrap fpsm-hidden-field-ref <?php echo ($hidden_field_type != 'prefilled') ? 'fpsm-display-none' : ''; ?>" data-toggle-ref="prefilled">
    <label><?php esc_html_e( 'Pre Filled Value', 'frontend-post-submission-manager' ); ?></label>
    <div class="fpsm-field">
        <input type="text" name="<?php echo esc_attr( $field_name_prefix ); ?>[pre_filled_value]" value="<?php echo (!empty( $field_details['pre_filled_value'] )) ? esc_attr( $field_details['pre_filled_value'] ) : ''; ?>"/>
    </div>
</div>
