<?php

use TypePhp\CompilerTest;
use TypePhp\Exception\TestError;

class NativePropertyTest extends \BaseTest
{
    private function compileNativeProperty(string $file): string
    {
        global $translator;

        $compiler = CompilerTest::create(TYPEPHP_ROOT_PATH);
        $translator = $compiler;
        $testFile = __DIR__ . '/../code/' . $file;
        $compiler->addFiles([$testFile]);
        $compiler->prepareFile($testFile);
        $compiler->convertFile($testFile);

        $this->addToAssertionCount(1);
        return TYPEPHP_ROOT_PATH . '/build/phpunit/code/' . basename($file, '.php') . '.cc';
    }

    public function testFindNativePropertyUsesFullClassNameAcrossBranches(): void
    {
        try {
            $this->compileNativeProperty('native-property-full-name.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testStaticStaticPropertyUsesDynamicCalledClassPath(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-property-full-name.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringContainsString(
            'zend_class_entry *const _typephp_called_ce = typephp_get_called_ce(this_);',
            $code,
        );
        $this->assertStringContainsString(
            'typephp_get_static_property_slot(_typephp_called_ce, get_str(',
            $code,
        );
        $this->assertStringNotContainsString('typephp_get_called_class(this_)', $code);
        $this->assertStringContainsString('= php::toInt(value);', $code);
    }

    public function testNativeIntPropertyAssignOpUsesNativeReference(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-property-assign-op-int.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringContainsString('php::Int &_object_prop_this___value = Z_LVAL_P(php::unwrap_zval(OBJ_PROP(this_.checkedObject("Attempt to read property"), ', $code);
        $this->assertStringContainsString('php::Int &_object_prop_box__value = Z_LVAL_P(box.attr(', $code);
        $this->assertStringContainsString('_object_prop_this___value += (2L);', $code);
        $this->assertStringContainsString('_object_prop_box__value += (2L);', $code);
        $this->assertSame(2, substr_count($code, 'Z_LVAL_P('));
        $this->assertStringNotContainsString('typephp_write_property_scoped(', $code);
    }

    public function testReadonlyPropertiesDoNotUseNativeScalarReferences(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('readonly-property-no-native-ref.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringNotContainsString('typephp_static_int_ref(', $code);
        $this->assertStringNotContainsString('typephp_static_float_ref(', $code);
        $this->assertStringNotContainsString('_object_prop_', $code);
        $this->assertStringNotContainsString('AttrMode::Update', $code);
        $this->assertSame(2, substr_count($code, 'typephp_write_property_scoped('));
    }

    public function testNativeIntPropertyAssignOpConvertsBitwiseNotClassConst(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-property-assign-op-class-const.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringContainsString('php::Int &_object_prop_this___flags = Z_LVAL_P(php::unwrap_zval(OBJ_PROP(this_.checkedObject("Attempt to read property"), ', $code);
        // A TypePHP class constant is available during conversion and is
        // folded before the native property operation is emitted.
        $this->assertStringContainsString('_object_prop_this___flags &= (~php::toInt(1L));', $code);
    }

    public function testNativePropertyWriteConvertsOnlyWhenTypesDiffer(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-property-write-conversion.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringContainsString(' = nativeValue;', $code);
        $this->assertStringContainsString(' = php::toIntExact(dynamicValue, "NativePropertyWriteConversionBox::$value");', $code);
        $this->assertStringContainsString(' = php::toStringExact(dynamicName, "NativePropertyWriteConversionBox::$name");', $code);
        $this->assertStringContainsString(' = php::toArrayExact(dynamicItems, "NativePropertyWriteConversionBox::$items");', $code);
        $this->assertStringNotContainsString(' = php::toInt(nativeValue);', $code);
        $this->assertStringNotContainsString('php::toString(([&]() -> php::Var', $code);
        $this->assertStringNotContainsString('php::toArray(([&]() -> php::Var', $code);
    }

    public function testNativeThisPropertyWriteUsesExactHelperOnNativeReference(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-property-this-write-conversion.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringContainsString('php::Int &_object_prop_this___value = Z_LVAL_P(php::unwrap_zval(OBJ_PROP(this_.checkedObject("Attempt to read property"), ', $code);
        $this->assertStringContainsString('_object_prop_this___value = php::toIntExact(dynamicValue, "NativePropertyThisWriteConversionBox::$value");', $code);
    }

    public function testUnsetTypedPropertyDisablesSlotHoisting(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-property-unset-disables-hoist.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringNotContainsString('_object_prop_this___value', $code);
        $this->assertStringContainsString('this_.attr(', $code);
    }

    public function testUnsetObjectDisablesPropertySlotsButKeepsNativeMethodCall(): void
    {
        try {
            $outputFile = $this->compileNativeProperty('native-method-unset-keeps-optimization.php');
        } catch (TestError $e) {
            $this->fail($e->getMessage());
        }

        $code = file_get_contents($outputFile);
        $this->assertStringNotContainsString('_object_prop_object__value', $code);
        $this->assertStringContainsString('object.attr(', $code);
        $this->assertStringContainsString('php_nativemethodunsetkeepsoptimization__read(object)', $code);
    }

    public function testNativePropertyStaticScalarTypeMismatchFailsAtCompileTime(): void
    {
        $this->exec(
            'Cannot assign string to property NativePropertyStaticTypeMismatchBox::$value of type int',
            'native-property-static-type-mismatch.php'
        );
    }

    public function testCannotAccessPrivateNativePropertyFromUnrelatedClass(): void
    {
        $this->exec('Cannot access private property `value` of class `NativePrivateOwner`', 'native-property-private-other-class.php');
    }

    public function testNativeClassCannotHideParentPrivateProperty(): void
    {
        $this->exec(
            'Declaration of `NativePrivateShadowChild::$value` conflicts with private property '
                . '`NativePrivateShadowParent::$value`; property shadowing across inheritance is not allowed',
            'native-property-private-shadow.php',
        );
    }

    public function testCannotWritePromotedPrivateSetNativePropertyOutsideDeclaringClass(): void
    {
        $this->exec(
            'Cannot modify private(set) property `NativePromotedPrivateSetExternalWrite::$value`',
            'native-promoted-private-set-external-write.php',
        );
    }

    public function testCannotAccessProtectedNativePropertyFromUnrelatedClass(): void
    {
        $this->exec('Cannot access protected property `value` of class `NativeProtectedOwner`', 'native-property-protected-unrelated-class.php');
    }

    public function testCannotAccessPrivateNativePropertyThroughNullsafe(): void
    {
        $this->exec('Cannot access private property `value` of class `NullsafePrivateOwner`', 'nullsafe-private-property.php');
    }

    public function testCannotAccessNestedPrivateNativePropertyThroughNullsafe(): void
    {
        $this->exec('Cannot access private property `value` of class `NullsafeNestedChild`', 'nullsafe-nested-private-property.php');
    }

    public function testCannotAssignThroughNullsafeProperty(): void
    {
        $this->exec("Can't use nullsafe operator in write context", 'nullsafe-write-assign.php');
    }

    public function testCannotUseCompoundAssignThroughNullsafeProperty(): void
    {
        $this->exec("Can't use nullsafe operator in write context", 'nullsafe-write-assign-op.php');
    }

    public function testCannotIncrementThroughNullsafeProperty(): void
    {
        $this->exec("Can't use nullsafe operator in write context", 'nullsafe-write-inc.php');
    }

    public function testCannotUnsetThroughNullsafeProperty(): void
    {
        $this->exec("Can't use nullsafe operator in write context", 'nullsafe-write-unset.php');
    }

    public function testCannotAssignReferenceToNullsafeProperty(): void
    {
        $this->exec("Can't use nullsafe operator in write context", 'nullsafe-write-assign-ref-left.php');
    }

    public function testCannotTakeReferenceOfNullsafeProperty(): void
    {
        $this->exec('Cannot take reference of a nullsafe chain', 'nullsafe-write-assign-ref-right.php');
    }
}
