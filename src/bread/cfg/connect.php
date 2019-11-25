<?php
require_once 'lib/sql/Database.class.php';
require_once 'lib/sql/Config.class.php';
require_once 'lib/sql/ConnectionFailureException.class.php';

use com\mxtof\sql as sql;

// Specifies the credentials for database access,
// and ensures it is started when we're included.
if (! sql\Database::hasInstance()) {
    try {
        sql\Database::start(new sql\Config(
            "bread",    // schema
            "mxtof",    // username
            "mxtof"     // password
        ));

    } catch (sql\ConnectionFailureException $x) {
        // FIXME: surely we can do better!
        die($x->getMessage());
    }
}
?>