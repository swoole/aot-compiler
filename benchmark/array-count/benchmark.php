<?php
function main(int $argc, array $argv): void
{
    $iterations = $argc > 1 ? (int) $argv[1] : 10000000;
    $items = [];
    $checksum = 0;
    for ($i = 0; $i < $iterations; ++$i) {
        $items[$i % 100] = $i;
        $checksum += count($items);
    }
    echo $checksum, "\n";
}
