<?php

namespace jans;

Class MyOrm {
  private $select = [];
  private $from = "";
  private $col;
  private $value;
  private $values = [];
  private $where = [];
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
      $w = "";
      foreach($this->where as $i => $where){
        if($i === 0){
          $w = $w.' '.$where['sql'];
        }else{
          $w = $w.' '.$where['AndOr'].' '.$where['sql'];
        }
      }
      $sql = $sql.' WHERE'.$w;
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
    $this->where[] = ['sql' => "$col = ?", 'AndOr' => 'AND'];
    $this->values[] = $value;
    return $this;
  }

  public function whereIn($col, $values)
  {
    $this->where[] = ['sql' => $col.' IN ('.implode(', ', array_map(function () { return '?'; }, $values)).')', 'AndOr' => 'AND'];
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
    $this->where[] = ['sql' => "$col = ?", 'AndOr' => 'OR'];
    $this->values[] = $value;
    return $this;
  }

  public function whereNull($col)
  {
    $this->where[] = ['sql' => "$col IS NULL", 'AndOr' => 'AND'];
    return $this;
  }
}