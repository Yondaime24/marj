<?php  
  use classes\auth;
  use classes\Cert\FeedCertificate;
  use classes\url;
  use classes\topiccategories as category;

  require_once "../___autoload.php";
  $cat = url::get("cat_id");
  if (empty($cat)) exit;
  $category = new category();
  $category->id = $cat;
  $data = $category->get();
  if (count($data) == 0) exit;
  $feed = new FeedCertificate();
  $feed->setTitle('Feed')->setEdit(false)->init();
  $feed->json["title"] = $data[0]["title"];
  $feed->draw()->display();
?>