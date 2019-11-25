<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'FieldException.class.php';

/**
 * Exception thrown when a given input does not conform with the field format.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource FieldFormatException.class.php
 */
class FieldFormatException extends FieldException {

    /**
     * Builds this exception for a given name-value pair.
     *
     * @param string $name The name of the field being modified.
     * @param mixed $value The invalid value causing this exception.
     */
    public function __construct(string $name, $value) {
        parent::__construct("format", $name, $value);
    }
}
?>