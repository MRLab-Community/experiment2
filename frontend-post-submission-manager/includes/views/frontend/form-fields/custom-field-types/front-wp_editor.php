<?php
defined('ABSPATH') or die('No script kiddies please!!');
$editor_type = (!empty($field_details['editor_type'])) ? $field_details['editor_type'] : 'rich';

$editor_height = (!empty($field_details['editor_height'])) ? intval($field_details['editor_height']) : '';

switch ($editor_type) {
    case 'rich':
        $teeny = false;
        $show_quicktags = true;
        $tinymce = true;
        break;
    case 'visual':
        $teeny = false;
        $show_quicktags = false;
        $tinymce = true;
        break;
    case 'html':
        $teeny = true;
        $show_quicktags = true;
        $tinymce = false;
        break;
}
$media_upload = (!empty($field_details['media_upload'])) ? true : false;
$editor_settings = array(
    'textarea_name' => $field_key,
    'media_buttons' => $media_upload,
    'teeny' => $teeny,
    'tinymce' => $tinymce,
    'wpautop' => true,
    'quicktags' => $show_quicktags,
    'editor_height' => $editor_height,
    'editor_class' => 'fpsm-custom-wp_editor-' . $custom_field_meta_key
);
/**
 * Filters Editor Settings
 *
 * @param array $editor_settings
 * @param array $form_row
 *
 * @since 1.2.8
 */
$editor_settings = apply_filters('fpsm_editor_settings', $editor_settings, $form_row);


$edit_flag = (!empty($edit_post)) ? true : false;


wp_editor($fpsm_library_obj->sanitize_html($custom_field_saved_value), 'fpsm-custom-wp_editor-' . $custom_field_meta_key, $editor_settings);
