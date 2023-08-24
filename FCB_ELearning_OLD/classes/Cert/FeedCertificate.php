<?php 
namespace classes\Cert;
use classes\Cert\signatories;
use classes\user;
use classes\pdf\FPDF;
use classes\number;
use classes\lib\sql;

class FeedCertificate {
  private static $key = [
    "[:ordinal_day]" => "Current Ordinal Day (Example 1st, 2nd, 3rd...)",
    "[:month]" => "Current Name of the moth (Example Jan, Feb, etc...)",
    "[:year]" => "current Full year (Example 2022)",
    "[:day]" => "Current day"
  ];
  public static function getKey() {
    return self::$key;
  }
  public function getContent() {
    $data = $this->feed->exec("select cert_id, cert_content from cert where cert_id = '1'");
    return $data;
  }
  public function __construct($emp_id = '') {
    
    self::$key["[:ordinal_day]"] = number::ordinal(date("d"));
    self::$key["[:month]"] = date("F");
    self::$key["[:year]"] = date("Y");
    self::$key["[:day]"] = date("d");


    $this->user = new user();
    $this->feed = sql::getInstance();

    $this->pdf = new FPDF("P", "mm", "cert");
    // adding a fonts
    $this->pdf->AddFont("CASTELAR", "B", "CASTELAR.php");
    $this->pdf->AddFont("BRUSHSCI", "B", "BRUSHSCI.php");
    $this->pdf->AddFont("CALISTI", "I", "CALISTI.php");
    $this->pdf->AddFont("Candarab", "B", "Candarab.php");
    $this->pdf->AddFont("trebucbd", "B", "trebucbd.php");
    $this->pdf->AddFont("Calibri", "", "calibri.php");
    // end font
    $this->emp_id = $emp_id;
    $this->json = [
      'com_title' => 'FCB E-LEARNING for EXCELLENCE and DEVELOPMENT',
      'title' => 'Title',
      'user' => [
        "title" => "",
        "name" => "Name"
      ],
      'left' => [
        'name' => '',
        'position' => ''
      ],
      'right' => [
        'name' => '',
        'position' => ''
      ],
      'content' => "Classroom Phase held on May 3-June 1, 2022
  at FCB Training Center, 4th Floor, FCB Tagbilaran Branch, CPG Avenue, Tagbilaran City, Bohol
  Given this [:ordinal_day] of [:month], [:year]  in Tagbilaran City"
    ];
    $this->edit = false;
  }
  public function setTitle($title) {
     $this->pdf->setTitle($title);
    return $this;
  }
  public function setEdit($is_edit) {
    $this->edit = $is_edit;
    return $this;
  }
  public function init() {
    $sig = new signatories();
    $sig->no = 1;
    $sig->get();

    $this->json['left']['name'] = $sig->getName();
    $this->json['left']['position'] = $sig->getPosition();

    $sig->no = 2;
    $sig->get();
    $this->json['right']['name'] = $sig->getName();
    $this->json['right']['position'] = $sig->getPosition();
    if (!$this->edit) {
      $this->json['title'] = 'MANAGEMENT TRAINING & DEVELOPMENT PROGRAM (MTDP) Batch XVIII';
      if (empty($emp_id)) {
        // load the user id
        // firstname
        if (!empty($this->user->fname)) {
          $this->user->fname = strtolower($this->user->fname);
          $this->user->fname = explode(' ', $this->user->fname);
          $len = count($this->user->fname);
          for ($i = 0; $i < $len; $i++) {
            if (!empty($this->user->fname[$i])) {
              $this->user->fname[$i][0] = strtoupper($this->user->fname[$i][0]);
            }
          }
          $this->user->fname = implode(' ', $this->user->fname);
        }
        // lastname
        if (!empty($this->user->lname)) {
          $this->user->lname = strtolower($this->user->lname);
          $this->user->lname = explode(' ', $this->user->lname);
          $len = count($this->user->lname);
          for ($i = 0; $i < $len; $i++) {
            if (!empty($this->user->lname[$i])) {
              $this->user->lname[$i][0] = strtoupper($this->user->lname[$i][0]);
            }
          }
          $this->user->lname = implode(' ', $this->user->lname);
        }

        $this->json['user']['title'] = '';
        $this->json['user']['name'] = $this->user->fname.' '.$this->user->lname;    
      }
    } // edit
    return $this;
  }
  public function load($json) {
    // reload custom file 
    $this->json = $json;
  }
  public function getJson() {
    return json_encode($this->json);
  }
  public function draw() {
    $data = $this->getContent();
    $this->json["content"] = isset($data[0]["cert_content"]) ? $data[0]["cert_content"] : "";
    $this->json["content"] = str_replace("[:ordinal_day]", number::ordinal(date("d")), $this->json["content"]);
    $this->json["content"] = str_replace("[:month]", date("F"), $this->json["content"]);
    $this->json["content"] = str_replace("[:year]", date("Y"), $this->json["content"]);
    $this->json["content"] = str_replace("[:day]", date("d"), $this->json["content"]);

    $image1 = "cert_bg.png";
    $border = 0; // debugger
    $this->pdf->AddPage();
    $this->pdf->SetFont("Arial","",18);
    $this->pdf->Image('../assets/images/'.$image1, 0, 0, 216, 160);
    $this->pdf->SetXY(0,0);
    $this->pdf->Image("../assets/images/fcblogo.png", 45, 7, 22, 20);
    $this->pdf->SetTextcolor(9, 118, 0);
    $this->pdf->setXY(73, 9);
    $this->pdf->cell(100, 10, "FIRST CONSOLIDATED BANK", $border, 0, 1, '');
    $this->pdf->setXY(73, 15);
    $this->pdf->cell(100, 10, "( A Private Development Bank )", $border, 0, 1, '');

    $this->pdf->SetTextcolor(0, 0, 0);

    $this->pdf->SetFont("CALISTI","I", 11);
    $this->pdf->setXY(98, 27);
    $this->pdf->cell(100, 10, "Through its", $border, 0, 1, '');
    $this->pdf->SetFont("Candarab","B", 14);
    $this->pdf->setXY(30, 35);
    $this->pdf->cell(170, 10, $this->json['com_title'], $border, 0, 'C', '');
    
    $this->pdf->SetFont("CALISTI","I", 11);
    $this->pdf->setXY(97, 43);
    $this->pdf->cell(100, 10, "Presents this", $border, 0, 1, '');

    $this->pdf->SetFont("CASTELAR", "B", 24);
    $this->pdf->setXY(38, 53);
    $this->pdf->cell(100, 10, "CERTIFICATE OF COMPLETION", $border, 0, 1, '');
    
    $this->pdf->SetFont("CALISTI", "I", 11);
    $this->pdf->setXY(104, 60);
    $this->pdf->cell(100, 10, "to", $border, 0, 1, '');

    $this->pdf->SetTextcolor(0, 116, 0);
    $this->pdf->SetFont("BRUSHSCI", "BU", 28);
    $this->pdf->setXY(25, 70);
    $this->pdf->cell(165, 10, $this->json['user']['title'].' '.$this->json['user']['name'], $border, 0, 'C');

    $this->pdf->SetTextcolor(0, 0, 0);
    $this->pdf->SetFont("CALISTI","I", 11);
    $this->pdf->setXY(86, 81);
    $this->pdf->cell(100, 10, "for successfully completed the", $border, 0, 1, '');

    $this->pdf->SetTextcolor(0, 0, 0);
    $this->pdf->SetFont("CALISTI","I", 11);
    $this->pdf->setXY(28, 96);
    $this->pdf->MultiCell(160, 10, $this->json["content"], $border, 'C');

    $this->pdf->SetTextcolor(0, 116, 0);
    $this->pdf->SetFont("trebucbd", "B", 11);
    $this->pdf->setXY(28, 88);
    $this->pdf->cell(160, 10, $this->json['title'], $border, 0, 'C');

    $this->pdf->SetTextcolor(0, 0, 0);
    $this->pdf->SetFont("trebucbd", "B", 11);
    $this->pdf->setXY(37, 136);
    $this->pdf->cell(70, 10, $this->json['left']['name'], $border, 0, 'C', false, '', false);
    $this->pdf->cell(70, 10, $this->json['right']['name'], $border, 0, 'C', false, '', false);
    
    $this->pdf->SetFont("Calibri", "", 11);
    $this->pdf->setXY(37, 143);
    $this->pdf->cell(70, 10, $this->json['left']['position'], $border, 0, 'C', false, '', false);
    $this->pdf->Cell(70, 10, $this->json['right']['position'], $border, 0, 'C', false, '', false);
    return $this;
  }
  public function display() {
    $this->pdf->output('I');
    return $this;
  }
  public function save() {
    $this->pdf->output('D');
  }
  public function text() {
    return $this->pdf->output("S");
  }
}