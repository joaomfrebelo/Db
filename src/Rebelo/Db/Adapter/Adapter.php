<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\Db\Adapter;

use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Adapter\Profiler\ProfilerInterface;

/**
 * Description of Adapter
 *
 * @author João Rebelo
 */
class Adapter
    extends \Zend\Db\Adapter\Adapter
{

    /**
     *
     * @var \Zend\Db\Sql\Sql
     */
    public $sql;

    /**
     *
     * @var \Rebelo\Db\Sql\Select
     */
    public $select;

    /**
     * @param \Zend\Db\Adapter\Driver\DriverInterface|array $driver
     * @param PlatformInterface $platform
     * @param ResultSetInterface $queryResultPrototype
     * @param ProfilerInterface $profiler
     * @throws \Zend\Db\Exception\InvalidArgumentException
     */
    public function __construct($driver, PlatformInterface $platform = null,
                                ResultSetInterface $queryResultPrototype = null,
                                ProfilerInterface $profiler = null
    )
    {
        parent::__construct($driver, $platform, $queryResultPrototype, $profiler);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        $this->getDriver()->getConnection()->beginTransaction();
    }

    /**
     * commit transaction
     */
    public function commit()
    {
        $this->getDriver()->getConnection()->commit();
    }

    /**
     * ´Rollback transaction
     */
    public function rollBack()
    {
        $this->getDriver()->getConnection()->rollback();
    }

    /**
     *
     * Get the select object to build and execute the select query
     *
     * @param  null|string|array|TableIdentifier $table
     * @return \Rebelo\Db\Sql\Select
     */
    public function select($table = null): \Rebelo\Db\Sql\Select
    {
        return \Rebelo\Db\Sql\Select::factory($this, $table);
    }

    /**
     * Get the update object to build and execute the update query
     *
     * @param  null|string|array|TableIdentifier $table
     * @return \Rebelo\Db\Sql\Update
     */
    public function update($table = null): \Rebelo\Db\Sql\Update
    {
        return \Rebelo\Db\Sql\Update::factory($this, $table);
    }

    /**
     *
     * Get the object to build and execute the insert query
     *
     * @param  null|string|array|TableIdentifier $table
     * @return \Rebelo\Db\Sql\Insert
     */
    public function insert($table = null): \Rebelo\Db\Sql\Insert
    {
        return \Rebelo\Db\Sql\Insert::factory($this, $table);
    }

    /**
     *
     * The object to create the and execute delete query
     *
     * @param  null|string|array|TableIdentifier $table
     * @return \Rebelo\Db\Sql\Delete
     */
    public function delete($table = null): \Rebelo\Db\Sql\Delete
    {
        return \Rebelo\Db\Sql\Delete::factory($this, $table);
    }

}
