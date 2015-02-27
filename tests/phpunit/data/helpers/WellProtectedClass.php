<?php

class WellProtectedClass {
	protected $property;

	public function __construct() {
		$this->property = 1;
	}

	protected function incrementPropertyValue() {
		$this->property++;
	}
}
