<?php

class WellProtectedParentClass {
	private $privateParentProperty;

	public function __construct() {
		$this->privateParentProperty = 9000;
	}

	private function incrementPrivateParentPropertyValue() {
		$this->privateParentProperty++;
	}

	public function getPrivateParentProperty() {
		return $this->privateParentProperty;
	}
}

class WellProtectedClass extends WellProtectedParentClass {
	protected static $staticProperty = 'sp';
	private static $staticPrivateProperty = 'spp';

	protected $property;
	private $privateProperty;

	protected static function staticMethod() {
		return 'sm';
	}

	private static function staticPrivateMethod() {
		return 'spm';
	}

	public function __construct() {
		parent::__construct();
		$this->property = 1;
		$this->privateProperty = 42;
	}

	protected function incrementPropertyValue() {
		$this->property++;
	}

	private function incrementPrivatePropertyValue() {
		$this->privateProperty++;
	}

	public function getProperty() {
		return $this->property;
	}

	public function getPrivateProperty() {
		return $this->privateProperty;
	}

	protected function whatSecondArg( $a, $b = false ) {
		return $b;
	}
}
