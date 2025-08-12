<div class="fpsm-field-wrap">
    <label><?php esc_html_e( 'Open in new tab', 'frontend-post-submission-manager' ); ?></label>
    <div class="fpsm-field">
        <input type="checkbox" name="<?php echo esc_attr( $field_name_prefix ) ?>[open_in_new_tab]" value="1" <?php echo (!empty( $field_details['open_in_new_tab'] )) ? 'checked="checked"' : ''; ?>/>
        <p class="description"><?php esc_html_e( 'Please check if you want to open the link in new tab.', 'frontend-post-submission-manager' ); ?></p>
    </div>
</div>
<div class="fpsm-field-wrap">
    <label><?php esc_html_e( 'Image Size', 'frontend-post-submission-manager' ); ?></label>
    <div class="fpsm-field">
        <select name="<?php echo esc_attr( $field_name_prefix ) ?>[image_size]">
            <?php
            $selected_image_size = (!empty( $field_details['image_size'] )) ? $field_details['image_size'] : 'thumbnail';
            $image_sizes = get_intermediate_image_sizes();
            foreach ( $image_sizes as $image_size ) {
                ?>
                <option value="<?php echo esc_attr( $image_size ); ?>" <?php selected( $selected_image_size, $image_size ); ?>><?php echo esc_html( $image_size ); ?></option>
                <?php
            }
            ?>

        </select>
        <p class="description"><?php esc_html_e( 'Please choose any desired image size from the list to display only if you are also receiving the images from this file uploader.', 'frontend-post-submission-manager' ); ?></p>
    </div>
</div>