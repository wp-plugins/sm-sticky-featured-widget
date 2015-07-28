=== SM Sticky Featured Widget ===

Contributors: sethcarstens
Donate link: http://sethmatics.com/extend/plugins/sm-sticky-widget
Tags: widgets, sticky, featured, classipress
Requires at least: 3.2
Tested up to: 4.2.3
Stable tag: 1.2.6

A tiny but high in demand widget to post sticky or "featured" posts into any widget area. Widget provided by http://sethmatics.com/.

== Description ==

A tiny but high in demand widget to post sticky or "featured" posts into any widget area. We would appriciate any assistance you would like to provide with coding of enhancments to this plugin.
Using Documentation from core trac default widgets as a guideline for development:
http://core.trac.wordpress.org/browser/trunk/wp-includes/default-widgets.php

Features include:

* Now COMPLETELY compatable with the premium "ClassiPress" theme which can be purchased at [ClassiPress](http://www.appthemes.com/themes/classipress/  "www.appthemes.com")
* sidebar widget added to your "Widgets" appearance settings that shows only sticky posts.
* option to set the title when displayed on the home page.
* makes use of new thumbnails and "featured images" for wordpress 2.9.2 and wordpress 3.0
* option to turn on and off the thumbnails
* option to set the number of posts listed (upgraded and not dependent on wordpress query options bug)
* option to show "Category Related Sticky Posts" when sidebar displays on category page
* detect if no featured ads are in category/subcategories and post any featured posts/pages.

Features Coming in version 2 Next Version Updates:

* option to choose a custom post type to display sticky ads for (instead of only compliant with ClassiPress).
* option to set the width and height of thumbnail inline css for non-classipress themes.
* option to disable display of the title, or always show a force title option text regardless of website page type.
* option to specify "only included" categoires or to "exclude only" certain categories.
* option to "only feature listed post / page ID's" in the widget
* option to turn off the "random" order listing and choose from a dropdown of order types.
* option to limit the title to X number of characters
* option to display exceprt up to X number of characters or default to the wordpress excerpt words and length configured.
* option to enable a jQuery based veritcle sidebar carousel (exact model to be determined).

Don't forget to rate our plugin so we know how we are doing!

== Installation ==

To install the plugin manually:

1. Extract the contents of the archive (zip file)
2. Upload the sm-sticky-featured-widget folder to your '/wp-content/plugins' folder
3. Activate the plugin through the Plugins section in your WordPress admin
4. Find and "click and drag" the newly created widget to your sidebar and set the options.

*New in 1.2.1

Add the following style to your ClassiPress child themes stylesheet and then upload your own 32px by 32px corner sold ribbon graphics to enhance your ClassiPress theme. Demo available at http://cpmodlite.sethmatics.com/

/* apply new sold corner banner from sm sticky featured widget */
.sold-ribbon {	
	height: 32px;
	width: 32px;
	background: url(images/sold_corner_32.png) no-repeat;
	position: absolute;
}

== Changelog ==

Version 1.2.5
- Fixed all widget form notices

Version 1.2.1- added readmore filter for customizing  the "..." at the end of the excerpt
- fixed widget title issue regarding widget filter and multi-language support
- Updated for custom post types used in ClassiPress.
- Heavily modified the queries and is now 100% ClassiPress compliant.
- See upgrade notices for more information about each version

== Upgrade Notice ==
Version 1.2.1
- Added sold-ribbon div tag for ClassiPress users that want to display sold graphics to overlay the featured images

Version 1.2.0
- Added the option to display "Non-sticky" posts into the widget as requested by a user (even though it seemed a bit ironic)

Version 1.1.0
- Compatable with ClassiPress version 3.0.5.X and 3.1
- Fixed homepage number of displayed ads bug

Version 1.0.2
- Adding the ability to "include" only certain categories OR exclude only certain categories.
- Added the ability to turn on or off thumbnails for the 
- Added the option to set the number of posts listed

Version 1.0.1
Rewrote all the query code to properly display only featured sticky posts and also allows the "ClassiPress" theme properly exclude all blog categories.

Version 1.0.0
Created the option to set the title when shown on the homepage (subpages show category names which will be optional in the future)
Registered the Widget (recurring so you can use it multiple times)

== Frequently Asked Questions ==

Q: How do I make a sticky post?
A: When you publish a post if you look under the "visibility" options you will see an option to make your post sticky.

Q: What is a sticky post?
A: Typically they are posts that you want to "always show on top" of your blog posts regardless of the post date.
   In some special cercumstances like ClassiPress, sticky is used to create a "featured" section of the website.

Q: How do I use the plugin?
A: Simple, just install it from your WP-Admin->Plugins page, then you will find a "widget" from the appearance section that you can drag
   into any of your sidebar areas. The exact placement will greatly change depending on your selected theme.

== Screenshots ==

1. A sample of the widget when used on premium theme like ClassiPress.
2. A sample of the widget on the default Wordpress theme, TwentyTen.