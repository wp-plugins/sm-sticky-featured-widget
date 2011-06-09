<?php
/*
Plugin Name: SM Sticky Featured Widget
Plugin URI: http://sethmatics.com/extend/plugins/sm-sticky-widget
Description: A tiny but high in demand widget to post sticky or "featured" posts into any widget area complient with ClassiPress.
Author: Seth Carstens
Version: 1.1.0
Author URI: http://sethmatics.com/
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
		wp_reset_query();
		extract($args);
		
		//detect and set configuration for classipress or standard wordpress theme
		if(post_type_exists('ad_listing')) { $cp = true; $postType = 'ad_listing'; $catType = 'ad_cat'; }
		else { $cp = false; $postType = 'post'; $catType = 'category'; }
		
		if(get_query_var('taxonomy')) {
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		}
		elseif(is_category()) {
			$term = get_term_by('id', get_cat_ID(single_cat_title("", false)), 'category');
		}
		elseif(is_single()) { //single page
			$terms = wp_get_post_terms(get_the_ID(), $catType); //print_r($terms); exit;
			$term = $terms[0];
		}

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
		
		//determine the sticky post type (custom post types)
		if(post_type_exists('ad_listing')) $postType = 'ad_listing';
		else $postType = 'post';
		
		//always use these attributes when loading the query
		$queryArrayOrString = array(
			'post__in'  => get_option('sticky_posts'),
			'post_type' => $postType,
			'posts_per_page' => $number,
			'orderby' => 'rand',
			'post_status' => 'publish');

		//if there is a term, only display featured ads from that term assuming the option is turned on
		if($term->slug && $catTitles) {
			if($cp) $queryArrayOrString['ad_cat'] = $term->slug;
			else $queryArrayOrString['category_name'] = $term->slug;
		}
		$smStickyPosts = new WP_Query($queryArrayOrString);
		
		//start printing the widget
		echo $before_widget;
		
		//if not on the home page, we must be in a category, display that title instead.
		if( !is_home() && $catTitles) echo $before_title . __('Featured in ') . $term->name . $after_title;	
		//means this is the home page : use defined title and exclude blog category ID's
		else {
			if(isset($instance['title'])) echo $before_title . $instance['title'] . $after_title;
			else  echo $before_title . __('Sticky Posts', 'cp') . $after_title;			
		}
		
		//The Loop (modified)
		echo '<ul class="featured-sidebar">';
		$i = 0;
		if(!$smStickyPosts->have_posts()) echo 'No Featured Posts Found';
		while ($smStickyPosts->have_posts() && ($i < $smStickyPosts->query_vars['posts_per_page'])) : $smStickyPosts->the_post();
			if(post_type_exists('ad_listing')) : 
		?>
			<li>
				<div class="post-thumb" style="min-width:50px;">
					<?php if(function_exists('cp_ad_featured_thumbnail') && $showthumbs) cp_ad_featured_thumbnail();
				elseif (has_post_thumbnail() && $showthumbs){
					echo '<a href="' . get_permalink() . '">';
					the_post_thumbnail(array(get_option('thumbnail_size_w'),get_option('thumbnail_size_h')), array('class' => 'alignleft'));
					echo '</a>';
				}	 ?>
				</div>
				<h3><a href="<?php the_permalink(); ?>"><?php if (mb_strlen(get_the_title()) >= 40) echo mb_substr(get_the_title(), 0, 40).'...'; else the_title(); ?></a></h3>
				<p class="side-meta"><span class="folder"><?php if (get_the_category()) the_category(', '); else echo get_the_term_list($post->ID, 'ad_cat', '', ', ', ''); ?></span> | <?php if(get_post_meta(get_the_ID(), 'price', true)) cp_get_price_legacy(get_the_ID()); else cp_get_price(get_the_ID(), 'cp_price'); ?></p>
				<p><?php echo mb_substr(strip_tags(get_the_content()), 0, 160).'...';?></p>
			</li>
			<?php
			else :	
				echo '<li>';
				if(function_exists('cp_ad_featured_thumbnail') && $showthumbs) cp_ad_featured_thumbnail();
				elseif (has_post_thumbnail() && $showthumbs){
					echo '<a href="' . get_permalink() . '">';
					the_post_thumbnail(array(get_option('thumbnail_size_w'),get_option('thumbnail_size_h')), array('style' => 'float: left; width: 50px; height: 50px; margin-right: 5px;'));
					echo '</a>';
				}		
				echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				echo '<p style="margin: 0px;">'.mb_substr(strip_tags(get_the_content()), 0, 160).'...'.'</p>';
				echo '<br style="clear: both;" />';
				echo '</li>';
			endif;
			$i++;
		endwhile;
		echo '</ul>';
		//End of The Loop
		echo $after_widget;
		
		//debug printing
		//echo 'query_vars <pre>'; print_r($smStickyPosts->query_vars); echo '</pre>';
		//echo '$queryArrayOrString <pre>'; print_r($queryArrayOrString); echo '</pre>';
		//echo '$term <pre>'; print_r($term); echo '</pre>';
		wp_reset_query();
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