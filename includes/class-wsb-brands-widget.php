<?php

/**
 * Wsb_Brands widget
 *
 * @link       https://www.webstudiobrana.com
 * @since      1.0.0
 *
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 */

/**
 * This class defines brands widget.
 *
 * @since      1.0.0
 * @package    Wsb_Brands
 * @subpackage Wsb_Brands/includes
 * @author     Branko Borilovic <brana@webstudiobrana.com>
 */
class Wsb_Brands_Widget extends WP_Widget {

	/**.
	 * @since    1.0.0
	 */
	public function __construct() {
		
		$general_term = get_option( 'wc_wsb_brands_admin_tab_general_labels' );
		
		$description = __('Brands widget', 'wsb-brands');
		
		if ( $general_term == "manufacturer" ) {
			
			$description = __('Manufacturers widget', 'wsb-brands');
			
		}
		
    	$widget_options = array( 
      		'classname' => 'wsb_brands_widget',
      		'description' => $description,
    	);
    parent::__construct( 'wsb_brands_widget', 'Brands Widget', $widget_options );
  	}
	
	
	public function widget( $args, $instance ) {
 		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$show_count = ! empty( $instance['show_count'] ) ? '1' : '0';
		$hide_empty = (! empty( $instance['hide_empty'] ) && '1' == $instance['hide_empty']) ? true : false;
  		$brands = get_terms( array(
    		'taxonomy' => 'wsb_brands',
    		'hide_empty' => $hide_empty,
			'orderby' => 'name',
			'order' => 'ASC'
		) );
  		echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>
        
        <div class="wsb-brands-list-wrap">
        <ul class="wsb-brands-list">
        
        <?php 
		foreach( $brands as $brand ){
			$brand_archive_link = get_term_link( $brand->term_id, 'wsb_brands' );
			$brand_counts = '';
			if($show_count)
			{
				$brand_counts = ' <span class="brand-count-span">('. $brand->count .')</span>';
			}
		?>
			<li><a href="<?php echo $brand_archive_link; ?>"><?php echo $brand->name . $brand_counts; ?> </a></li>
        <?php	
		}
		?>
        </ul>
        </div>

  		<?php 
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'show_count' => 0, 'hide_empty' => 0) );
  		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
 		<p>
    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __('Title', 'wsb-brands'); ?>:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat " />
  		</p>
        <p>
        <input class="checkbox" type="checkbox"<?php checked( $instance['show_count'] ); ?> id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>" /> <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Show products count', 'wsb-brands'); ?></label>
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
		$instance[ 'hide_empty' ] = $new_instance[ 'hide_empty' ] ? 1 : 0;
  		return $instance;
		
	}

}