<?php

// It would be great if we were able to use PHPUnit's getMockForAbstractClass
// instead of the MaintenanceFixup hack below. However, we cannot do
// without changing the visibility and without working around hacks in
// Maintenance.php
//
// For the same reason, we cannot just use FakeMaintenance.

/**
 * makes parts of the API of Maintenance that is hidden by protected visibily
 * visible for testing, and makes up for a stream closing hack in Maintenance.php.
 *
 * This class is solely used for being able to test Maintenance right now
 * without having to apply major refactorings to fix some design issues in
 * Maintenance.php. Before adding more functions here, please consider whether
 * this approach is correct, or a refactoring Maintenance to separate concers
 * is more appropriate.
 *
 * Upon refactoring, keep in mind that besides the maintenance scrits themselves
 * and tests right here, also at least Extension:Maintenance make use of
 * Maintenance.
 *
 * Due to a hack in Maintenance.php using register_shutdown_function, be sure to
 * finally call simulateShutdown on MaintenanceFixup instance before a test
 * ends.
 *
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
	 * shutdownSimulated === true iff simulateShutdown has done it's work
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

		return call_user_func_array ( array( "parent", __FUNCTION__ ), func_get_args() );
	}

	/**
	 * Safety net around register_shutdown_function of Maintenance.php
	 */
	public function __destruct() {
		if ( ( ! $this->shutdownSimulated ) && ( ! $this->testCase->hasFailed() ) ) {
			// Someone generated a MaintenanceFixup instance without calling
			// simulateShutdown. We'd have to raise a PHPUnit exception to correctly
			// flag this illegal usage. However, we are already in a destruktor, which
			// would trigger undefined behaviour. Hence, we can only report to the
			// error output :( Hopefully people read the PHPUnit output.
			fwrite( STDERR, "ERROR! Instance of " . __CLASS__ . " destructed without "
				. "calling simulateShutdown method. Call simulateShutdown on the "
				. "instance before it gets destructed." );
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
		return call_user_func_array ( array( "parent", __FUNCTION__ ), func_get_args() );
	}



	// --- Requirements for getting instance of abstract class

	public function execute() {
		$this->testCase->fail( __METHOD__ . " called unexpectedly" );
	}

}

class MaintenanceTest extends MediaWikiTestCase {


	/**
	 * The main Maintenance instance that is used for testing.
	 *
	 * @var MaintenanceFixup
	 */
	private $m;


	protected function setUp() {
		parent::setUp();
		$this->m = new MaintenanceFixup( $this );
	}


	/**
	 * asserts the output before and after simulating shutdown
	 *
	 * This function simulates shutdown of self::m.
	 *
	 * @param $preShutdownOutput string: expected output before simulating shutdown
	 * @param $expectNLAppending bool: Whether or not shutdown simulation is expected
	 *            to add a newline to the output. If false, $preShutdownOutput is the
	 *            expected output after shutdown simulation. Otherwise,
	 *            $preShutdownOutput with an appended newline is the expected output
	 *            after shutdown simulation.
	 */
	private function assertOutputPrePostShutdown( $preShutdownOutput, $expectNLAppending ) {

		$this->assertEquals( $preShutdownOutput, $this->getActualOutput(),
				"Output before shutdown simulation" );

		$this->m->simulateShutdown();

		$postShutdownOutput = $preShutdownOutput . ( $expectNLAppending ? "\n" : "" );
		$this->expectOutputString( $postShutdownOutput );
	}


	// Although the following tests do not seem to be too consistent (compare for
	// example the newlines within the test.*StringString tests, or the
	// test.*Intermittent.* tests), the objective of these tests is not to describe
	// consistent behaviour, but rather currently existing behaviour.


	function testOutputEmpty() {
		$this->m->output( "" );
		$this->assertOutputPrePostShutdown( "", False );
	}

	function testOutputString() {
		$this->m->output( "foo" );
		$this->assertOutputPrePostShutdown( "foo", False );
	}

	function testOutputStringString() {
		$this->m->output( "foo" );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testOutputStringNL() {
		$this->m->output( "foo\n" );
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testOutputStringNLNL() {
		$this->m->output( "foo\n\n" );
		$this->assertOutputPrePostShutdown( "foo\n\n", False );
	}

	function testOutputStringNLString() {
		$this->m->output( "foo\nbar" );
		$this->assertOutputPrePostShutdown( "foo\nbar", False );
	}

	function testOutputStringNLStringNL() {
		$this->m->output( "foo\nbar\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputStringNLStringNLLinewise() {
		$this->m->output( "foo\n" );
		$this->m->output( "bar\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputStringNLStringNLArbitrary() {
		$this->m->output( "" );
		$this->m->output( "foo" );
		$this->m->output( "" );
		$this->m->output( "\n" );
		$this->m->output( "ba" );
		$this->m->output( "" );
		$this->m->output( "r\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputStringNLStringNLArbitraryAgain() {
		$this->m->output( "" );
		$this->m->output( "foo" );
		$this->m->output( "" );
		$this->m->output( "\nb" );
		$this->m->output( "a" );
		$this->m->output( "" );
		$this->m->output( "r\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputWNullChannelEmpty() {
		$this->m->output( "", null );
		$this->assertOutputPrePostShutdown( "", False );
	}

	function testOutputWNullChannelString() {
		$this->m->output( "foo", null );
		$this->assertOutputPrePostShutdown( "foo", False );
	}

	function testOutputWNullChannelStringString() {
		$this->m->output( "foo", null );
		$this->m->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testOutputWNullChannelStringNL() {
		$this->m->output( "foo\n", null );
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testOutputWNullChannelStringNLNL() {
		$this->m->output( "foo\n\n", null );
		$this->assertOutputPrePostShutdown( "foo\n\n", False );
	}

	function testOutputWNullChannelStringNLString() {
		$this->m->output( "foo\nbar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar", False );
	}

	function testOutputWNullChannelStringNLStringNL() {
		$this->m->output( "foo\nbar\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputWNullChannelStringNLStringNLLinewise() {
		$this->m->output( "foo\n", null );
		$this->m->output( "bar\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputWNullChannelStringNLStringNLArbitrary() {
		$this->m->output( "", null );
		$this->m->output( "foo", null );
		$this->m->output( "", null );
		$this->m->output( "\n", null );
		$this->m->output( "ba", null );
		$this->m->output( "", null );
		$this->m->output( "r\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputWNullChannelStringNLStringNLArbitraryAgain() {
		$this->m->output( "", null );
		$this->m->output( "foo", null );
		$this->m->output( "", null );
		$this->m->output( "\nb", null );
		$this->m->output( "a", null );
		$this->m->output( "", null );
		$this->m->output( "r\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputWChannelString() {
		$this->m->output( "foo", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", True );
	}

	function testOutputWChannelStringNL() {
		$this->m->output( "foo\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", True );
	}

	function testOutputWChannelStringNLNL() {
		// If this test fails, note that output takes strings with double line
		// endings (although output's implementation in this situation calls
		// outputChanneled with a string ending in a nl ... which is not allowed
		// according to the documentation of outputChanneled)
		$this->m->output( "foo\n\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\n", True );
	}

	function testOutputWChannelStringNLString() {
		$this->m->output( "foo\nbar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputWChannelStringNLStringNL() {
		$this->m->output( "foo\nbar\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputWChannelStringNLStringNLLinewise() {
		$this->m->output( "foo\n", "bazChannel" );
		$this->m->output( "bar\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", True );
	}

	function testOutputWChannelStringNLStringNLArbitrary() {
		$this->m->output( "", "bazChannel" );
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "\n", "bazChannel" );
		$this->m->output( "ba", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "r\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", True );
	}

	function testOutputWChannelStringNLStringNLArbitraryAgain() {
		$this->m->output( "", "bazChannel" );
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "\nb", "bazChannel" );
		$this->m->output( "a", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "r\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputWMultipleChannelsChannelChange() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "bar", "bazChannel" );
		$this->m->output( "qux", "quuxChannel" );
		$this->m->output( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", True );
	}

	function testOutputWMultipleChannelsChannelChangeNL() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "bar\n", "bazChannel" );
		$this->m->output( "qux\n", "quuxChannel" );
		$this->m->output( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", True );
	}

	function testOutputWAndWOChannelStringStartWO() {
		$this->m->output( "foo" );
		$this->m->output( "bar", "bazChannel" );
		$this->m->output( "qux" );
		$this->m->output( "quux", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nquxquux", True );
	}

	function testOutputWAndWOChannelStringStartW() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "bar" );
		$this->m->output( "qux", "bazChannel" );
		$this->m->output( "quux" );
		$this->assertOutputPrePostShutdown( "foo\nbarqux\nquux", False );
	}

	function testOutputWChannelTypeSwitch() {
		$this->m->output( "foo", 1 );
		$this->m->output( "bar", 1.0 );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputIntermittentEmpty() {
		$this->m->output( "foo" );
		$this->m->output( "" );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testOutputIntermittentFalse() {
		$this->m->output( "foo" );
		$this->m->output( false );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testOutputIntermittentFalseAfterOtherChannel() {
		$this->m->output( "qux", "quuxChannel" );
		$this->m->output( "foo" );
		$this->m->output( false );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "qux\nfoobar", False );
	}

	function testOutputWNullChannelIntermittentEmpty() {
		$this->m->output( "foo", null );
		$this->m->output( "", null );
		$this->m->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testOutputWNullChannelIntermittentFalse() {
		$this->m->output( "foo", null );
		$this->m->output( false, null );
		$this->m->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testOutputWChannelIntermittentEmpty() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", True );
	}

	function testOutputWChannelIntermittentFalse() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( false, "bazChannel" );
		$this->m->output( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", True );
	}

	// Note that (per documentation) outputChanneled does take strings that end
	// in \n, hence we do not test such strings.

	function testOutputChanneledEmpty() {
		$this->m->outputChanneled( "" );
		$this->assertOutputPrePostShutdown( "\n", False );
	}

	function testOutputChanneledString() {
		$this->m->outputChanneled( "foo" );
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testOutputChanneledStringString() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputChanneledStringNLString() {
		$this->m->outputChanneled( "foo\nbar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputChanneledStringNLStringNLArbitraryAgain() {
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "\nb" );
		$this->m->outputChanneled( "a" );
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "r" );
		$this->assertOutputPrePostShutdown( "\nfoo\n\n\nb\na\n\nr\n", False );
	}

	function testOutputChanneledWNullChannelEmpty() {
		$this->m->outputChanneled( "", null );
		$this->assertOutputPrePostShutdown( "\n", False );
	}

	function testOutputChanneledWNullChannelString() {
		$this->m->outputChanneled( "foo", null );
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testOutputChanneledWNullChannelStringString() {
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputChanneledWNullChannelStringNLString() {
		$this->m->outputChanneled( "foo\nbar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputChanneledWNullChannelStringNLStringNLArbitraryAgain() {
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "\nb", null );
		$this->m->outputChanneled( "a", null );
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "r", null );
		$this->assertOutputPrePostShutdown( "\nfoo\n\n\nb\na\n\nr\n", False );
	}

	function testOutputChanneledWChannelString() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", True );
	}

	function testOutputChanneledWChannelStringNLString() {
		$this->m->outputChanneled( "foo\nbar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputChanneledWChannelStringString() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", True );
	}

	function testOutputChanneledWChannelStringNLStringNLArbitraryAgain() {
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "\nb", "bazChannel" );
		$this->m->outputChanneled( "a", "bazChannel" );
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "r", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputChanneledWMultipleChannelsChannelChange() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->m->outputChanneled( "qux", "quuxChannel" );
		$this->m->outputChanneled( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", True );
	}

	function testOutputChanneledWMultipleChannelsChannelChangeEnclosedNull() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", null );
		$this->m->outputChanneled( "qux", null );
		$this->m->outputChanneled( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\ncorge", True );
	}

	function testOutputChanneledWMultipleChannelsChannelAfterNullChange() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", null );
		$this->m->outputChanneled( "qux", null );
		$this->m->outputChanneled( "corge", "quuxChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\ncorge", True );
	}

	function testOutputChanneledWAndWOChannelStringStartWO() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->m->outputChanneled( "qux" );
		$this->m->outputChanneled( "quux", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\nquux", True );
	}

	function testOutputChanneledWAndWOChannelStringStartW() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar" );
		$this->m->outputChanneled( "qux", "bazChannel" );
		$this->m->outputChanneled( "quux" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\nquux\n", False );
	}

	function testOutputChanneledWChannelTypeSwitch() {
		$this->m->outputChanneled( "foo", 1 );
		$this->m->outputChanneled( "bar", 1.0 );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testOutputChanneledWOChannelIntermittentEmpty() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\n\nbar\n", False );
	}

	function testOutputChanneledWOChannelIntermittentFalse() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( false );
		$this->m->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputChanneledWNullChannelIntermittentEmpty() {
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\n\nbar\n", False );
	}

	function testOutputChanneledWNullChannelIntermittentFalse() {
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( false, null );
		$this->m->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testOutputChanneledWChannelIntermittentEmpty() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", True );
	}

	function testOutputChanneledWChannelIntermittentFalse() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( false, "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", True );
	}

	function testCleanupChanneledClean() {
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "", False );
	}

	function testCleanupChanneledAfterOutput() {
		$this->m->output( "foo" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo", False );
	}

	function testCleanupChanneledAfterOutputWNullChannel() {
		$this->m->output( "foo", null );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo", False );
	}

	function testCleanupChanneledAfterOutputWChannel() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testCleanupChanneledAfterNLOutput() {
		$this->m->output( "foo\n" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testCleanupChanneledAfterNLOutputWNullChannel() {
		$this->m->output( "foo\n", null );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testCleanupChanneledAfterNLOutputWChannel() {
		$this->m->output( "foo\n", "bazChannel" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testCleanupChanneledAfterOutputChanneledWOChannel() {
		$this->m->outputChanneled( "foo" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testCleanupChanneledAfterOutputChanneledWNullChannel() {
		$this->m->outputChanneled( "foo", null );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testCleanupChanneledAfterOutputChanneledWChannel() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", False );
	}

	function testMultipleMaintenanceObjectsInteractionOutput() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo" );
		$m2->output( "bar" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWNullChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo", null );
		$m2->output( "bar", null );

		$this->assertEquals( "foobar", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar", False );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo", "bazChannel" );
		$m2->output( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n", True );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWNullChannelNL() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo\n", null );
		$m2->output( "bar\n", null );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWChannelNL() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo\n", "bazChannel" );
		$m2->output( "bar\n", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n", True );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneled() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo" );
		$m2->outputChanneled( "bar" );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneledWNullChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo", null );
		$m2->outputChanneled( "bar", null );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", False );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneledWChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo", "bazChannel" );
		$m2->outputChanneled( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
				"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n", True );
	}

	function testMultipleMaintenanceObjectsInteractionCleanupChanneledWChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo", "bazChannel" );
		$m2->outputChanneled( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
				"Output before first cleanup" );
		$this->m->cleanupChanneled();
		$this->assertEquals( "foobar\n", $this->getActualOutput(),
				"Output after first cleanup" );
		$m2->cleanupChanneled();
		$this->assertEquals( "foobar\n\n", $this->getActualOutput(),
				"Output after second cleanup" );

		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n\n", False );
	}


}