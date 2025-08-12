<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
$form_template = (!empty( $form_details['layout']['template'] )) ? $form_details['layout']['template'] : 'template-1';
$label_background_templates = array( 'template-7', 'template-12', 'template-18', 'template-22' );
?>
<div class="fpsm-settings-each-section fpsm-display-none" data-tab="customize">
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e( 'Hide Form Title', 'frontend-post-submission-manager' ); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[customize][hide_form_title]" value="1" <?php echo (!empty( $form_details['customize']['hide_form_title'] )) ? 'checked="checked"' : ''; ?>/>
            <p class="description"><?php esc_html_e( 'Please check if you want to hide form title in the form.', 'frontend-post-submission-manager' ); ?></p>
        </div>
    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e( 'Customize Form Template', 'frontend-post-submission-manager' ); ?></label>
        <div class="fpsm-field">
            <input type="checkbox" name="form_details[customize][form][enable]" value="1" <?php echo (!empty( $form_details['customize']['form']['enable'] )) ? 'checked="checked"' : ''; ?> class="fpsm-checkbox-toggle-trigger" data-toggle-class="fpsm-custom-enable-ref"/>
            <p class="description"><?php esc_html_e( 'Please check if you want to customize the choose form template.', 'frontend-post-submission-manager' ); ?></p>
        </div>
    </div>

    <div class="fpsm-custom-enable-ref <?php echo (empty( $form_details['customize']['form']['enable'] )) ? 'fpsm-display-none' : ''; ?>">
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Background Type', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <?php
                $selected_background_type = (!empty( $form_details['customize']['form']['background_type'] )) ? $form_details['customize']['form']['background_type'] : 'color';
                ?>
                <label><input type="radio" name="form_details[customize][form][background_type]" value="color" class="fpsm-toggle-trigger" data-toggle-class="fpsm-background-type-ref" <?php checked( $selected_background_type, 'color' ); ?>/><?php esc_html_e( 'Color', 'frontend-post-submission-manager' ); ?></label>
                <label><input type="radio" name="form_details[customize][form][background_type]" value="image" class="fpsm-toggle-trigger" data-toggle-class="fpsm-background-type-ref" <?php checked( $selected_background_type, 'image' ); ?>/><?php esc_html_e( 'Image', 'frontend-post-submission-manager' ); ?></label>
            </div>
        </div>
        <div class="fpsm-field-wrap fpsm-background-type-ref <?php echo ($selected_background_type != 'color') ? 'fpsm-display-none' : ''; ?>" data-toggle-ref="color">
            <label>
                <?php esc_html_e( 'Background Color', 'frontend-post-submission-manager' ); ?>
            </label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][background_color]" value="<?php echo (!empty( $form_details['customize']['form']['background_color'] )) ? esc_attr( $form_details['customize']['form']['background_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-background-type-ref <?php echo ($selected_background_type != 'image') ? 'fpsm-display-none' : ''; ?>" data-toggle-ref="image">
            <div class="fpsm-field-wrap">
                <label>
<!--                    --><?php //esc_html_e( 'Background Image', 'frontend-post-submission-manager' ); ?>
                </label>
                <div class="fpsm-field">
                    <input type="text" name="form_details[customize][form][background_image]" value="<?php echo (!empty( $form_details['customize']['form']['background_image'] )) ? esc_url( $form_details['customize']['form']['background_image'] ) : ''; ?>"/>
                    <input type="hidden" name="form_details[customize][form][background_image_id]" value="<?php echo (!empty( $form_details['customize']['form']['background_image_id'] )) ? intval( $form_details['customize']['form']['background_image_id'] ) : ''; ?>"/>
                    <input type="button" class="fpsm-media-uploader button-secondary" value="<?php esc_html_e( 'Upload Image', 'frontend-post-submission-manager' ); ?>"/>
                    <div class="fpsm-media-preview">
                        <?php
                        if ( !empty( $form_details['customize']['form']['background_image'] ) ) {
                            $thumbnail_url = wp_get_attachment_image_src( $form_details['customize']['form']['background_image_id'], 'thumbnail' );
                            ?>
                            <img src="<?php echo esc_url( $thumbnail_url[0] ); ?>"/>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e( 'Background Size', 'frontend-post-submission-manager' ); ?></label>
                <div class="fpsm-field">
                    <select name="form_details[customize][form][background_size]">
                        <?php
                        $selected_background_size = (!empty( $form_details['customize']['form']['background_size'] )) ? $form_details['customize']['form']['background_size'] : 'cover';
                        ?>
                        <option value="cover" <?php selected( $selected_background_size, 'cover' ); ?>><?php esc_html_e( 'Cover', 'frontend-post-submission-manager' ); ?></option>
                        <option value="contain" <?php selected( $selected_background_size, 'contain' ); ?>><?php esc_html_e( 'Contain', 'frontend-post-submission-manager' ); ?></option>
                        <option value="100%" <?php selected( $selected_background_size, '100%' ); ?>><?php esc_html_e( 'Full', 'frontend-post-submission-manager' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e( 'Background Repeat', 'frontend-post-submission-manager' ); ?></label>
                <div class="fpsm-field">
                    <select name="form_details[customize][form][background_repeat]">
                        <?php
                        $selected_background_repeat = (!empty( $form_details['customize']['form']['background_repeat'] )) ? $form_details['customize']['form']['background_repeat'] : 'repeat';
                        ?>
                        <option value="repeat" <?php selected( $selected_background_repeat, 'repeat' ); ?>><?php esc_html_e( 'Repeat', 'frontend-post-submission-manager' ); ?></option>
                        <option value="repeat-x" <?php selected( $selected_background_repeat, 'repeat-x' ); ?>><?php esc_html_e( 'Repeat X', 'frontend-post-submission-manager' ); ?></option>
                        <option value="repeat-y" <?php selected( $selected_background_repeat, 'repeat-y' ); ?>><?php esc_html_e( 'Repeat Y', 'frontend-post-submission-manager' ); ?></option>
                        <option value="no-repeat" <?php selected( $selected_background_repeat, 'no-repeat' ); ?>><?php esc_html_e( 'No Repeat', 'frontend-post-submission-manager' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Text Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][text_color]" value="<?php echo (!empty( $form_details['customize']['form']['text_color'] )) ? esc_attr( $form_details['customize']['form']['text_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-label-background-ref <?php echo (!in_array( $form_template, $label_background_templates )) ? 'fpsm-display-none' : ''; ?>">
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e( 'Label Color', 'frontend-post-submission-manager' ); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="form_details[customize][form][label_color]" value="<?php echo (!empty( $form_details['customize']['form']['label_color'] )) ? esc_attr( $form_details['customize']['form']['label_color'] ) : ''; ?>" class="fpsm-color-picker"/>
                </div>
            </div>
            <div class="fpsm-field-wrap">
                <label><?php esc_html_e( 'Label Background Color', 'frontend-post-submission-manager' ); ?></label>
                <div class="fpsm-field">
                    <input type="text" name="form_details[customize][form][label_background_color]" value="<?php echo (!empty( $form_details['customize']['form']['label_background_color'] )) ? esc_attr( $form_details['customize']['form']['label_background_color'] ) : ''; ?>" class="fpsm-color-picker"/>
                </div>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Field Text Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][field_text_color]" value="<?php echo (!empty( $form_details['customize']['form']['field_text_color'] )) ? esc_attr( $form_details['customize']['form']['field_text_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Field Border Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][field_border_color]" value="<?php echo (!empty( $form_details['customize']['form']['field_border_color'] )) ? esc_attr( $form_details['customize']['form']['field_border_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Field Background Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][field_background_color]" value="<?php echo (!empty( $form_details['customize']['form']['field_background_color'] )) ? esc_attr( $form_details['customize']['form']['field_background_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Button Background Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][button_background_color]" value="<?php echo (!empty( $form_details['customize']['form']['button_background_color'] )) ? esc_attr( $form_details['customize']['form']['button_background_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Button Hover Background Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][button_hover_background_color]" value="<?php echo (!empty( $form_details['customize']['form']['button_hover_background_color'] )) ? esc_attr( $form_details['customize']['form']['button_hover_background_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Button Text Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][button_text_color]" value="<?php echo (!empty( $form_details['customize']['form']['button_text_color'] )) ? esc_attr( $form_details['customize']['form']['button_text_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Button Hover Text Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][button_hover_text_color]" value="<?php echo (!empty( $form_details['customize']['form']['button_hover_text_color'] )) ? esc_attr( $form_details['customize']['form']['button_hover_text_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Radio Button Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][radio_button_color]" value="<?php echo (!empty( $form_details['customize']['form']['radio_button_color'] )) ? esc_attr( $form_details['customize']['form']['radio_button_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Radio Button Checked Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][radio_button_checked_color]" value="<?php echo (!empty( $form_details['customize']['form']['radio_button_checked_color'] )) ? esc_attr( $form_details['customize']['form']['radio_button_checked_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Checkbox Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][checkbox_color]" value="<?php echo (!empty( $form_details['customize']['form']['checkbox_color'] )) ? esc_attr( $form_details['customize']['form']['checkbox_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>
        <div class="fpsm-field-wrap">
            <label><?php esc_html_e( 'Checkbox Checked Color', 'frontend-post-submission-manager' ); ?></label>
            <div class="fpsm-field">
                <input type="text" name="form_details[customize][form][checkbox_checked_color]" value="<?php echo (!empty( $form_details['customize']['form']['checkbox_checked_color'] )) ? esc_attr( $form_details['customize']['form']['checkbox_checked_color'] ) : ''; ?>" class="fpsm-color-picker"/>
            </div>
        </div>

    </div>
    <div class="fpsm-field-wrap">
        <label><?php esc_html_e( 'Custom CSS', 'frontend-post-submission-manager' ); ?></label>
        <div class="fpsm-field">
            <textarea name="form_details[customize][custom_css]"><?php echo (!empty( $form_details['customize']['custom_css'] )) ? $fpsm_library_obj->sanitize_html( $form_details['customize']['custom_css'] ) : ''; ?></textarea>
        </div>
    </div>
</div>