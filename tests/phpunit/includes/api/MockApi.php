<?php

class MockApi extends ApiBase {
	public function execute() {
	}

	public function __construct() {
	}

	public function getAllowedParams() {
		return array(
			'filename' => null,
			'enablechunks' => false,
			'sessionkey' => null,
		);
	}
}
