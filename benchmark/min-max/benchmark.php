<?php

function main(int $argc, array $argv): void
{
    $iterations = $argc > 1 ? (int) $argv[1] : 10000000;
    $checksum = 0;
    for ($i = 0; $i < $iterations; ++$i) {
        $damage = ($i % 101) - 20;
        $hp = 100 - ($i % 100);
        $damage = max(0, $damage);
        $remaining = min($hp, $damage);
        $checksum += $remaining;
    }
    echo $checksum, "\n";
}
