<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 JoÃ£o M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\Test\Db\Adapter;

/**
 *
 * Test of \Rebelo\Db
 *
 * This test are made with the sample database Sakila
 * whre you can download from:
 * https://dev.mysql.com/doc/index-other.html
 *
 * @author João Rebelo
 */
class AdapterTest
    extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * Drver adapter config
     *
     * @var array
     */
    static $config;

    public function setUp()
    {
        parent::setUp();

        static::$config = [
            'driver'   => 'Pdo_Mysql',
            'username' => 'root',
            'password' => 'password',
            'charset'  => 'utf8',
            'hostname' => 'localhost',
            'database' => 'sakila'];
    }

    /**
     * Test the creation of a \Rebelo\Db\Adapter\Adapter instance
     */
    public function testAdapter()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $this->assertInstanceOf(\Rebelo\Db\Adapter\Adapter::class, $adapter);
    }

    /**
     * @depends testAdapter
     */
    public function testFactory()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $select  = \Rebelo\Db\Sql\Select::factory($adapter);
        $this->assertInstanceOf(\Rebelo\Db\Sql\Select::class, $select);
    }

    /**
     * @depends testFactory
     */
    public function testFetch()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $result  = $adapter->select()->from("actor")->fetch();
        $this->assertEquals(200, count($result));
        for ($index = 0; $index < count($result); $index++)
        {
            $this->assertEquals($index + 1, $result[$index]["actor_id"]);
        }
        $this->assertEquals("PENELOPE", $result[0]["first_name"]);
    }

    /**
     * @depends testFetch
     */
    public function testFetchRow()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $result  = $adapter->select("actor")
            ->limit(5)->offset(1)
            ->fetchRow();
        $this->assertEquals(2, $result["actor_id"]);
        $this->assertEquals("NICK", $result["first_name"]);
        $this->assertEquals("WAHLBERG", $result["last_name"]);
    }

    /**
     * @depends testFetchRow
     */
    public function testFetchOne()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $result  = $adapter->select("actor")
            ->columns([
                new \Zend\Db\Sql\Expression("COUNT(*)")])
            ->fetchOne();
        $this->assertEquals(200, $result);
    }

    /**
     * @depends testFactory
     */
    public function testMultiQuery()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $adapter->beginTransaction();

        $result = $adapter->select("film")
            ->columns([
                new \Zend\Db\Sql\Expression("COUNT(*)")])
            ->fetchOne();
        $this->assertEquals(1000, $result);

        $result2 = $adapter->select()->from("city")
            ->where([
                "city_id" => 288])
            ->fetchRow();
        $this->assertEquals("La Paz", $result2["city"]);
        $adapter->commit();
    }

    /**
     *
     */
    public function testInsert()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);

        $adapter->insert("language")->values([
            "language_id" => "9",
            "name"        => "PT"
        ])->execute();

        $row = $adapter->select()
            ->from("language")
            ->where([
                "language_id" => 9])
            ->fetchRow();
        $this->assertEquals("9", $row["language_id"]);
        $this->assertEquals("PT", $row["name"]);
    }

    /**
     * @depends testInsert
     */
    public function testUpdate()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $adapter->update("language")->set([
                "name" => "Portuguese"])
            ->where([
                "language_id" => 9])
            ->execute();

        $row = $adapter->select("language")
            ->where([
                "language_id" => 9])
            ->fetchRow();
        $this->assertEquals("9", $row["language_id"]);
        $this->assertEquals("Portuguese", $row["name"]);
    }

    /**
     * @depends testInsert
     */
    public function testDelete()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $adapter->delete("language")->where([
                "language_id" => 9])
            ->execute();

        $row = $adapter->select("language")
            ->where([
                "language_id" => 9])
            ->fetch();

        $this->assertEquals(0, count($row));
    }

    /**
     *
     */
    public function testTransactionRollBack()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $adapter->beginTransaction();

        $adapter->insert("language")->values([
            "language_id" => "99",
            "name"        => "Guiné"
        ])->execute();

        $row = $adapter->select("language")
            ->where([
                "language_id" => 99])
            ->fetchRow();
        $this->assertEquals("99", $row["language_id"]);
        $this->assertEquals("Guiné", $row["name"]);

        $adapter->rollBack();

        $rowRb = $adapter->select("language")
            ->where([
                "language_id" => 99])
            ->fetch();

        $this->assertEquals(0, count($rowRb));
    }

    /**
     *
     */
    public function testTransactionCommit()
    {
        $adapter = new \Rebelo\Db\Adapter\Adapter(static::$config);
        $adapter->beginTransaction();

        $adapter->insert("language")->values([
            "language_id" => "90",
            "name"        => "Iceland"
        ])->execute();

        $adapter->commit();

        $row = $adapter->select("language")
            ->where([
                "language_id" => 90])
            ->fetchRow();
        $this->assertEquals(90, $row["language_id"]);
        $this->assertEquals("Iceland", $row["name"]);

        $adapter->delete("language")->where([
            "language_id" => 90])->execute();

        $rowCom = $adapter->select("language")
            ->where([
                "language_id" => 90])
            ->fetch();

        $this->assertEquals(0, count($rowCom));
    }

}
