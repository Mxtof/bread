<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'DatabaseException.class.php';

/**
 * Provides a base type to exceptions related with invalid field inputs.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource FieldException.class.php
 */
class FieldException extends DatabaseException {

    /**
     * The attribute name for the invalid value.
     *
     * @var string
     */
    protected $attribute;
    /**
     * The name of the field that could not be set.
     *
     * @var string
     */
    protected $name;
    /**
     * The invalid value causing this exception.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Builds this exception for a specific field, given an attribute name-value pair.
     *
     * @param string $name      The name of the field that could not be set.
     * @param string $attribute The attribute name for the invalid value.
     * @param mixed $value      The invalid value causing this exception.
     */
    public function __construct(string $attribute, string $name, $value) {
        parent::__construct(
            "Invalid {$attribute}: [name: {$name}, value: {$value}]"
        );
        $this->attribute = $attribute;
        $this->name      = $name;
        $this->value     = $value;
    }

    /**
     * Retrieves the name of the field being modified.
     *
     * @return  string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * Retrieves the attribute name for the invalid value.
     *
     * @return  string
     */
    public function getAttribute() {
        return $this->attribute;
    }

    /**
     * Retrieves the invalid value causing this exception.
     *
     * @return  mixed
     */
    public function getValue() : mixed {
        return $this->value;
    }

    /**
     * Provides a string expression of the invalid value
     * which is safe for a view in HTML page.
     *
     * @return string
     */
    public function getDisplayValue() : string {
        return \htmlentities((string)$this->value);
    }

    /**
     * Provides a safe message that is suitable for a display in HTML page.
     *
     * @return string
     */
    public function getDisplayMessage() : string {
        $value = $this->getDisplayValue();
        return "Invalid {$this->attribute}: [field: {$this->name}, value: {$value}]";
    }
}
?>