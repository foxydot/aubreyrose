<?php
/*
Plugin Name: MSD Woo Product Widget
Description: Creates a widgetized product.
Version: 0.1
Author: Catherine M OBrien Sandrick (CMOS)
Author URI: http://msdlab.com/biological-assets/catherine-obrien-sandrick/
License: GPL v2
*/

class MSD_Woo_Product_Widget extends WP_Widget{
	function __construct() {
		$widget_ops = array('classname' => 'widget_product', 'description' => __('Product in sidebar'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('product', __('Product Widget'), $widget_ops, $control_ops);
	}
	
	function widget( $args, $instance ) {
		extract($args);
		global $woocommerce;
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$query_args = array(
			'ID'			 => $instance['product_id'],
    		'posts_per_page' => $number,
    		'post_status' 	 => 'publish',
    		'post_type' 	 => 'product',
    		'no_found_rows'  => 1,
    		'order'          => $order == 'asc' ? 'asc' : 'desc'
    	);
		$product = new WP_Query($query_args);
		ob_start();
		if($product->have_posts()){
			while($product->have_posts()){
				$product->the_post();
				wc_get_template( 'content-single-product.php', array( 'show_rating' => $show_rating ) );
			}
		}
		$content = ob_get_clean();
		echo $before_widget; 
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
		echo $content;
		echo $after_widget;
	}
	
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['product_id'] = $new_instance['product_id'];
		
		return $instance;
	}
	
	
	function form( $instance ) {
		$products = get_posts(array(
			'post_type' => 'product'
		));
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$product_id = $instance['product_id'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('product_id'); ?>"><?php _e('Product:'); ?></label>
		<select name="<?php echo $this->get_field_name('product_id'); ?>" id="<?php echo $this->get_field_id('product_id'); ?>">
			<?php
				foreach($products AS $product){
					print '<option value="'.$product->ID.'" '.selected($product_id,$product->ID,0).'>'.$product->post_title.'</option>';
				}
			?>
		</select>
		</p>
<?php
	}
	
	function init() {
		if ( !is_blog_installed() )
			return;
	
		register_widget('MSD_Woo_Product_Widget');
	}
}

add_action('widgets_init',array('MSD_Woo_Product_Widget','init'),10);