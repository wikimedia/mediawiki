<?php

class MockApi extends ApiBase {
	public $warnings = [];

	public function execute() {
	}

	public function __construct() {
	}

	public function getModulePath() {
		return $this->getModuleName();
	}

	public function addWarning( $warning, $code = null, $data = null ) {
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
