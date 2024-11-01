<?php

/**
 * Wsb_Brands Plugin shortcodes
 *
 * @link       https://www.webstudiobrana.com
 * @since      1.0.0
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 */

/**
 * This class defines shortcodes for the plugin.
 *
 * @since      1.0.0
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 * @author     Branko Borilovic <brana@webstudiobrana.com>
 */
class Wsb_Brands_Shortcodes {

	/**.
	 * @since    1.0.0
	 */
	public static function init() {

		add_shortcode( 'brands_page', array( __CLASS__, 'custom_brands_page' ) );
		
	}
	
	public static function custom_brands_page($atts) {
		
		/*
		 * Shortcode attributes with default values defined
		 */
		$a = shortcode_atts( array(
      		'logo_height' => '40',
			'count' 	  => 'yes',
			'show' 		  => 'both',
			'grid' 		  => '4',
			'hide_empty'  => 'no'
   		), $atts );
		
		$hide_empty = 'yes' == $a['hide_empty'] ? true : false;
		
    	$brands = get_terms( array(
    		'taxonomy' => 'wsb_brands',
    		'hide_empty' => $hide_empty,
		) );
		
		$html = '';

		if(!empty($brands)){
			
			switch($a['grid'])
			{
				case "1":
				$grid_class = "-col-1";
				break;
				case "2":
				$grid_class = "-col-2";
				break;
				case "3":
				$grid_class = "-col-3";
				break;
				case "4":
				$grid_class = "-col-4";
				break;
				case "5":
				$grid_class = "-col-5";
				break;
				case "6":
				$grid_class = "-col-6";
				break;
				default:
				$grid_class = "-col-4";
				break;
			}
			
			
		
        $html = '<div class="wsb_brands_grid_wrap">';
        $html .= '<div class="wsb_brands_grid'.$grid_class.'">';
 
			foreach( $brands as $brand ){ //Brand box start
				$brand_archive_link = get_term_link( $brand->term_id, 'wsb_brands' );
				$html .= '<a href="'.$brand_archive_link.'"><div>';
				$brand_name = $brand->name;
				$brand_count = " (".$brand->count.")";
				if( "no" == $a['count']) {
					$brand_count = "";
				}
				
				$logo = "";
				$logo_height = $a['logo_height'];
				$logo_id = get_term_meta( $brand->term_id, 'logo', 1 );
				
				if( "logo" == $a['show'] ) {
					if($logo_id)
					{
						$logo = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'max-height:'.$logo_height.'px !important;') );
					} else {
						$logo = $brand_name;
					}
					$html .= $logo;
				} elseif ( "name" == $a['show'] ) {
					$html .= '<h4>'.$brand_name.' '.$brand_count.'</h4>';
				} else {
					if($logo_id)
					{
						$logo = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'max-height:'.$logo_height.'px !important;') );
					}
					$html .= $logo.'<p>'.$brand_name.' '.$brand_count.'</p>';
				}
			
				$html .= '</div></a>';	
			} // Brand box end
			
            $html .= '</div></div>';
		}
		/**.
		* @since    1.0.5
		* replaced "echo" for gutenberg compatibility
	 	*/
		return $html;

	}

}