<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiQueryBase;

class MockApiQueryBase extends ApiQueryBase {
	/** @var string */
	private $name;

	public function execute() {
	}

	public function __construct( string $name = 'mock' ) {
		$this->name = $name;
	}

	/** @inheritDoc */
	public function getModuleName() {
		return $this->name;
	}

	/** @inheritDoc */
	public function getModulePath() {
		return 'query+' . $this->getModuleName();
	}
}
