<?php

/**
 * @link       https://www.webstudiobrana.com
 * @since      1.0.0
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/public
 * @author     Branko Borilovic <brana@webstudiobrana.com>
 */
class Wsb_Brands_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}

	/**
	 * Register the stylesheets for frontend.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( 'slick', plugin_dir_url( __FILE__ ) . 'css/slick.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wsb-brands-public.css', array(), $this->version, 'all' );

	}
	
	/**
	 * Register scripts for frontend.
	 *
	 * @since    1.0.4
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'slick-js', plugin_dir_url( __FILE__ ) . 'js/slick.min.js', array('jquery'), $this->version, false );

	}

	
	/**
	 * Brand position on product details page.
	 *
	 * @since    1.0.0
	 */
	public function single_product_page_brand() {

		$position_set = get_option( 'wc_wsb_brands_admin_tab_single_position' );
		
		$hook = "woocommerce_product_meta_end";
		
		switch ($position_set) 
		{
			case "after_short_desc":
				$hook = "woocommerce_before_add_to_cart_form";
				break;
			case "after_add_to_cart":
				$hook = "woocommerce_after_add_to_cart_form";
				break;
			case "after_meta":
				$hook = "woocommerce_product_meta_end";
				break;
			case "after_sharing":
				$hook = "woocommerce_share";
				break;
		}
		
		add_action( $hook, array( $this, 'single_product_page_brand_position' ) );
		
	}
	
	
	/**
	 * Brand position on product loop.
	 *
	 * @since    1.0.0
	 */
	public function product_loop_page_brand() {

		$position_set = get_option( 'wc_wsb_brands_admin_tab_loop_position' );
		
		$hook = "woocommerce_after_shop_loop_item_title";
		
		switch ($position_set) 
		{
			case "after_name":
				$hook = "woocommerce_after_shop_loop_item_title";
				break;
			case "on_top":
				$hook = "woocommerce_before_shop_loop_item";
				break;
			case "after_price":
				$hook = "woocommerce_after_shop_loop_item";
				break;
		}
		
		add_action( $hook, array( $this, 'product_loop_brand_position' ), 1 );
		
	}
	
	
	/**
	 * Parameters for display brand on product details page.
	 *
	 * @since    1.0.0
	 */
	public function single_product_page_brand_position() {
		
		$id = get_the_ID();
		
		$brand = get_the_terms( $id, 'wsb_brands' );
		
		$display_options = get_option( 'wsb_brands_admin_tab_show_single_product' );
		
		$label = "";
		
		$show_label = get_option( 'wc_wsb_brands_admin_tab_show_label_single_product' );
		
		$logo_height = "50";
		
		if( "" != (get_option( 'wsb_brands_logo_height_single_product' )))
		{
			$logo_height = get_option( 'wsb_brands_logo_height_single_product' );
		}
		
		if ( "yes" == $show_label)
		{
			$label = "<span>" . get_taxonomy( 'wsb_brands' )->labels->singular_name . ": </span>";
		}
		
		if($brand && 'no' != $display_options)
		{
			$link = get_term_link( $brand[0]->name, 'wsb_brands' );
			$logo_id = get_term_meta( $brand[0]->term_id, 'logo', 1 );
			$logo = null;
			if($logo_id)
			{
				$logo = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'height:'.$logo_height.'px !important; width: auto! important; display:inline-block;') );
			}
			
			switch ( $display_options )
			{
				case "brand":
					echo "<p>".$label.$brand[0]->name."</p>";
					break;
				case "brand_link":
					echo "<p><a class='brand_link_single_product' href='".$link."'>".$label.$brand[0]->name."</a></p>";
					break;
				case "logo":
					if( $logo ){
              			echo '<p>'.$label.$logo.'</p>';
            		} else {
              			echo '<p>'.$label.$brand[0]->name.'</p>';
            		}
					break;
				case "logo_link":
					if( $logo ){
              			echo '<p><a class="brand_link_single_product" href="'.$link.'">'.$label.$logo.'</a></p>';
            		} else {
              			echo '<p><a class="brand_link_single_product" href="'.$link.'">'.$label.$brand[0]->name.'</a></p>';
            		}
					break;
			}
			
		}
	}
	
	
	/**
	 * Parameters for display brand on product loop.
	 *
	 * @since    1.0.0
	 */
	public function product_loop_brand_position() {
		
		$product = wc_get_product();
		
		$id = $product->get_id();
		
		$brand = get_the_terms( $id, 'wsb_brands' );
		
		$display_options = get_option( 'wsb_brands_admin_tab_show_product_loop' );
		
		$label = "";
		
		$show_label = get_option( 'wc_wsb_brands_admin_tab_show_label_loop' );
		
		if ( "yes" == $show_label)
		{
			$label = get_taxonomy( 'wsb_brands' )->labels->singular_name . ": ";
		}
		
		if($brand && 'no' != $display_options)
		{
			switch ( $display_options )
			{
				case "brand":
					echo "<p>".$label.$brand[0]->name."</p>";
					break;
				case "brand_link":
					$link = get_term_link( $brand[0]->name, 'wsb_brands' );
					echo '<p><a class="brand_link_loop" href="'.$link.'">'.$label.$brand[0]->name.'</a></p>';
					break;
			}
			
		}
	}
	
	
	public function change_document_title() {
		if ( is_tax( 'wsb_brands' ) )
		{
			$brand = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
    		return $brand->name;
		}
	}
	
	public function change_page_title() {
		if ( is_tax( 'wsb_brands' ) )
		{
			$brand = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$logo_id = get_term_meta( $brand->term_id, 'logo', 1 );
			$logo = '';
			$logo_height = "50";
			$page_title = "<h1>" . $brand->name . "</h1>";
			if ( get_option( 'wsb_brands_logo_height_archive' ))
			{
				$logo_height = get_option( 'wsb_brands_logo_height_archive' );
			}
			$logo_position = "right";
			if ( get_option( 'wc_wsb_brands_admin_tab_archive_position' ))
			{
				$logo_position = get_option( 'wc_wsb_brands_admin_tab_archive_position' );
			}
			$show_logo = "yes";
			if ( get_option( 'wc_wsb_brands_admin_tab_show_logo_archive' ))
			{
				$show_logo = get_option( 'wc_wsb_brands_admin_tab_show_logo_archive' );
			}
			$show_title = "yes";
			if ( get_option( 'wc_wsb_brands_admin_tab_show_title_archive' ))
			{
				$show_title = get_option( 'wc_wsb_brands_admin_tab_show_title_archive' );
			}
			
			$header_bg = "transparent";
			if ( get_option( 'wc_wsb_brands_brand_archive_bgcolor' ))
			{
				$header_bg = get_option( 'wc_wsb_brands_brand_archive_bgcolor' );
			}
			
			$border_thickness = "0px";
			if ( get_option( 'wc_wsb_brands_brand_archive_border_thickness' ))
			{
				$border_thickness = get_option( 'wc_wsb_brands_brand_archive_border_thickness' ) . "px";
			}
			
			$border_color = "transparent";
			if ( get_option( 'wc_wsb_brands_brand_archive_border_color' ))
			{
				$border_color = get_option( 'wc_wsb_brands_brand_archive_border_color' );
			}
			
			if ( "yes" != $show_title ) {
				$page_title = "";
			}
			
			if ( $logo_id  && "yes" == $show_logo) {
				$logo = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'margin: 0 10px; max-height:'.$logo_height.'px !important; width: auto! important;') );
				?>
				
				<div class="wsb-brand-logo-wrap" style="background-color: <?php echo $header_bg; ?>; border: <?php echo $border_thickness; ?> solid <?php echo $border_color; ?>; ">
				
                <?php
				if( "right" == $logo_position )
				{
				?>
                	
                    	<div class="wsb-brand-archive-left">
                        	<?php echo $page_title; ?>
                        </div>
                        <div class="wsb-brand-archive-right">
                        	<?php echo $logo; ?>
                        </div>
                        <div style="clear:both;"></div>

				<?php 
                } 
				else if ("left" == $logo_position )
				{
					?>
                    	<div class="wsb-brand-archive-left">
                        	<?php echo $logo; ?>
                        </div>
                        <div class="wsb-brand-archive-left">
                        	<?php echo $page_title; ?>
                        </div>
                        <div style="clear:both;"></div>

				<?php
				}
			}
			else
			{
				echo $page_title;
			}
			?>
            </div>
            <?php 
		} else {
			return true;
		}
	}
	
	
	public function wsb_brands_product_tab( $tabs ) {
		
		$show_tab = "yes";
		$id = get_the_ID();
		$brand = get_the_terms( $id, 'wsb_brands' );

		if ( ! $brand){
			return $tabs;
		}
		
		if( "no" == get_option( 'wc_wsb_brands_admin_tab_single_product_tab') || !$brand)
		{
			$show_tab = "no";
		}
		
		if( "yes" == $show_tab)
		{
			$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
			$label_info = __( 'Brand info', 'wsb-brands' );
			
			if ( $general_term == "manufacturer" ) {
				
				$label_info = __( 'Manufacturer info', 'wsb-brands' );
				
			}
	
			$tabs['wsb_brands_tab'] = array(
			'title' 	=> $label_info,
			'priority' 	=> 50,
			'callback' 	=> array( $this, 'wsb_brands_product_tab_content'),
			);
		}

	return $tabs;

	}
	
	function wsb_brands_product_tab_content() {

	$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$label_info = __( 'Brand info', 'wsb-brands' );
		
		if ( $general_term == "manufacturer" ) {
			
			$label_info = __( 'Manufacturer info', 'wsb-brands' );
			
		}
	
	echo '<h2>'. $label_info .'</h2>';
	
	$id = get_the_ID();
		
	$brand = get_the_terms( $id, 'wsb_brands' );
		
	$logo_height = "100";
		
	$link = get_term_link( $brand[0]->name, 'wsb_brands' );
	
	$logo_id = get_term_meta( $brand[0]->term_id, 'logo', 1 );
	
	$website = get_term_meta( $brand[0]->term_id, 'url', 1 );
	
	$logo = "";
	
	if($logo_id)
	{
	  $logo = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'max-height:'.$logo_height.'px !important; width: auto! important;') );
	}
	
	?>
    
    <div class="wsb-brand-product-logo-wrap">
        <div class="wsb-brand-product-left">
             <h3><?php 
			 echo $brand[0]->name; ?></h3> 
             <?php 
			 echo "<p><a class='brand_link_single_product' href='".$link."'>".__( 'View All Products', 'wsb-brands' )."</a></p>";
	
			 if( $website ){
				echo "<p><a class='brand_link_single_product' href='".$website."'>".__( 'Website', 'wsb-brands' )."</a></p>";	
			 }
			 ?>
        </div>
        <div class="wsb-brand-product-right">
             <?php echo $logo; ?>
             
        </div>
        
    	<div style="clear:both;"></div>
        <p><?php echo $brand[0]->description; ?></p>
    </div>
    
    <?php 
	}

	/**
	 * Adding brand to structured data for single product.
	 *
	 * @since    1.1
	 */
	function wsb_brands_add_brand_to_structured_data( $markup, $product ) {
		if( is_product() ){
			$id = $product->get_id();
			$brand = get_the_terms( $id, 'wsb_brands' );
			if( $brand ){
				$markup['brand'] = $brand[0]->name;
			}
		} 
		return $markup;
	}

}