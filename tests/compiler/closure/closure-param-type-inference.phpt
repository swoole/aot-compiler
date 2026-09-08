--TEST--
Native local closure parameter types are inferred from type declarations and call-site literals
--FILE--
<?php
function testTypeHintInt(): void
{
    $fn = fn(int $x) => $x + 1;
    var_dump($fn(42));
}
function testTypeHintFloat(): void
{
    $fn = fn(float $x) => $x * 2.0;
    var_dump($fn(3.14));
}
function testTypeHintBool(): void
{
    $fn = fn(bool $x) => !$x;
    var_dump($fn(true));
}
function testTypeHintString(): void
{
    $fn = fn(string $x) => strlen($x);
    var_dump($fn("hello"));
}
function testTypeHintArray(): void
{
    $fn = fn(array $x) => count($x);
    var_dump($fn([1, 2, 3]));
}
function testCallSiteInt(): void
{
    $fn = fn($x) => $x + 1;
    var_dump($fn(42));
}
function testCallSiteFloat(): void
{
    $fn = fn($x) => $x * 2.0;
    var_dump($fn(3.14));
}
function testCallSiteBool(): void
{
    $fn = fn($x) => !$x;
    var_dump($fn(true));
}
function testCallSiteArray(): void
{
    $fn = fn($arr) => count($arr);
    var_dump($fn([1, 2, 3, 4, 5]));
}
function testMultiCallNoInfer(): void
{
    $fn = fn($x) => $x + 1;
    var_dump($fn(42));
    var_dump($fn(3.14));
}
function main(): void
{
    testTypeHintInt();
    testTypeHintFloat();
    testTypeHintBool();
    testTypeHintString();
    testTypeHintArray();
    testCallSiteInt();
    testCallSiteFloat();
    testCallSiteBool();
    testCallSiteArray();
    testMultiCallNoInfer();
}
?>
--EXPECT--
int(43)
float(6.28)
bool(false)
int(5)
int(3)
int(43)
float(6.28)
bool(false)
int(5)
int(43)
float(44)
