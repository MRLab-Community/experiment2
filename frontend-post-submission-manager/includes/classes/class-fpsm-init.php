<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('FPSM_Init')) {

    class FPSM_Init {

        function __construct() {
            //All tasks needed to be executed in init hooks are placed here
            add_action('init', array($this, 'init_tasks'));
        }

        function init_tasks() {
            /**
             * Fires on init hook
             *
             * @since 1.0.0
             */
            do_action('fpsm_init');

            load_plugin_textdomain('frontend-post-submission-manager', false, FPSM_LANGAUGE_PATH);
            $custom_field_type_list = array(
                'textfield' => array('label' => esc_html__('Texfield', 'frontend-post-submission-manager'), 'icon' => 'fas fa-edit'),
                'textarea' => array('label' => esc_html__('Textarea', 'frontend-post-submission-manager'), 'icon' => 'fas fa-expand'),
                'select' => array('label' => esc_html__('Select Dropdown', 'frontend-post-submission-manager'), 'icon' => 'far fa-caret-square-down'),
                'checkbox' => array('label' => esc_html__('Checkbox', 'frontend-post-submission-manager'), 'icon' => 'far fa-check-square'),
                'radio' => array('label' => esc_html__('Radio Button', 'frontend-post-submission-manager'), 'icon' => 'far fa-dot-circle'),
                'number' => array('label' => esc_html__('Number', 'frontend-post-submission-manager'), 'icon' => 'fas fa-sort'),
                'email' => array('label' => esc_html__('Email', 'frontend-post-submission-manager'), 'icon' => 'fas fa-envelope'),
                'datepicker' => array('label' => esc_html__('Datepicker', 'frontend-post-submission-manager'), 'icon' => 'far fa-calendar-alt'),
                'file_uploader' => array('label' => esc_html__('File Uploader', 'frontend-post-submission-manager'), 'icon' => 'fas fa-paperclip'),
                'url' => array('label' => esc_html__('URL', 'frontend-post-submission-manager'), 'icon' => 'fas fa-globe-asia'),
                'tel' => array('label' => esc_html__('Tel', 'frontend-post-submission-manager'), 'icon' => 'fas fa-phone'),
                'youtube' => array('label' => esc_html__('Youtube Embed', 'frontend-post-submission-manager'), 'icon' => 'fab fa-youtube'),
                'hidden' => array('label' => esc_html('Hidden', 'frontend-post-submission-manager'), 'icon' => 'far fa-minus-square'),
                'wp_editor' => array('label' => esc_html('WYSIWYG Editor', 'frontend-post-submission-manager'), 'icon' => 'fas fa-file-word')

            );
            /**
             * Filters custom field type list
             *
             * @param array $custom_field_type_list
             *
             * @since 1.0.0
             */
            $custom_field_type_list = apply_filters('fpsm_custom_field_type_list', $custom_field_type_list);
            defined('FPSM_CUSTOM_FIELD_TYPE_LIST') or define('FPSM_CUSTOM_FIELD_TYPE_LIST', $custom_field_type_list);
        }
    }

    new FPSM_Init();
}
