<?php
function minMaxIntegers(int $a, int $b): array
{
    return [min($a, $b), max($a, $b)];
}
function minMaxFallbacks(mixed $a, mixed $b): array
{
    return [min($a, $b), max($a, $b), min(1.5, 2.5), max(1, 2.5), min([3, 1]), max(1, 2, 3), min(...[3, 1])];
}
function minMaxUnproven(mixed $a): array
{
    return [min($a + 1, 2), max($a + 1, 2)];
}
class MinMaxNullableProperty
{
    public ?int $value = null;
}
function minMaxNullable(?int $value, MinMaxNullableProperty $object): array
{
    return [min($value, 0), max($value, 0), min($object->value, 0), max($object->value, 0)];
}
function minMaxCasts(mixed $a): array
{
    return [min((int) $a, 2), max((int) $a, 2)];
}
function main(): void
{
    minMaxIntegers(3, 1);
}
