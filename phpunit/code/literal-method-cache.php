<?php
function literalMethodCalls(object $object, ?object $nullable): array
{
    return [$object->{'run'}(1), $nullable?->{'run'}(2)];
}
class LiteralMethodScope
{
    private function hidden(): int { return 3; }
    public function invoke(): int { return $this->{'hidden'}(); }
}
