<?php

namespace MediaWiki\Tests\HookContainer;

use MediaWiki\Api\ApiHookRunner;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWikiUnitTestCase;
use ReflectionClass;
use ReflectionParameter;

/**
 * Tests that all arguments passed into HookRunner are passed along to HookContainer.
 * @covers \MediaWiki\HookContainer\HookRunner
 * @covers \MediaWiki\Api\ApiHookRunner
 * @package MediaWiki\Tests\HookContainer
 */
class HookRunnerTest extends MediaWikiUnitTestCase {

	public function provideHookMethods() {
		$hookRunnerClasses = [ ApiHookRunner::class, HookRunner::class ];
		foreach ( $hookRunnerClasses as $hookRunnerClass ) {
			$hookRunnerReflectionClass = new ReflectionClass( $hookRunnerClass );
			foreach ( $hookRunnerReflectionClass->getInterfaces() as $hookInterface ) {
				yield $hookRunnerClass . ':' . $hookInterface->getName()
					=> [ $hookRunnerClass, $hookInterface ];
			}
		}
	}

	/**
	 * @dataProvider provideHookMethods
	 * @param string $hookRunnerClass
	 * @param ReflectionClass $hookInterface
	 */
	public function testAllArgumentsArePassed(
		string $hookRunnerClass,
		ReflectionClass $hookInterface
	) {
		$this->assertSame( 1, count( $hookInterface->getMethods() ),
			'Hook interface should have one method' );
		$hookMethod = $hookInterface->getMethods()[0];
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

	private function getMockedParamValue( ReflectionParameter $param ) {
		$paramType = $param->getType();
		if ( !$paramType || $paramType->getName() === 'string' ) {
			// Return a string for all the untyped parameters, good enough for our purposes.
			return $param->getName();
		}
		if ( $paramType->getName() === 'array' ) {
			return [];
		}
		return $this->createNoOpMock( $paramType->getName() );
	}
}
