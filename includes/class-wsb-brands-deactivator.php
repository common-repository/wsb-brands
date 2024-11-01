<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.webstudiobrana.com
 * @since      1.0.0
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 * @author     Branko Borilovic <brana@chili.com.hr>
 */
class Wsb_Brands_Deactivator {

	/**
	 * @since    1.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
