--TEST--
Literal method expressions preserve overrides, magic calls, private scope and nullsafe access
--FILE--
<?php
class LiteralCacheBase {
    public function run(): string { return 'base'; }
}
class LiteralCacheChild extends LiteralCacheBase {
    public function run(): string { return 'child'; }
}
class LiteralCacheMagic {
    public function __call(string $name, array $args): string { return 'magic-' . $name; }
}
class LiteralCachePrivate {
    private function hidden(): string { return 'private'; }
    public function invoke(): string { return $this->{'hidden'}(); }
}
function literalCachedCall(object $object): string { return $object->{'run'}(); }
function literalNullsafeCall(?object $object): ?string { return $object?->{'run'}(); }
function main(): void {
    foreach ([new LiteralCacheBase(), new LiteralCacheChild(), new LiteralCacheMagic()] as $object) {
        echo literalCachedCall($object), ':', literalCachedCall($object), "\n";
        echo literalNullsafeCall($object), "\n";
    }
    $private = new LiteralCachePrivate();
    echo $private->invoke(), ':', $private->invoke(), "\n";
    var_dump(literalNullsafeCall(null));
}
?>
--EXPECT--
base:base
base
child:child
child
magic-run:magic-run
magic-run
private:private
NULL
