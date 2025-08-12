<input type="tel"
       name="<?php echo esc_attr( $field_key ); ?>"
       value="<?php echo esc_attr( $custom_field_saved_value ); ?>"
       <?php if ( !empty( $field_details['pattern'] ) ) { ?>pattern="<?php echo esc_attr( $field_details['pattern'] ); ?>"<?php } ?>
       <?php if ( !empty( $field_details['max_length'] ) ) { ?>maxlength="<?php echo esc_attr( $field_details['max_length'] ) ?>"<?php } ?>
       <?php if ( !empty( $field_details['min_length'] ) ) { ?>minlength="<?php echo esc_attr( $field_details['min_length'] ) ?>"/><?php } ?>
>