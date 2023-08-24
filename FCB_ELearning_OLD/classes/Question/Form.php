<?php 
namespace classes\Question;

class Form {
  public $type = '';
  public function __construct() {

  }
  public function create() {
    switch ($this->type) {
      case 'MUL':
        return 
      break;
      case 'BL':
        return 'True or False';
      default:
        return '';
      break;
    }
  }
}