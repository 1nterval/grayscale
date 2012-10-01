=== Grayscale ===
Contributors: fab1en
Tags: image, images, thumbnail, grayscale
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Automatically create grayscale thumbnails for a given image size.

== Description ==

Sometimes, your website design requires to turn pictures in black & white : for example in a gallery display, you can have all thumbnails in grayscale and bring up colors when the mouse is hovering it.
This plugin will make it possible without the need to manualy create the grayscale variant of each picture. 

In your theme's `functions.php`, instead of using WP [add_image_size](http://codex.wordpress.org/Function_Reference/add_image_size) function, use `grayscale_add_image_size('custom_size', xxx, yyy, crop, grayscale)`. Then, you can use [the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) or any thumbnail function to get your grayscale image : 

`the_post_thumbnail('custom_size-gray')`

== Installation ==

Upload the plugin files in your wp-content/plugins directory and go to the Plugins menu to activate it. 

This plugin requires the GD php library bundled with PHP since version 4.0.6. You will be notified if your system does not support it, and you will be able to activate the plugin.

== Frequently Asked Questions ==

= Do I still have to use add_image_size if I want a colored version of my images ? =

No, a colored thumbnail at the specified size is already created. You can display it with `the_post_thumbnail('custom_size')` whereas the black and white one will use `the_post_thumbnail('custom_size-gray')`.

== Screenshots ==

There is no user interface.

== Changelog ==

= 1.0 =
* Initial version


