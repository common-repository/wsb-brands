<?php

/**
 * @link             	 https://www.webstudiobrana.com
 * @since             	 1.0.0
 * @package          	 Wsb_Brands
 *
 * @wordpress-plugin
 * Plugin Name:     	 WSB Brands
 * Plugin URI:       	 https://www.webstudiobrana.com/wsb-brands-plugin-for-woocommerce/
 * Description:       	 Brands / manufacturers plugin for Woocommerce
 * Version:          	 1.2
 * Author:            	 Branko Borilovic
 * Author URI:        	 https://profiles.wordpress.org/branahr
 * WC requires at least: 4.0
 * WC tested up to: 	 7.5
 * License:          	 GPL-2.0+
 * License URI:    		 http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     	 wsb-brands
 * Domain Path:      	 /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/*
 * First we need to check if Woocommerce is installed and active
 */
if ( class_exists( 'WooCommerce' ) ) {

/**
 * Current plugin version.
 */
define( 'WSB_BRANDS_VERSION', '1.2' );

/**
 * The code that runs during plugin activation.
 */
function activate_wsb_brands() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-brands-activator.php';
	Wsb_Brands_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wsb_brands() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsb-brands-deactivator.php';
	Wsb_Brands_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wsb_brands' );
register_deactivation_hook( __FILE__, 'deactivate_wsb_brands' );
	
	
/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wsb-brands.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wsb_brands() {

	$plugin = new Wsb_Brands();
	$plugin->run();

}
		
  run_wsb_brands();
  
} else {
	if( is_admin() ) {
	?>
  		<div class="notice notice-warning is-dismissible">
    	<p><?php echo __( 'WSB Brands plugin can not work properly without Woocommerce installed and enabled!', 'wsb-brands' ); ?></p>
		</div>
	<?php
	}
}