=== Grayscale ===
Contributors: fab1en
Tags: image, images, thumbnail, grayscale
Requires at least: 3.0.1
Tested up to: 3.7
Stable tag: 1.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Automatically create grayscale thumbnails for a given image size.

== Description ==

Sometimes, your website design requires to turn pictures in black & white : for example in a gallery display, you can have all thumbnails in grayscale and bring up colors when the mouse is hovering it.
This plugin will make it possible without the need to manualy create the grayscale variant of each picture. 

In your theme's `functions.php`, instead of using WP [add_image_size](http://codex.wordpress.org/Function_Reference/add_image_size) function, use `grayscale_add_image_size('custom_size', xxx, yyy, $crop, $grayscale)` (with `$crop` set to `true` if you want to crop the image, and `$grayscale` set to `true` if you want a grayscale version of the image). Then, you can use [the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) or any thumbnail function to get your grayscale image : 

`the_post_thumbnail('custom_size-gray')`

A gamma correction is applied automatically to make the black & white pictures look good. From version 1.2, You can tune this gamma correction with the `grayscale_gamma_correction` filter. Your filter would have to return a float number that will be use as the `outputgamma` parameter of the [imagegammacorrect](http://php.net/manual/fr/function.imagegammacorrect.php) function.

= Known bugs =
* Grayscale images are not generated when the original image size is smaller than the specified thumbnail size.

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

= 1.1 =
* Minor change in documentation

= 1.2 =
* Remove wp_load_image use (deprecated)
* Use a class that extends WP_Image_Editor_GD
* Add grayscale_gamma_correction filter to tune gamma correction
