--TEST--
Integer min/max evaluates integer casts exactly once per argument
--FILE--
<?php
class MinMaxWarnings
{
    public int $count = 0;
}
function main(): void
{
    $warnings = new MinMaxWarnings();
    set_error_handler(static function (int $severity, string $message, string $file, int $line) use ($warnings): bool {
        ++$warnings->count;
        return true;
    });
    $object = new stdClass();
    var_dump(min((int) $object, 2), max((int) $object, 0));
    restore_error_handler();
    var_dump($warnings->count);
}
?>
--EXPECT--
int(1)
int(1)
int(2)
