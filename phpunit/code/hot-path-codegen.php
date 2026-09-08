<?php

function hotPathTrace(string $value): string
{
    return $value;
}

function hotPathCodegen(int $limit): int
{
    $items = [];
    $other = [2];
    $value = 1;

    $items[0] = $value;
    $items[] = $value;
    $items[] = hotPathTrace('append');
    $appendResult = ($items[] = $value);
    $items[0] += $value;
    $items[0] += $other[0];
    $items[2] = $other[0];

    $assigned = ($items[1] = $value);
    $assigned += ($items[0] += $value);

    $safe = 'item-' . $limit;
    $ordered = hotPathTrace('left') . hotPathTrace('right');
    $length = strlen('item-' . $limit);

    while ($limit-- > 0) {
        ++$value;
    }

    return $assigned + $length + strlen($safe) + strlen($ordered) + $value;
}

function hotPathTypedReads(array $hash, string $str, bool $flag, int $fallback): void
{
    $hashValue = $hash['value'];
    $stringValue = $str[0];
    $arraySelection = $hash ?: null;
    $boolSelection = $flag ?: $fallback;

    // A compound left operand still needs the generic chain helper so the
    // array lookup is evaluated exactly once.
    $chainedSelection = $hash['value'] ?: $fallback;
}
