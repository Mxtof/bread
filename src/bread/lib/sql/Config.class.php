<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

/**
 * Represents messenger objects containing database settings.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource Config.class.php
 */
final class Config {

    /**
     * The name of the underlying database.
     *
     * @var string
     */
    private $schema;
    /**
     * The name of the database's user.
     *
     * @var string
     */
    private $username;
    /**
     * The password to the database.
     *
     * @var string
     */
    private $password;
    /**
     * The database's host.
     *
     * @var string
     */
    private $host;
    /**
     * The name of the database driver.
     *
     * @var string
     */
    private $driver;
    /**
     * The charset in use for character encoding.
     *
     * @var string
     */
    private $charset;

    /**
     * Initializes this messenger with the given credentials.
     *
     * @param string $schema    The name of the underlying database.
     * @param string $username  The name of the database's user.
     * @param string $password  The password to the database.
     * @param string $host      The database's host (optional).
     * @param string $driver    The name of the database driver (optional).
     * @param string $charset   The charset in use for character encoding (optional).
     */
    public function __construct(
        string $schema,
        string $username,
        string $password,
        string $host    = Database::DEFAULT_HOST,
        string $driver  = Database::DEFAULT_DRIVER,
        string $charset = Database::DEFAULT_CHARSET
    ) {
        $this->schema   = $schema;
        $this->username = $username;
        $this->password = $password;
        $this->host     = $host;
        $this->driver   = $driver;
        $this->charset  = $charset;
    }

    /**
     * Opens a new connection to the underlying database.
     *
     * @param array $options
     * A key=>value array of driver-specific connection options.
     * (optional)
     * @return \PDO
     * A fresh PDO instance, built upon this object's credentials.
     * @throws \PDOException
     * If such connection could not be opened.
     */
    public function openConnection(?array $options = null) : \PDO {
        return new \PDO(
            $this->getDSN(),
            $this->username,
            $this->password,
            $options
        );
    }

    /**
     * Computes and returns the Data Source Name.
     *
     * @return string
     */
    public function getDSN() : string {
        return "{$this->driver}:"
             . "host={$this->host};"
             . "dbname={$this->schema};"
             . "charset={$this->charset}";
    }

    /**
     * Provides an informative string describing this object.
     *
     * @return string
     */
    public function __toString() : string {
        return $this->getDSN();
    }

    /**
     * Provides a view of this objet's state.
     *
     * @return array
     */
    public function __debugInfo() {
        return array(
            // 'username'  => $this->username,
            // 'password'  => $this->password,
            'schema'    => $this->schema,
            'host'      => $this->host,
            'driver'    => $this->driver,
            'charset'   => $this->charset
        );
    }
}
?>