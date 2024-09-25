<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiBase;

class MockApi extends ApiBase {
	/** @var array */
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
