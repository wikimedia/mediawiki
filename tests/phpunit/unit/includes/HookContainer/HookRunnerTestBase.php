<?php

namespace MediaWiki\Tests\HookContainer;

use Generator;
use MediaWiki\HookContainer\HookContainer;
use MediaWikiUnitTestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;

/**
 * Tests that all arguments passed into HookRunner are passed along to HookContainer.
 * @stable to extend
 * @since 1.36
 */
abstract class HookRunnerTestBase extends MediaWikiUnitTestCase {
	/**
	 * @return Generator|array
	 */
	// abstract public static function provideHookRunners();

	/**
	 * @var string[] Method names ("onFooBar") of hook methods that do not return void,
	 * even though their hooks are called with the 'abortable' option set to false.
	 * Ideally there should be no such hooks, but if you have any,
	 * you can declare them as exceptions here.
	 */
	protected static array $unabortableHooksNotReturningVoid = [];

	/**
	 * @var string[] Method names ("onFooBar") of hook methods that return void,
	 * even though their hooks are not called with the 'abortable' option set to false.
	 * Ideally there should be no such hooks (consider adding the 'abortable' option),
	 * but if you have any, you can declare them as exceptions here.
	 */
	protected static array $voidMethodsNotUnabortableHooks = [];

	/**
	 * Temporary override to make provideHookRunners static.
	 * See T332865.
	 *
	 * @return Generator|array
	 */
	final public static function provideHookRunnersStatically() {
		$reflectionMethod = new ReflectionMethod( static::class, 'provideHookRunners' );
		if ( $reflectionMethod->isStatic() ) {
			return $reflectionMethod->invoke( null );
		}

		trigger_error(
			'overriding provideHookRunners as an instance method is deprecated. (' .
			$reflectionMethod->getFileName() . ':' . $reflectionMethod->getEndLine() . ')',
			E_USER_DEPRECATED
		);

		return $reflectionMethod->invoke( new static() );
	}

	/**
	 * @dataProvider provideHookRunnersStatically
	 */
	public function testAllMethodsInheritedFromInterface( string $hookRunnerClass ) {
		$hookRunnerReflectionClass = new ReflectionClass( $hookRunnerClass );
		$hookMethods = $hookRunnerReflectionClass->getMethods();
		$hookInterfaces = $hookRunnerReflectionClass->getInterfaces();
		foreach ( $hookMethods as $method ) {
			if ( $method->isConstructor() ) {
				continue;
			}
			$interfacesWithMethod = array_filter(
				$hookInterfaces,
				static function ( ReflectionClass $interface ) use ( $method ) {
					return $interface->hasMethod( $method->getName() );
				}
			);
			$this->assertCount( 1, $interfacesWithMethod,
				'Exactly one hook interface must have method ' . $method->getName() );
		}
	}

	/**
	 * @dataProvider provideHookRunnersStatically
	 */
	public function testHookInterfacesConvention( string $hookRunnerClass ) {
		$hookRunnerReflectionClass = new ReflectionClass( $hookRunnerClass );
		$hookInterfaces = $hookRunnerReflectionClass->getInterfaces();
		$hookMethods = [];
		foreach ( $hookInterfaces as $interface ) {
			$name = $interface->getName();

			$this->assertStringEndsWith( 'Hook', $name,
				"Interface name '$name' must have the suffix 'Hook'." );

			$methods = $interface->getMethods();
			$this->assertCount( 1, $methods,
				'Hook interface should have one method' );

			$method = $methods[0];
			$methodName = $method->getName();
			$this->assertStringStartsWith( 'on', $methodName,
				"Interface method '$methodName' must have prefix 'on'." );
			$this->assertTrue( $method->isPublic(), "Interface method '$methodName' should public" );
			$this->assertFalse( $method->isStatic(), "Interface method '$methodName' should not static." );

			$hookMethods[] = $methodName;
		}
		$this->assertArrayEquals( $hookMethods, array_unique( $hookMethods ) );
	}

	public static function provideHookMethods() {
		foreach ( self::provideHookRunnersStatically() as $name => [ $hookRunnerClass ] ) {
			$hookRunnerReflectionClass = new ReflectionClass( $hookRunnerClass );
			foreach ( $hookRunnerReflectionClass->getInterfaces() as $hookInterface ) {
				yield $name . ':' . $hookInterface->getName()
					=> [ $hookRunnerClass, $hookInterface->getMethods()[0] ];
			}
		}
	}

	/**
	 * @dataProvider provideHookMethods
	 */
	public function testHookContainerArguments(
		string $hookRunnerClass,
		ReflectionMethod $hookMethod
	) {
		$params = [];
		foreach ( $hookMethod->getParameters() as $param ) {
			$bogusValue = $this->getMockedParamValue( $param );
			if ( $param->isPassedByReference() ) {
				$params[] = &$bogusValue;
				unset( $bogusValue );
			} else {
				$params[] = $bogusValue;
			}
		}
		$hookMethodName = $hookMethod->getName();
		$hookMethodIsVoid = $this->isVoidType( $hookMethod->getReturnType() );
		$mockContainer = $this->createNoOpMock( HookContainer::class, [ 'run' ] );
		$mockContainer
			->expects( $this->once() )
			->method( 'run' )
			->willReturnCallback( function ( string $hookName, array $hookCallParams, array $options = [] )
				use ( $hookMethodName, $hookMethodIsVoid, $params )
			{
				// HookContainer builds the method from the hook name with some normalisation,
				// so the passed hook name and the method must be equal
				// This is not a function in HookContainer as hooks are a hot path
				// and just avoid the extra call for performance
				$expectedFuncName = 'on' . strtr( ucfirst( $hookName ), ':-', '__' );
				$this->assertSame( $expectedFuncName, $hookMethodName,
					'Interface function must be named "on<hook name>" with : or - replaced by _' );
				$this->assertSame( $params, $hookCallParams );

				if (
					!( $options['abortable'] ?? true ) &&
					!in_array( $hookMethodName, static::$unabortableHooksNotReturningVoid, true )
				) {
					$this->assertTrue( $hookMethodIsVoid,
						'Hook method must return void because hook is marked as not abortable' );
				}
				if (
					$hookMethodIsVoid &&
					!in_array( $hookMethodName, static::$voidMethodsNotUnabortableHooks, true )
				) {
					$this->assertArrayHasKey( 'abortable', $options,
						'Hook must be marked as not abortable because hook method returns void' );
					$this->assertSame( false, $options['abortable'],
						'Hook must be marked as not abortable because hook method returns void' );
				}

				return true;
			} );
		$hookRunner = new $hookRunnerClass( $mockContainer );
		$hookRunner->$hookMethodName( ...$params );
	}

	protected function getMockedParamValue( ReflectionParameter $param ) {
		$paramType = $param->getType();
		if ( !$paramType ) {
			// Return a string for all the untyped parameters, good enough for our purposes.
			return $param->getName();
		}
		$paramName = $paramType->getName();
		if ( $paramName === 'string' ) {
			return $param->getName();
		}
		if ( $paramName === 'array' ) {
			return [];
		}
		if ( $paramName === 'bool' ) {
			return false;
		}
		if ( $paramName === 'int' ) {
			return 42;
		}
		if ( $paramName === 'float' ) {
			return 42.0;
		}
		if ( $paramName === 'callable' ) {
			return static function () {
				// No-op
			};
		}
		return $this->createNoOpMock( $paramName );
	}

	private function isVoidType( ?ReflectionType $type ): bool {
		if ( $type === null ) {
			return false;
		}
		if ( !( $type instanceof ReflectionNamedType ) ) {
			return false;
		}
		if ( !$type->isBuiltin() ) {
			return false;
		}
		return $type->getName() === 'void';
	}
}
