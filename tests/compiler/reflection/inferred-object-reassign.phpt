--TEST--
Re-assign an inferred local object to a different class across if/else
--FILE--
<?php
function main(): void {
    // A local variable has no type declaration: its class is inferred from the
    // first assignment. Assigning a different concrete class in a mutually
    // exclusive branch is valid PHP, so the inferred local must widen to a
    // dynamic object rather than being rejected.
    $a = 'strlen';
    if (is_string($a)) {
        $ref = new ReflectionFunction($a);
    } else {
        $ref = new ReflectionMethod($a, 'count');
    }
    echo get_class($ref), "\n";

    $b = [ArrayObject::class, 'count'];
    if (is_string($b)) {
        $ref2 = new ReflectionFunction($b);
    } else {
        $ref2 = new ReflectionMethod($b[0], $b[1]);
    }
    echo get_class($ref2), "\n";
}
?>
--EXPECT--
ReflectionFunction
ReflectionMethod
