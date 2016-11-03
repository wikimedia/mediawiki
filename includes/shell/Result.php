<?php

namespace MediaWiki\Shell;

class Result {
	/** @var int */
	private $statusCode;

	/** @var string */
	private $stdout;

	public function __construct( $statusCode, $stdout ) {
		$this->statusCode = $statusCode;
		$this->stdout = $stdout;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function getStdout() {
		return $this->stdout;
	}
}
