<?php

class TestDeprecatedSubclass extends TestDeprecatedClass {

	private $subclassPrivateNondeprecated = 1;

	public function getDeprecatedPrivateParentProperty() {
		return $this->privateDeprecated;
	}

	public function setDeprecatedPrivateParentProperty( $value ) {
		$this->privateDeprecated = $value;
	}

	public function getNondeprecatedPrivateParentProperty() {
		return $this->privateNonDeprecated;
	}

	public function setNondeprecatedPrivateParentProperty( $value ) {
		$this->privateNonDeprecated = $value;
	}

}
