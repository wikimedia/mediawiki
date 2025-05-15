<?php

declare( strict_types=1 );

namespace MediaWiki\Tests;

use MediaWikiIntegrationTestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;

/**
 * Base class for testing ExtensionServices classes.
 *
 * Such classes are used in many extensions to access services more easily.
 * They usually have one method like this for each service they register:
 *
 * ```php
 * public static function getService1( ?ContainerInterface $services = null ): Service1 {
 * 	return ( $services ?? MediaWikiServices::getInstance() )
 * 		->get( 'ExtensionName.Service1' );
 * }
 * ```
 *
 * To test an ExtensionServices class,
 * create a subclass of this test base class and specify $className and $serviceNamePrefix.
 *
 * @license GPL-2.0-or-later
 */
abstract class ExtensionServicesTestBase extends MediaWikiIntegrationTestCase {

	/**
	 * @var string The name of the ExtensionServices class.
	 * (A fully qualified name, usually specified via ::class syntax.)
	 */
	// protected static string $className;

	/**
	 * @var string The prefix of the services in the service wiring.
	 * Usually something like 'ExtensionName.'.
	 * @see ExtensionJsonTestBase::$serviceNamePrefix
	 */
	protected string $serviceNamePrefix;

	/**
	 * @var string[] An optional list of service names that,
	 * despite starting with the {@link self::$serviceNamePrefix},
	 * have no corresponding getter method on the ExtensionServices class.
	 * This can be used to temporarily support the old name of a renamed service
	 * for backwards compatibility with other extensions.
	 */
	protected array $serviceNamesWithoutMethods = [];

	/** @dataProvider provideMethods */
	public function testMethodSignature( ReflectionMethod $method ): void {
		$this->assertTrue( $method->isPublic(),
			'service accessor must be public' );
		$this->assertTrue( $method->isStatic(),
			'service accessor must be static' );
		$this->assertStringStartsWith( 'get', $method->getName(),
			'service accessor must be a getter' );
		$this->assertTrue( $method->hasReturnType(),
			'service accessor must declare return type' );
	}

	/** @dataProvider provideMethods */
	public function testMethodWithDefaultServiceContainer( ReflectionMethod $method ): void {
		$methodName = $method->getName();
		$serviceName = $this->serviceNamePrefix . substr( $methodName, strlen( 'get' ) );
		$expectedService = $this->createValue( $method->getReturnType() );
		$this->setService( $serviceName, $expectedService );

		$actualService = self::getClassName()::$methodName();

		$this->assertSame( $expectedService, $actualService,
			'should return service from MediaWikiServices' );
	}

	/** @dataProvider provideMethods */
	public function testMethodWithCustomServiceContainer( ReflectionMethod $method ): void {
		$methodName = $method->getName();
		$serviceName = $this->serviceNamePrefix . substr( $methodName, strlen( 'get' ) );
		$expectedService = $this->createValue( $method->getReturnType() );
		$services = $this->createMock( ContainerInterface::class );
		$services->expects( $this->once() )
			->method( 'get' )
			->with( $serviceName )
			->willReturn( $expectedService );

		$actualService = self::getClassName()::$methodName( $services );

		$this->assertSame( $expectedService, $actualService,
			'should return service from injected container' );
	}

	public static function provideMethods(): iterable {
		$reflectionClass = new ReflectionClass( self::getClassName() );
		$methods = $reflectionClass->getMethods();

		foreach ( $methods as $method ) {
			if ( $method->isConstructor() ) {
				continue;
			}
			yield $method->getName() => [ $method ];
		}
	}

	private function createValue( ReflectionType $type ) {
		// (in PHP 8.0, account for $type being a ReflectionUnionType here)
		$this->assertInstanceOf( ReflectionNamedType::class, $type );
		/** @var ReflectionNamedType $type */
		if ( $type->allowsNull() ) {
			return null;
		}
		if ( $type->isBuiltin() ) {
			switch ( $type->getName() ) {
				case 'bool':
					return true;
				case 'int':
					return 0;
				case 'float':
					return 0.0;
				case 'string':
					return '';
				case 'array':
				case 'iterable':
					return [];
				case 'callable':
					return 'is_null';
				default:
					$this->fail( "unknown builtin type {$type->getName()}" );
			}
		}
		return $this->createMock( $type->getName() );
	}

	public function testMethodsExist(): void {
		if ( $this->serviceNamePrefix === '' ) {
			return;
		}

		$reflectionClass = new ReflectionClass( self::getClassName() );
		foreach ( $this->getServiceContainer()->getServiceNames() as $serviceName ) {
			if ( in_array( $serviceName, $this->serviceNamesWithoutMethods, true ) ) {
				continue;
			}
			if ( str_starts_with( $serviceName, $this->serviceNamePrefix ) ) {
				$serviceNameSuffix = substr( $serviceName, strlen( $this->serviceNamePrefix ) );
				$_ = $reflectionClass->getMethod( 'get' . $serviceNameSuffix ); // should not throw
			}
		}

		$this->assertTrue( true, 'test did not throw' );
	}

	private static function getClassName(): string {
		// Temporary get the path depending of the property state - T393207
		static $cache = [];
		if ( !isset( $cache[static::class] ) ) {
			$reflectionProperty = new ReflectionProperty( static::class, 'className' );
			$reflectionProperty->setAccessible( true );
			$invokeObject = null;
			if ( !$reflectionProperty->isStatic() ) {
				$invokeObject = new static();
			}
			$cache[static::class] = $reflectionProperty->getValue( $invokeObject );
		}
		return $cache[static::class];
	}
}
