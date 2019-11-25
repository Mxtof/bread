<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'FieldException.class.php';

/**
 * Exception thrown when the size of an input is out of an expected range.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource FieldSizeException.class.php
 */
class FieldSizeException extends FieldException {

    /**
     * The lower bound of the range.
     *
     * @var int
     */
    protected $min;
    /**
     * The upper bound of the range.
     *
     * @var int
     */
    protected $max;

    /**
     * Builds this exception upon specified range values.
     *
     * @param integer $size     The size of the input being out of range.
     * @param integer $min      The lower bound of the range.
     * @param integer $max      The upper bound of the range.
     */
    public function __construct(string $name, int $size, int $min, int $max) {
        parent::__construct("size", $name, $size);
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Retrieves the lower bound of the range.
     *
     * @return  int
     */
    public function getMin() : int {
        return $this->min;
    }

    /**
     * Retrieves the upper bound of the range.
     *
     * @return  int
     */
    public function getMax() : int {
        return $this->max;
    }
}
?>