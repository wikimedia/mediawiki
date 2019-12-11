<?php

use PHPUnit\Framework\TestResult;

/**
 * Trait that returns to PHPUnit test suites the support for setUp/tearDown events that
 * was removed in PHPUnit 8.
 *
 * @since 1.35
 */
trait SuiteEventsTrait {
	/**
	 * @inheritDoc
	 */
	public function run( TestResult $result = null ) : TestResult {
		if ( \PHPUnit\Runner\Version::id()[0] !== '8' ) {
			// Temporary until we upgrade to PHPUnit 8 for real
			return parent::run( $result );
		}
		$calls = 0;
		if ( is_callable( [ $this, 'setUp' ] ) ) {
			$this->setUp();
			$calls++;
		}
		$res = parent::run( $result );
		if ( is_callable( [ $this, 'tearDown' ] ) ) {
			$this->tearDown();
			$calls++;
		}
		if ( !$calls ) {
			throw new LogicException(
				get_class( $this )
				. " uses neither setUp() nor tearDown(), so it doesn't need SuiteEventsTrait"
			);
		}
		return $res;
	}
}
