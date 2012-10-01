Grayscale
=======

Wordpress plugin to automatically create grayscale thumbnails for a given image size.

### Description

Sometimes, your website design requires to turn pictures in black & white : for example in a gallery display, you can have all thumbnails in grayscale and bring up colors when the mouse is hovering it.
This plugin will make it possible without the need to manualy create the grayscale variant of each picture. 

In your theme's `functions.php`, instead of using WP [add_image_size](http://codex.wordpress.org/Function_Reference/add_image_size) function, use `grayscale_add_image_size('custom_size', xxx, yyy, crop, grayscale)`. Then, you can use [the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) or any thumbnail function to get your grayscale image : 

`the_post_thumbnail('custom_size-gray')`

### License

© Copyright 2012 Fabien Quatravaux

Grayscale is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Grayscale is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Grayscale.  If not, see <http://www.gnu.org/licenses/>
