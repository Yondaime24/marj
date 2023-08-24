<?php 
namespace classes;
use sql;
use classes\topiccategories as Category;
use classes\topics as Topics;
use classes\subtopics as SubTopics;
use classes\pptx as Powerpoint;
class Q {
  public $type = '';
  public $objId = '';
  public $group_id = '';
  public $title = [
    'cat' => '',
    'main' => '',
    'sub' => '',
    'pptx' => ''
  ];
  public $titles = [];
  public function getCategory() {
    $category = new Category();
    $category->status  = '1';
    $category->id = $this->objId;
    return $category->get();
  }
  public function getMainTopic() {
    $topics = new Topics();
    $topics->status = '1';
    $topics->id = $this->objId;
    return $topics->getInfo();
  }
  public function getSubTopic() {
    $subTopics = new SubTopics();
    $subTopics->id = $this->objId;
    $subTopics->status = '1';
    return $subTopics->getInfo();
  }
  public function getPowerpoint() {
  }
  public function get() {
   switch ($this->type) {
      case 'CAT':
        // retrive the category information
        // for the cateogry table
        $cat = $this->getCategory();
        if (count($cat)) {
          $this->titles[] = $cat[0]['title'];
        }
        return $this->titles;
      break;
      case 'MAINTOPIC':
        // for main topic must retrive the category also since the category is the parent of the main topics
        //retrieve the maintopic
        $main = $this->getMainTopic();
        $this->objId =  count($main) > 0 ? $main[0]['catid'] : '';
        $cat = $this->getCategory();
        if (count($cat)) {
          $this->titles[] = $cat[0]['title'];
        }
        if (count($main)) {
          $this->titles[] = $main[0]['title'];
        }
        return $this->titles;;
      break;
      case 'SUBTOPIC':
        // for the subtopics retrieve the Category and Maintopic since it is the parents of the topics
        $sub = $this->getSubTopic();
        $this->objId = count($sub) ? $sub[0]['topic_id'] : '';
        $main = $this->getMainTopic();
        $this->objId =  count($main) > 0 ? $main[0]['catid'] : '';
        $cat = $this->getCategory();
        if (count($cat)) {
          $this->titles[] = $cat[0]['title'];
        }
        if (count($main)) {
          $this->titles[] = $main[0]['title'];
        }
        if (count($sub)) {
          $this->titles[] = $sub[0]['title'];
        }
        return $this->titles;
      break;
      case 'POWERPOINT':
       // retrievet the question of the powerpoint, then the logic is every slide of the powerpoint can be optionaly create atleaast one question

      break;
      default:
        return [];
      break;
   }
  }
}