--TEST--
Known-array append expressions preserve values, references and RHS mutations
--FILE--
<?php
function nextValue(array &$items): int
{
    $items[] = 10;
    return count($items);
}
function main(): void
{
    $source = std::any(1);
    $reference =& $source;
    $items = [];
    $result = ($items[] = $reference);
    $reference = 2;
    var_dump($items, $result);
    $items[] =& $reference;
    $reference = 3;
    var_dump($items, $result);
    $copy = $items;
    $value = ($items[] = nextValue($items));
    var_dump($items, $copy, $value);
    $nested = [&$reference];
    $last = ($items[] = $nested[0]);
    $reference = 4;
    var_dump($items, $last);
}
?>
--EXPECT--
array(1) {
  [0]=>
  int(1)
}
int(1)
array(2) {
  [0]=>
  int(1)
  [1]=>
  &int(3)
}
int(1)
array(4) {
  [0]=>
  int(1)
  [1]=>
  &int(3)
  [2]=>
  int(10)
  [3]=>
  int(3)
}
array(2) {
  [0]=>
  int(1)
  [1]=>
  &int(3)
}
int(3)
array(5) {
  [0]=>
  int(1)
  [1]=>
  &int(4)
  [2]=>
  int(10)
  [3]=>
  int(3)
  [4]=>
  int(3)
}
int(3)
