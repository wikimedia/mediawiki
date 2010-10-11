<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	echo <<<EOF
You are running these tests directly from phpunit. You may not have all globals correctly set.
Running phpunit.php instead is recommended.
EOF;
	require_once ( dirname( __FILE__ ) . "/phpunit.php" );
}

// Output a notice when running with older versions of PHPUnit
if ( !version_compare( PHPUnit_Runner_Version::id(), "3.4.1", ">" ) ) {
  echo <<<EOF
********************************************************************************

These tests run best with version PHPUnit 3.4.2 or better. Earlier versions may
show failures because earlier versions of PHPUnit do not properly implement
dependencies.

********************************************************************************

EOF;
}

/* Classes */

abstract class MediaWikiTestSetup extends PHPUnit_Framework_TestCase {
	protected $suite;
	public $regex = '';
	public $runDisabled = false;


	function __construct( PHPUnit_Framework_TestSuite $suite = null ) {
		if ( null !== $suite ) {
			$this->suite = $suite;
		}
			}

	function __call( $func, $args ) {
		if ( method_exists( $this->suite, $func ) ) {
			return call_user_func_array( array( $tohis->suite, $func ), $args);
		} else {
			throw new MWException( "Called non-existant $func method on "
				. get_class( $this ) );
		}
		return false;
	}
}

