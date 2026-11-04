<?php

declare( strict_types=1 );

namespace MediaWiki\Tests;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
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
 * Other extensions use instance methods instead.
 * Callers create an instance with a static wrap() method:
 *
 * ```php
 * public static function wrap( MediaWikiServices $services ): self {
 * 	return new self( $services );
 * }
 *
 * public function getService1(): Service1 {
 * 	return $this->services->get( 'ExtensionName.Service1' );
 * }
 * ```
 *
 * To test an ExtensionServices class,
 * create a subclass of this test base class and specify $className and $serviceNamePrefix.
 * For a class with a wrap() method, also set $wrapsServiceContainer to true.
 *
 * @license GPL-2.0-or-later
 */
abstract class ExtensionServicesTestBase extends MediaWikiIntegrationTestCase {

	/**
	 * @var string The name of the ExtensionServices class.
	 * (A fully qualified name, usually specified via ::class syntax.)
	 */
	protected static string $className;

	/**
	 * @var bool Set to true if the ExtensionServices class has instance getter methods,
	 * and callers create an instance with ClassName::wrap( MediaWikiServices $services ).
	 * If false, the getter methods must be static.
	 */
	protected static bool $wrapsServiceContainer = false;

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

	/**
	 * @var string[] An optional map from getter method names to service names,
	 * for getter methods whose service name is not the {@link self::$serviceNamePrefix}
	 * followed by the method name without 'get'.
	 */
	protected array $serviceNamesByMethod = [];

	/** @dataProvider provideMethods */
	public function testMethodSignature( ReflectionMethod $method ): void {
		$this->assertTrue( $method->isPublic(),
			'service accessor must be public' );
		if ( static::$wrapsServiceContainer ) {
			$this->assertFalse( $method->isStatic(),
				'service accessor must not be static' );
		} else {
			$this->assertTrue( $method->isStatic(),
				'service accessor must be static' );
		}
		$this->assertStringStartsWith( 'get', $method->getName(),
			'service accessor must be a getter' );
		$this->assertTrue( $method->hasReturnType(),
			'service accessor must declare return type' );
	}

	/** @dataProvider provideMethods */
	public function testMethodWithDefaultServiceContainer( ReflectionMethod $method ): void {
		$methodName = $method->getName();
		$serviceName = $this->getServiceName( $methodName );
		$expectedService = $this->createValue( $method->getReturnType() );
		$this->setService( $serviceName, $expectedService );

		if ( static::$wrapsServiceContainer ) {
			$actualService = static::$className::wrap( $this->getServiceContainer() )->$methodName();
		} else {
			$actualService = static::$className::$methodName();
		}

		$this->assertSame( $expectedService, $actualService,
			'should return service from MediaWikiServices' );
	}

	/** @dataProvider provideMethods */
	public function testMethodWithCustomServiceContainer( ReflectionMethod $method ): void {
		$methodName = $method->getName();
		$serviceName = $this->getServiceName( $methodName );
		$expectedService = $this->createValue( $method->getReturnType() );
		if ( static::$wrapsServiceContainer ) {
			// wrap() requires a MediaWikiServices object.
			// Mock only getService(), because the real get() method calls getService().
			$services = $this->createPartialMock( MediaWikiServices::class, [ 'getService' ] );
			$containerMethod = 'getService';
		} else {
			$services = $this->createMock( ContainerInterface::class );
			$containerMethod = 'get';
		}
		$services->expects( $this->once() )
			->method( $containerMethod )
			// Incorrect type hint in PHPUnit 9, fixed in PHPUnit 10
			// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal
			->with( $serviceName )
			->willReturn( $expectedService );

		if ( static::$wrapsServiceContainer ) {
			$actualService = static::$className::wrap( $services )->$methodName();
		} else {
			$actualService = static::$className::$methodName( $services );
		}

		$this->assertSame( $expectedService, $actualService,
			'should return service from injected container' );
	}

	public static function provideMethods(): iterable {
		$reflectionClass = new ReflectionClass( static::$className );
		$methods = $reflectionClass->getMethods();

		foreach ( $methods as $method ) {
			if ( $method->isConstructor() ) {
				continue;
			}
			if ( static::$wrapsServiceContainer && $method->getName() === 'wrap' ) {
				continue;
			}
			yield $method->getName() => [ $method ];
		}
	}

	private function getServiceName( string $methodName ): string {
		return $this->serviceNamesByMethod[$methodName]
			?? $this->serviceNamePrefix . substr( $methodName, strlen( 'get' ) );
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
		$reflectionClass = new ReflectionClass( static::$className );
		$methodNamesByService = array_flip( $this->serviceNamesByMethod );
		foreach ( $this->getServiceContainer()->getServiceNames() as $serviceName ) {
			if ( in_array( $serviceName, $this->serviceNamesWithoutMethods, true ) ) {
				continue;
			}
			if ( isset( $methodNamesByService[$serviceName] ) ) {
				$methodName = $methodNamesByService[$serviceName];
			} elseif ( $this->serviceNamePrefix !== '' && str_starts_with( $serviceName, $this->serviceNamePrefix ) ) {
				$methodName = 'get' . substr( $serviceName, strlen( $this->serviceNamePrefix ) );
			} else {
				continue;
			}
			$_ = $reflectionClass->getMethod( $methodName ); // should not throw
		}

		$this->assertTrue( true, 'test did not throw' );
	}
}
