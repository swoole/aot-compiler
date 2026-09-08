<?php
class LiteralMethodTarget {
    public function run(int $value): int { return $value + 1; }
}
function runCalls(object $target, int $iterations): int {
    $checksum = 0;
    for ($i = 0; $i < $iterations; ++$i) {
        $checksum += $target->{'run'}($i % 100);
    }
    return $checksum;
}
function main(int $argc, array $argv): void {
    $iterations = $argc > 1 ? (int) $argv[1] : 10000000;
    echo runCalls(new LiteralMethodTarget(), $iterations), "\n";
}
