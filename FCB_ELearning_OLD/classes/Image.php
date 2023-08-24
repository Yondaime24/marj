<?php 
namespace classes;

class Image {
  public function __construct() {

  }
  public function Scale($img_string, $width = 50, $path = "") {
    $img = imagecreatefromstring($img_string); 
    $w = imagesx($img);
    $h = imagesy($img);
    $new_w = $width;
    $new_h = ($new_w * $h) / $w;
    $new_img = imagecreatetruecolor($new_w, $new_h);

    imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
    if (empty($path))
      imagepng($new_img);
    else
      imagepng($new_img, $path);
  }
}