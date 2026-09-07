--TEST--
Integer min/max optimization preserves values, evaluation order, and dynamic fallback
--FILE--
<?php
function nextMinMaxValue(int &$value): int
{
    ++$value;
    return $value;
}
function compareMinMax(int $a, int $b): void
{
    var_dump(min($a, $b), max($a, $b));
}
function dynamicMinMax(mixed $a, mixed $b): void
{
    var_dump(min($a, $b), max($a, $b));
}
function main(): void
{
    compareMinMax(8, -3);
    compareMinMax(4, 4);
    compareMinMax(PHP_INT_MIN, PHP_INT_MAX);
    $value = 2;
    var_dump(min($value, (int) nextMinMaxValue($value)), $value);
    var_dump(max(nextMinMaxValue($value), nextMinMaxValue($value)), $value);
    var_dump(min(nextMinMaxValue($value), $value), $value);
    $counter = 0;
    var_dump(false && min(++$counter, ++$counter), $counter);
    var_dump(true ? max(++$counter, ++$counter) : min(++$counter, ++$counter), $counter);
    dynamicMinMax('20', 3);
    dynamicMinMax(false, 2);
    var_dump(min(1, 1.5), max(1, 1.5), min(-2.5, -1.0), max(-1.0, -2.5));
    var_dump(min([4, 2]), max(...[1, 7, 3]), min(3, 2, 1));
    $callable = min(...);
    var_dump($callable(4, 2));
}
?>
--EXPECT--
int(-3)
int(8)
int(4)
int(4)
int(-9223372036854775808)
int(9223372036854775807)
int(3)
int(3)
int(5)
int(5)
int(6)
int(6)
bool(false)
int(0)
int(2)
int(2)
int(3)
string(2) "20"
bool(false)
int(2)
int(1)
float(1.5)
float(-2.5)
float(-1)
int(2)
int(7)
int(1)
int(2)
