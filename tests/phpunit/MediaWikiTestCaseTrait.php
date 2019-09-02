<?php

/**
 * For code common to both MediaWikiUnitTestCase and MediaWikiIntegrationTestCase.
 */
trait MediaWikiTestCaseTrait {
	/**
	 * Returns a PHPUnit constraint that matches anything other than a fixed set of values. This can
	 * be used to whitelist values, e.g.
	 *   $mock->expects( $this->never() )->method( $this->anythingBut( 'foo', 'bar' ) );
	 * which will throw if any unexpected method is called.
	 *
	 * @param mixed ...$values Values that are not matched
	 */
	protected function anythingBut( ...$values ) {
		return $this->logicalNot( $this->logicalOr(
			...array_map( [ $this, 'matches' ], $values )
		) );
	}

	/**
	 * Return a PHPUnit mock that is expected to never have any methods called on it.
	 *
	 * @param string $type
	 * @return object
	 */
	protected function createNoOpMock( $type ) {
		$mock = $this->createMock( $type );
		$mock->expects( $this->never() )->method( $this->anythingBut( '__destruct' ) );
		return $mock;
	}
}
