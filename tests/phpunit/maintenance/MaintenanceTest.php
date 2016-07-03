<?php

// It would be great if we were able to use PHPUnit's getMockForAbstractClass
// instead of the MaintenanceFixup hack below. However, we cannot do
// without changing the visibility and without working around hacks in
// Maintenance.php
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
	 * shutdownSimulated === true if simulateShutdown has done it's work
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

/**
 * @covers Maintenance
 */
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

	protected function tearDown() {
		if ( $this->m ) {
			$this->m->simulateShutdown();
			$this->m = null;
		}
		parent::tearDown();
	}

	/**
	 * asserts the output before and after simulating shutdown
	 *
	 * This function simulates shutdown of self::m.
	 *
	 * @param string $preShutdownOutput Expected output before simulating shutdown
	 * @param bool $expectNLAppending Whether or not shutdown simulation is expected
	 *   to add a newline to the output. If false, $preShutdownOutput is the
	 *   expected output after shutdown simulation. Otherwise,
	 *   $preShutdownOutput with an appended newline is the expected output
	 *   after shutdown simulation.
	 */
	private function assertOutputPrePostShutdown( $preShutdownOutput, $expectNLAppending ) {

		$this->assertEquals( $preShutdownOutput, $this->getActualOutput(),
			"Output before shutdown simulation" );

		$this->m->simulateShutdown();
		$this->m = null;

		$postShutdownOutput = $preShutdownOutput . ( $expectNLAppending ? "\n" : "" );
		$this->expectOutputString( $postShutdownOutput );
	}

	// Although the following tests do not seem to be too consistent (compare for
	// example the newlines within the test.*StringString tests, or the
	// test.*Intermittent.* tests), the objective of these tests is not to describe
	// consistent behavior, but rather currently existing behavior.

	function testOutputEmpty() {
		$this->m->output( "" );
		$this->assertOutputPrePostShutdown( "", false );
	}

	function testOutputString() {
		$this->m->output( "foo" );
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testOutputStringString() {
		$this->m->output( "foo" );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputStringNL() {
		$this->m->output( "foo\n" );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputStringNLNL() {
		$this->m->output( "foo\n\n" );
		$this->assertOutputPrePostShutdown( "foo\n\n", false );
	}

	function testOutputStringNLString() {
		$this->m->output( "foo\nbar" );
		$this->assertOutputPrePostShutdown( "foo\nbar", false );
	}

	function testOutputStringNLStringNL() {
		$this->m->output( "foo\nbar\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputStringNLStringNLLinewise() {
		$this->m->output( "foo\n" );
		$this->m->output( "bar\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputStringNLStringNLArbitrary() {
		$this->m->output( "" );
		$this->m->output( "foo" );
		$this->m->output( "" );
		$this->m->output( "\n" );
		$this->m->output( "ba" );
		$this->m->output( "" );
		$this->m->output( "r\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputStringNLStringNLArbitraryAgain() {
		$this->m->output( "" );
		$this->m->output( "foo" );
		$this->m->output( "" );
		$this->m->output( "\nb" );
		$this->m->output( "a" );
		$this->m->output( "" );
		$this->m->output( "r\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelEmpty() {
		$this->m->output( "", null );
		$this->assertOutputPrePostShutdown( "", false );
	}

	function testOutputWNullChannelString() {
		$this->m->output( "foo", null );
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testOutputWNullChannelStringString() {
		$this->m->output( "foo", null );
		$this->m->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputWNullChannelStringNL() {
		$this->m->output( "foo\n", null );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputWNullChannelStringNLNL() {
		$this->m->output( "foo\n\n", null );
		$this->assertOutputPrePostShutdown( "foo\n\n", false );
	}

	function testOutputWNullChannelStringNLString() {
		$this->m->output( "foo\nbar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar", false );
	}

	function testOutputWNullChannelStringNLStringNL() {
		$this->m->output( "foo\nbar\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelStringNLStringNLLinewise() {
		$this->m->output( "foo\n", null );
		$this->m->output( "bar\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelStringNLStringNLArbitrary() {
		$this->m->output( "", null );
		$this->m->output( "foo", null );
		$this->m->output( "", null );
		$this->m->output( "\n", null );
		$this->m->output( "ba", null );
		$this->m->output( "", null );
		$this->m->output( "r\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelStringNLStringNLArbitraryAgain() {
		$this->m->output( "", null );
		$this->m->output( "foo", null );
		$this->m->output( "", null );
		$this->m->output( "\nb", null );
		$this->m->output( "a", null );
		$this->m->output( "", null );
		$this->m->output( "r\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWChannelString() {
		$this->m->output( "foo", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", true );
	}

	function testOutputWChannelStringNL() {
		$this->m->output( "foo\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", true );
	}

	function testOutputWChannelStringNLNL() {
		// If this test fails, note that output takes strings with double line
		// endings (although output's implementation in this situation calls
		// outputChanneled with a string ending in a nl ... which is not allowed
		// according to the documentation of outputChanneled)
		$this->m->output( "foo\n\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\n", true );
	}

	function testOutputWChannelStringNLString() {
		$this->m->output( "foo\nbar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputWChannelStringNLStringNL() {
		$this->m->output( "foo\nbar\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputWChannelStringNLStringNLLinewise() {
		$this->m->output( "foo\n", "bazChannel" );
		$this->m->output( "bar\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputWChannelStringNLStringNLArbitrary() {
		$this->m->output( "", "bazChannel" );
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "\n", "bazChannel" );
		$this->m->output( "ba", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "r\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputWChannelStringNLStringNLArbitraryAgain() {
		$this->m->output( "", "bazChannel" );
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "\nb", "bazChannel" );
		$this->m->output( "a", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "r\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputWMultipleChannelsChannelChange() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "bar", "bazChannel" );
		$this->m->output( "qux", "quuxChannel" );
		$this->m->output( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", true );
	}

	function testOutputWMultipleChannelsChannelChangeNL() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "bar\n", "bazChannel" );
		$this->m->output( "qux\n", "quuxChannel" );
		$this->m->output( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", true );
	}

	function testOutputWAndWOChannelStringStartWO() {
		$this->m->output( "foo" );
		$this->m->output( "bar", "bazChannel" );
		$this->m->output( "qux" );
		$this->m->output( "quux", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nquxquux", true );
	}

	function testOutputWAndWOChannelStringStartW() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "bar" );
		$this->m->output( "qux", "bazChannel" );
		$this->m->output( "quux" );
		$this->assertOutputPrePostShutdown( "foo\nbarqux\nquux", false );
	}

	function testOutputWChannelTypeSwitch() {
		$this->m->output( "foo", 1 );
		$this->m->output( "bar", 1.0 );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputIntermittentEmpty() {
		$this->m->output( "foo" );
		$this->m->output( "" );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputIntermittentFalse() {
		$this->m->output( "foo" );
		$this->m->output( false );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputIntermittentFalseAfterOtherChannel() {
		$this->m->output( "qux", "quuxChannel" );
		$this->m->output( "foo" );
		$this->m->output( false );
		$this->m->output( "bar" );
		$this->assertOutputPrePostShutdown( "qux\nfoobar", false );
	}

	function testOutputWNullChannelIntermittentEmpty() {
		$this->m->output( "foo", null );
		$this->m->output( "", null );
		$this->m->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputWNullChannelIntermittentFalse() {
		$this->m->output( "foo", null );
		$this->m->output( false, null );
		$this->m->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputWChannelIntermittentEmpty() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( "", "bazChannel" );
		$this->m->output( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputWChannelIntermittentFalse() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->output( false, "bazChannel" );
		$this->m->output( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	// Note that (per documentation) outputChanneled does take strings that end
	// in \n, hence we do not test such strings.

	function testOutputChanneledEmpty() {
		$this->m->outputChanneled( "" );
		$this->assertOutputPrePostShutdown( "\n", false );
	}

	function testOutputChanneledString() {
		$this->m->outputChanneled( "foo" );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputChanneledStringString() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledStringNLString() {
		$this->m->outputChanneled( "foo\nbar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledStringNLStringNLArbitraryAgain() {
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "\nb" );
		$this->m->outputChanneled( "a" );
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "r" );
		$this->assertOutputPrePostShutdown( "\nfoo\n\n\nb\na\n\nr\n", false );
	}

	function testOutputChanneledWNullChannelEmpty() {
		$this->m->outputChanneled( "", null );
		$this->assertOutputPrePostShutdown( "\n", false );
	}

	function testOutputChanneledWNullChannelString() {
		$this->m->outputChanneled( "foo", null );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputChanneledWNullChannelStringString() {
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWNullChannelStringNLString() {
		$this->m->outputChanneled( "foo\nbar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWNullChannelStringNLStringNLArbitraryAgain() {
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "\nb", null );
		$this->m->outputChanneled( "a", null );
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "r", null );
		$this->assertOutputPrePostShutdown( "\nfoo\n\n\nb\na\n\nr\n", false );
	}

	function testOutputChanneledWChannelString() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", true );
	}

	function testOutputChanneledWChannelStringNLString() {
		$this->m->outputChanneled( "foo\nbar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputChanneledWChannelStringString() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputChanneledWChannelStringNLStringNLArbitraryAgain() {
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "\nb", "bazChannel" );
		$this->m->outputChanneled( "a", "bazChannel" );
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "r", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputChanneledWMultipleChannelsChannelChange() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->m->outputChanneled( "qux", "quuxChannel" );
		$this->m->outputChanneled( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", true );
	}

	function testOutputChanneledWMultipleChannelsChannelChangeEnclosedNull() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", null );
		$this->m->outputChanneled( "qux", null );
		$this->m->outputChanneled( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\ncorge", true );
	}

	function testOutputChanneledWMultipleChannelsChannelAfterNullChange() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar", null );
		$this->m->outputChanneled( "qux", null );
		$this->m->outputChanneled( "corge", "quuxChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\ncorge", true );
	}

	function testOutputChanneledWAndWOChannelStringStartWO() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->m->outputChanneled( "qux" );
		$this->m->outputChanneled( "quux", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\nquux", true );
	}

	function testOutputChanneledWAndWOChannelStringStartW() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "bar" );
		$this->m->outputChanneled( "qux", "bazChannel" );
		$this->m->outputChanneled( "quux" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\nquux\n", false );
	}

	function testOutputChanneledWChannelTypeSwitch() {
		$this->m->outputChanneled( "foo", 1 );
		$this->m->outputChanneled( "bar", 1.0 );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputChanneledWOChannelIntermittentEmpty() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( "" );
		$this->m->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\n\nbar\n", false );
	}

	function testOutputChanneledWOChannelIntermittentFalse() {
		$this->m->outputChanneled( "foo" );
		$this->m->outputChanneled( false );
		$this->m->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWNullChannelIntermittentEmpty() {
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( "", null );
		$this->m->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\n\nbar\n", false );
	}

	function testOutputChanneledWNullChannelIntermittentFalse() {
		$this->m->outputChanneled( "foo", null );
		$this->m->outputChanneled( false, null );
		$this->m->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWChannelIntermittentEmpty() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( "", "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputChanneledWChannelIntermittentFalse() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->outputChanneled( false, "bazChannel" );
		$this->m->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testCleanupChanneledClean() {
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "", false );
	}

	function testCleanupChanneledAfterOutput() {
		$this->m->output( "foo" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testCleanupChanneledAfterOutputWNullChannel() {
		$this->m->output( "foo", null );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testCleanupChanneledAfterOutputWChannel() {
		$this->m->output( "foo", "bazChannel" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterNLOutput() {
		$this->m->output( "foo\n" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterNLOutputWNullChannel() {
		$this->m->output( "foo\n", null );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterNLOutputWChannel() {
		$this->m->output( "foo\n", "bazChannel" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterOutputChanneledWOChannel() {
		$this->m->outputChanneled( "foo" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterOutputChanneledWNullChannel() {
		$this->m->outputChanneled( "foo", null );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterOutputChanneledWChannel() {
		$this->m->outputChanneled( "foo", "bazChannel" );
		$this->m->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutput() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo" );
		$m2->output( "bar" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWNullChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo", null );
		$m2->output( "bar", null );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo", "bazChannel" );
		$m2->output( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n", true );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWNullChannelNL() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo\n", null );
		$m2->output( "bar\n", null );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWChannelNL() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->output( "foo\n", "bazChannel" );
		$m2->output( "bar\n", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n", true );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneled() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo" );
		$m2->outputChanneled( "bar" );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneledWNullChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo", null );
		$m2->outputChanneled( "bar", null );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneledWChannel() {
		$m2 = new MaintenanceFixup( $this );

		$this->m->outputChanneled( "foo", "bazChannel" );
		$m2->outputChanneled( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->simulateShutdown();
		$this->assertOutputPrePostShutdown( "foobar\n", true );
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
		$this->assertOutputPrePostShutdown( "foobar\n\n", false );
	}

	/**
	 * @covers Maintenance::getConfig
	 */
	public function testGetConfig() {
		$this->assertInstanceOf( 'Config', $this->m->getConfig() );
		$this->assertSame(
			ConfigFactory::getDefaultInstance()->makeConfig( 'main' ),
			$this->m->getConfig()
		);
	}

	/**
	 * @covers Maintenance::setConfig
	 */
	public function testSetConfig() {
		$conf = $this->getMock( 'Config' );
		$this->m->setConfig( $conf );
		$this->assertSame( $conf, $this->m->getConfig() );
	}

	function testParseArgs() {
		$m2 = new MaintenanceFixup( $this );
		// Create an option with an argument allowed to be specified multiple times
		$m2->addOption( 'multi', 'This option does stuff', false, true, false, true );
		$m2->loadWithArgv( [ '--multi', 'this1', '--multi', 'this2' ] );

		$this->assertEquals( [ 'this1', 'this2' ], $m2->getOption( 'multi' ) );
		$this->assertEquals( [ [ 'multi', 'this1' ], [ 'multi', 'this2' ] ],
			$m2->orderedOptions );

		$m2->simulateShutdown();

		$m2 = new MaintenanceFixup( $this );

		$m2->addOption( 'multi', 'This option does stuff', false, false, false, true );
		$m2->loadWithArgv( [ '--multi', '--multi' ] );

		$this->assertEquals( [ 1, 1 ], $m2->getOption( 'multi' ) );
		$this->assertEquals( [ [ 'multi', 1 ], [ 'multi', 1 ] ], $m2->orderedOptions );

		$m2->simulateShutdown();

		$m2 = new MaintenanceFixup( $this );
		// Create an option with an argument allowed to be specified multiple times
		$m2->addOption( 'multi', 'This option doesn\'t actually support multiple occurrences' );
		$m2->loadWithArgv( [ '--multi=yo' ] );

		$this->assertEquals( 'yo', $m2->getOption( 'multi' ) );
		$this->assertEquals( [ [ 'multi', 'yo' ] ], $m2->orderedOptions );

		$m2->simulateShutdown();
	}
}
