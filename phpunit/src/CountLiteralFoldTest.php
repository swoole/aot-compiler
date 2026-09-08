<?php
/**
 * This file is part of TypePHP(AOT).
 *
 * @link     https://www.swoole.com/aot/
 * @contact  service@swoole.com
 */

namespace TypePhp\Tests;

use PHPUnit\Framework\TestCase;
use TypePhp\CompilerTest;

/**
 * @internal
 * @coversNothing
 */
class CountLiteralFoldTest extends TestCase
{
    public function testUnfoldableArrayLiteralsKeepTheRuntimeCall(): void
    {
        $cpp = $this->compileToCpp('count-literal-fold-unsafe.php');

        // Every call in the fixture must stay on the runtime path: element
        // side effects, a repeated key, a spread, a by-reference item, a
        // plain variable read, a constant or class constant fetch that may
        // be undefined, and an interpolated string that may call __get().
        self::assertSame(10, substr_count($cpp, 'php::fn::count('));
        self::assertStringContainsString('php_bump()', $cpp);
        self::assertStringContainsString('i++', $cpp);
    }

    public function testPlainArrayLiteralsStillFoldAtCompileTime(): void
    {
        $cpp = $this->compileToCpp('count-literal-fold-safe.php');

        self::assertStringNotContainsString('php::fn::count(', $cpp);
    }

    public function testArgumentUnpackingUsesTheRuntimeCallPath(): void
    {
        $cpp = $this->compileToCpp('count-literal-fold-unpack.php');

        self::assertSame(3, substr_count($cpp, '.appendUnpacked('));
        self::assertStringNotContainsString('php::fn::count(', $cpp);
    }

    public function testKnownArrayVariableUsesDirectCount(): void
    {
        $cpp = $this->compileToCpp('count-known-array.php');

        self::assertStringContainsString('static_cast<php::Int>(items.count())', $cpp);
        self::assertSame(4, substr_count($cpp, 'php::fn::count('));
    }

    private function compileToCpp(string $file): string
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/' . $file;
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);

        return file_get_contents($compiler->convertFile($source));
    }
}
