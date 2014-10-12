<?php
/**
 * Break access restrictions to manipulate class internals.
 *
 * TODO: Looks like there are friends in tests/testHelpers.inc, but these
 * should be broken out into a subdirectory somewhere.
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
		$methodReflection->setAccessible( ReflectionMethod::IS_PUBLIC );
		return $methodReflection->invoke( $this->object, $args );
	}

	public function __set( $name, $value ) {
		$classReflection = new ReflectionClass( $this->object );
		$propertyReflection = $classReflection->getProperty( $name );
		$propertyReflection->setAccessible( ReflectionProperty::IS_PUBLIC );
		$propertyReflection->setValue( $this->object, $value );
	}

	public function __get( $name ) {
		$classReflection = new ReflectionClass( $this->object );
		$propertyReflection = $classReflection->getProperty( $name );
		$propertyReflection->setAccessible( ReflectionProperty::IS_PUBLIC );
		return $propertyReflection->getValue( $this->object );
	}
}
