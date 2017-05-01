<?php
class MockApiQueryBase extends ApiQueryBase {
	private $name;

	public function execute() {
	}

	public function __construct( $name = 'mock' ) {
		$this->name = $name;
	}

	public function getModuleName() {
		return $this->name;
	}

	public function getModulePath() {
		return 'query+' . $this->getModuleName();
	}
}
