<?php

namespace Wikimedia\Reflection;

/**
 * This class used to contain a $privateField, $protectedField and $publicField.
 * This is used to test that unserialized instances still have the values of
 * these ghost fields and the values can be accessed with GhostFieldAccessTrait.
 * @package Wikimedia\Reflection
 */
class GhostFieldTestClass {
	use GhostFieldAccessTrait;

	public function getPrivateField() {
		return $this->getGhostFieldValue( 'privateField' );
	}

	public function getProtectedField() {
		return $this->getGhostFieldValue( 'protectedField' );
	}

	public function getPublicField() {
		return $this->getGhostFieldValue( 'publicField' );
	}
}
