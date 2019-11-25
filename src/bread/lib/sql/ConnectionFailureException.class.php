<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'DatabaseException.class.php';

/**
 * Exception thrown when a connection attempt has failed.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource ConnectionFailureException.class.php
 */
class ConnectionFailureException extends DatabaseException {}
?>