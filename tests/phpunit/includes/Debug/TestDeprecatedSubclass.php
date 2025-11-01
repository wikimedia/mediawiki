<?php

class TestDeprecatedSubclass extends TestDeprecatedClass {

	/** @var int */
	private $subclassPrivateNondeprecated = 1;

	/**
	 * @return mixed
	 */
	public function getDeprecatedPrivateParentProperty() {
		return $this->privateDeprecated;
	}

	/**
	 * @param mixed $value
	 */
	public function setDeprecatedPrivateParentProperty( $value ) {
		$this->privateDeprecated = $value;
	}

	/**
	 * @return mixed
	 */
	public function getNondeprecatedPrivateParentProperty() {
		return $this->privateNonDeprecated;
	}

	/**
	 * @param mixed $value
	 */
	public function setNondeprecatedPrivateParentProperty( $value ) {
		$this->privateNonDeprecated = $value;
	}

}
