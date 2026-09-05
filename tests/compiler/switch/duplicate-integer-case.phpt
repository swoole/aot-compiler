--TEST--
Duplicate integer switch cases retain PHP first-match semantics
--FILE--
<?php
function select_duplicate_int(int $value): string
{
    switch ($value) {
        case 1:
            return 'first-int';
        case 1:
            return 'second-int';
        default:
            return 'default-int';
    }
}

function select_duplicate_bool(bool $value): string
{
    switch ($value) {
        case 1:
            return 'first-bool';
        case 1:
            return 'second-bool';
        default:
            return 'default-bool';
    }
}

function main(): void
{
    echo select_duplicate_int(1), "\n";
    echo select_duplicate_int(2), "\n";
    echo select_duplicate_bool(true), "\n";
    echo select_duplicate_bool(false), "\n";
}
?>
--EXPECT--
first-int
default-int
first-bool
default-bool
