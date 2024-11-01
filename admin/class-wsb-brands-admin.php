<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webstudiobrana.com
 * @since      1.0.0
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/admin
 * @author     Branko Borilovic <brana@webstudiobrana.com>
 */
class Wsb_Brands_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $pagenow, $taxnow ;
		if ( ( "edit-tags.php" == $pagenow && "wsb_brands" != $taxnow ) || ( "term.php" == $pagenow && "wsb_brands" != $taxnow ) ) return;

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wsb-brands-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_media();
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wsb-brands-admin.js', array( 'wp-color-picker' ), $this->version, false );

	}
	
	/**
	 * Register wsb_brand taxonomy 
	 *
	 * @since 1.0.0
	**/
	public function register_brand_taxonomy(){
		
		/**
		 * Labels for "brands"
		 * @since 1.0.4
		**/
		$labels_brand = array(
    		'name' 				=> _x( 'Brands', 'taxonomy general name', 'wsb-brands' ),
    		'singular_name' 	=> _x( 'Brand', 'taxonomy singular name', 'wsb-brands' ),
    		'search_items' 		=> __( 'Search Brands', 'wsb-brands' ),
    		'all_items' 		=> __( 'All Brands', 'wsb-brands' ),
			'parent_item'       => __( 'Parent Brand', 'wsb-brands' ),
    		'parent_item_colon' => __( 'Parent Brand:', 'wsb-brands' ),
    		'edit_item' 		=> __( 'Edit Brand', 'wsb-brands' ), 
    		'update_item' 		=> __( 'Update Brand', 'wsb-brands' ),
    		'add_new_item' 		=> __( 'Add New Brand', 'wsb-brands' ),
    		'new_item_name' 	=> __( 'New Brand Name', 'wsb-brands' ),
    		'menu_name' 		=> __( 'Brands', 'wsb-brands' ),
			'not_found' 		=> __( 'No brands found', 'wsb-brands' ),
			'back_to_items'		=> __( 'Back to Brands', 'wsb-brands' ),
			'view_item'			=> __( 'View Brand', 'wsb-brands' ),
			
  		); 
		
		/**
		 * Labels for "manufacturers"
		 * @since 1.0.4
		**/
		$labels_manufacturer = array(
    		'name' 				=> _x( 'Manufacturers', 'taxonomy general name', 'wsb-brands' ),
    		'singular_name' 	=> _x( 'Manufacturer', 'taxonomy singular name', 'wsb-brands' ),
    		'search_items' 		=> __( 'Search Manufacturers', 'wsb-brands' ),
    		'all_items' 		=> __( 'All Manufacturers', 'wsb-brands' ),
			'parent_item'       => __( 'Parent Manufacturer', 'wsb-brands' ),
    		'parent_item_colon' => __( 'Parent Manufacturer:', 'wsb-brands' ),
    		'edit_item' 		=> __( 'Edit Manufacturer', 'wsb-brands' ), 
    		'update_item' 		=> __( 'Update Manufacturer', 'wsb-brands' ),
    		'add_new_item' 		=> __( 'Add New Manufacturer', 'wsb-brands' ),
    		'new_item_name' 	=> __( 'New Manufacturer Name', 'wsb-brands' ),
    		'menu_name' 		=> __( 'Manufacturers', 'wsb-brands' ),
			'not_found' 		=> __( 'No Manufacturers found', 'wsb-brands' ),
			'back_to_items'		=> __( 'Back to Manufacturers', 'wsb-brands' ),
			'view_item'			=> __( 'View Manufacturer', 'wsb-brands' ),
  		);
		
		
		$labels = $labels_brand;
		$slug = _x( 'brands', 'slug', 'wsb-brands' );
		
		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		if ( "manufacturer" == $general_term ) {
			$labels = $labels_manufacturer;
			$slug = _x( 'manufacturers', 'slug', 'wsb-brands' );
		}
 
		// Now register the taxonomy
 
  		register_taxonomy('wsb_brands', array('product'), array(
    		'hierarchical' => true,
			'public' => true,
    		'labels' => $labels,
    		'show_ui' => true,
    		'show_admin_column' => true,
			'show_in_quick_edit' => true,
    		'query_var' => true,
    		'rewrite' => array( 'slug' => $slug ),
			'supports'          => array( 'thumbnail' ),
  		));
		
		flush_rewrite_rules();
	}
	
	
	/**
	*Remove original Brands metabox from product edit page
	**/
	function brandsdiv_remove()
	{
    	remove_meta_box("wsb_brandsdiv", "product", "side");
	}
	
	
	/**
	*Add Brands select metabox to the product edit page
	**/
	function brands_metabox_add()
	{
		$label = get_taxonomy( 'wsb_brands' )->labels->singular_name;
		add_meta_box('wsb-brands-meta-box', $label, array($this, 'brands_meta_box_markup'), 'product', 'side', 'high', null);
	}
	
	function brands_meta_box_markup($post)
	{
    	 $label = __('Select Brand', 'wsb-brands');
		 $general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		 if ( $general_term == "manufacturer") {
			$label = __('Select Manufacturer', 'wsb-brands'); 
		 }

		 
		 global $post;
		 $values = get_the_terms( $post, 'wsb_brands' );
		 $brands_array = array( "" => $label);
    	 $selected = isset( $values[0]->term_id ) ? $values[0]->term_id: '';
		 $brands = get_terms( array(
    	 'taxonomy' => 'wsb_brands',
    	 'hide_empty' => false,
		 ) );
		
     
    	 // We'll use this nonce field later on when saving.
   		 wp_nonce_field( 'wsb_meta_box_nonce', 'wsb_brands_meta_box_nonce' );
		 
		if(!empty($brands)){
			foreach( $brands as $brand ){
				$brands_array[$brand->term_id] = $brand->name;
			}
		}
		
		?>
		<select name="meta_box_brand" id="meta_box_brand">
    	<?php 
		    
       foreach ( $brands_array as $key => $val ):  ?>
          <option value="<?php echo esc_html($key);?>" <?php selected( $selected, $key ); ?>><?php echo esc_html($val);?></option>
        <?php  
       endforeach;
    	?>
		</select> 
		<?php 

	}
	
	function brand_meta_box_save( $post_id )
	{
    	$meta_box_nonce = sanitize_text_field($_POST['wsb_brands_meta_box_nonce'] );
    	if( !isset( $meta_box_nonce ) || !wp_verify_nonce( $meta_box_nonce, 'wsb_meta_box_nonce' ) ) return;
    
    	$meta_box_brand = sanitize_text_field($_POST['meta_box_brand'] );
    	// Make sure your data is set before trying to save it
     	if( isset($meta_box_brand) )     
        	{
				wp_set_post_terms( $post_id, $meta_box_brand, 'wsb_brands', false );
			}
	}
	
	/**
 	* Adding brands custom fields
 	*/
	function brands_add_fields( $term ) {
		?>
        
        <div class="form-field term-group">
     	<label for="brandLogo"><?php _e( 'Logo', 'wsb-brands' ); ?></label>
     	<input type="hidden" id="brandLogo" name="brandLogo" class="custom_media_url" value="">
        
     	<div id="brand-logo-wrapper"></div>
     	<p>
       <input type="button" class="button button-secondary wsb_brands_media_button" id="wsb_brands_media_button" name="wsb_brands_media_button" value="<?php _e( 'Add Logo', 'wsb-brands' ); ?>" />
       <input type="button" class="button button-secondary wsb_brands_media_remove" id="wsb_brands_media_remove" name="wsb_brands_media_remove" value="<?php _e( 'Remove Logo', 'wsb-brands' ); ?>" />
    </p>
   </div>
        
        <div class="form-field">
            
            <label for="brandUrl"><?php _e( 'Website', 'wsb-brands' ); ?></label>

			<input type="text" name="brandUrl" id="brandUrl" value="" placeholder="<?php echo __( 'Enter full url (Example: http://www.google.com)', 'wsb-brands' ); ?>">
		</div>
		<?php
	}
	
	/**
 	* Edit brands custom fields
 	*/
	function brands_edit_fields( $term ) {
	
		$term_id = $term->term_id;
 
		$url = get_term_meta( $term_id, 'url', true ); 
		
		$logo = get_term_meta( $term_id, 'logo', true );
		
		?>
        
        <tr class="form-field term-group-wrap">
        
     		<th scope="row">
       			<label for="brandLogo"><?php _e( 'Logo', 'wsb-brands' ); ?></label>
     		</th>
            
     		<td>
       			<input type="hidden" id="brandLogo" name="brandLogo" value="<?php echo esc_attr($logo); ?>">
       			<div id="brand-logo-wrapper">
         		<?php if ( $logo ) { ?>
           		<?php echo wp_get_attachment_image ( esc_attr($logo), 'medium', true, array( 'style' => 'max-height:100px !important; width: auto! important;') ); ?>
         		<?php } ?>
       			</div>
       			<p>
         		<input type="button" class="button button-secondary wsb_brands_media_button" id="wsb_brands_media_button" name="wsb_brands_media_button" value="<?php _e( 'Add Logo', 'wsb-brands' ); ?>" />
         		<input type="button" class="button button-secondary wsb_brands_media_remove" id="wsb_brands_media_remove" name="wsb_brands_media_remove" value="<?php _e( 'Remove Logo', 'wsb-brands' ); ?>" />
       			</p>
     		</td>
   		</tr>
        
        <tr class="form-field">
        
			<th><label for="brandUrl"><?php _e( 'Website', 'wsb-brands' ); ?></label></th>
		 
				<td>	 
					<input type="text" name="brandUrl" id="brandUrl" value="<?php echo esc_url( $url ); ?>" placeholder="<?php echo __( 'Enter full url (Example: http://www.google.com)', 'wsb-brands' ); ?>">
				</td>
		</tr>
		<?php
	}
	
	/**
 	* Saving brands custom fields
 	*/
	function brands_save_fields( $term_id ) {
		
		$url = $_POST['brandUrl'];
		update_term_meta( $term_id, 'url', $url );

     	$logo = $_POST['brandLogo'];
     	update_term_meta( $term_id, 'logo', $logo );
	 
	} 


	/**
 	* Adding Brands to the Woocommerce settings tabs
 	*/
	public static function add_wsb_brands_settings_tab( $tabs ) {
		
			$label = get_taxonomy( 'wsb_brands' )->labels->name;
			
            $tabs['wsb_brands_admin_tab'] = $label;
			
            return $tabs;
        }
	
	
	
	/**
	 * Renders an options page. 
	 */
	public function render_settings_tab_content() {
		
		settings_errors();
		
		?>
        
        <div class="brands_settings_wrap">
        
        <?php

		woocommerce_admin_fields( $this->wsb_brands_general_settings() );
		
		woocommerce_admin_fields( $this->brand_product_page_settings() ); 
		
		woocommerce_admin_fields( $this->brand_archive_page_settings() );
		
		woocommerce_admin_fields( $this->brand_product_loop_settings() );
		
		?>
        
        </div>
        
        <?php

	}
	
	
	public function wsb_brands_general_settings() {

		$settings = array(
                'section_title' => array(
                    'name'  => __( 'General settings', 'wsb-brands' ),
                    'type'  => 'title',
                    'desc'  => '',
					'class' => 'wsb-brands-options-title',
                    'id'    => 'wc_wsb_brands_admin_tab_section_general_settings_title'
                ), 
                'wsb_brands_general_labels' => array(
                    'name'    => __( 'Main term', 'wsb-brands' ),
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Choose preferred term to use on the website.', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_general_labels',
                    'options' => array(
                      'brand'    => __( 'Brand', 'wsb-brands' ),
                      'manufacturer' 	=> __( 'Manufacturer', 'wsb-brands' ),
                    ),
					'desc_tip'    => true,
				),
				'wsb_brands_show_in_cart' => array(
                    'name'    => __( 'Show in cart', 'wsb-brands' ),
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Show product brand in the cart?', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_show_in_cart',
                    'options' => array(
                      'no'    => __( 'No', 'wsb-brands' ),
					  'yes' 	=> __( 'Yes', 'wsb-brands' ),
                    ),
					'desc_tip'    => true,
                ),
                'section_end' => array(
                     'type' => 'sectionend',
                     'id'   => 'wc_wsb_brands_admin_tab_general_settings_end',
                ),
            );
			return apply_filters( 'wc_wsb_brands_admin_tab_settings', $settings );
	} 
	
	
	public function brand_product_page_settings() {

		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$label_show_item = __( 'Show Brand', 'wsb-brands' );
		$label_info_tab = __( 'Brand info tab', 'wsb-brands' );
		
		if ( $general_term == "manufacturer" ) {
			$label_show_item = __( 'Show Manufacturer', 'wsb-brands' );
			$label_info_tab = __( 'Manufacturer info tab', 'wsb-brands' );
		}
		
		$settings = array(
                'section_title' => array(
                    'name'  => __( 'Single product options', 'wsb-brands' ),
                    'type'  => 'title',
                    'desc'  => '',
					'class' => 'wsb-brands-options-title',
                    'id'    => 'wc_wsb_brands_admin_tab_section_title'
                ),
                'wsb_brands_show_on_single_product' => array(
                    'name'    => $label_show_item,
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Show on a single product page', 'wsb-brands' ),
                    'default' => 'brand_link',
                    'id'      => 'wsb_brands_admin_tab_show_single_product',
					'desc_tip'    => true,
                    'options' => array(
                      'no'           => __( 'None', 'wsb-brands' ),
					  'brand'   	 => __( 'Name only', 'wsb-brands' ),
					  'logo'  		 => __( 'Logo only', 'wsb-brands' ),
                      'brand_link'   => __( 'Name with link', 'wsb-brands' ),
                      'logo_link'   => __( 'Logo with link', 'wsb-brands' ),
                    ),
                ),
				'wsb_brands_show_label_single_product' => array(
                    'name'    => __( 'Show label', 'wsb-brands' ),
                    'type'    => 'checkbox',
                    'default' => 'yes',
                    'desc'    => __( 'Show label next to the name or logo', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_show_label_single_product',
                ),
				'wsb_brands_logo_height_single_product' => array(
                    'name'        => __( 'Logo height', 'wsb-brands' ),
                    'type'        => 'text',
                    'desc'        => __( 'Logo height on single product page (in pixels)', 'wsb-brands' ),
                    'id'          => 'wsb_brands_logo_height_single_product',
					'default' 	  => '30',
                    'desc_tip'=> true,
                ),  
                'wsb_brands_position_single_product' => array(
                    'name'    => __( 'Display position', 'wsb-brands' ),
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Where to show the brand/manufacturer on single product page?', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_single_position',
                    'options' => array(
                      'after_short_desc'    => __( 'After short description', 'wsb-brands' ),
                      'after_add_to_cart' 	=> __( 'After Add To Cart', 'wsb-brands' ),
                      'after_ameta' 		=> __( 'After Meta', 'wsb-brands' ),
                      'after_sharing'     	=> __( 'After sharing', 'wsb-brands' ),
                    ),
					'desc_tip'    => true,
                ),
				'wsb_brands_tab_single_product' => array(
                    'name'    => $label_info_tab,
                    'type'    => 'checkbox',
                    'default' => 'yes',
                    'desc'    => __( 'Show tab with details on single product page', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_single_product_tab',
                ),
                'section_end' => array(
                     'type' => 'sectionend',
                     'id'   => 'wc_wsb_brands_admin_tab_section_end',
                ),
            );
			return apply_filters( 'wc_wsb_brands_admin_tab_settings', $settings );
	} 
	
	
	
	public function brand_archive_page_settings() {

		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$label_page_options = __( 'Brand page options', 'wsb-brands' );
		
		if ( $general_term == "manufacturer" ) {
			
			$label_page_options = __( 'Manufacturer page options', 'wsb-brands' );
			
		}
		
		$settings = array(
                'section_title' => array(
                    'name'  => $label_page_options,
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'wc_wsb_brands_admin_tab_section_archive_title'
                ),
				'wsb_brands_show_logo_brand_archive' => array(
                    'name'    => __( 'Show logo', 'wsb-brands' ),
                    'type'    => 'checkbox',
                    'default' => 'yes',
                    'desc'    => __( 'Show logo in the archive header', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_show_logo_archive',
                ),
				'wsb_brands_logo_height_archive' => array(
                    'name'        => __( 'Logo height', 'wsb-brands' ),
                    'type'        => 'text',
                    'desc'        => __( 'Logo height on archive page (in pixels)', 'wsb-brands' ),
                    'id'          => 'wsb_brands_logo_height_archive',
					'default' 	  => '50',
                    'desc_tip'=> true,
                ),  
                'wsb_brands_logo_position_archive' => array(
                    'name'    => __( 'Logo position', 'wsb-brands' ),
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Position for logo on archive page', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_archive_position',
                    'options' => array(
						'right'     => __( 'Right', 'wsb-brands' ),
                        'left' 		=> __( 'Left', 'wsb-brands' ),
                    ),
					'desc_tip'    => true,
                ),
				'wsb_brands_show_title_brand_archive' => array(
                    'name'    => __( 'Show page title', 'wsb-brands' ),
                    'type'    => 'checkbox',
                    'default' => 'yes',
                    'desc'    => __( 'Show title in a page header', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_show_title_archive',
                ),
				'wsb_brands_brand_archive_bgcolor' => array(
                    'name'        		 => __( 'Header background', 'wsb-brands' ),
                    'type'       		 => 'text',
					'class' 	  		 => 'wsb_color-field',
                    'desc'        		 => __( 'Background of the page header', 'wsb-brands' ),
                    'id'          		 => 'wc_wsb_brands_brand_archive_bgcolor',
					'data-default-color' => '',
                    'desc_tip'=> true,
                ),
				'wsb_brands_brand_archive_border_thickness' => array(
                    'name'      => __( 'Header border thickness', 'wsb-brands' ),
                    'type'      => 'text',
                    'desc'      => __( 'Header border thickness in pixels', 'wsb-brands' ),
                    'id'        => 'wc_wsb_brands_brand_archive_border_thickness',
					'default'   => '1',
                    'desc_tip'	=> true,
                ),
				'wsb_brands_brand_archive_border_color' => array(
                    'name'        		 => __( 'Header border color', 'wsb-brands' ),
                    'type'       		 => 'text',
					'class' 	  		 => 'wsb_color-field',
                    'desc'        		 => __( 'Header border color', 'wsb-brands' ),
                    'id'          		 => 'wc_wsb_brands_brand_archive_border_color',
					'data-default-color' => '',
                    'desc_tip'=> true,
                ),
                'section_end' => array(
                     'type' => 'sectionend',
                     'id'   => 'wc_wsb_brands_admin_tab_section_archive_end',
                ),
            );
			return apply_filters( 'wc_wsb_brands_admin_tab_settings', $settings );
	}
	
	
	public function brand_product_loop_settings() {
		
		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$label_show_brand = __( 'Show Brand', 'wsb-brands' );
		
		if ( $general_term == "manufacturer" ) {
			
			$label_show_brand = __( 'Show Manufacturer', 'wsb-brands' );
			
		}

		$settings = array(
                'section_title' => array(
                    'name'  => __( 'Product listing options', 'wsb-brands' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'wc_wsb_brands_admin_tab_section_loop_title',
                ),
				 'wsb_brands_show_on_product_loop' => array(
                    'name'    => $label_show_brand,
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Show on product listing page', 'wsb-brands' ),
                    'id'      => 'wsb_brands_admin_tab_show_product_loop',
					'desc_tip'    => true,
                    'options' => array(
                      'brand_link'   => __( 'Name with link', 'wsb-brands' ),
					  'brand'   	 => __( 'Name only', 'wsb-brands' ),
					  'no'           => __( 'None', 'wsb-brands' ),
                    ),
				),
                'wsb_brands_brand_position_loop' => array(
                    'name'    => __( 'Display position', 'wsb-brands' ),
                    'type'    => 'select',
                    'class'   => 'wsb-brands-admin-tab-field',
                    'desc'    => __( 'Position on archive page', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_loop_position',
                    'options' => array(
                      'after_name' 	=> __( 'After product name', 'wsb-brands' ),
					  'on_top'     => __( 'On top', 'wsb-brands' ),
                      'after_price' 		=> __( 'After price', 'wsb-brands' ),
                    ),
					'desc_tip'    => true,
                ),
				'wsb_brands_show_label_loop' => array(
                    'name'    => __( 'Show label', 'wsb-brands' ),
                    'type'    => 'checkbox',
                    'default' => 'yes',
                    'desc'    => __( 'Show label on the left side of the name on products loop', 'wsb-brands' ),
                    'id'      => 'wc_wsb_brands_admin_tab_show_label_loop',
                ),
                'section_end' => array(
                     'type' => 'sectionend',
                     'id'   => 'wc_wsb_brands_admin_tab_section_loop_end',
                ),
            );
			return apply_filters( 'wc_wsb_brands_admin_tab_settings', $settings );
	}
	
	
	
	 public function update_wsb_brands_settings() {

            woocommerce_update_options( $this->wsb_brands_general_settings() );
			
			woocommerce_update_options( $this->brand_product_page_settings() );
			
			woocommerce_update_options( $this->brand_archive_page_settings() );
			
			woocommerce_update_options( $this->brand_product_loop_settings() );

    }
	
	/**
	 * Custom columns for Brands taxonomy in admin panel
	**/
		
	function brands_columns($brands_columns) {
		
	$label = get_taxonomy( 'wsb_brands' )->labels->singular_name;
	
    $new_columns = array(
        'cb' 	=> '<input type="checkbox" />',
		'name'  => $label,
		'logo'	=> __('Logo', 'wsb-brands'), 
        'slug' 	=> __('Slug'),
        'posts' => __('Products', 'wsb-brands')
        );
		
		
		
    return $new_columns;
	}
	
	/**
	 * Adding content to Logo column in brand list
	**/
	function manage_wsb_brands_columns( $value, $column_name, $tax_id ) {
	
    switch ($column_name) {
        case 'logo': 
			$logo_id = get_term_meta( $tax_id, 'logo', true );
			if( '' !=  $logo_id){
				$value = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'max-height:40px !important; width: auto! important;') );
			} 
            break;
 
        default:
            break;
    }
    return $value;    
	}
	
	/**
	 * Register Brands menu widget
	 *
	 * @since    1.0.1
	**/
	function wsb_brands_load_widget() {
    	register_widget( 'wsb_brands_widget' );
	}
	
	/**
	 * Register Brands Carousel widget
	 *
	 * @since    1.0.4
	**/
	function wsb_brands_load_brands_carousel_widget() {
    	register_widget( 'wsb_brands_carousel_widget' );
	}
	
	
	/**
	 * When duplicate product, we need to duplicate brand too
	 *
	 * @since    1.0.1
	**/
	function copy_brand_on_product_duplicate( $duplicate, $product ) { 
	
		$original_id = $product->get_id();
		$brand = get_the_terms( $original_id, 'wsb_brands' );
		
		$duplicate_id = $duplicate->save();
		
		wp_set_object_terms( $duplicate_id, $brand[0]->term_id, 'wsb_brands', false );
		
	}
	
	/**
	 * Add CSV columns for exporting brands.
	 * @since    1.0.2
	**/
	function wsb_export_add_columns( $columns ) {
		$label = get_taxonomy( 'wsb_brands' )->labels->singular_name;
		$columns[ 'wsb_brands' ] = $label;
		return $columns;
	}
	
	/**
	 * Brand export column content.
	 *
	 * @since    1.0.2
	**/
	function wsb_brands_export( $value, $product ) {
		$brands = get_terms( array( 'object_ids' => $product->get_ID(), 'taxonomy' => 'wsb_brands' ) );
		if ( ! is_wp_error( $brands ) ) {
			if( ! empty ($brands)){
				$value = $brands[0]->name;
			} else {
				$value = "";
			}
		}
		return $value;
	}
	
	/**
 	  * Register the Brand column in the importer.
 	  *
	  * @since    1.0.2
 	 **/
	function wsb_map_columns( $options ) {
		$label = get_taxonomy( 'wsb_brands' )->labels->singular_name;
		$options[ 'wsb_brands' ] = $label;
		return $options;
	}
	
	/**
 	  * Add automatic mapping support for brands.
	  *
	  * @since 1.0.2
 	 **/
	 function wsb_add_brands_to_mapping( $columns ) {
		$label = get_taxonomy( 'wsb_brands' )->labels->singular_name;
	 	$columns[ $label ] = 'wsb_brands';
		return $columns;
	 }
	 
	 /**
 	   * Decode data items and parse JSON IDs.
 	   *
	   * @since 1.0.2
	   **/
	function wsb_parse_brand_json( $parsed_data, $importer ) {
		if ( ! empty( $parsed_data[ 'wsb_brands' ] ) ) {
			$data = $parsed_data[ 'wsb_brands' ];
			unset( $parsed_data[ 'wsb_brands' ] );
			$parsed_data[ 'wsb_brands' ] = array();
			$parsed_data[ 'wsb_brands' ][] = $data;	
		}
		return $parsed_data;
	}
	
	/**
 	  * Set Brand
 	  *
 	  * @since 1.0.2
 	 **/
	function wsb_set_taxonomy( $product, $data ) {
		if ( is_a( $product, 'WC_Product' ) ) {
			if( ! empty( $data[ 'wsb_brands' ] ) ) {
				wp_set_object_terms( $product->get_id(),  (array) $data[ 'wsb_brands' ], 'wsb_brands' );
			}
		}
		return $product;
	}
	
	/**
 	  * Filtering product list by brands in admin area
 	  *
 	  * @since 1.0.4
 	 **/
	function filter_products_by_brands( $post_type, $which ) {

	if ( 'product' !== $post_type ) {
		return;
	}
		
		$taxonomy_object = get_taxonomy( 'wsb_brands' );
		
		$taxonomy_name = $taxonomy_object->labels->name;
		
		$all_brands_label = $taxonomy_object->labels->all_items;

		$brands = get_terms( 'wsb_brands' );

		echo "<select name='wsb_brands' id='wsb_brands' class='postform'>";
		echo '<option value="">' . sprintf( esc_html__( $all_brands_label, 'wsb-brands' ), $taxonomy_name ) . '</option>';
		foreach ( $brands as $brand ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$brand->slug,
				( ( isset( $_GET['wsb_brands'] ) && ( $_GET['wsb_brands'] == $brand->slug ) ) ? ' selected="selected"' : '' ),
				$brand->name,
				$brand->count
			);
		}
		echo '</select>';

	}
	
	/**
 	  * Hiding parent select list in brand metabox
 	  *
 	  * @since 1.0.4
 	 **/
	function wsb_hide_parent_dropdown( $args ) {
    if ( 'wsb_brands' == $args['taxonomy'] ) {
        $args['echo'] = false;
    }
    return $args;
	}
	
	/**
 	  * Sorting brands in admin panel
 	  *
 	  * @since 1.0.4
 	 **/
	function wsb_brands_sorting( $sortable ) {
		$sortable[] = 'wsb_brands';
		return $sortable;
	}
	

	/**
 	  * Brands coupon filter in admin panel
 	  *
 	  * @since 1.0.5
 	 **/
	function wsb_brands_coupon_filter($coupon_id, $coupon) {
		$brand_ids = get_post_meta( $coupon_id, 'wsb_brands' );
		?>
		<p class="form-field">
					<label for="wsb_brands"><?php _e( 'Product brands', 'wsb-brands' ); ?></label>
					<select id="wsb_brands" name="wsb_brands[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Any brand', 'wsb-brands' ); ?>">
						<?php
						$brands   = get_terms( 'wsb_brands', 'orderby=name&hide_empty=0' );

						if ( $brands ) {
							foreach ( $brands as $brand ) {
								echo '<option value="' . esc_attr( $brand->term_id ) . '"' . wc_selected( $brand->term_id, $brand_ids[0] ) . '>' . esc_html( $brand->name ) . '</option>';
							}
						}
						?>
					</select> <?php echo wc_help_tip( __( 'Brands that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'wsb-brands' ) ); ?>
				</p>
		<?php 
	}
	
	/**
 	  * @since 1.0.5
 	 **/
	function wsb_brands_coupon_object_updated_props($coupon, $updated_props) {
		
		$wsb_brands = isset( $_POST['wsb_brands'] ) ? (array) $_POST['wsb_brands'] : array();
		$updated = update_post_meta( $coupon->get_id(), 'wsb_brands', array_filter( array_map( 'intval', $wsb_brands ) ) );
		if ( $updated ) {
             $updated_props[] = 'wsb_brands';
        }

		return $updated_props;
	}
	
	/**
 	  * Check if coupon is valid for fixed cart discount
 	  *
 	  * @since 1.0.5
 	 **/
	function wsb_brands_coupon_is_valid_for_cart( $valid, $coupon, $discounts) {
		
		$cart_items = $discounts->get_items();

		$coupon_brands = $coupon->get_meta( 'wsb_brands', true, 'view' );
		if(!$coupon_brands) return $valid;
			if ( is_array ( $coupon_brands ) ){
				foreach($cart_items as $product) {
					$id = wp_get_post_parent_id( $product->object['product_id'] > 0 ) ? wp_get_post_parent_id( $product->object['product_id'] ) : $product->object['product_id'];
					$product_brands = get_the_terms( $id, 'wsb_brands' );
					if($product_brands) {
						foreach ( $product_brands as $product_brand){
							if ( in_array($product_brand->term_id, $coupon_brands) ){
								return true;
							} else {
								$valid = false;
							}
						}
					} else {
						$valid = false;
					}
				}
				
			}
			
		return $valid;
	}

	
	/**
 	  * Check if coupon is valid for product
 	  *
 	  * @since 1.0.5
 	 **/
	function wsb_brands_coupon_is_valid_for_product( $valid, $product, $coupon, $values ) {
		
			$coupon_brands = $coupon->get_meta( 'wsb_brands', true, 'view' );
			if(!$coupon_brands) return $valid;
			if ( is_array ( $coupon_brands ) ){
				$id = $product->get_parent_id() > 0? $product->get_parent_id() : $product->get_id();
				$product_brands = get_the_terms( $id, 'wsb_brands' );
				if($product_brands) {
					foreach ( $product_brands as $product_brand){
						if ( in_array($product_brand->term_id, $coupon_brands) && $valid == true ){
							$valid = true;
						} else {
							$valid = false;
						}
					}
				} else {
					$valid = false;
				}
			}

		return $valid;
	 }
	 
	 /**
 	  * Show brand name under product in the cart
 	  *
 	  * @since 1.0.6
 	 **/
	 function wsb_brands_cart_item_data( $cart_data, $cart_item ) {

		$show_brand = get_option( 'wc_wsb_brands_admin_tab_show_in_cart' );

		$product_id =  $cart_item['product_id'];

		$brand = get_the_terms( $product_id, 'wsb_brands' );

		if ( $brand && 'no' != $show_brand ){

			$brand_label = get_taxonomy( 'wsb_brands' )->labels->singular_name;
	
			$product =  $cart_item['data']; 

			foreach( wp_get_post_terms( $product_id, 'wsb_brands' ) as $wp_term ){
				$brand_names[] = $wp_term->name;
			}
				
			$brands = implode( ', ', $brand_names);
	
			if( count( $brand_names ) > 0 ){
				$cart_data[] = array(
					'name'      => $brand_label,
					'value'     => $brands,
					'display'   => '',
				);
			}
		}
		return $cart_data;
	}

}