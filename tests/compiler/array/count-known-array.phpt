--TEST--
Known-array count tracks mutation and preserves recursive and dynamic counting
--FILE--
<?php
function main(): void
{
    $items = [];
    var_dump(count($items));
    $items[] = [1, 2];
    $items['key'] = 3;
    var_dump(count($items), count($items, COUNT_RECURSIVE));
    unset($items['key']);
    var_dump(count($items), count($items) - 2);
    $copy = $items;
    $items[] = 4;
    var_dump(count($items), count($copy));
    $dynamic = std::any($items);
    var_dump(count($dynamic));
}
?>
--EXPECT--
int(0)
int(2)
int(4)
int(1)
int(-1)
int(2)
int(1)
int(2)
