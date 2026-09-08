--TEST--
First-class callable of a stream-returning function
--FILE--
<?php
function useOpener(Closure $open): void {
    echo "callable\n";
}

function main(): void {
    useOpener(fopen(...));
}
?>
--EXPECT--
callable
