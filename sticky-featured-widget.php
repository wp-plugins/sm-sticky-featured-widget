<?php
/*
Plugin Name: SM Sticky Featured Widget
Plugin URI: http://www.smwphosting.com/extend/plugins/sm-sticky-widget
Description: A tiny but high in demand widget to post sticky or "featured" posts into any widget area.
Author: Seth Carstens
Version: 1.0.1
Author URI: http://www.smwphosting.com/
*/

class WP_Widget_smSticky extends WP_Widget {

	// The widget construct instatiates the widget
	function WP_Widget_smSticky() {
		$widget_ops = array( 'classname' => 'widget_smSticky', 'description' => __( "SM Sticky Featured Posts" ) );
		$this->WP_Widget('smSticky', __('SM Sticky Posts'), $widget_ops);
	} // End function WP_Widget_smSticky

	// This code displays the widget on the screen.
	function widget($args, $instance) {
		extract($args);
		echo $before_widget;

		//Building The Query
		
		//if the blog category is set, get it and set it to a negative number to exclude it
		if(get_option('cp_blog_cat')) $blogcatexclude = (int)CP_BLOG_CAT_ID * (-1);
		else $blogcatexclude = '';
		
		//getting the category depending on the type of page we are on
		if(is_single()) {
			$categories = get_the_category($post->ID);
			$get_cat_name = $categories[0]->cat_name; 
			$get_cat_id = $categories[0]->cat_ID;
		}
		else
		{
			$get_cat_name = single_cat_title("", false);
			$get_cat_id = get_cat_ID($get_cat_name);
		}
		
		//if get cat ID returned nothing (zero) or we are on the home page, use defined title and exclude blog
		if($get_cat_id == 0 || is_home()) {
			if(isset($instance['title'])) echo $before_title . $instance['title'] . $after_title;
			else  echo $before_title . __('Sticky Posts', 'cp') . $after_title;
			
			//CLASSIPRESS ONLY grab a string of all the blog categories.
			$blog_cats_string = (string)CP_BLOG_CAT_ID;
			$blog_cat_args = array(
				'type'                     => 'post',
				'child_of'                 => CP_BLOG_CAT_ID,
				'pad_counts'               => false );
			$blog_categories = get_categories($blog_cat_args);
			foreach ($blog_categories as $cat) {
				$blog_cats_string .= "," . $cat->cat_ID;
			}
			
			//grab all the categories and exclude the blog categories
			$cp_cat_args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 1,
				'hierarchical'             => 1,
				'exclude'                  => $blog_cats_string,
				'pad_counts'               => false );
			
			$cp_categories = get_categories($cp_cat_args);
			foreach ($cp_categories as $cat) {
				$cp_cats_string .= $cat->cat_ID . ",";
			}

			$argsqry = array('cat' => $cp_cats_string);	
		}
		//if no error and not on the home page, we must be in a category, display that title instead.
		else {
			echo $before_title . __('Featured in ') . $get_cat_name . $after_title;	
			$argsqry = array('cat' => $categories[0]->cat_ID);
		}
		
		//always use these attributes when loading the query
		$queryArrayOrString = array(
			'post__in'  => get_option('sticky_posts'),
			'posts_per_page' => 5,
			'orderby' => 'rand',
			'post_status' => 'publish');

		if(isset($argsqry)) $queryArrayOrString = array_merge($argsqry, $queryArrayOrString);
		$smStickyPosts = new WP_Query($queryArrayOrString);

		//The Loop
		while ($smStickyPosts->have_posts()) : $smStickyPosts->the_post();
			if (has_post_thumbnail()){
				echo '<a href="' . get_permalink() . '">';
				the_post_thumbnail(array(get_option('thumbnail_size_w'),get_option('thumbnail_size_h')), array('class' => 'alignleft'));
				echo '</a>';
			}
			elseif(function_exists('cp_get_image_url_feat')) cp_get_image_url_feat(get_the_id(), 'thumbnail', 'captify', 1); 
			echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			echo '<br style="clear: both;" />';
		endwhile;
		//End of The Loop
		
		//Reset Query
		//wp_reset_query();
		//echo '<br /><br />Debug of Query Array Arguments Passed: <br />';
		//var_dump($queryArrayOrString);
		
		echo $after_widget;
	} // End function widget.
	
	
	
	// Updates the settings.
	function update($new_instance, $old_instance) {
		return $new_instance;
	} // End function update
	
	
	
	// The admin form.
	function form($instance) {		
		echo '<div id="smSticky-admin-panel">';
		echo '<label for="' . $this->get_field_id("title") .'">smSticky Title:</label>';
		echo '<input type="text" class="widefat" ';
		echo 'name="' . $this->get_field_name("title") . '" '; 
		echo 'id="' . $this->get_field_id("title") . '" ';
		echo 'value="' . $instance["title"] . '" />';
		echo '<p>This widget will display the title you choose above followed by posts with the "sticky" published option enabled.</p>';
		echo '</div>';
	} // end function form

} // end class WP_Widget_smSticky

// Register the widget.
add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_smSticky");'));

?>