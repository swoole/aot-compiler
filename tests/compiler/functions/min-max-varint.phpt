--TEST--
min/max keep overflow-capable varint values on the Zend path
--FILE--
<?php
use varint_types;
function main(): void
{
    $value = PHP_INT_MAX;
    ++$value;
    var_dump(min($value, 2), is_float(max($value, 2)));
    $value = 1;
    $value += 0.5;
    var_dump(min($value, 2), max($value, 2));
}
?>
--EXPECT--
int(2)
bool(true)
float(1.5)
int(2)
