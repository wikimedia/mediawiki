<?php

/**
 * Test that a factory class correctly forwards all arguments to the class it constructs. This is
 * useful because sometimes a class' constructor will have more arguments added, and it's easy to
 * accidentally have the factory's constructor fall out of sync.
 */
trait FactoryArgTestTrait {
	/**
	 * @return string Name of factory class
	 */
	abstract protected static function getFactoryClass();

	/**
	 * @return string Name of instance class
	 */
	abstract protected static function getInstanceClass();

	/**
	 * @return int The number of arguments that the instance constructor receives but the factory
	 * constructor doesn't. Used for a simple argument count check. Override if this isn't zero.
	 */
	protected static function getExtraClassArgCount() {
		return 0;
	}

	/**
	 * Override if your factory method name is different from newInstanceClassName.
	 *
	 * @return string
	 */
	protected function getFactoryMethodName() {
		return 'new' . $this->getInstanceClass();
	}

	/**
	 * Override if $factory->$method( ...$args ) isn't the right way to create an instance, where
	 * $method is returned from getFactoryMethodName(), and $args is constructed by applying
	 * getMockValueForParam() to the factory method's parameters.
	 *
	 * @param object $factory Factory object
	 * @return object Object created by factory
	 */
	protected function createInstanceFromFactory( $factory ) {
		$methodName = $this->getFactoryMethodName();
		$methodObj = new ReflectionMethod( $factory, $methodName );
		$mocks = [];
		foreach ( $methodObj->getParameters() as $param ) {
			$mocks[] = $this->getMockValueForParam( $param );
		}

		return $factory->$methodName( ...$mocks );
	}

	public function testConstructorArgNum() {
		$factoryClass = static::getFactoryClass();
		$instanceClass = static::getInstanceClass();
		$factoryConstructor = new ReflectionMethod( $factoryClass, '__construct' );
		$instanceConstructor = new ReflectionMethod( $instanceClass, '__construct' );
		$this->assertSame(
			$instanceConstructor->getNumberOfParameters() - static::getExtraClassArgCount(),
			$factoryConstructor->getNumberOfParameters(),
			"$instanceClass and $factoryClass constructors have an inconsistent number of " .
			' parameters. Did you add a parameter to one and not the other?' );
	}

	/**
	 * Override if getMockValueForParam doesn't produce suitable values for one or more of the
	 * parameters to your factory constructor or create method.
	 *
	 * @param ReflectionParameter $param One of the factory constructor's arguments
	 * @return array Empty to not override, or an array of one element which is the value to pass
	 *   that will allow the object to be constructed successfully
	 */
	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		return [];
	}

	/**
	 * Override if this doesn't produce suitable values for one or more of the parameters to your
	 * factory constructor or create method.
	 *
	 * @param ReflectionParameter $param One of the factory constructor's arguments
	 * @return mixed A value to pass that will allow the object to be constructed successfully
	 */
	protected function getMockValueForParam( ReflectionParameter $param ) {
		$overridden = $this->getOverriddenMockValueForParam( $param );
		if ( $overridden ) {
			return $overridden[0];
		}

		$pos = $param->getPosition();

		$type = (string)$param->getType();

		if ( $type === 'array' ) {
			return [ "some unlikely string $pos" ];
		}

		if ( class_exists( $type ) || interface_exists( $type ) ) {
			return $this->createMock( $type );
		}

		if ( $type === '' ) {
			// Optimistically assume a string is okay
			return "some unlikely string $pos";
		}

		$this->fail( "Unrecognized parameter type $type" );
	}

	/**
	 * Assert that the given $instance correctly received $val as the value for parameter $name. By
	 * default, checks that the instance has some member whose value is the same as $val.
	 *
	 * @param object $instance
	 * @param string $name Name of parameter to the factory object's constructor
	 * @param mixed $val
	 */
	protected function assertInstanceReceivedParam( $instance, $name, $val ) {
		foreach ( ( new ReflectionObject( $instance ) )->getProperties() as $prop ) {
			$prop->setAccessible( true );
			if ( $prop->getValue( $instance ) === $val ) {
				$this->assertTrue( true );
				return;
			}
		}

		$this->assertFalse( true, "Param $name not received by " . static::getInstanceClass() );
	}

	public function testAllArgumentsWerePassed() {
		$factoryClass = static::getFactoryClass();

		$factoryConstructor = new ReflectionMethod( $factoryClass, '__construct' );
		$mocks = [];
		foreach ( $factoryConstructor->getParameters() as $param ) {
			$mocks[$param->getName()] = $this->getMockValueForParam( $param );
		}

		$instance =
			$this->createInstanceFromFactory( new $factoryClass( ...array_values( $mocks ) ) );

		foreach ( $mocks as $name => $mock ) {
			$this->assertInstanceReceivedParam( $instance, $name, $mock );
		}
	}
}
