<?php

namespace jans;

Class MyOrm {
  private $select = [];

  public function toSql()
  {
    if($this->select){
      if($this->from){
        $sql = $this->select.$this->from;
      }else{
        $sql = $this->select;
      }
    }else{
      if($this->from){
        $sql = 'SELECT 1 '.$this->from;
      }else{
        $sql = 'SELECT 1';
      }
    }
    Return $sql;
  }

  public function select($columns)
  {
    $col = is_array($columns) ? implode(", ", $columns) : $columns[0];
    $sql = 'SELECT '.$col.' ';
    $this->select = $sql;
  }

  public function from($table)
  {
    $sql = 'From '.$table;
    $this->from = $sql;
  }
}