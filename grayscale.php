<?php
/*
 * Plugin Name: Grayscale
 * Author URI: http://www.1nterval.com
 * Description: Automatically create grayscale thumbnails for a given image size
 * Author: Fabien Quatravaux
 * Version: 1.2
 
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

// hook to call the image generation function if needed
add_filter('wp_generate_attachment_metadata', 'grayscale_check_grayscale_image', 10, 2);
function grayscale_check_grayscale_image($metadata, $attachment_id){
    global $_wp_additional_image_sizes;
    $attachment = get_post( $attachment_id );
    if ( preg_match('!image!', get_post_mime_type( $attachment )) ) {
    
        require_once('class-grayscale-image-editor.php');
        
        foreach($_wp_additional_image_sizes as $size => $size_data){
            if(isset($size_data['grayscale']) && $size_data['grayscale']) {
                if(is_array($metadata['sizes']) && isset($metadata['sizes'][$size])){
                    $file = pathinfo(get_attached_file($attachment_id));
                    $filename = $file['dirname'].'/'.$metadata['sizes'][$size]['file'];
                    $metadata['sizes'][$size.'-gray'] = $metadata['sizes'][$size];
                } else {
                    // this size has no image attached, probably because the original is too small
                    // create the grayscale image from the original file
                    $file = wp_upload_dir();
                    $filename = $file['basedir'].'/'.$metadata['file'];
                    $metadata['sizes'][$size.'-gray'] = array(
                        'width' => $metadata['width'], 
                        'height' => $metadata['height'],
                    );
                }
                
                $image = new Grayscale_Image_Editor($filename);
                $image->load();
                $image->make_grayscale();
                $result = $image->save($image->generate_filename('gray'));
                $metadata['sizes'][$size.'-gray']['file'] = $result['file'];
            }
        }
    }
    return $metadata;
}
    
?>
