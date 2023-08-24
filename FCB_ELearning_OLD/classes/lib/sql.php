<?php
namespace classes\lib;
use PDO;
class sql {
  private static $_obj = null;
  public static function getInstance($con = 'feed') {
    if (!isset(self::$_obj[$con]))
      self::$_obj[$con] = new sql($con);
    return self::$_obj[$con];
  } 
  private $config = [];
  /* last id  */
  private $lid;
  private $flagopen = false;
  public function __construct($con = 'feed') {
    if ($con == 'feed')
      $this->config = FEED17;
    else if ($con == 'cat')
      $this->config = CAT;
    else if ($con == 'net')
      $this->config = NETLINKZ;
    else if ($con == "cif")
      $this->config = CIF;
    else {
      print $con.' connection is not available!';
      exit;
    }
  }
  public function open() {
    if (!$this->flagopen) {
      $this->con = new PDO($this->config['constring'], $this->config['uid'], $this->config['pass']);
      $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->flagopen = true;
    }
  }
  public function exec($query, $data = []): array {
    $this->open();
    $this->lid = "";
    $query = trim($query);
    /*
      $data = exec("select * from testdb where id = :id", [id])
    */
    /* Gell matches  */
    preg_match_all("/:[a-zA-Z_]+/i", $query, $m);
    $m = isset($m[0]) ? $m[0] : [];
    $ml = count($m);
    $dl = count($data);
    if($dl != $ml) return ["eror" => "array list not matches"];
    
    $st = $this->con->prepare($query);
    if ($ml > 0)
      for ($i = 0; $i < $ml; $i++) {
        $st->bindParam($m[$i], $data[$i]);
      }
    $st->execute();
    /*   */
    if ($query[0] == 's' || $query[0] == 'S') {
      $st->setFetchMode(self::mode());
      $data = $st->fetchAll();
      $st = null;
      return $data;
    } else if ($query[0] == 'i' || $query[0] == 'I') {
      // logic to get the last inserted id
      return [];
    }
    return [];
  }
  public function mode() {
    return PDO::FETCH_ASSOC;
  }
  public function close() {
    $this->con = null;
  }
  public function con() {
    return $this->con;
  }
}

