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
	 * @return PHPUnit_Framework_Constraint
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

	/**
	 * Don't throw a warning if $function is deprecated and called later
	 *
	 * @since 1.19
	 *
	 * @param string $function
	 */
	public function hideDeprecated( $function ) {
		Wikimedia\suppressWarnings();
		wfDeprecated( $function );
		Wikimedia\restoreWarnings();
	}

	/**
	 * Check whether file contains given data.
	 * @param string $fileName
	 * @param string $actualData
	 * @param bool $createIfMissing If true, and file does not exist, create it with given data
	 *                              and skip the test.
	 * @param string $msg
	 * @since 1.30
	 */
	protected function assertFileContains(
		$fileName,
		$actualData,
		$createIfMissing = false,
		$msg = ''
	) {
		if ( $createIfMissing ) {
			if ( !file_exists( $fileName ) ) {
				file_put_contents( $fileName, $actualData );
				$this->markTestSkipped( "Data file $fileName does not exist" );
			}
		} else {
			$this->assertFileExists( $fileName );
		}
		$this->assertEquals( file_get_contents( $fileName ), $actualData, $msg );
	}

	/**
	 * Assert that two arrays are equal. By default this means that both arrays need to hold
	 * the same set of values. Using additional arguments, order and associated key can also
	 * be set as relevant.
	 *
	 * @since 1.20
	 *
	 * @param array $expected
	 * @param array $actual
	 * @param bool $ordered If the order of the values should match
	 * @param bool $named If the keys should match
	 */
	protected function assertArrayEquals(
		array $expected, array $actual, $ordered = false, $named = false
	) {
		if ( !$ordered ) {
			$this->objectAssociativeSort( $expected );
			$this->objectAssociativeSort( $actual );
		}

		if ( !$named ) {
			$expected = array_values( $expected );
			$actual = array_values( $actual );
		}

		call_user_func_array(
			[ $this, 'assertEquals' ],
			array_merge( [ $expected, $actual ], array_slice( func_get_args(), 4 ) )
		);
	}

	/**
	 * Does an associative sort that works for objects.
	 *
	 * @since 1.20
	 *
	 * @param array &$array
	 */
	protected function objectAssociativeSort( array &$array ) {
		uasort(
			$array,
			function ( $a, $b ) {
				return serialize( $a ) <=> serialize( $b );
			}
		);
	}
}
