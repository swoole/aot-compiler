<?php

use TypePhp\CompilerTest;

final class ClosureParamTypeTest extends BaseTest
{
    public function testTypeHintParametersUseNativeCppTypes(): void
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/closure-param-type.php';
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);
        $code = file_get_contents($compiler->convertFile($source));

        self::assertIsString($code);

        self::assertStringContainsString('(php::Int a)', $code);
        self::assertStringContainsString('(php::Float a)', $code);
        self::assertStringContainsString('(php::Bool a)', $code);
        self::assertStringContainsString('(php::Str a)', $code);
        self::assertStringContainsString('(php::Array a)', $code);
    }

    public function testCallSiteInferredParametersUseNativeCppTypes(): void
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/closure-param-type.php';
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);
        $code = file_get_contents($compiler->convertFile($source));

        self::assertIsString($code);

        self::assertStringContainsString('(php::Int x)', $code);
        self::assertStringContainsString('(php::Float x)', $code);
        self::assertStringContainsString('(php::Bool x)', $code);
        self::assertStringContainsString('(php::Array arr)', $code);
    }

    public function testMultiCallClosureRemainsPhpVar(): void
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/closure-param-type.php';
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);
        $code = file_get_contents($compiler->convertFile($source));

        self::assertIsString($code);

        self::assertStringContainsString('(php::Var x)', $code);
    }
}
