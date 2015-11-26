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
 * - Provide access to static methods and properties.
 * - Organize other helper classes in tests/testHelpers.inc into a directory.
 */
class TestingAccessWrapper {
	public $object;

	/**
	 * Return the same object, without access restrictions.
	 */
	public static function newFromObject( $object ) {
		$wrapper = new TestingAccessWrapper();
		$wrapper->object = $object;
		return $wrapper;
	}

	public function __call( $method, $args ) {
		$classReflection = new ReflectionClass( $this->object );
		$methodReflection = $classReflection->getMethod( $method );
		$methodReflection->setAccessible( true );
		return $methodReflection->invokeArgs( $this->object, $args );
	}

	/**
	 * ReflectionClass::getProperty() fails if the private property is defined
	 * in a parent class. This works more like ReflectionClass::getMethod().
	 */
	private function getProperty( $name ) {
		$classReflection = new ReflectionClass( $this->object );
		try {
			return $classReflection->getProperty( $name );
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
					return $propertyReflection;
				} else {
					throw $ex;
				}
			}
		}
	}

	public function __set( $name, $value ) {
		$propertyReflection = $this->getProperty( $name );
		$propertyReflection->setAccessible( true );
		$propertyReflection->setValue( $this->object, $value );
	}

	public function __get( $name ) {
		$propertyReflection = $this->getProperty( $name );
		$propertyReflection->setAccessible( true );
		return $propertyReflection->getValue( $this->object );
	}
}
