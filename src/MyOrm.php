<?php

namespace jans;

Class MyOrm {
  private $select = [];
  private $from = "";
  private $col;
  private $value;
  private $values = [];
  private $where = "";
  private $whereNull = "";

  public function toSql()
  {
    if($this->select){
      $sql = $this->select;
    }elseif($this->from){
      $sql = 'SELECT *';
    }else{
      $sql = 'SELECT 1';
    }
    if($this->from){
      $sql = $sql.' '.$this->from;
    }
    if($this->where){
      $sql = $sql.' '.$this->where;
    }
    if($this->whereNull){
      $sql = $sql.' '.$this->whereNull;
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
    }else{
      $sql = 'WHERE '.$col.' = '.'?';
    }
    $this->where = $sql;
    $this->values[] = $value;
    return $this;
  }

  public function whereIn($col, $values)
  {
    if($this->where){
      $sql = $this->where.' AND '.$col.' IN ('.implode(', ', array_map(function () { return '?'; }, $values)).')';
    }else{
      $sql = 'WHERE '.$col.' IN ('.implode(', ', array_map(function () { return '?'; }, $values)).')';
    }
    $this->where = $sql;
    $this->values = array_merge($this->values, $values);
    return $this;
  }

  public function getBindings()
  {
    if($this->where){
      return $this->values;
    }
  }

  public function orWhere($col, $value)
  {
    if($this->where){
      $sql = $this->where.' OR '.$col.' = '.'?';
    }else{
      $sql = 'WHERE '.$col.' = '.'?';
    }
    $this->where = $sql;
    $this->values[] = $value;
    return $this;
  }

  public function whereNull($col)
  {
    if($this->where){
      $sql = $this->where.' AND '.$col.' IS NULL';
    }else{
      $sql = 'WHERE '.$col.' IS NULL';
    }
    $this->whereNull = $sql;
    return $this;
  }
}