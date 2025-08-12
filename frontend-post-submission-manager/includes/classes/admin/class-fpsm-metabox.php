<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('FPSM_Metabox')) {

    class FPSM_Metabox {

        function __construct() {
            add_action('add_meta_boxes', array($this, 'register_fpsm_metabox'));
            add_action('save_post', array($this, 'save_fpsm_metabox'), 10, 2);
        }

        function register_fpsm_metabox() {
            add_meta_box('fpsm-metabox', esc_html__('Frontend Post Submission Manager'), array($this, 'render_fpsm_metabox'));
        }

        function render_fpsm_metabox($post) {
            include(FPSM_PATH . '/includes/cores/fpsm-metabox-render.php');
        }

        function save_fpsm_metabox($post_id, $post) {
            if (empty($_POST['fpsm_metabox_nonce_field']) || empty($_POST['fpsm_custom_fields'])) {
                return;
            }
            if (!wp_verify_nonce($_POST['fpsm_metabox_nonce_field'], 'fpsm_metabox_nonce')) {
                return;
            }
            // Check if user has permissions to save data.
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Check if not an autosave.
            if (wp_is_post_autosave($post_id)) {
                return;
            }

            // Check if not a revision.
            if (wp_is_post_revision($post_id)) {
                return;
            }
            if (isset($_POST['fpsm_disable_metabox_update'])) {
                update_post_meta($post_id, 'fpsm_disable_metabox_update', 1);
            } else {
                update_post_meta($post_id, 'fpsm_disable_metabox_update', 0);
            }
            if (empty($_POST['fpsm_disable_metabox_update'])) {
                global $fpsm_library_obj;
                $fpsm_sanitize_rule = (!empty($_POST['fpsm_sanitize_rule'])) ? $fpsm_library_obj->sanitize_array($_POST['fpsm_sanitize_rule']) : [];
                $fpsm_custom_fields = $fpsm_library_obj->sanitize_array($_POST['fpsm_custom_fields'], $fpsm_sanitize_rule);
                $fpsm_included_custom_fields = $fpsm_library_obj->sanitize_array($_POST['fpsm_included_custom_fields']);
                foreach ($fpsm_included_custom_fields as $custom_field_key) {
                    $custom_field_value = (isset($fpsm_custom_fields[$custom_field_key])) ? $fpsm_custom_fields[$custom_field_key] : '';
                    update_post_meta($post_id, $custom_field_key, $custom_field_value);
                }
            }
        }
    }

    new FPSM_Metabox();
}
