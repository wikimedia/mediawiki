<?php

namespace MediaWiki\Tests\Maintenance;

use Maintenance;
use MediaWikiTestCase;

/**
 * Makes parts of Maintenance class API visible for testing, and makes up for a
 * stream closing hack in Maintenance.php.
 *
 * This class is solely used for being able to test Maintenance right now
 * without having to apply major refactorings to fix some design issues in
 * Maintenance.php. Before adding more functions here, please consider whether
 * this approach is correct, or a refactoring Maintenance to separate concerns
 * is more appropriate.
 *
 * Upon refactoring, keep in mind that besides the maintenance scripts themselves
 * and tests right here, some extensions including Extension:Maintenance make
 * use of the Maintenance class.
 *
 * Due to a hack in Maintenance.php using register_shutdown_function, be sure to
 * call simulateShutdown on MaintenanceFixup instance before a test ends.
 *
 * FIXME:
 * It would be great if we were able to use PHPUnit's getMockForAbstractClass
 * instead of the MaintenanceFixup hack below. However, we cannot do so
 * without changing method visibility and without working around hacks in
 * Maintenance.php
 *
 * For the same reason, we cannot just use FakeMaintenance.
 */
class MaintenanceFixup extends Maintenance {

	// --- Making up for the register_shutdown_function hack in Maintenance.php

	/**
	 * The test case that generated this instance.
	 *
	 * This member is motivated by allowing the destructor to check whether or not
	 * the test failed, in order to avoid unnecessary nags about omitted shutdown
	 * simulation.
	 * But as it is already available, we also usi it to flagging tests as failed
	 *
	 * @var MediaWikiTestCase
	 */
	private $testCase;

	/**
	 * shutdownSimulated === true if simulateShutdown has done its work
	 *
	 * @var bool
	 */
	private $shutdownSimulated = false;

	/**
	 * Simulates what Maintenance wants to happen at script's end.
	 */
	public function simulateShutdown() {
		if ( $this->shutdownSimulated ) {
			$this->testCase->fail( __METHOD__ . " called more than once" );
		}

		// The cleanup action.
		$this->outputChanneled( false );

		// Bookkeeping that we simulated the clean up.
		$this->shutdownSimulated = true;
	}

	// Note that the "public" here does not change visibility
	public function outputChanneled( $msg, $channel = null ) {
		if ( $this->shutdownSimulated ) {
			if ( $msg !== false ) {
				$this->testCase->fail( "Already past simulated shutdown, but msg is "
					. "not false. Did the hack in Maintenance.php change? Please "
					. "adapt the test case or Maintenance.php" );
			}

			// The current call is the one registered via register_shutdown_function.
			// We can safely ignore it, as we simulated this one via simulateShutdown
			// before (if we did not, the destructor of this instance will warn about
			// it)
			return;
		}

		call_user_func_array( [ "parent", __FUNCTION__ ], func_get_args() );
	}

	/**
	 * Safety net around register_shutdown_function of Maintenance.php
	 */
	public function __destruct() {
		if ( !$this->shutdownSimulated ) {
			// Someone generated a MaintenanceFixup instance without calling
			// simulateShutdown. We'd have to raise a PHPUnit exception to correctly
			// flag this illegal usage. However, we are already in a destruktor, which
			// would trigger undefined behavior. Hence, we can only report to the
			// error output :( Hopefully people read the PHPUnit output.
			$name = $this->testCase->getName();
			fwrite( STDERR, "ERROR! Instance of " . __CLASS__ . " for test $name "
				. "destructed without calling simulateShutdown method. Call "
				. "simulateShutdown on the instance before it gets destructed." );
		}

		// The following guard is required, as PHP does not offer default destructors :(
		if ( is_callable( "parent::__destruct" ) ) {
			parent::__destruct();
		}
	}

	public function __construct( MediaWikiTestCase $testCase ) {
		parent::__construct();
		$this->testCase = $testCase;
	}

	// --- Making protected functions visible for test

	public function output( $out, $channel = null ) {
		// Just to make PHP not nag about signature mismatches, we copied
		// Maintenance::output signature. However, we do not use (or rely on)
		// those variables. Instead we pass to Maintenance::output whatever we
		// receive at runtime.
		return call_user_func_array( [ "parent", __FUNCTION__ ], func_get_args() );
	}

	public function addOption( $name, $description, $required = false,
		$withArg = false, $shortName = false, $multiOccurance = false
	) {
		return call_user_func_array( [ "parent", __FUNCTION__ ], func_get_args() );
	}

	public function getOption( $name, $default = null ) {
		return call_user_func_array( [ "parent", __FUNCTION__ ], func_get_args() );
	}

	// --- Requirements for getting instance of abstract class

	public function execute() {
		$this->testCase->fail( __METHOD__ . " called unexpectedly" );
	}
}
