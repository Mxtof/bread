<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'DatabaseException.class.php';

/**
 * Exception thrown to indicate a failure of a database query.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource QueryException.class.php
 */
class QueryException extends DatabaseException {}
?>
