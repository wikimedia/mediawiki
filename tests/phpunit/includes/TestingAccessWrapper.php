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

	public function __set( $name, $value ) {
		$classReflection = new ReflectionClass( $this->object );
		$propertyReflection = $classReflection->getProperty( $name );
		$propertyReflection->setAccessible( true );
		$propertyReflection->setValue( $this->object, $value );
	}

	public function __get( $name ) {
		$classReflection = new ReflectionClass( $this->object );
		$propertyReflection = $classReflection->getProperty( $name );
		$propertyReflection->setAccessible( true );
		return $propertyReflection->getValue( $this->object );
	}
}
