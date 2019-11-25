<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'QueryException.class.php';
require_once 'ConnectionFailureException.class.php';

/**
 * Represents a connection to a database.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource Database.class.php
 */
final class Database {

    /**
     * Specifies the default host.
     */
    public const DEFAULT_HOST    = "localhost";
    /**
     * Specifies the default driver.
     */
    public const DEFAULT_DRIVER  = "mysql";
    /**
     * Specifies the default charset.
     */
    public const DEFAULT_CHARSET = "utf8mb4";

    /**
     * The initial options for the PDO handle.
     */
    private const DRIVER_OPTIONS = [
        \PDO::ATTR_PERSISTENT => false,
        \PDO::ATTR_ERRMODE    => \PDO::ERRMODE_EXCEPTION
    ];

    /**
     * The singleton database instance.
     *
     * @var Database
     */
    private static $instance = null;

    /**
     * Starts, or restarts the database connection upon a given configuration.
     *
     * Previous instance, if any, is replaced by a new instance
     * initialized with the given credentials.
     *
     * @param  Config $config
     * The configuration messenger.
     * @throws ConnectionFailureException
     * If the connection could not be opened.
     * @return void
     */
    public static function start(Config $config) {
        static::$instance = new static($config);
    }

    /**
     * Checks whether an instance has been started.
     *
     * @return boolean  * `true`, if the database has been started;
     *                  * `false` otherwise.
     */
    public static function hasInstance() : bool {
        return (static::$instance !== null);
    }

    /**
     * Provides the database singleton instance.
     *
     * @return Database         The singleton instance.
     * @throws \LogicException  if the database has not been started yet.
     */
    public static function getInstance() : Database {
        if (static::hasInstance()) {
            return static::$instance;
        }
        throw new \LogicException("Database is not started yet.");
    }

    /**
     * The connection / query handle to the database.
     *
     * @var \PDO
     */
    private $handle;

    /**
     * Opens the connection to the database, with the given credentials.
     *
     * @param Config $config
     */
    private function __construct(Config $config) {
        try {
            $this->handle = $config->openConnection(self::DRIVER_OPTIONS);

        } catch (\PDOException $cause) {
            throw new ConnectionFailureException("Database connection failed.", 0, $cause);
        }
    }

    /**
     * Retrieves the connection / query handle to the database.
     *
     * @return  \PDO
     */
    public function getHandle() {
        return $this->handle;
    }

    /**
     * Checks whether a given entry is present in the database.
     *
     * @param string $name      The column name.
     * @param string $value     The value to look for.
     * @param string $table     The name of the table to be searched.
     * @return boolean          * `true`, if such entry is present;
     *                          * `false`, if not found.
     * @throws QueryException   If the search query raised an issue.
     */
    public function contains(
        string $name,
        string $value,
        string $table = User::TABLE_NAME
    ): bool {

        $sql =
<<<STATEMENT
            SELECT COUNT(`{$name}`) AS num
            FROM `{$table}`
            WHERE `{$name}` = :value
            LIMIT 1;
STATEMENT;

        $statement = $this->handle->prepare($sql);
        $statement->bindValue(":value", $value);

        try {
            $statement->execute();

        } catch (\PDOException $cause) {
            throw new QueryException("search failure", $cause);
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        return ($row['num'] > 0);
    }

    /**
     * Disconnects this database before destruction.
     */
    public function __destruct() {
        unset($this->handle);
    }

    /**
     * Prevents further instances creation.
     *
     * @return void
     * @throws \LogicException if any clone operation is somehow attempted.
     */
    private function __clone() {
        throw new \LogicException("Unsupported operation");
    }
}
?>