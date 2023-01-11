<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use jans\MyOrm;


Class MyOrmTest extends TestCase {
    public function testInstance()
    {
      $orm = new MyOrm;
      $this->assertInstanceOf(MyOrm::class, $orm);
    }
    public function test_toSql() 
    {
      $orm = new MyOrm;
      $this->assertSame('SELECT 1', $orm->toSql());
    }
    public function testSelect()
    {
      $orm = new MyOrm;
      $orm->select(['col1', 'col2']);
      $this->assertSame('SELECT col1, col2', $orm->toSql());
    }

    public function testFrom()
    {
      $orm = new MyOrm;
      $orm->select(['col1', 'col2']);
      $orm->from('users');
      $this->assertSame('SELECT col1, col2 From users', $orm->toSql());
    }
}