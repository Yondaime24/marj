<?php
use classes\url;
use classes\Question\Form;
require_once '../___autoload.php';
class App {
  private $route = null;
  private $form = null;
  public function __construct() {
    $this->route = isset($_GET['q']) ? $_GET['q'] : '';
    $this->form = new Form();
  }
  public function Run() {
    if ($this->route == 'CreateQuestion') {
      $this->form->type = url::post('type');
      $url['form'] = $this->form->create();
    }
    print base64_encode(json_encode($url));
  }
}
$web = new App();
$web->Run();
?>