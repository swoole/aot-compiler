<?php
function closureTypeHintInt(int $x): int
{
    $fn = fn(int $a) => $a + 1;
    return $fn($x);
}
function closureTypeHintFloat(float $x): float
{
    $fn = fn(float $a) => $a * 2.0;
    return $fn($x);
}
function closureTypeHintBool(bool $x): bool
{
    $fn = fn(bool $a) => !$a;
    return $fn($x);
}
function closureTypeHintString(string $x): int
{
    $fn = fn(string $a) => strlen($a);
    return $fn($x);
}
function closureTypeHintArray(array $x): int
{
    $fn = fn(array $a) => count($a);
    return $fn($x);
}
function closureCallSiteInt(): int
{
    $fn = fn($x) => $x + 1;
    return $fn(42);
}
function closureCallSiteFloat(): float
{
    $fn = fn($x) => $x * 2.0;
    return $fn(3.14);
}
function closureCallSiteBool(): bool
{
    $fn = fn($x) => !$x;
    return $fn(true);
}
function closureCallSiteArray(): int
{
    $fn = fn($arr) => count($arr);
    return $fn([1, 2, 3, 4, 5]);
}
function closureMultiCallNoInfer(): void
{
    $fn = fn($x) => $x + 1;
    var_dump($fn(42));
    var_dump($fn(3.14));
}
function main(): void
{
    closureTypeHintInt(10);
    closureTypeHintFloat(1.5);
    closureTypeHintBool(false);
    closureTypeHintString("test");
    closureTypeHintArray([1, 2]);
    closureCallSiteInt();
    closureCallSiteFloat();
    closureCallSiteBool();
    closureCallSiteArray();
    closureMultiCallNoInfer();
}
