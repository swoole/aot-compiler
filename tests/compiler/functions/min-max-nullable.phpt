--TEST--
min/max preserve null arguments and nullable property values
--FILE--
<?php
class MinMaxNullable
{
    public ?int $value = null;
    public static ?int $staticValue = null;
}
function nullableMinMax(?int $value): void
{
    var_dump(min($value, 0), max($value, 0));
}
function main(): void
{
    $object = new MinMaxNullable();
    nullableMinMax(null);
    var_dump(min($object->value, 0), max($object->value, 0));
    var_dump(min(MinMaxNullable::$staticValue, 0), max(MinMaxNullable::$staticValue, 0));
    $object->value = 3;
    var_dump(min($object->value, 0), max($object->value, 0));
}
?>
--EXPECT--
NULL
NULL
NULL
NULL
NULL
NULL
int(0)
int(3)
