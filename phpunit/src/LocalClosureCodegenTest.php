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
final class LocalClosureCodegenTest extends BaseTest
{
    public function testOnlyProvenLocalClosuresUseConcreteCppLambdas(): void
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $source = TYPEPHP_ROOT_PATH . '/phpunit/code/local-native-closure-codegen.php';
        $compiler->addFiles([$source]);
        $compiler->prepareFile($source);
        $generated = $compiler->convertFile($source);
        $code = file_get_contents($generated);

        self::assertIsString($code);
        self::assertStringContainsString(
            'auto direct = [base = base](php::Int value) mutable -> php::Var {',
            $code,
        );
        self::assertStringContainsString('direct(2L)', $code);
        self::assertStringNotContainsString('typephp_call_cached(direct', $code);

        // Escaped values and dynamic references remain real Zend Closures.
        self::assertSame(2, substr_count($code, 'php::newClosureWithParameters('));
        self::assertStringContainsString('typephp_call_cached(dynamicRef', $code);
    }
}
