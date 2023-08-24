<?php 
namespace classes\Question;
use sql;

class Type {
  public function __construct() {
    $this->type = [
      'MUL' => 'Multiple Choice',
      'ESSAY' => 'Essay',
      'BL' => 'True or False',
      'ENUM' => 'Enumeration'
    ];   
  }
  public function get() {
    
    return $this->type;
  }
}