<?php
/**
 * Circumvent access restrictions on object internals
 *
 * This can be helpful for writing tests that can probe object internals,
 * without having to modify the class under test to accomodate.
 *
 * Wrap an object with private methods as follows:
 *    $title = TestingAccessWrapper::newFromObject( Title::newFromDBkey( $key ) );
 *
 * You can access private and protected instance methods and variables:
 *    $formatter = $title->getTitleFormatter();
 *
 * TODO:
 * - Organize other helper classes in tests/testHelpers.inc into a directory.
 */
class TestingAccessWrapper {
	/** @var mixed The object, or the class name for static-only access */
	public $object;

	/**
	 * Return the same object, without access restrictions.
	 */
	public static function newFromObject( $object ) {
		if ( !is_object( $object ) ) {
			throw new InvalidArgumentException( __METHOD__ . ' must be called with an object' );
		}
		$wrapper = new TestingAccessWrapper();
		$wrapper->object = $object;
		return $wrapper;
	}

	/**
	 * Allow access to non-public static methods and properties of the class.
	 * Use non-static access,
	 */
	public static function newFromClass( $className ) {
		if ( !is_string( $className ) ) {
			throw new InvalidArgumentException( __METHOD__ . ' must be called with a class name' );
		}
		$wrapper = new TestingAccessWrapper();
		$wrapper->object = $className;
		return $wrapper;
	}

	public function __call( $method, $args ) {
		$methodReflection = $this->getMethod( $method );

		if ( $this->isStatic() && !$methodReflection->isStatic() ) {
			throw new DomainException( __METHOD__ . ': Cannot call non-static when wrapping static class' );
		}

		return $methodReflection->invokeArgs( $methodReflection->isStatic() ? null : $this->object,
			$args );
	}

	public function __set( $name, $value ) {
		$propertyReflection = $this->getProperty( $name );

		if ( $this->isStatic() && !$propertyReflection->isStatic() ) {
			throw new DomainException( __METHOD__ . ': Cannot set property when wrapping static class' );
		}

		$propertyReflection->setValue( $this->object, $value );
	}

	public function __get( $name ) {
		$propertyReflection = $this->getProperty( $name );

		if ( $this->isStatic() && !$propertyReflection->isStatic() ) {
			throw new DomainException( __METHOD__ . ': Cannot get property when wrapping static class' );
		}

		return $propertyReflection->getValue( $this->object );
	}

	private function isStatic() {
		return is_string( $this->object );
	}

	/**
	 * Return a property and make it accessible.
	 * @param string $name
	 * @return ReflectionMethod
	 */
	private function getMethod( $name ) {
		$classReflection = new ReflectionClass( $this->object );
		$methodReflection = $classReflection->getMethod( $name );
		$methodReflection->setAccessible( true );
		return $methodReflection;
	}

	/**
	 * Return a property and make it accessible.
	 *
	 * ReflectionClass::getProperty() fails if the private property is defined
	 * in a parent class. This works more like ReflectionClass::getMethod().
	 *
	 * @param string $name
	 * @return ReflectionProperty
	 * @throws ReflectionException
	 */
	private function getProperty( $name ) {
		$classReflection = new ReflectionClass( $this->object );
		try {
			$propertyReflection = $classReflection->getProperty( $name );
		} catch ( ReflectionException $ex ) {
			while ( true ) {
				$classReflection = $classReflection->getParentClass();
				if ( !$classReflection ) {
					throw $ex;
				}
				try {
					$propertyReflection = $classReflection->getProperty( $name );
				} catch ( ReflectionException $ex2 ) {
					continue;
				}
				if ( $propertyReflection->isPrivate() ) {
					break;
				} else {
					throw $ex;
				}
			}
		}
		$propertyReflection->setAccessible( true );
		return $propertyReflection;
	}
}
