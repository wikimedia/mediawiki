<?php

namespace Wikimedia\Tests\Reflection;

use Wikimedia\Reflection\GhostFieldAccessTrait;
use Wikimedia\Reflection\GhostFieldTestClass as OldGhostFieldTestClass;

/**
 * This class used to contain a $privateField, $protectedField and $publicField.
 * This is used to test that unserialized instances still have the values of
 * these ghost fields and the values can be accessed with GhostFieldAccessTrait.
 *
 */
#[\AllowDynamicProperties]
class GhostFieldTestClass {
	use GhostFieldAccessTrait;

	public function getPrivateField() {
		return $this->getGhostFieldValue( 'privateField', OldGhostFieldTestClass::class );
	}

	public function getProtectedField() {
		return $this->getGhostFieldValue( 'protectedField' );
	}

	public function getPublicField() {
		return $this->getGhostFieldValue( 'publicField' );
	}
}
// Do not delete this alias; it is needed for GhostFieldAccessTraitTest
class_alias( GhostFieldTestClass::class, 'Wikimedia\\Reflection\\GhostFieldTestClass' );
