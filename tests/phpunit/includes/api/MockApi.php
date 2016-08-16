<?php

class MockApi extends ApiBase {
	public $warnings = [];

	public function execute() {
	}

	public function __construct() {
	}

	public function setWarning( $warning ) {
		$this->warnings[] = $warning;
	}

	public function getAllowedParams() {
		return [
			'filename' => null,
			'enablechunks' => false,
			'sessionkey' => null,
		];
	}
}
