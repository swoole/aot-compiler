<?php

use TypePhp\CompilerTest;

final class MinMaxIntegerTest extends BaseTest
{
    public function testOnlyTwoProvenIntegerArgumentsBypassZend(): void
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/min-max-integer.php';
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);
        $code = file_get_contents($compiler->convertFile($source));

        self::assertIsString($code);
        $start = strpos($code, 'php::Array php_minmaxintegers(');
        self::assertNotFalse($start);
        $end = strpos($code, 'php::Array php_minmaxfallbacks(', $start);
        self::assertNotFalse($end);
        $integerBody = substr($code, $start, $end - $start);
        self::assertStringNotContainsString('php::call(', $integerBody);
        self::assertSame(13, substr_count($code, 'php::call('));
        $castStart = strpos($code, 'php::Array php_minmaxcasts(');
        self::assertNotFalse($castStart);
        $castEnd = strpos($code, 'void php_main(', $castStart);
        self::assertNotFalse($castEnd);
        self::assertSame(2, substr_count(substr($code, $castStart, $castEnd - $castStart), 'php::toInt(a)'));
    }
}
