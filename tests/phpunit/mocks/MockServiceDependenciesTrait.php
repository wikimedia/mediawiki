<?php

namespace MediaWiki\Tests\Unit;

use ReflectionClass;
use ReflectionParameter;

/**
 * Helper trait for instantiating MW service object for testing
 * with mocked dependencies.
 *
 */
trait MockServiceDependenciesTrait {

	/**
	 * Construct a new instance of $serviceClass with all constructor arguments
	 * mocked. $parameterOverrides allows to provide some constructor argument.
	 *
	 * @param string $serviceClass
	 * @param array $parameterOverrides [ argument name => argument value ]
	 * @return mixed
	 */
	protected function newServiceInstance(
		string $serviceClass,
		array $parameterOverrides
	) {
		$params = [];
		$reflectionClass = new ReflectionClass( $serviceClass );
		$constructor = $reflectionClass->getConstructor();
		foreach ( $constructor->getParameters() as $parameter ) {
			$params[] = $parameterOverrides[$parameter->getName()]
				?? $this->getMockValueForParam( $parameter );
		}
		return new $serviceClass( ...$params );
	}

	/**
	 * Override if this doesn't produce suitable values for one or more of the parameters to your
	 * factory constructor or create method.
	 *
	 * @param ReflectionParameter $param One of the factory constructor's arguments
	 * @return mixed A value to pass that will allow the object to be constructed successfully
	 */
	private function getMockValueForParam( ReflectionParameter $param ) {
		$pos = $param->getPosition();
		$type = $param->getType();
		if ( !$type || $type->getName() === 'string' ) {
			// Optimistically assume a string is okay
			return "some unlikely string $pos";
		}
		$type = $type->getName();
		if ( $type === 'array' || $type === 'iterable' ) {
			return [ "some unlikely string $pos" ];
		}

		if ( class_exists( $type ) || interface_exists( $type ) ) {
			return $this->createMock( $type );
		}

		$this->fail( "Unrecognized parameter type $type" );
	}
}
