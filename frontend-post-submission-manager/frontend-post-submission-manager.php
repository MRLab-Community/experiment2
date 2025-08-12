<?php

defined('ABSPATH') or die('No script kiddies please');

/*
  Plugin Name: Frontend Post Submission Manager
  Description: A plugin to submit and manage WordPress posts from frontend with or without logging in
  Version:     1.4.6
  Author:      WP Shuffle
  Author URI:  http://wpshuffle.com
  Plugin URI: http://wpshuffle.com/wordpress-plugins/frontend-post-submission-manager
  Domain Path: /languages
  Text Domain: frontend-post-submission-manager
 */

// Define FPSM_URL and FPSM_PATH
defined('FPSM_URL') or define('FPSM_URL', untrailingslashit(plugin_dir_url(__FILE__)));
defined('FPSM_PATH') or define('FPSM_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
defined('FPSM_LANGAUGE_PATH') or define('FPSM_LANGAUGE_PATH', dirname(plugin_basename(__FILE__)) . '/languages');



// Include the plugin's main class.
include(FPSM_PATH . '/includes/classes/class-frontend-post-submission-manager.php');

/**
 * Returns the main instance of Frontend Post Submission Manager class.
 *
 * @since  1.0.0
 * return Frontend_Post_Submission_Manager
 */
function fpsm_initialize() {
  return Frontend_Post_Submission_Manager::instance();
}

$GLOBALS['fpsm'] = fpsm_initialize();
