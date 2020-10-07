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
		$calls = 0;
		if ( is_callable( [ $this, 'setUp' ] ) ) {
			try {
				$this->setUp();
			} catch ( \Throwable $_ ) {
				// FIXME handle
			}
			$calls++;
		}
		$res = parent::run( $result );
		if ( is_callable( [ $this, 'tearDown' ] ) ) {
			try {
				$this->tearDown();
			} catch ( \Throwable $_ ) {
				// FIXME handle
			}
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
