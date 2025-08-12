<div class="fpsm-each-form-field">
    <div class="fpsm-field-head fpsm-clearfix">
        <h3 class="fpsm-field-title"><span class="dashicons dashicons-arrow-down"></span>{{data.label}} <span class="fpsm-field-type-label">- <?php esc_html_e( 'Datepicker', 'frontend-post-submission-manager' ); ?></span></h3>
        <a href="javascript:void(0);" class="fpsm-field-remove-trigger"><span class="dashicons dashicons-trash"></span></a>
    </div>
    <div class="fpsm-field-body fpsm-display-none">
        <?php include(FPSM_PATH . '/includes/views/backend/js-templates/tmpl-common-fields.php'); ?>
        <div class="fpsm-show-fields-ref-{{data.meta_key}} fpsm-display-none">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e( 'Date Format', 'frontend-post-submission-manager' ); ?></label>
                <div class="fpsm-field">
                    <select name="<?php echo esc_attr( $field_name_prefix ) ?>[date_format]">
                        <?php
                        $fpsm_datepicker_formats = array(
                            array( 'format' => 'mm/dd/yy', 'label' => esc_html__( 'Default', 'frontend-post-submission-manager' ) ),
                            array( 'format' => 'yy-mm-dd', 'label' => esc_html__( 'ISO 8601', 'frontend-post-submission-manager' ) ),
                            array( 'format' => 'd M, y', 'label' => esc_html__( 'Short', 'frontend-post-submission-manager' ) ),
                            array( 'format' => 'd MM, y', 'label' => esc_html__( 'Medium', 'frontend-post-submission-manager' ) ),
                            array( 'format' => 'DD, d MM, yy', 'label' => esc_html__( 'DD, d MM, yy', 'frontend-post-submission-manager' ) ),
                            array( 'format' => "'day' d 'of' MM 'in the year' yy", 'label' => esc_html__( 'With text', 'frontend-post-submission-manager' ) )
                        );
                        $fpsm_datepicker_formats = apply_filters( 'fpsm_datepicker_formats', $fpsm_datepicker_formats );
                        foreach ( $fpsm_datepicker_formats as $datepicker_format_array ) {
                            ?>
                            <option value="<?php echo esc_attr( $datepicker_format_array['format'] ); ?>"><?php echo esc_html( $datepicker_format_array['label'] ); ?> - <?php echo esc_html( $datepicker_format_array['format'] ); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e( 'Save as string', 'frontend-post-submission-manager' ); ?></label>
                <div class="fpsm-field">
                    <input type="checkbox" name="<?php echo esc_attr( $field_name ) ?>[string_format]" value="1"/>
                    <p class="description"><?php esc_html_e( 'Please check this if you want to save the date as the raw string format.', 'frontend-post-submission-manager' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>