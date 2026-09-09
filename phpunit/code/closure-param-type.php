<?php
/**
 * Test fixture for closure parameter type narrowing.
 *
 * Tests:
 * 1. Type declaration inference (int, float, bool, string, array)
 * 2. Call-site literal inference (int, float, bool, array)
 * 3. Multi-call fallback to php::Var
 * 4. UnaryMinus/UnaryPlus expressions
 * 5. Boolean expressions (===, ||, instanceof)
 * 6. String concatenation
 * 7. goto invalidates candidates
 * 8. Nested functions (still narrowed)
 * 9. Cast expressions
 */

// --- Type declaration inference ---
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

// --- Call-site literal inference ---
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

// --- Multi-call fallback ---
function closureMultiCallNoInfer(): void
{
    $fn = fn($x) => $x + 1;
    var_dump($fn(42));
    var_dump($fn(3.14));
}

// --- UnaryMinus/UnaryPlus ---
function closureCallSiteNegInt(): int
{
    $fn = fn($x) => $x + 1;
    return $fn(-42);
}

function closureCallSiteNegFloat(): float
{
    $fn = fn($x) => $x * 2.0;
    return $fn(-3.14);
}

function closureCallSiteUnaryPlus(): int
{
    $fn = fn($x) => $x + 1;
    return $fn(+42);
}

// --- Boolean expressions ---
function closureCallSiteBoolExpr(): bool
{
    $fn = fn($x) => !$x;
    return $fn(1 === 2);
}

function closureCallSiteLogicalOr(): bool
{
    $fn = fn($x) => $x;
    return $fn(true || false);
}

function closureCallSiteInstanceof(): bool
{
    $fn = fn($x) => $x;
    return $fn(new \stdClass() instanceof \stdClass);
}

// --- String concatenation ---
function closureCallSiteConcat(): string
{
    $fn = fn($x) => $x;
    return $fn("hello" . "world");
}

function closureCallSiteConcatWithInt(): string
{
    $fn = fn($x) => $x;
    return $fn("hello" . 42);
}

// --- Cast expressions ---
function closureCallSiteCastInt(): int
{
    $fn = fn($x) => $x + 1;
    return $fn((int)"42");
}

function closureCallSiteCastString(): string
{
    $fn = fn($x) => $x;
    return $fn((string)42);
}

// --- goto invalidates candidates ---
function closureWithGoto(): void
{
    $fn = fn($x) => $x + 1;
    var_dump($fn(1));
    goto end;
    end:
}

// --- Nested functions (still narrowed) ---
function closureWithNestedFn(): int
{
    $fn = fn($x) => $x + 1;
    return $fn(42);
}

// --- Entry point ---
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
    closureCallSiteNegInt();
    closureCallSiteNegFloat();
    closureCallSiteUnaryPlus();
    closureCallSiteBoolExpr();
    closureCallSiteLogicalOr();
    closureCallSiteInstanceof();
    closureCallSiteConcat();
    closureCallSiteConcatWithInt();
    closureCallSiteCastInt();
    closureCallSiteCastString();
    closureWithGoto();
    closureWithNestedFn();
}
