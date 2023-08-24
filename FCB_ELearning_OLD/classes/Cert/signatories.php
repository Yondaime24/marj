<?php 
namespace classes\Cert;
use classes\lib\sql;

class signatories {

  public function __construct() {
    $this->no = 0;
    $this->sig_id = 1;
    $this->name = '';
    $this->pos = '';

    $this->feed = new sql();
    $this->data = [];
  }
  public function get() {
    $this->data = $this->feed->exec('select * from cert_signatories where no = :no and sig_id = :sig', [$this->no, $this->sig_id]);
    return $this->data;
  }
  public function getName() {
    return isset($this->data[0]['display_name']) ? $this->data[0]['display_name'] : '';
  } 
  public function getPosition() {
    return isset($this->data[0]['display_position']) ? $this->data[0]['display_position'] : '';
  }
}