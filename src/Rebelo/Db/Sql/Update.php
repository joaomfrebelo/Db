<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\Db\Sql;

/**
 * Description of Select
 *
 * @author João Rebelo
 */
class Update
    extends \Zend\Db\Sql\Update
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
     * Excecute the update query
     *
     * @return array
     */
    public function execute(): void
    {
        $this->adapter->query($this->getQueryString(),
                              \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        return;
    }

    /**
     * This method Inicitalizes the Zend\Db\Sql\Sql and Zend\Db\Sql\Update objects,
     * return a instance of Select object in order to build the query and fetch
     * the results
     *
     * @param  \Rebelo\Db\Adapter\Adapter $adapter
     * @param  null|string|array|TableIdentifier $table
     * @return \Rebelo\Db\Sql\Update
     */
    public static function factory(\Rebelo\Db\Adapter\Adapter $adapter,
                                   $table = null): \Rebelo\Db\Sql\Update
    {
        $update      = new \Rebelo\Db\Sql\Update($table);
        $update->setAdapter($adapter);
        $update->sql = new \Zend\Db\Sql\Sql($update->getAdapter());
        return $update;
    }

}
