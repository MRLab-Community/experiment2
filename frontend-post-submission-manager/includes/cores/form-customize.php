<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!empty($form_details['customize']['form']['enable'])) {
    /**
     * Background Color
     */
    if ($form_details['customize']['form']['background_type'] == 'color') {
        $background_color = esc_html($form_details['customize']['form']['background_color']);
        $background_css = ".$form_alias_class{background-color:$background_color;}";
        wp_add_inline_style('fpsm-custom-style', $background_css);
    } else {
        if (!empty($form_details['customize']['form']['background_image'])) {
            $background_image = esc_url($form_details['customize']['form']['background_image']);
            $background_image_css = ".$form_alias_class{background-image:url('$background_image');}";
            wp_add_inline_style('fpsm-custom-style', $background_image_css);
            $background_size = esc_html($form_details['customize']['form']['background_size']);
            $background_size_css = ".$form_alias_class{background-size:$background_size;}";
            wp_add_inline_style('fpsm-custom-style', $background_size_css);
            $background_repeat = esc_html($form_details['customize']['form']['background_repeat']);
            $background_repeat_css = ".$form_alias_class{background-repeat:$background_repeat;}";
            wp_add_inline_style('fpsm-custom-style', $background_repeat_css);
        }
    }

    /**
     * Text color
     */
    if (!empty($form_details['customize']['form']['text_color'])) {
        $text_color = esc_html($form_details['customize']['form']['text_color']);
        $text_color_css = ".$form_alias_class{color:$text_color;}";
        wp_add_inline_style('fpsm-custom-style', $text_color_css);
    }
    /**
     * Button Text Color
     */
    if (!empty($form_details['customize']['form']['button_text_color'])) {
        $button_text_color = esc_html($form_details['customize']['form']['button_text_color']);
        $button_text_color_css = ".$form_alias_class  .fpsm-field input[type='submit'] {color:$button_text_color;}";
        wp_add_inline_style('fpsm-custom-style', $button_text_color_css);
        $fileuploader_text_color = ".$form_alias_class  .qq-upload-button {color:$button_text_color;}";
        wp_add_inline_style('fpsm-custom-style', $fileuploader_text_color);
    }
    /**
     * Button Background Color
     */
    if (!empty($form_details['customize']['form']['button_background_color'])) {
        $button_background_color = esc_html($form_details['customize']['form']['button_background_color']);
        $button_background_color_css = ".$form_alias_class  .fpsm-field input[type='submit'] {background-color:$button_background_color;}";
        wp_add_inline_style('fpsm-custom-style', $button_background_color_css);
        $fileuploader_background_color = ".$form_alias_class  .qq-upload-button {background-color:$button_background_color;}";
        wp_add_inline_style('fpsm-custom-style', $fileuploader_background_color);
    }
    /**
     * Button Hover Text Color
     */
    if (!empty($form_details['customize']['form']['button_hover_text_color'])) {
        $button_hover_text_color = esc_html($form_details['customize']['form']['button_hover_text_color']);
        $button_hover_text_color_css = ".$form_alias_class  .fpsm-field input[type='submit']:hover {color:$button_hover_text_color;}";
        wp_add_inline_style('fpsm-custom-style', $button_hover_text_color_css);
        $fileuploader_hover_text_color = ".$form_alias_class  .qq-upload-button:hover {color:$button_hover_text_color;}";
        wp_add_inline_style('fpsm-custom-style', $fileuploader_hover_text_color);
    }
    /**
     * Button Background Color
     */
    if (!empty($form_details['customize']['form']['button_hover_background_color'])) {
        $button_hover_background_color = esc_html($form_details['customize']['form']['button_hover_background_color']);
        $button_hover_background_color_css = ".$form_alias_class  .fpsm-field input[type='submit']:hover {background-color:$button_hover_background_color;}";
        wp_add_inline_style('fpsm-custom-style', $button_hover_background_color_css);
        $fileuploader_hover_background_color = ".$form_alias_class  .qq-upload-button:hover {background-color:$button_hover_background_color;}";
        wp_add_inline_style('fpsm-custom-style', $fileuploader_hover_background_color);
    }
    /**
     * Field Text Color
     */
    if (!empty($form_details['customize']['form']['field_text_color'])) {
        $field_text_color = esc_html($form_details['customize']['form']['field_text_color']);
        $field_text_color_css = ".$form_alias_class.fpsm-front-form input[type='text'],
                                .$form_alias_class.fpsm-front-form input[type='email'],
                                .$form_alias_class.fpsm-front-form input[type='number'],
                                .$form_alias_class.fpsm-front-form input[type='tel'],
                                .$form_alias_class.fpsm-front-form textarea,
                                .$form_alias_class.fpsm-front-form .fpsm-taxonomy-select .fpsm-field .fpsm-select-field,
                                    .fpsm-front-form .fpsm-custom-field-select .fpsm-field .fpsm-select-field,
                                .$form_alias_class.fpsm-field .fpsm-select-field{color:$field_text_color;}";
        wp_add_inline_style('fpsm-custom-style', $field_text_color_css);
    }
    /**
      /**
     * Field background Color
     */
    if (!empty($form_details['customize']['form']['field_background_color'])) {
        $field_background_color = esc_html($form_details['customize']['form']['field_background_color']);
        $field_background_color_css = ".$form_alias_class.fpsm-front-form input[type='text'],
                                .$form_alias_class.fpsm-front-form input[type='email'],
                                .$form_alias_class.fpsm-front-form input[type='number'],
                                .$form_alias_class.fpsm-front-form input[type='tel'],
                                .$form_alias_class.fpsm-front-form textarea,
                                .$form_alias_class.fpsm-front-form .fpsm-taxonomy-select .fpsm-field .fpsm-select-field,
                                .fpsm-front-form .fpsm-custom-field-select .fpsm-field .fpsm-select-field,
                                .$form_alias_class.fpsm-field .fpsm-select-field{background-color:$field_background_color;}";
        wp_add_inline_style('fpsm-custom-style', $field_background_color_css);
    }
    /**
     * Field Border Color
     */
    if (!empty($form_details['customize']['form']['field_border_color'])) {
        $field_border_color = esc_html($form_details['customize']['form']['field_border_color']);
        $field_border_color_css = ".$form_alias_class.fpsm-front-form input[type='text'],
                                .$form_alias_class.fpsm-front-form input[type='email'],
                                .$form_alias_class.fpsm-front-form input[type='number'],
                                .$form_alias_class.fpsm-front-form input[type='tel'],
                                .$form_alias_class.fpsm-front-form textarea,
                                .$form_alias_class.fpsm-front-form .fpsm-taxonomy-select .fpsm-field .fpsm-select-field,
                                .fpsm-front-form .fpsm-custom-field-select .fpsm-field .fpsm-select-field,
                                .$form_alias_class.fpsm-field .fpsm-select-field{border-color:$field_border_color;}";
        wp_add_inline_style('fpsm-custom-style', $field_border_color_css);
    }
    /**
     *  Radio Button Color
     */
    if (!empty($form_details['customize']['form']['radio_button_color'])) {
        $radio_button_color = esc_html($form_details['customize']['form']['radio_button_color']);
        $radio_button_color_css = ".$form_alias_class .fpsm-radio label::before{border-color:$radio_button_color !important;}";
        wp_add_inline_style('fpsm-custom-style', $radio_button_color_css);
    }
    /**
     *  Radio Button Checked Color
     */
    if (!empty($form_details['customize']['form']['radio_button_checked_color'])) {
        $radio_button_checked_color = esc_html($form_details['customize']['form']['radio_button_checked_color']);
        $radio_button_checked_color_css = ".$form_alias_class .fpsm-radio input[type='radio']:checked + label::before{border-color:$radio_button_checked_color !important;}";
        wp_add_inline_style('fpsm-custom-style', $radio_button_checked_color_css);
    }
    /**
     *  Checkbox Color
     */
    if (!empty($form_details['customize']['form']['checkbox_color'])) {
        $checkbox_color = esc_html($form_details['customize']['form']['checkbox_color']);
        $checkbox_color_css = ".$form_alias_class .fpsm-checkbox label::before{background-color:$checkbox_color !important;}";
        wp_add_inline_style('fpsm-custom-style', $checkbox_color_css);
    }
    /**
     *  Checkbox Checked Color
     */
    if (!empty($form_details['customize']['form']['checkbox_checked_color'])) {
        $checkbox_checked_color = esc_html($form_details['customize']['form']['checkbox_checked_color']);
        $checkbox_checked_color_css = ".$form_alias_class .fpsm-checkbox input[type='checkbox']:checked + label::before{background-color:$checkbox_checked_color !important;}";
        wp_add_inline_style('fpsm-custom-style', $checkbox_checked_color_css);
    }

    $label_background_templates = array('template-7', 'template-12', 'template-18', 'template-22');
    if (in_array($form_template, $label_background_templates)) {
        /**
         * Label Color
         */
        if (!empty($form_details['customize']['form']['label_color'])) {
            $label_color = esc_html($form_details['customize']['form']['label_color']);
            $label_color_css = ".$form_alias_class.fpsm-$form_template .fpsm-field-wrap > label{color:$label_color !important;}";
            wp_add_inline_style('fpsm-custom-style', $label_color_css);
            $icon_color_css = ".$form_alias_class.fpsm-$form_template .fpsm-field::before,.$form_alias_class.fpsm-$form_template .qq-uploader::before{color:$label_color !important;}";
            wp_add_inline_style('fpsm-custom-style', $icon_color_css);
        }
        /**
         * Label Background Color
         */
        if (!empty($form_details['customize']['form']['label_background_color'])) {
            $label_background_color = esc_html($form_details['customize']['form']['label_background_color']);
            $label_background_color_css = ".$form_alias_class.fpsm-$form_template .fpsm-field-wrap > label{background-color:$label_background_color !important;} .$form_alias_class.fpsm-$form_template .fpsm-field-wrap > label:after{background-color:$label_background_color !important;}";
            wp_add_inline_style('fpsm-custom-style', $label_background_color_css);
            $label_before_color = ".$form_alias_class.fpsm-$form_template .fpsm-field-wrap > label::before{border-color:$label_background_color transparent transparent transparent !important;}";
            wp_add_inline_style('fpsm-custom-style', $label_before_color);
            $icon_background_color_css = ".$form_alias_class.fpsm-$form_template .fpsm-field::before,.$form_alias_class.fpsm-$form_template .qq-uploader::before{background-color:$label_background_color !important;}";
            wp_add_inline_style('fpsm-custom-style', $icon_background_color_css);
        }
    }
}
if (!empty($form_details['customize']['custom_css'])) {
    $custom_css = $fpsm_library_obj->sanitize_html($form_details['customize']['custom_css']);
    $custom_css = str_replace('<br>', '', $custom_css);
    wp_add_inline_style('fpsm-custom-style', $custom_css);
}