--TEST--
First-class callable of a math function (round)
--FILE--
<?php
function main(): void {
    // round() is in the math-function return-type optimization list. For the
    // first-class callable `round(...)`, args[0] is a VariadicPlaceholder with
    // no ->value, so the optimization must be skipped and the expression must
    // resolve to a Closure instead of crashing.
    $values = [1.4, 2.6, 3.5];
    $rounded = array_map(round(...), $values);
    echo implode(',', $rounded), "\n";
}
?>
--EXPECT--
1,3,4
