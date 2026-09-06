--TEST--
First-class callable of builtins requiring multiple arguments
--FILE--
<?php
function main(): void {
    // str_repeat() and array_slice() are internal functions with 2 required
    // parameters. `foo(...)` builds a Closure; its single VariadicPlaceholder
    // must not be counted as one real argument and rejected by the internal
    // function argument-count check.
    $repeat = str_repeat(...);
    echo $repeat('ab', 3), "\n";

    $slice = array_slice(...);
    echo implode(',', $slice([1, 2, 3, 4, 5], 1, 2)), "\n";
}
?>
--EXPECT--
ababab
2,3
