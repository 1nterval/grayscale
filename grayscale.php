<?php
/*
 * Plugin Name: Grayscale
 * Author URI: http://www.1nterval.com
 * Description: Automatically create grayscale thumbnails for a given image size
 * Author: Fabien Quatravaux
 * Version: 1.0
 
  Copyright © 2012  Fabien Quatravaux  (email : fabien.quatravaux@1nterval.com)
    
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
*/

register_activation_hook(__FILE__, 'grayscale_check'); 
function grayscale_check() {
    if ( !extension_loaded('gd') ) {
        die("La librairie php GD est nécessaire pour ce plugin. Il semble qu'elle ne soit pas installée.");
    }
    if ( !function_exists('imagecopymergegray') ){
        die("La fonction imagecopymergegray n'est pas présente, mettez à jour la librairie php GD");
    }
    if ( !function_exists('imagegammacorrect') ){
        die("La fonction imagegammacorrect n'est pas présente, mettez à jour la librairie php GD");
    }
}

add_action('init', 'grayscale_init');
function grayscale_init() {
    load_plugin_textdomain( 'grayscale', false, basename(dirname(__FILE__)) );
}

// provide a new function to declare grayscaled images
function grayscale_add_image_size( $name, $width = 0, $height = 0, $crop = false, $grayscale = false ) {
	global $_wp_additional_image_sizes;
	$_wp_additional_image_sizes[$name] = array( 'width' => absint( $width ), 'height' => absint( $height ), 'crop' => (bool) $crop, 'grayscale' => (bool) $grayscale );
}

// actualy create the black and white image
function grayscale_make_grayscale_image($resized_file){
    $image = wp_load_image( $resized_file );
    if ( !is_resource( $image ) )
	    return new WP_Error( 'error_loading_image', $image, $resized_file );
	    
    $size = @getimagesize( $resized_file );
    if ( !$size )
	    return new WP_Error('invalid_image', __('Could not read image size'), $resized_file);
    list($orig_w, $orig_h, $orig_type) = $size;
    
     // Apply grayscale filter
    $dest = wp_load_image( $resized_file );
    imagecopymergegray($dest, $image, 0, 0, 0, 0, $orig_w, $orig_h, 0);
    imagegammacorrect($dest, 1.0, 0.7);

    $info = pathinfo($resized_file);
    $dir = $info['dirname'];
    $ext = $info['extension'];
    $name = wp_basename($resized_file, ".$ext");

    $destfilename = "{$dir}/{$name}-gray.{$ext}";

    if ( IMAGETYPE_GIF == $orig_type ) {
	    if ( !imagegif( $dest, $destfilename ) )
		    return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
    } elseif ( IMAGETYPE_PNG == $orig_type ) {
	    if ( !imagepng( $dest, $destfilename ) )
		    return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
    } else {
	    // all other formats are converted to jpg
	    $destfilename = "{$dir}/{$name}-gray.jpg";
	    if ( !imagejpeg( $dest, $destfilename, apply_filters( 'jpeg_quality', 90, 'image_resize' ) ) )
		    return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
    }

    imagedestroy( $image );
    imagedestroy( $dest );

    // Set correct file permissions
    $stat = stat( dirname( $destfilename ));
    $perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
    @ chmod( $destfilename, $perms );

    return wp_basename($destfilename);
}

// hook to call the image generation function if needed
add_filter('wp_generate_attachment_metadata', 'grayscale_check_grayscale_image', 10, 2);
function grayscale_check_grayscale_image($metadata, $attachment_id){
    global $_wp_additional_image_sizes;
    $attachment = get_post( $attachment_id );
    if ( preg_match('!image!', get_post_mime_type( $attachment )) ) {
        foreach($metadata['sizes'] as $size => $size_data){
            if(isset($_wp_additional_image_sizes[$size]['grayscale']) && $_wp_additional_image_sizes[$size]['grayscale']) {
                $file = pathinfo(get_attached_file($attachment_id));
                $metadata['sizes'][$size.'-gray'] = $metadata['sizes'][$size];
                $metadata['sizes'][$size.'-gray']['file'] = _wp_relative_upload_path(grayscale_make_grayscale_image($file['dirname'].'/'.$size_data['file']));
            }
        }
    }
    return $metadata;
}

    
?>
