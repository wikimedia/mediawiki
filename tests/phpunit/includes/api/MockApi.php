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

	/** @inheritDoc */
	public function getModulePath() {
		return $this->getModuleName();
	}

	/** @inheritDoc */
	public function addWarning( $warning, $code = null, $data = null ) {
		$this->warnings[] = $warning;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'filename' => null,
			'enablechunks' => false,
			'sessionkey' => null,
		];
	}
}
