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

      $orm3 = new MyOrm;
      $this->assertSame('SELECT * FROM users', $orm3->from('users')->toSql());
    }

    public function testWhere()
    {
      $orm = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE last_name = ? AND first_name = ?', 
                        $orm->from('users')->where('last_name', 'tanaka')->where('first_name', 'tarou')->toSql());
      $this->assertSame(['tanaka', 'tarou'], $orm->getBindings());
    }

    public function testWhereIn()
    {
      $orm = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE id IN (?, ?, ?)', $orm->from('users')->whereIn('id', [1, 2, 3])->toSql());
      $this->assertSame([1, 2, 3], $orm->getBindings());

      $orm2 = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE last_name = ? AND id IN (?, ?, ?)', $orm2->from('users')->where('last_name', 'tanaka')->whereIn('id', [1, 2, 3])->toSql());
      $this->assertSame(['tanaka', 1, 2, 3], $orm2->getBindings());

      $orm2 = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE id IN (?, ?, ?) AND last_name = ?', $orm2->from('users')->whereIn('id', [1, 2, 3])->where('last_name', 'tanaka')->toSql());
      $this->assertSame([1, 2, 3, 'tanaka'], $orm2->getBindings());

      $orm2 = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE id IN (?, ?, ?) AND last_name = ? AND first_name = ?', $orm2->from('users')->whereIn('id', [1, 2, 3])->where('last_name', 'tanaka')->where('first_name', 'taro')->toSql());
      $this->assertSame([1, 2, 3, 'tanaka', 'taro'], $orm2->getBindings());

      $orm2 = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE id IN (?, ?, ?) AND last_name = ? AND first_name IN (?, ?, ?)', $orm2->from('users')->whereIn('id', [1, 2, 3])->where('last_name', 'tanaka')->whereIn('first_name', ['taro', 'jiro', 'saburo'])->toSql());
      $this->assertSame([1, 2, 3, 'tanaka', 'taro', 'jiro', 'saburo'], $orm2->getBindings());
    }

    // public function testOrWhere()
    // {
    //   $orm = new MyOrm;
    //   $this->assertSame('SELECT * FROM users WHERE last_name = ? OR first_name = ?', $orm->from('users')->where('last_name', 'tanaka')->orWhere('first_name', 'tarou')->toSql());
    // }

    public function testWhereNull()
    {
      $orm = new MyOrm;
      $this->assertSame('SELECT * FROM users WHERE last_name IS NULL', $orm->from('users')->whereNull('last_name')->toSql());
    }
}