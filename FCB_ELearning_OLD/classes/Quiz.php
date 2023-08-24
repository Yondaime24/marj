<?php 
namespace classes;

use sql;
use classes\Question\Group;
use classes\Question\Items;
use classes\Question\ItemsChoices;
use classes\Date as Date2;

class Quiz {

  public $GroupId = '';
  
  public function __construct() {

  }

  public function Get() {
    $group = new Group();
    $items = new Items();
    $itemsChoices = new ItemsChoices();

    $group->QGId = $this->GroupId;
    $group->Status = '1';
    $groupData = $group->Get();

    $items->QGId = isset($groupData[0]['QGId']) ? $groupData[0]['QGId'] : '';
    $items->Status = '1';
    $itemsData = $items->Get();

    $q = [];
    $len = count($itemsData);

    for ($i = 0; $i < $len; $i++) {
      
      $itemsChoices->QIId = $itemsData[$i]['QIId'];
      $itemsChoices->Status = '1';
      $d1 = $itemsChoices->Get();

      $q[$i] = [
        "Question" => $itemsData[$i],
        "Items" => $d1 
      ];
      //print $itemsData[$i];
    }
    $data = [
      'Group' => [
        'Id' => isset($groupData[0]['QGId']) ? $groupData[0]['QGId'] : '',
        'Code' => isset($groupData[0]['QCCode']) ? $groupData[0]['QCCode'] : '',
        'Title' => (isset($groupData[0]['Title']) ? $groupData[0]['Title'] : ''),
        'Description' => isset($groupData[0]['Description']) ? $groupData[0]['Description']: '',
        'age' => isset($groupData[0]['DateCreated']) ? Date2::ago($groupData[0]['DateCreated']) : '',
        'TimeLimit' => isset($groupData[0]['TimeLimit']) ? $groupData[0]['TimeLimit'] : '',
        'ObjId' => isset($groupData[0]['ObjId']) ? $groupData[0]['ObjId'] : ''
      ],
      //storage for the question Items
      'Data' => $q
    ];

    return $data;
  }

}