<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('FPSM_Frontend_Hooks')) {

    class FPSM_Frontend_Hooks {

        function __construct() {
            add_action('wp_footer', array($this, 'append_extra_html'));
            add_filter('the_content', array($this, 'append_custom_fields_before'), 10);
            add_filter('the_content', array($this, 'append_custom_fields_after'), 11);
            add_filter('the_content', array($this, 'append_post_edit_button'), 100);
            add_action('template_redirect', array($this, 'generate_form_preview'));
            add_filter('body_class', array($this, 'add_preview_class'));
        }

        function append_extra_html() {
            include(FPSM_PATH . '/includes/views/frontend/wp_footer.php');
        }

        function register_frontend_assets() {

            wp_enqueue_style('fpsm-style', FPSM_URL . '/assets/css/fpsm-frontend-style.css', array(), FPSM_VERSION);
            if (is_rtl()) {
                wp_enqueue_style('fpsm-rtl-style', FPSM_URL . '/assets/css/fpsm-rtl-frontend-style.css', array(), FPSM_VERSION);
            }

            wp_enqueue_style('fpsm-fonts', FPSM_URL . '/assets/font-face/NunitoSans/stylesheet.css', array(), FPSM_VERSION);
            wp_enqueue_style('fpsm-fonts', FPSM_URL . '/assets/font-face/comingsoon/stylesheet.css', array(), FPSM_VERSION);
        }

        function append_custom_fields_before($content) {
            $this->register_frontend_assets();
            $append_content = '';
            $display_position_check = 'before_content';
            include(FPSM_PATH . '/includes/cores/post-content-append.php');
            $content = $append_content . $content;
            return $content;
        }

        function append_custom_fields_after($content) {
            $this->register_frontend_assets();
            $append_content = '';
            $display_position_check = 'after_content';
            include(FPSM_PATH . '/includes/cores/post-content-append.php');
            $content = $content . $append_content;
            return $content;
        }

        function generate_form_preview() {
            if (is_user_logged_in() && !empty($_GET['fpsm_form_preview']) && !empty($_GET['fpsm_form_alias']) && wp_verify_nonce($_GET['_wpnonce'], 'fpsm_preview_nonce')) {
                include(FPSM_PATH . '/includes/views/frontend/frontend-preview-template.php');
                die();
            }
        }

        function add_preview_class($classes) {
            if (isset($_GET['fpsm_form_preview'])) {
                $classes[] = 'fpsm-preview-page';
            }
            return $classes;
        }

        function append_post_edit_button($content) {
            global $wp_query;
            if (empty($wp_query->queried_object_id)) {
                return $content;
            }
            $post_id = $wp_query->queried_object_id;
            $form_alias = get_post_meta($post_id, '_fpsm_form_alias', true);
            if (empty($form_alias)) {
                return $content;
            }
            ob_start();
            include(FPSM_PATH . '/includes/cores/edit-button-append.php');
            $edit_button_markup = ob_get_contents();
            ob_end_clean();
            if (empty($edit_button_markup)) {
                return $content;
            } else {
                return $content . $edit_button_markup;
            }
        }
    }

    new FPSM_Frontend_Hooks();
}
