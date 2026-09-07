--TEST--
Integer min/max optimization respects local functions and imported builtin aliases
--FILE--
<?php
namespace MinMaxGame {
    use function min as builtinMin;
    use function max as builtinMax;

    function min(int $a, int $b): int { return 99; }
    function max(int $a, int $b): int { return -99; }
    function compare(int $a, int $b): void
    {
        var_dump(min($a, $b), max($a, $b));
        var_dump(builtinMin($a, $b), builtinMax($a, $b));
        var_dump(\min($a, $b), \max($a, $b));
    }
}
namespace {
    function main(): void { \MinMaxGame\compare(3, 7); }
}
?>
--EXPECT--
int(99)
int(-99)
int(3)
int(7)
int(3)
int(7)
