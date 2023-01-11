<?php

namespace jans;

Class MyOrm {
  private $select = [];
  private $from = "";

  public function toSql()
  {
    if($this->select){
      $sql = $this->select;
    }else{
      $sql = 'SELECT 1';
    }
    if($this->from){
      $sql = $sql.$this->from;
    }
    Return $sql;
  }

  public function select($columns)
  {
    $col = implode(", ", $columns);
    $sql = 'SELECT '.$col.' ';
    $this->select = $sql;
  }

  public function from($table)
  {
    $sql = 'From '.$table;
    $this->from = $sql;
  }
}