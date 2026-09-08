<?php
function knownArrayCount(array $items, mixed $dynamic): int
{
    $normal = count($items);
    $recursive = count($items, COUNT_RECURSIVE);
    $unknown = count($dynamic);
    return $normal + $recursive + $unknown;
}
