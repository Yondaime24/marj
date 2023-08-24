<?php
  use classes\lib\sql;
  use classes\carousel_display;
  require "../___autoload.php";

  $image_id = isset($_GET["image_id"]) ? $_GET["image_id"] : "";

  $objCarousel = new Carousel_display();
  $image = $objCarousel->getSingleImage($image_id);

  if(count($image) > 0){
    print(base64_decode($image[0]["image"]));
  }