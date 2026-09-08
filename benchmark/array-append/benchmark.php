<?php
function makeAppendValue(int $value): int { return $value + 1; }
function main(int $argc, array $argv): void
{
    $rounds = $argc > 1 ? (int) $argv[1] : 10000;
    $checksum = 0;
    for ($r = 0; $r < $rounds; ++$r) {
        $items = [];
        for ($i = 0; $i < 1000; ++$i) {
            $items[] = makeAppendValue($i);
        }
        $checksum += count($items) + $items[999];
    }
    echo $checksum, "\n";
}
