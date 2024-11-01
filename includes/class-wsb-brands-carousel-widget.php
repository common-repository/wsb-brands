<?php

/**
 * Wsb Brands Carousel widget
 *
 * @link       https://www.webstudiobrana.com
 * @since      1.0.4
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 */

/**
 * This class defines brands carousel widget.
 *
 * @since      1.0.4
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 * @author     Branko Borilovic <brana@webstudiobrana.com>
 */
class Wsb_Brands_Carousel_Widget extends WP_Widget {

	/**.
	 * @since    1.0.4
	 */
	public function __construct() {
		
		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$description = __('Brands carousel widget', 'wsb-brands');
		
		if ( $general_term == "manufacturer" ) {
			
			$description = __('Manufacturers carousel widget', 'wsb-brands');
			
		}
		
    	$widget_options = array( 
      		'classname' => 'wsb_brands_carousel_widget',
      		'description' => $description,
    	);
    parent::__construct( 'wsb_brands_carousel_widget', 'Brands carousel widget', $widget_options );
  	}
	
	
	public function widget( $args, $instance ) {
		
		$widget_id = $args['widget_id'];
 		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$show_brand_name = ! empty( $instance['show_brand_name'] ) ? '1' : '0';
		$show_count = ! empty( $instance['show_count'] ) ? '1' : '0';
		$show_arrows = ! empty( $instance['show_arrows'] ) ? '1' : '0';
		
		$order_by = ! empty( $instance['order_by'] ) ? $instance['order_by'] : 'ordering_name_asc';
		//'ordering_name_asc'
		
		switch ( $order_by ) {
			case 'ordering_name_asc':
				$orderby = "name";
				$order = "ASC";
				break;
			case 'ordering_name_desc':
				$orderby = "name";
				$order = "DESC";
				break;
			case 'ordering_id_asc':
				$orderby = "term_id";
				$order = "ASC";
				break;
			case 'ordering_id_desc':
				$orderby = "term_id";
				$order = "DESC";
				break;
			default:
				$orderby = "name";
				$order = "ASC";
		}
		
		
		$hide_empty = (! empty( $instance['hide_empty'] ) && '1' == $instance['hide_empty']) ? true : false;
		$brands_to_load = (! empty( $instance['brands_to_load'] ) ) ? $instance['brands_to_load'] : false;
		$selected_action = (! empty( $instance['selected_action'] ) ) ? $instance['selected_action'] : 'include';
		
		
  		$brands = get_terms( array(
    		'taxonomy' => 'wsb_brands',
    		'hide_empty' => $hide_empty,
			'orderby' => $orderby,
			'order' => $order,
			$selected_action => $brands_to_load,
		) );
  		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>
        
        
        <div id="carousel-<?php echo $widget_id; ?>" class="wsb-brands-carousel-wrap">
        
        <?php 
		foreach( $brands as $brand ){
			$brand_archive_link = get_term_link( $brand->term_id, 'wsb_brands' );
			$brand_counts = '';
			$brand_name = '';
			if($show_count)
			{
				$brand_counts = ' <span>('. $brand->count .')</span>';
			}
			if ( $show_brand_name )
			{
				$brand_name = $brand->name;
			}
			
			$logo = "";
			$logo_height = ! empty( $instance['logo_height'] ) ? $instance['logo_height'] : 50;
			$logo_id = get_term_meta( $brand->term_id, 'logo', 1 );
			
			if($logo_id){
				$logo = wp_get_attachment_image( $logo_id, 'medium', true, array( 'style' => 'max-height:'.$logo_height.'px; width: auto; max-width: 100%;') );
			} else {
				$logo = $brand_name;
			}
			
			/**
			  * Slider params
			 **/
			 $slider_autoplay = 'true';
			 $slide_delay = ! empty( $instance['slide_delay'] ) ? $instance['slide_delay'] : 3000;
			 $arrows = (! empty( $instance['show_arrows'] ) && '1' == $instance['show_arrows']) ? 'true' : 'false';
			 $slides_to_show = ! empty( $instance['slides_to_show'] ) ? $instance['slides_to_show'] : 1;
			 $slide_border = ! empty( $instance['slide_border'] ) ? $instance['slide_border'] : 0;
			 $slides_to_scroll = ! empty( $instance['slides_to_scroll'] ) ? $instance['slides_to_scroll'] : 1;
			
		?>
			<div style="position: relative;">
            <div class="wsb-slide-item" style="border-width: <?php echo $slide_border; ?>px;">
            <a style="display:inline-block;" href="<?php echo $brand_archive_link; ?>"><?php echo $logo; ?> </a>
            <?php 
			if($show_brand_name || $show_count)
			{
			?>
            	<p class="slide-text"><?php echo $brand_name . $brand_counts; ?></p>
            <?php 
			}
			?>
            </div>
            </div>  
        <?php	
		}
		?>
        </div>

        <script type="text/javascript">
        jQuery(document).ready( function($) {

		$('#carousel-<?php echo $widget_id; ?>.wsb-brands-carousel-wrap').slick({
			
			prevArrow: '<button type="button" class="slick-prev"></button>',
			nextArrow: '<button type="button" class="slick-next"></button>',
			autoplay: <?php echo $slider_autoplay; ?>, 
			autoplaySpeed: <?php echo $slide_delay; ?>,
			arrows: <?php echo $arrows; ?>,
			slidesToShow: <?php echo $slides_to_show; ?>,
			slidesToScroll: <?php echo $slides_to_scroll; ?>,
			variableWidth: false,
			centerMode: false,
			infinite: true,
			responsive: [
   			 
    		{
     			 breakpoint: 480,
     			 settings: {
        		 	slidesToShow: 1,
            	 	slidesToScroll: 1
      			 }
    		}
  		  ]

  		});
  
		});
		</script>

  		<?php 
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		
		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$instance_title = __('Brands carousel', 'wsb-brands');
		
		if ( $general_term == "manufacturer" ) {
			
			$instance_title = __('Manufacturers carousel', 'wsb-brands');
			
		}
		
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => $instance_title, 
			'show_count' => 1,
			'show_brand_name' => 1,
			'show_arrows' => 0,
			'hide_empty' => 0,
			'slide_delay' => 3000,
			'slides_to_show' => 1,
			'brands_to_load' => false,
			'slides_to_scroll' => 1,
			'logo_height' => 40,
			'slide_border' => 1,
			'order_by' => 'ordering_name_asc',
			'selected_action' => 'include',
			));
  		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$slide_delay = ! empty( $instance['slide_delay'] ) ? $instance['slide_delay'] : 3000;
		$slides_to_show = ! empty( $instance['slides_to_show'] ) ? $instance['slides_to_show'] : 1;
		$brands_to_load = ! empty( $instance['brands_to_load'] ) ? $instance['brands_to_load'] : false;
		$slides_to_scroll = ! empty( $instance['slides_to_scroll'] ) ? $instance['slides_to_scroll'] : 1;
		$logo_height = ! empty( $instance['logo_height'] ) ? $instance['logo_height'] : 40;
		$slide_border = ! empty( $instance['slide_border'] ) ? $instance['slide_border'] : 1;
		$order_by = ! empty( $instance['order_by'] ) ? $instance['order_by'] : 'ordering_name_asc';
		$selected_action = ! empty( $instance['selected_action'] ) ? $instance['selected_action'] : 'include';

		?>
 		<p>
    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __('Title', 'wsb-brands'); ?>:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat " />
  		</p>
        <p>
      <label for="<?php echo esc_attr( $this->get_field_id('selected_action') ); ?>"><?php echo __( 'Select:', 'wsb-brands' );?></label>
      <select
        class="widefat "
        id="<?php echo esc_attr( $this->get_field_id('selected_action') ); ?>"
        name="<?php echo esc_attr( $this->get_field_name('selected_action') ); ?>">
        <option value="include" <?php selected( $selected_action, 'include' ); ?>><?php echo __( 'Show checked', 'wsb-brands' );?></option>
        <option value="exclude" <?php selected( $selected_action, 'exclude' ); ?>><?php echo __( 'Hide checked', 'wsb-brands' );?></option>
      </select>
    </p>
        <p>
        <div class="brand-checklist-wrap">
        <ul>
    	<?php wp_terms_checklist( 0, array(
		'taxonomy' => 'wsb_brands', 
		'selected_cats' => $brands_to_load,
		) ); ?>
        </ul>
        </div>
  		</p>
        <p>
    	<label for="<?php echo $this->get_field_id( 'slides_to_show' ); ?>"><?php echo __('Slides to show', 'wsb-brands'); ?>:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'slides_to_show' ); ?>" name="<?php echo $this->get_field_name( 'slides_to_show' ); ?>" value="<?php echo esc_attr( $slides_to_show ); ?>" class="widefat " />
  		</p>
        <p>
    	<label for="<?php echo $this->get_field_id( 'slides_to_scroll' ); ?>"><?php echo __('Slides to scroll', 'wsb-brands'); ?>:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'slides_to_scroll' ); ?>" name="<?php echo $this->get_field_name( 'slides_to_scroll' ); ?>" value="<?php echo esc_attr( $slides_to_scroll ); ?>" class="widefat " />
  		</p>
        <p>
    	<label for="<?php echo $this->get_field_id( 'slide_delay' ); ?>"><?php echo __('Slide delay', 'wsb-brands'); ?>:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'slide_delay' ); ?>" name="<?php echo $this->get_field_name( 'slide_delay' ); ?>" value="<?php echo esc_attr( $slide_delay ); ?>" class="widefat " />
  		</p>
        <p>
    	<label for="<?php echo $this->get_field_id( 'logo_height' ); ?>"><?php echo __('Max logo height', 'wsb-brands'); ?>:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'logo_height' ); ?>" name="<?php echo $this->get_field_name( 'logo_height' ); ?>" value="<?php echo esc_attr( $logo_height ); ?>" class="widefat " />
  		</p>
        
        <p>
      <label for="<?php echo esc_attr( $this->get_field_id('order_by') ); ?>"><?php echo __( 'Order by:', 'wsb-brands' );?></label>
      <select
        class="widefat "
        id="<?php echo esc_attr( $this->get_field_id('order_by') ); ?>"
        name="<?php echo esc_attr( $this->get_field_name('order_by') ); ?>">
        <option value="ordering_name_asc" <?php selected( $order_by, 'ordering_name_asc' ); ?>><?php echo __( 'Name ASC', 'wsb-brands' );?></option>
        <option value="ordering_name_desc" <?php selected( $order_by, 'ordering_name_desc' ); ?>><?php echo __( 'Name DESC', 'wsb-brands' );?></option>
        <option value="ordering_id_asc" <?php selected( $order_by, 'ordering_id_asc' ); ?>><?php echo __( 'ID ASC', 'wsb-brands' );?></option>
        <option value="ordering_id_desc" <?php selected( $order_by, 'ordering_id_desc' ); ?>><?php echo __( 'ID DESC', 'wsb-brands' );?></option>
      </select>
    </p>
        
        <p>
        <input class="checkbox" type="checkbox"<?php checked( $instance['show_brand_name'] ); ?> id="<?php echo $this->get_field_id('show_brand_name'); ?>" name="<?php echo $this->get_field_name('show_brand_name'); ?>" /> <label for="<?php echo $this->get_field_id('show_brand_name'); ?>"><?php _e('Show name', 'wsb-brands'); ?></label>
  		</p>
        <p>
        <input class="checkbox" type="checkbox"<?php checked( $instance['show_count'] ); ?> id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>" /> <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Show products count', 'wsb-brands'); ?></label>
  		</p>
        <p>
        <input class="checkbox" type="checkbox"<?php checked( $instance['show_arrows'] ); ?> id="<?php echo $this->get_field_id('show_arrows'); ?>" name="<?php echo $this->get_field_name('show_arrows'); ?>" /> <label for="<?php echo $this->get_field_id('show_arrows'); ?>"><?php _e('Show navigation arrows', 'wsb-brands'); ?></label>
  		</p>
        <p>
        <input class="checkbox" type="checkbox"<?php checked( $instance['slide_border'] ); ?> id="<?php echo $this->get_field_id('slide_border'); ?>" name="<?php echo $this->get_field_name('slide_border'); ?>" /> <label for="<?php echo $this->get_field_id('slide_border'); ?>"><?php _e('Slide border', 'wsb-brands'); ?></label>
  		</p>
        <p>
        <input class="checkbox" type="checkbox"<?php checked( $instance['hide_empty'] ); ?> id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" /> <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e('Hide empty', 'wsb-brands'); ?></label>
  		</p>
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		
		
		
  		$instance = $old_instance;
 		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'show_count' ] = $new_instance[ 'show_count' ] ? 1 : 0;
		$instance[ 'show_brand_name' ] = $new_instance[ 'show_brand_name' ] ? 1 : 0;
		$instance[ 'hide_empty' ] = $new_instance[ 'hide_empty' ] ? 1 : 0;
		$instance[ 'show_arrows' ] = $new_instance[ 'show_arrows' ] ? 1 : 0;
		$instance[ 'slide_border' ] = $new_instance[ 'slide_border' ] ? 1 : 0;
		$instance[ 'slide_delay' ] = strip_tags( $new_instance[ 'slide_delay' ] );
		$instance[ 'brands_to_load' ] = $_POST[ 'tax_input' ][ 'wsb_brands' ] ;
		$instance[ 'slides_to_show' ] = strip_tags( $new_instance[ 'slides_to_show' ] );
		$instance[ 'slides_to_scroll' ] = strip_tags( $new_instance[ 'slides_to_scroll' ] );
		$instance[ 'logo_height' ] = strip_tags( $new_instance[ 'logo_height' ] );
		$instance[ 'order_by' ] = $new_instance[ 'order_by' ] ;
		$instance[ 'selected_action' ] = $new_instance[ 'selected_action' ] ;
		
		
  		return $instance;
		
	}

}