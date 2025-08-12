<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('Frontend_Post_Submission_Manager')) {

    /**
     * Plugin Main Class
     *
     * @since 1.0.0
     */
    class Frontend_Post_Submission_Manager {

        /**
         * Plugin's current version.
         *
         * @var string
         */
        public $version = '1.4.6';

        /**
         * The single instance of the class.
         *
         * @since 1.0.0
         */
        protected static $_instance = null;

        /**
         * Main FPSM Instance.
         *
         * Ensures only one instance of FPSM is loaded or can be loaded.
         *
         * @since 1.0.0
         * @static
         * @return Frontend_Post_Submission_Manager - Main instance.
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Throw error on object clone.
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         *
         * @since 1.6
         * @access protected
         * @return void
         */
        public function __clone() {
            // Cloning instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, esc_html__('No script kiddies please!!', 'frontend-post-submission-manager'), '1.6');
        }

        /**
         * Disable unserializing of the class.
         *
         * @since 1.6
         * @access protected
         * @return void
         */
        public function __wakeup() {
            // Unserializing instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, esc_html__('No script kiddies please!!', 'frontend-post-submission-manager'), '1.6');
        }

        /**
         * Returns true if the request is a non-legacy REST API request.
         *
         * Legacy REST requests should still run some extra code for backwards compatibility.
         *
         * @todo: replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
         *
         * @return bool
         */
        public function is_rest_api_request() {
            if (empty($_SERVER['REQUEST_URI'])) {
                return false;
            }

            $rest_prefix = trailingslashit(rest_get_url_prefix());
            $is_rest_api_request = (false !== strpos($_SERVER['REQUEST_URI'], $rest_prefix)); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

            return $is_rest_api_request;
        }

        /**
         * What type of request is this?
         *
         * @param  string $type admin, ajax, cron or frontend.
         * @return bool
         */
        private function is_request($type) {
            switch ($type) {
                case 'admin':
                    return is_admin();
                case 'ajax':
                    return defined('DOING_AJAX');
                case 'cron':
                    return defined('DOING_CRON');
                case 'frontend':
                    return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON') && !$this->is_rest_api_request();
            }
        }

        /**
         * Plugin's initialization constructor
         *
         * @since 1.0.0
         */
        public function __construct() {
            $this->define_constants();
            $this->includes();
        }

        public function define_constants() {
            global $wpdb;
            defined('FPSM_VERSION') or define('FPSM_VERSION', $this->version);
            defined('FPSM_FORM_TABLE') or define('FPSM_FORM_TABLE', $wpdb->prefix . 'fpsm_forms');
        }

        public function includes() {
            include(FPSM_PATH . '/includes/classes/class-fpsm-init.php');
            include(FPSM_PATH . '/includes/classes/class-fpsm-library.php');
            include(FPSM_PATH . '/includes/classes/class-fpsm-shortcode.php');
            include(FPSM_PATH . '/includes/classes/class-fpsm-fileuploader.php');
            include(FPSM_PATH . '/includes/classes/class-fpsm-ajax.php');
            include(FPSM_PATH . '/includes/classes/class-fpsm-frontend-hooks.php');
            include(FPSM_PATH . '/includes/classes/class-fpsm-notification.php');


            //include all the admin related classes
            if ($this->is_request('admin')) {
                include(FPSM_PATH . '/includes/classes/admin/class-fpsm-activation.php');
                include(FPSM_PATH . '/includes/classes/admin/class-fpsm-admin-enqueue.php');
                include(FPSM_PATH . '/includes/classes/admin/class-fpsm-admin.php');
                include(FPSM_PATH . '/includes/classes/admin/class-fpsm-ajax-admin.php');
                include(FPSM_PATH . '/includes/classes/admin/class-fpsm-metabox.php');
            }
        }
    }
}
