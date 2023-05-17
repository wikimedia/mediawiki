<?php

namespace MediaWiki\Tests\HookContainer;

use MediaWiki\HookContainer\HookContainer;
use MediaWikiUnitTestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Tests that all arguments passed into HookRunner are passed along to HookContainer.
 * @stable to extend
 * @since 1.36
 * @package MediaWiki\Tests\HookContainer
 */
abstract class HookRunnerTestBase extends MediaWikiUnitTestCase {
	/**
	 * @return Generator|array
	 */
	 // abstract public static function provideHookRunners();

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
	public function testHookInterfacesHaveUniqueMethods( string $hookRunnerClass ) {
		$hookRunnerReflectionClass = new ReflectionClass( $hookRunnerClass );
		$hookInterfaces = $hookRunnerReflectionClass->getInterfaces();
		$hookMethods = [];
		foreach ( $hookInterfaces as $interface ) {
			$this->assertCount( 1, $interface->getMethods(),
				'Hook interface should have one method' );
			$hookMethods[] = $interface->getMethods()[0]->getName();
		}
		$this->assertArrayEquals( $hookMethods, array_unique( $hookMethods ) );
	}

	/**
	 * @dataProvider provideHookRunners
	 */
	public function testHookInterfacesNamingConvention( string $hookRunnerClass ) {
		$hookRunnerReflectionClass = new ReflectionClass( $hookRunnerClass );

		foreach ( $hookRunnerReflectionClass->getInterfaces() as $interface ) {
			$name = $interface->getName();

			$this->assertStringEndsWith( 'Hook', $name,
				"Interface name '$name' must have the suffix 'Hook'." );

		}
	}

	public function provideHookMethods() {
		foreach ( $this->provideHookRunners() as $name => [ $hookRunnerClass ] ) {
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
	public function testAllArgumentsArePassed(
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
		$mockContainer = $this->createNoOpMock( HookContainer::class, [ 'run' ] );
		$mockContainer
			->expects( $this->once() )
			->method( 'run' )
			->willReturnCallback( function ( string $hookName, array $hookCallParams ) use ( $params ) {
				$this->assertNotSame( '', $hookName );
				$this->assertSame( $params, $hookCallParams );
				return true;
			} );
		$hookRunner = new $hookRunnerClass( $mockContainer );
		$hookMethodName = $hookMethod->getName();
		$hookRunner->$hookMethodName( ...$params );
	}

	protected function getMockedParamValue( ReflectionParameter $param ) {
		$paramType = $param->getType();
		if ( !$paramType || $paramType->getName() === 'string' ) {
			// Return a string for all the untyped parameters, good enough for our purposes.
			return $param->getName();
		}
		if ( $paramType->getName() === 'array' ) {
			return [];
		}
		if ( $paramType->getName() === 'bool' ) {
			return false;
		}
		if ( $paramType->getName() === 'int' ) {
			return 42;
		}
		return $this->createNoOpMock( $paramType->getName() );
	}
}
