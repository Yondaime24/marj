<?php
namespace classes;
use sql;
class memo {
  public function __construct() {
    $this->search = "";
  }
  public function get() {
    $st = sql::cat()->prepare("select * from memo order by m_date desc");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }

  public function getAllMemo() {
    $st = sql::cat()->prepare("SELECT memo.m_id, memo.m_name, memo.m_date, attachment.attach_file FROM memo INNER JOIN attachment ON memo.m_id = attachment.unique_id WHERE attachment.ind = 'M' ORDER BY memo.m_date DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }

  public function search() {
    $search = "%".$this->search."%";
    $st = sql::cat()->prepare("SELECT memo.m_name, memo.m_date, attachment.attach_file FROM memo INNER JOIN attachment ON memo.m_id = attachment.unique_id WHERE attachment.ind = 'M' AND memo.m_name LIKE :m_name OR attachment.attach_file LIKE :attach_file OR memo.m_date LIKE :m_date ORDER BY memo.m_date DESC");
    $st->bindParam(':m_name', $search);
    $st->bindParam(':attach_file', $search);
    $st->bindParam(':m_date', $search);
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $data_len = count($data);
    $st = null;
    return $data;
  }
 

}