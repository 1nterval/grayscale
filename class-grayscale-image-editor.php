<?php

require_once(ABSPATH . 'wp-includes/class-wp-image-editor.php');
require_once(ABSPATH . 'wp-includes/class-wp-image-editor-gd.php');

class Grayscale_Image_Editor extends WP_Image_Editor_GD {
    public function make_grayscale(){
        // create a copy
        $dest = $this->image;
        unset($this->image);
        $this->load();
        if ( is_resource( $dest ) ) {
            // Apply grayscale filter
            imagecopymergegray($dest, $this->image, 0, 0, 0, 0, $this->size['width'], $this->size['height'], 0);
            imagegammacorrect($dest, 1.0, apply_filters('grayscale_gamma_correction', 0.7));
            imagedestroy( $this->image );
            $this->image = $dest;
            return true;
        }
    }
}
