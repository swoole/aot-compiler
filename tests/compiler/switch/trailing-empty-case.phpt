--TEST--
Switch evaluates and matches a trailing empty case
--FILE--
<?php
function mark(string $value): string
{
    echo "evaluated:$value\n";
    return $value;
}

function main(): void
{
    switch ('subject') {
        default:
            echo "default\n";
            break;
        case mark('other'):
    }

    switch ('subject') {
        default:
            echo "default\n";
            break;
        case mark('subject'):
    }

    switch ('subject') {
        default:
            echo "unexpected-default\n";
            break;
        case mark('first-miss'):
        case mark('subject'):
    }

    switch ('subject') {
        case mark('default-miss'):
            echo "unexpected-case\n";
            break;
        default:
    }
}
?>
--EXPECT--
evaluated:other
default
evaluated:subject
evaluated:first-miss
evaluated:subject
evaluated:default-miss
