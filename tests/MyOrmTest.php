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
      $this->assertSame('SELECT col1, col2 ', $orm->toSql());
    }

    public function testFrom()
    {
      $orm = new MyOrm;
      $orm->select(['col1', 'col2']);
      $orm->from('users');
      $this->assertSame('SELECT col1, col2  FROM users', $orm->toSql());
    }

    public function testChain()
    {
      $orm = new MyOrm;
      $orm->select(['col1', 'col2'])->from('users');
      $this->assertSame('SELECT col1, col2  FROM users', $orm->toSql());

      $orm2 = new MyOrm;
      $this->assertSame('SELECT name, email  FROM users', $orm2->select(['name', 'email'])->from('users')->toSql());
    }

    public function testWhere()
    {
      $orm = new MyOrm;
      $this->assertSame('SELECT 1 FROM users WHERE last_name = ? AND first_name = ?', 
                        $orm->from('users')->where('last_name', 'tanaka')->where('first_name', 'tarou')->toSql());
      $this->assertSame(['tanaka', 'tarou'], $orm->getBindings());
    }

    public function testWhereIn()
    {
      $orm = new MyOrm;
      $this->assertSame('SELECT 1 FROM users WHERE id IN (?, ?, ?)', $orm->from('users')->whereIn('id', [1, 2, 3])->toSql());
      $this->assertSame([1, 2, 3], $orm->getBindings());
    }
}