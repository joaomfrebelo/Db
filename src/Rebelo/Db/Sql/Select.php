<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 JoÃ£o M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\Db\Sql;

/**
 * Description of Select
 *
 * @author João Rebelo
 */
class Select
    extends \Zend\Db\Sql\Select
{

    /**
     *
     * @var \Rebelo\Db\Adapter\Adapter
     */
    protected $adapter;

    /**
     *
     * @var \Zend\Db\Sql\Sql;
     */
    public $sql;

    /**
     * Constructor
     *
     * @param  null|string|array|TableIdentifier $table
     */
    public function __construct($table = null)
    {
        parent::__construct($table);
    }

    /**
     *
     * Set the adapter
     *
     * @param \Rebelo\Db\Adapter\Adapter $adapter
     * @return void
     */
    public function setAdapter(\Rebelo\Db\Adapter\Adapter $adapter): void
    {
        $this->adapter = $adapter;
    }

    /**
     *
     * Get the adapter
     *
     * @return \Rebelo\Db\Adapter\Adapter
     */
    public function getAdapter(): \Rebelo\Db\Adapter\Adapter
    {
        return $this->adapter;
    }

    /**
     * Build and get the sql query string
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->sql->buildSqlString($this);
    }

    /**
     * Get all fetch query result rows as array
     * [index=>[column=>value]]
     *
     * @return array
     */
    public function fetch(): array
    {
        $results = $this->adapter->query($this->getQueryString(),
                                         \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        return $results->toArray();
    }

    /**
     * Get the first fetch query result row as array
     * [column=>value]
     *
     * @return array
     */
    public function fetchRow(): array
    {
        $result = $this->fetch();
        if (count($result) === 0 || array_key_exists(0, $result) === false)
        {
            throw new EmptyResultException("Row not found");
        }
        $row = $result[0];
        if (!is_array($row) || count($row) === 0)
        {
            throw new EmptyResultException("Empty row");
        }
        return $row;
    }

    /**
     * Get the value fo the first column of the first row
     *
     * @return Mixed
     */
    public function fetchOne()
    {
        $result = \array_values($this->fetchRow());
        return $result[0];
    }

    /**
     * This method Inicitalizes the Zend\Db\Sql\Sql and Zend\Db\Sql\Select objects,
     * return a instance of Select object in order to build the query and fetch
     * the results
     *
     * @param  \Rebelo\Db\Adapter\Adapter $adapter
     * @param  null|string|array|TableIdentifier $table
     * @return \Rebelo\Db\Sql\Select
     */
    public static function factory(\Rebelo\Db\Adapter\Adapter $adapter,
                                   $table = null): \Rebelo\Db\Sql\Select
    {
        $select      = new \Rebelo\Db\Sql\Select($table);
        $select->setAdapter($adapter);
        $select->sql = new \Zend\Db\Sql\Sql($select->getAdapter());
        return $select;
    }

}
