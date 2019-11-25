<?php
function info( $value) { print($value . PHP_EOL);     }
function debug($value) { info(print_r($value, true)); }
?>