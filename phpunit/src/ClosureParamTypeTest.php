<?php
/**
 * This file is part of TypePHP(AOT).
 *
 * @link     https://www.swoole.com/aot/
 * @contact  service@swoole.com
 */

use TypePhp\CompilerTest;

/**
 * @internal
 * @coversNothing
 */
final class ClosureParamTypeTest extends BaseTest
{
    private function compileFixture(string $fixture): string
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/' . $fixture;
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);
        return file_get_contents($compiler->convertFile($source));
    }

    public function testTypeHintIntInfersNativeType(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Int x)', $code);
    }

    public function testTypeHintFloatInfersNativeType(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Float x)', $code);
    }

    public function testTypeHintBoolInfersNativeType(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Bool x)', $code);
    }

    public function testTypeHintStringInfersNativeType(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Str x)', $code);
    }

    public function testTypeHintArrayInfersNativeType(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Array x)', $code);
    }

    public function testCallSiteIntLiteralInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Int x)', $code);
    }

    public function testCallSiteFloatLiteralInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Float x)', $code);
    }

    public function testCallSiteBoolLiteralInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Bool x)', $code);
    }

    public function testCallSiteArrayLiteralInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Array x)', $code);
    }

    public function testMultiCallFallsBackToVar(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Var x)', $code);
    }

    public function testNegIntInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Int x)', $code);
    }

    public function testNegFloatInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Float x)', $code);
    }

    public function testUnaryPlusInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Int x)', $code);
    }

    public function testBoolExprInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Bool x)', $code);
    }

    public function testLogicalOrInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Bool x)', $code);
    }

    public function testInstanceofInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Bool x)', $code);
    }

    public function testConcatStringInference(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Str x)', $code);
    }

    public function testConcatWithNonStringOperandInfersString(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Str x)', $code);
    }

    public function testCastExpressionsInferNativeType(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Int x)', $code);
        self::assertStringContainsString('(php::Str x)', $code);
    }

    public function testGotoInvalidatesAllCandidates(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('newClosureWithParameters', $code);
    }

    public function testNestedFnStillWorks(): void
    {
        $code = $this->compileFixture('closure-param-type.php');
        self::assertStringContainsString('(php::Int x)', $code);
    }

    public function testClassMethodClosureStaysZend(): void
    {
        $code = $this->compileFixture('closure-param-type-class.php');
        self::assertStringNotContainsString('(php::Int x)', $code);
        self::assertStringContainsString('newClosureWithParameters', $code);
    }
}
