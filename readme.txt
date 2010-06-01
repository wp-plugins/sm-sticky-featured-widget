=== SM Sticky Featured Widget ===

Contributors: Sethmatics Inc feat. Seth Carstens
Donate link: http://smwphosting.com/extend
Tags: widgets, sticky, featured, classipress
Requires at least: 2.7
Tested up to: 3.0-RC1
Stable tag: 1.0.2

A tiny but high in demand widget to post sticky or "featured" posts into any widget area. Widget provided by http://smwphosting.com/.

== Description ==

A tiny but high in demand widget to post sticky or "featured" posts into any widget area. We would appriciate any assistance you would like to provide with coding of enhancments to this plugin.
Using Documentation from core trac default widgets as a guideline for development:
http://core.trac.wordpress.org/browser/trunk/wp-includes/default-widgets.php

Features include:

* sidebar widget added to your "Widgets" appearance settings that shows only sticky posts.
* option to set the title when displayed on the home page.
* Now COMPLETELY compatable with the premium "ClassiPress" theme which can be purchased at [ClassiPress](http://wpclassipress.com/ "www.wpclassipress.com")
* demo of activated widget available at http://cpmodlite.smwphosting.com/
* uses the WP_Query to efficiently grab your sticky posts
* makes use of new thumbnails and "featured images" for wordpress 2.9.2 and wordpress 3.0
* option to turn on and off the thumbnails
* option to set the number of posts listed
* option Only show sticky posts of the post category & sub categories?
* detect if no featured ads are in category/subcategories and post any featured posts/pages.
* If no featured ads exist anywhere, display a message indicating no ads have been featured.

Features Coming in the Next Version Updates:

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

== Changelog ==
- Heavily modified the queries and is now 100% ClassiPress compliant.
- Version 1.0.0 is the original released version. No changes logged yet.

== Upgrade Notice ==
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
2. 