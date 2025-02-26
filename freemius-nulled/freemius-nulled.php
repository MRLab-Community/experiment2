<?php
/**
 * Plugin Name: Freemius SDK (Nulled)
 * Plugin URI:  https://babiato.co/
 * Description: Automatically Activate All Plugins Using Freemius SDK 2.10.1.0 and Under.
 * Version:     2.10.1.0
 * Author:      sharmanhall
 * Author URI:  https://babiato.co/
 * License: GPL2
 * Text Domain: freemius-nulled
 * Domain Path: /languages
 */

/**
 * @package     Freemius SDK (Nulled)
 * @copyright   Copyright (c) 2023, babiato, Inc.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Ensure the Freemius SDK file exists in the same folder.
if ( file_exists( dirname( __FILE__ ) . '/freemius/start.php' ) ) {
    // Initialize Freemius.
    require_once dirname( __FILE__ ) . '/freemius/start.php';

    function my_fs() {
        global $my_fs;

        if ( ! isset( $my_fs ) ) {
            $my_fs = fs_dynamic_init( array(
                'id'           => '6969',
                'slug'         => 'freemius-nulled',
                'type'         => 'plugin',
                'public_key'   => 'pk_69696969696969696969696969696',
                'is_premium'   => false,
                'has_addons'   => false,
                'has_paid_plans' => false,
				'menu' => array(
					'first-path' => 'plugins.php?page=freemius',
					'icon' => plugin_dir_url( __FILE__ ) . 'img/icon.png',
				),
            ) );
        }

        return $my_fs;
    }

    // Hook function to add menu item in admin menu.
    add_action('admin_menu', 'freemius_nulled_admin_menu');

    function freemius_nulled_admin_menu() {
        // Adding the main menu item at the top
        add_menu_page('Freemius SDK (Nulled)', 'Freemius SDK (Nulled)', 'manage_options', 'plugins.php?page=freemius', '', '', 0);
    }

    // Do the Freemius SDK start.
    my_fs();
} else {
    function fs_sdk_missing_admin_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e( 'Error: Freemius SDK (start.php) file is missing in the plugin folder.', 'freemius-nulled' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'fs_sdk_missing_admin_notice' );
}
