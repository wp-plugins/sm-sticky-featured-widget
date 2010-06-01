<?php
/*
Plugin Name: SM Sticky Featured Widget
Plugin URI: http://www.smwphosting.com/extend/plugins/sm-sticky-widget
Description: A tiny but high in demand widget to post sticky or "featured" posts into any widget area.
Author: Seth Carstens
Version: 1.0.2
Author URI: http://www.smwphosting.com/
*/

// Register the widget.
add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_smSticky");'));

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

		/* User-selected settings. */
		//title
		$title = apply_filters('widget_title', $instance['title'] );
		//number of posts (if never saved, defaults to 5
		if ( !$number = (int) $instance['number'] ) $number = 5;
		else if ( $number < 1 ) $number = 1;
		else if ( $number > 15 ) $number = 15;
		//thumbnails
		$showthumbs = isset( $instance['showthumbs'] ) ? $instance['showthumbs'] : false;
		$catTitles = isset( $instance['catTitles'] ) ? $instance['catTitles'] : false;


		//Building The Query
		//if the blog category is set, get it and set it to a negative number to exclude it
		if(get_option('cp_blog_cat')) $blogcatexclude = (int)CP_BLOG_CAT_ID * (-1);
		else $blogcatexclude = '';
		
		//getting the category depending on the type of page we are on IS_SINGLE=POST/PAGE
		if(is_single()) {
			$categories = get_the_category($post->ID);
			$get_cat_name = $categories[0]->cat_name; 
			$get_cat_id = $categories[0]->cat_ID;
		}
		//else its a ARCHIVE/INDEX
		else
		{
			$get_cat_name = single_cat_title("", false);
			$get_cat_id = get_cat_ID($get_cat_name);
			
		}
		
		//if not on the home page, we must be in a category, display that title instead.
		if( ($get_cat_id > 0) && $catTitles) {
			echo $before_title . __('Featured in ') . $get_cat_name . $after_title;	
			$argsqry = array('cat' => $get_cat_id);
		}
		//means this is the home page : use defined title and exclude blog category ID's
		else {
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

			if(trim($cp_cats_string) != '') { $argsqry = array('cat' => $cp_cats_string); }
			
		}
		
		//always use these attributes when loading the query
		$queryArrayOrString = array(
			'post__in'  => get_option('sticky_posts'),
			'posts_per_page' => $number,
			'orderby' => 'rand',
			'post_status' => 'publish');
		if(isset($argsqry) && (count($argsqry) > 0) ) { $queryArrayOrString = array_merge($argsqry, $queryArrayOrString); }
		$smStickyPosts = new WP_Query($queryArrayOrString);

		//The Loop
		while ($smStickyPosts->have_posts()) : $smStickyPosts->the_post();
			if (has_post_thumbnail() && $showthumbs){
				echo '<a href="' . get_permalink() . '">';
				the_post_thumbnail(array(get_option('thumbnail_size_w'),get_option('thumbnail_size_h')), array('class' => 'alignleft'));
				echo '</a>';
			}
			elseif(function_exists('cp_get_image_url_feat') && $showthumbs) cp_get_image_url_feat(get_the_id(), 'thumbnail', 'captify', 1);
			echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			echo '<br style="clear: both;" />';
		endwhile;
		//End of The Loop
		
		echo $after_widget;
	} // End function widget.
	
	
	
	// Updates the settings.
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
        $instance['showthumbs'] = isset($new_instance['showthumbs']) ? 1 : 0;
        $instance['catTitles'] = isset($new_instance['catTitles']) ? 1 : 0;
		return $instance;
	} // End function update
	
	
	
	// The admin form.
	function form($instance) {	
	$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	if ( !isset($instance['number']) || !$number = (int) $instance['number'] ) $number = 5;
?>
<div id="smSticky-admin-panel">
    <p><label for="<?php echo $this->get_field_id("title"); ?>">Title:</label>
    <input type="text" class="widefat" name="<?php echo $this->get_field_name("title"); ?>" id="<?php echo $this->get_field_id("title"); ?>" value="<?php echo $instance["title"]; ?>" /></p>

	<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
	<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

    <p><input class="checkbox" type="checkbox" <?php checked( $instance['showthumbs'], true ); ?> id="<?php echo $this->get_field_id( 'showthumbs' ); ?>" name="<?php echo $this->get_field_name('showthumbs'); ?>" />
    <label for="<?php echo $this->get_field_id('showthumbs'); ?>"> Display Thumbnails?</label></p>
    
        <p><input class="checkbox" type="checkbox" <?php checked( $instance['catTitles'], true ); ?> id="<?php echo $this->get_field_id( 'catTitles' ); ?>" name="<?php echo $this->get_field_name('catTitles'); ?>" />
    <label for="<?php echo $this->get_field_id('catTitles'); ?>"> Only show sticky posts of the post category & sub categories?</label></p>
    
    </div>
    
<?php
	} // end function form

} // end class WP_Widget_smSticky

?>