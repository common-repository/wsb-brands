<?php

/**
 * @link       https://www.webstudiobrana.com
 * @since      1.0.0
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 */

/**
 * The core plugin class
 * This is used to define internationalization, admin-specific hooks,
 * frontend hooks and shortcodes.
 *
 * @since      1.0.0
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 * @author     Branko Borilovic <brana@webstudiobrana.com>
 */
class Wsb_Brands {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wsb_Brands_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wsb-brands';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wsb_Brands_Loader. Orchestrates the hooks of the plugin.
	 * - Wsb_Brands_i18n. Defines internationalization functionality.
	 * - Wsb_Brands_Admin. Defines all hooks for the admin area.
	 * - Wsb_Brands_Public. Defines all hooks for the public side of the site.
	 * - Wsb_Brands_Shortcodes. Defines all shortcodes.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-brands-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-brands-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wsb-brands-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the frontend
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wsb-brands-public.php';
		
		/**
		 * The class responsible for rendering all plugin shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-brands-shortcodes.php';
		
		/**
		 * Brands menu widget class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-brands-widget.php';
		
		/**
		 * Brands carousel widget class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsb-brands-carousel-widget.php';
		

		$this->loader = new Wsb_Brands_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wsb_Brands_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wsb_Brands_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wsb_Brands_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_admin, 'register_brand_taxonomy', 5 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_settings_tabs_wsb_brands_admin_tab', $plugin_admin, 'render_settings_tab_content' );
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'brandsdiv_remove' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'brands_metabox_add' );
		$this->loader->add_action( 'woocommerce_update_product', $plugin_admin, 'brand_meta_box_save' );
		$this->loader->add_action( 'wsb_brands_add_form_fields', $plugin_admin, 'brands_add_fields', 10, 2 );
		$this->loader->add_action( 'wsb_brands_edit_form_fields', $plugin_admin, 'brands_edit_fields', 10 );
		
		/**
		 * Copy a brand to duplicated product 
	 	 * @since    1.0.1
		**/
		$this->loader->add_action( 'woocommerce_product_duplicate_before_save', $plugin_admin, 'copy_brand_on_product_duplicate', 10, 2 );
		/**
		 * Exporting brands
	 	 * @since    1.0.2
		**/
		$this->loader->add_filter( 'woocommerce_product_export_column_names', $plugin_admin, 'wsb_export_add_columns' );
		$this->loader->add_filter( 'woocommerce_product_export_product_default_columns', $plugin_admin, 'wsb_export_add_columns' );
		$this->loader->add_filter( 'woocommerce_product_export_product_column_wsb_brands', $plugin_admin, 'wsb_brands_export', 10, 2 );
		/**
		 * Filtering products by brands
	 	 * @since    1.0.4
		**/
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'filter_products_by_brands' , 10, 2);
		
		$this->loader->add_filter( 'woocommerce_csv_product_import_mapping_options', $plugin_admin, 'wsb_map_columns' );
		$this->loader->add_filter( 'woocommerce_csv_product_import_mapping_default_columns', $plugin_admin, 'wsb_add_brands_to_mapping' );
		$this->loader->add_filter( 'woocommerce_product_importer_parsed_data', $plugin_admin, 'wsb_parse_brand_json', 10, 2 );
		$this->loader->add_filter( 'woocommerce_product_import_inserted_product_object', $plugin_admin, 'wsb_set_taxonomy', 10, 2 );
		
		/**
		 * Hide parent dropdown select in brands metabox - we don't need it
	 	 * @since    1.0.4
		**/
		$this->loader->add_filter( 'post_edit_category_parent_dropdown_args', $plugin_admin, 'wsb_hide_parent_dropdown' );
		
		/**
		 * Make brands sortable in administration
	 	 * @since    1.0.4
		**/
		$this->loader->add_filter( 'woocommerce_sortable_taxonomies', $plugin_admin, 'wsb_brands_sorting' );
		
		$this->loader->add_action( 'edited_wsb_brands', $plugin_admin, 'brands_save_fields' );
		$this->loader->add_action( 'created_wsb_brands', $plugin_admin, 'brands_save_fields' );
		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $plugin_admin, 'add_wsb_brands_settings_tab', 100 );
		$this->loader->add_action( 'woocommerce_update_options_wsb_brands_admin_tab', $plugin_admin, 'update_wsb_brands_settings' );
		$this->loader->add_filter( 'manage_edit-wsb_brands_columns', $plugin_admin, 'brands_columns' );
		$this->loader->add_action( 'manage_wsb_brands_custom_column', $plugin_admin, 'manage_wsb_brands_columns', 10, 3);
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'wsb_brands_load_widget' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'wsb_brands_load_brands_carousel_widget' );
		
		/**
		 * Add brands filter to coupon options
	 	 * @since    1.0.5
		**/
		$this->loader->add_action( 'woocommerce_coupon_options_usage_restriction', $plugin_admin, 'wsb_brands_coupon_filter', 10, 2 );
		$this->loader->add_action( 'woocommerce_coupon_object_updated_props', $plugin_admin, 'wsb_brands_coupon_object_updated_props', 10, 2 );
		
		/**
	 	 * @since    1.0.5
		**/
		$this->loader->add_filter( 'woocommerce_coupon_is_valid', $plugin_admin, 'wsb_brands_coupon_is_valid_for_cart', 10, 3 );
		/**
	 	 * @since    1.0.5
		**/
		$this->loader->add_filter( 'woocommerce_coupon_is_valid_for_product', $plugin_admin, 'wsb_brands_coupon_is_valid_for_product', 10, 4 );
		/**
	 	 * @since    1.0.6
		**/
		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_admin, 'wsb_brands_cart_item_data', 10, 2 );
		

	}

	/**
	 * Register all of the hooks related to the frontend
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wsb_Brands_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_single_product_summary', $plugin_public, 'single_product_page_brand' );
		$this->loader->add_action( 'woocommerce_before_shop_loop', $plugin_public, 'product_loop_page_brand' );
		$this->loader->add_filter( 'pre_get_document_title', $plugin_public, 'change_document_title');
		$this->loader->add_filter( 'woocommerce_show_page_title', $plugin_public, 'change_page_title');
		$this->loader->add_filter( 'woocommerce_product_tabs', $plugin_public, 'wsb_brands_product_tab' );
		/**
	 	 * @since    1.1
		**/
		$this->loader->add_filter( 'woocommerce_structured_data_product', $plugin_public, 'wsb_brands_add_brand_to_structured_data', 10, 2 );

	}
	
	/**
	 * Register all of the shortcodes. 
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {

		$plugin_shortcodes = new Wsb_Brands_Shortcodes();
		
		$plugin_shortcodes->init();

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wsb_Brands_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}