<?php
declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

class PhpUnitSlowTest {

	private int $duration;
	private string $test;

	public function __construct( int $duration, string $test ) {
		$this->duration = $duration;
		$this->test = $test;
	}

	public function getDuration(): int {
		return $this->duration;
	}

	public function getTest(): string {
		return $this->test;
	}

}
