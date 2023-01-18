<?php

namespace jans;

Class MyOrm {
  private $select = [];
  private $from = "";
  private $col;
  private $value;
  private $values = [];
  private $where = "";

  public function toSql()
  {
    if($this->select){
      $sql = $this->select;
    }else{
      $sql = 'SELECT 1';
    }
    if($this->from){
      $sql = $sql.' '.$this->from;
    }
    if($this->where){
      $sql = $sql.' '.$this->where;
    }
    return $sql;
  }

  public function select($columns)
  {
    $col = implode(", ", $columns);
    $sql = 'SELECT '.$col.' ';
    $this->select = $sql;
    return $this;
  }

  public function from($table)
  {
    $sql = 'FROM '.$table;
    $this->from = $sql;
    return $this;
  }

  public function where($col, $value)
  {
    if($this->where){
      $sql = $this->where.' AND '.$col.' = '.'?';
      $this->where = $sql;
      $this->values[] = $value;
    }else{
      $sql = 'WHERE '.$col.' = '.'?';
      $this->where = $sql;
      $this->values = [$value];
    }
    return $this;
  }

  public function whereIn($col, $values)
  {
    $sql = 'WHERE '.$col.' IN '.implode(', ', array_map(function () { return '?'; }, $values));
  }

  public function getBindings()
  {
    if($this->where){
      return $this->values;
    }
  }
}