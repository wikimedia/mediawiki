<?php

/**
 * Use this trait to check that code run by tests accesses every key declared for this class'
 * ServiceOptions, e.g., in a CONSTRUCTOR_OPTIONS member const. To use this trait, you need to do
 * two things (other than use-ing it):
 *
 * 1) Don't use the regular ServiceOptions when constructing your objects, but rather
 * LoggedServiceOptions. These are used the same as ServiceOptions, except in the constructor, pass
 * self::$serviceOptionsAccessLog before the regular arguments.
 *
 * 2) Make a test that calls assertAllServiceOptionsUsed(). If some ServiceOptions keys are not yet
 * accessed in tests but actually are used by the class, pass their names as an argument.
 *
 * Currently we support only one ServiceOptions per test class.
 */
trait TestAllServiceOptionsUsed {
	/** @var array [ expected keys (as list), keys accessed so far (as dictionary) ] */
	private static $serviceOptionsAccessLog = [];

	/**
	 * @param string[] $expectedUnused Options that we know are not yet tested
	 */
	public function assertAllServiceOptionsUsed( array $expectedUnused = [] ) {
		$this->assertNotEmpty( self::$serviceOptionsAccessLog,
			'You need to pass LoggedServiceOptions to your class instead of ServiceOptions ' .
			'for TestAllServiceOptionsUsed to work.'
		);

		list( $expected, $actual ) = self::$serviceOptionsAccessLog;

		$expected = array_diff( $expected, $expectedUnused );

		$this->assertSame(
			[],
			array_diff( $expected, array_keys( $actual ) ),
			"Some ServiceOptions keys were not accessed in tests. If they really aren't used, " .
			"remove them from the class' option list. If they are used, add tests to cover them, " .
			"or ignore the problem for now by passing them to assertAllServiceOptionsUsed() in " .
			"its \$expectedUnused argument."
		);

		if ( $expectedUnused ) {
			$this->markTestIncomplete( 'Some ServiceOptions keys are not yet accessed by tests: ' .
				implode( ', ', $expectedUnused ) );
		}
	}
}
