<?php

namespace MediaWiki\Tests\Maintenance;

use Maintenance;
use MediaWiki\MediaWikiServices;

/**
 * Stub to allow us to instantiate and test an abstract Maintenance object.
 */
class ConcreteMaintenance extends Maintenance {
	public function execute() {
		// Now we're real.
	}
}

/**
 * @covers Maintenance
 */
class MaintenanceTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return ConcreteMaintenance::class;
	}

	// Although the following tests do not seem to be too consistent (compare for
	// example the newlines within the test.*StringString tests, or the
	// test.*Intermittent.* tests), the objective of these tests is not to describe
	// consistent behavior, but rather currently existing behavior.

	function testOutputEmpty() {
		$this->maintenance->output( "" );
		$this->assertOutputPrePostShutdown( "", false );
	}

	function testOutputString() {
		$this->maintenance->output( "foo" );
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testOutputStringString() {
		$this->maintenance->output( "foo" );
		$this->maintenance->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputStringNL() {
		$this->maintenance->output( "foo\n" );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputStringNLNL() {
		$this->maintenance->output( "foo\n\n" );
		$this->assertOutputPrePostShutdown( "foo\n\n", false );
	}

	function testOutputStringNLString() {
		$this->maintenance->output( "foo\nbar" );
		$this->assertOutputPrePostShutdown( "foo\nbar", false );
	}

	function testOutputStringNLStringNL() {
		$this->maintenance->output( "foo\nbar\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputStringNLStringNLLinewise() {
		$this->maintenance->output( "foo\n" );
		$this->maintenance->output( "bar\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputStringNLStringNLArbitrary() {
		$this->maintenance->output( "" );
		$this->maintenance->output( "foo" );
		$this->maintenance->output( "" );
		$this->maintenance->output( "\n" );
		$this->maintenance->output( "ba" );
		$this->maintenance->output( "" );
		$this->maintenance->output( "r\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputStringNLStringNLArbitraryAgain() {
		$this->maintenance->output( "" );
		$this->maintenance->output( "foo" );
		$this->maintenance->output( "" );
		$this->maintenance->output( "\nb" );
		$this->maintenance->output( "a" );
		$this->maintenance->output( "" );
		$this->maintenance->output( "r\n" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelEmpty() {
		$this->maintenance->output( "", null );
		$this->assertOutputPrePostShutdown( "", false );
	}

	function testOutputWNullChannelString() {
		$this->maintenance->output( "foo", null );
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testOutputWNullChannelStringString() {
		$this->maintenance->output( "foo", null );
		$this->maintenance->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputWNullChannelStringNL() {
		$this->maintenance->output( "foo\n", null );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputWNullChannelStringNLNL() {
		$this->maintenance->output( "foo\n\n", null );
		$this->assertOutputPrePostShutdown( "foo\n\n", false );
	}

	function testOutputWNullChannelStringNLString() {
		$this->maintenance->output( "foo\nbar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar", false );
	}

	function testOutputWNullChannelStringNLStringNL() {
		$this->maintenance->output( "foo\nbar\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelStringNLStringNLLinewise() {
		$this->maintenance->output( "foo\n", null );
		$this->maintenance->output( "bar\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelStringNLStringNLArbitrary() {
		$this->maintenance->output( "", null );
		$this->maintenance->output( "foo", null );
		$this->maintenance->output( "", null );
		$this->maintenance->output( "\n", null );
		$this->maintenance->output( "ba", null );
		$this->maintenance->output( "", null );
		$this->maintenance->output( "r\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWNullChannelStringNLStringNLArbitraryAgain() {
		$this->maintenance->output( "", null );
		$this->maintenance->output( "foo", null );
		$this->maintenance->output( "", null );
		$this->maintenance->output( "\nb", null );
		$this->maintenance->output( "a", null );
		$this->maintenance->output( "", null );
		$this->maintenance->output( "r\n", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputWChannelString() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", true );
	}

	function testOutputWChannelStringNL() {
		$this->maintenance->output( "foo\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", true );
	}

	function testOutputWChannelStringNLNL() {
		// If this test fails, note that output takes strings with double line
		// endings (although output's implementation in this situation calls
		// outputChanneled with a string ending in a nl ... which is not allowed
		// according to the documentation of outputChanneled)
		$this->maintenance->output( "foo\n\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\n", true );
	}

	function testOutputWChannelStringNLString() {
		$this->maintenance->output( "foo\nbar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputWChannelStringNLStringNL() {
		$this->maintenance->output( "foo\nbar\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputWChannelStringNLStringNLLinewise() {
		$this->maintenance->output( "foo\n", "bazChannel" );
		$this->maintenance->output( "bar\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputWChannelStringNLStringNLArbitrary() {
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "\n", "bazChannel" );
		$this->maintenance->output( "ba", "bazChannel" );
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "r\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputWChannelStringNLStringNLArbitraryAgain() {
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "\nb", "bazChannel" );
		$this->maintenance->output( "a", "bazChannel" );
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "r\n", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputWMultipleChannelsChannelChange() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( "bar", "bazChannel" );
		$this->maintenance->output( "qux", "quuxChannel" );
		$this->maintenance->output( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", true );
	}

	function testOutputWMultipleChannelsChannelChangeNL() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( "bar\n", "bazChannel" );
		$this->maintenance->output( "qux\n", "quuxChannel" );
		$this->maintenance->output( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", true );
	}

	function testOutputWAndWOChannelStringStartWO() {
		$this->maintenance->output( "foo" );
		$this->maintenance->output( "bar", "bazChannel" );
		$this->maintenance->output( "qux" );
		$this->maintenance->output( "quux", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nquxquux", true );
	}

	function testOutputWAndWOChannelStringStartW() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( "bar" );
		$this->maintenance->output( "qux", "bazChannel" );
		$this->maintenance->output( "quux" );
		$this->assertOutputPrePostShutdown( "foo\nbarqux\nquux", false );
	}

	function testOutputWChannelTypeSwitch() {
		$this->maintenance->output( "foo", 1 );
		$this->maintenance->output( "bar", 1.0 );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputIntermittentEmpty() {
		$this->maintenance->output( "foo" );
		$this->maintenance->output( "" );
		$this->maintenance->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputIntermittentFalse() {
		$this->maintenance->output( "foo" );
		$this->maintenance->output( false );
		$this->maintenance->output( "bar" );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputIntermittentFalseAfterOtherChannel() {
		$this->maintenance->output( "qux", "quuxChannel" );
		$this->maintenance->output( "foo" );
		$this->maintenance->output( false );
		$this->maintenance->output( "bar" );
		$this->assertOutputPrePostShutdown( "qux\nfoobar", false );
	}

	function testOutputWNullChannelIntermittentEmpty() {
		$this->maintenance->output( "foo", null );
		$this->maintenance->output( "", null );
		$this->maintenance->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputWNullChannelIntermittentFalse() {
		$this->maintenance->output( "foo", null );
		$this->maintenance->output( false, null );
		$this->maintenance->output( "bar", null );
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testOutputWChannelIntermittentEmpty() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( "", "bazChannel" );
		$this->maintenance->output( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputWChannelIntermittentFalse() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->output( false, "bazChannel" );
		$this->maintenance->output( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	// Note that (per documentation) outputChanneled does take strings that end
	// in \n, hence we do not test such strings.

	function testOutputChanneledEmpty() {
		$this->maintenance->outputChanneled( "" );
		$this->assertOutputPrePostShutdown( "\n", false );
	}

	function testOutputChanneledString() {
		$this->maintenance->outputChanneled( "foo" );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputChanneledStringString() {
		$this->maintenance->outputChanneled( "foo" );
		$this->maintenance->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledStringNLString() {
		$this->maintenance->outputChanneled( "foo\nbar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledStringNLStringNLArbitraryAgain() {
		$this->maintenance->outputChanneled( "" );
		$this->maintenance->outputChanneled( "foo" );
		$this->maintenance->outputChanneled( "" );
		$this->maintenance->outputChanneled( "\nb" );
		$this->maintenance->outputChanneled( "a" );
		$this->maintenance->outputChanneled( "" );
		$this->maintenance->outputChanneled( "r" );
		$this->assertOutputPrePostShutdown( "\nfoo\n\n\nb\na\n\nr\n", false );
	}

	function testOutputChanneledWNullChannelEmpty() {
		$this->maintenance->outputChanneled( "", null );
		$this->assertOutputPrePostShutdown( "\n", false );
	}

	function testOutputChanneledWNullChannelString() {
		$this->maintenance->outputChanneled( "foo", null );
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testOutputChanneledWNullChannelStringString() {
		$this->maintenance->outputChanneled( "foo", null );
		$this->maintenance->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWNullChannelStringNLString() {
		$this->maintenance->outputChanneled( "foo\nbar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWNullChannelStringNLStringNLArbitraryAgain() {
		$this->maintenance->outputChanneled( "", null );
		$this->maintenance->outputChanneled( "foo", null );
		$this->maintenance->outputChanneled( "", null );
		$this->maintenance->outputChanneled( "\nb", null );
		$this->maintenance->outputChanneled( "a", null );
		$this->maintenance->outputChanneled( "", null );
		$this->maintenance->outputChanneled( "r", null );
		$this->assertOutputPrePostShutdown( "\nfoo\n\n\nb\na\n\nr\n", false );
	}

	function testOutputChanneledWChannelString() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo", true );
	}

	function testOutputChanneledWChannelStringNLString() {
		$this->maintenance->outputChanneled( "foo\nbar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputChanneledWChannelStringString() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputChanneledWChannelStringNLStringNLArbitraryAgain() {
		$this->maintenance->outputChanneled( "", "bazChannel" );
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "", "bazChannel" );
		$this->maintenance->outputChanneled( "\nb", "bazChannel" );
		$this->maintenance->outputChanneled( "a", "bazChannel" );
		$this->maintenance->outputChanneled( "", "bazChannel" );
		$this->maintenance->outputChanneled( "r", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputChanneledWMultipleChannelsChannelChange() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "bar", "bazChannel" );
		$this->maintenance->outputChanneled( "qux", "quuxChannel" );
		$this->maintenance->outputChanneled( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar\nqux\ncorge", true );
	}

	function testOutputChanneledWMultipleChannelsChannelChangeEnclosedNull() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "bar", null );
		$this->maintenance->outputChanneled( "qux", null );
		$this->maintenance->outputChanneled( "corge", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\ncorge", true );
	}

	function testOutputChanneledWMultipleChannelsChannelAfterNullChange() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "bar", null );
		$this->maintenance->outputChanneled( "qux", null );
		$this->maintenance->outputChanneled( "corge", "quuxChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\ncorge", true );
	}

	function testOutputChanneledWAndWOChannelStringStartWO() {
		$this->maintenance->outputChanneled( "foo" );
		$this->maintenance->outputChanneled( "bar", "bazChannel" );
		$this->maintenance->outputChanneled( "qux" );
		$this->maintenance->outputChanneled( "quux", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\nquux", true );
	}

	function testOutputChanneledWAndWOChannelStringStartW() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "bar" );
		$this->maintenance->outputChanneled( "qux", "bazChannel" );
		$this->maintenance->outputChanneled( "quux" );
		$this->assertOutputPrePostShutdown( "foo\nbar\nqux\nquux\n", false );
	}

	function testOutputChanneledWChannelTypeSwitch() {
		$this->maintenance->outputChanneled( "foo", 1 );
		$this->maintenance->outputChanneled( "bar", 1.0 );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testOutputChanneledWOChannelIntermittentEmpty() {
		$this->maintenance->outputChanneled( "foo" );
		$this->maintenance->outputChanneled( "" );
		$this->maintenance->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\n\nbar\n", false );
	}

	function testOutputChanneledWOChannelIntermittentFalse() {
		$this->maintenance->outputChanneled( "foo" );
		$this->maintenance->outputChanneled( false );
		$this->maintenance->outputChanneled( "bar" );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWNullChannelIntermittentEmpty() {
		$this->maintenance->outputChanneled( "foo", null );
		$this->maintenance->outputChanneled( "", null );
		$this->maintenance->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\n\nbar\n", false );
	}

	function testOutputChanneledWNullChannelIntermittentFalse() {
		$this->maintenance->outputChanneled( "foo", null );
		$this->maintenance->outputChanneled( false, null );
		$this->maintenance->outputChanneled( "bar", null );
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testOutputChanneledWChannelIntermittentEmpty() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( "", "bazChannel" );
		$this->maintenance->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foobar", true );
	}

	function testOutputChanneledWChannelIntermittentFalse() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->outputChanneled( false, "bazChannel" );
		$this->maintenance->outputChanneled( "bar", "bazChannel" );
		$this->assertOutputPrePostShutdown( "foo\nbar", true );
	}

	function testCleanupChanneledClean() {
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "", false );
	}

	function testCleanupChanneledAfterOutput() {
		$this->maintenance->output( "foo" );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testCleanupChanneledAfterOutputWNullChannel() {
		$this->maintenance->output( "foo", null );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo", false );
	}

	function testCleanupChanneledAfterOutputWChannel() {
		$this->maintenance->output( "foo", "bazChannel" );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterNLOutput() {
		$this->maintenance->output( "foo\n" );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterNLOutputWNullChannel() {
		$this->maintenance->output( "foo\n", null );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterNLOutputWChannel() {
		$this->maintenance->output( "foo\n", "bazChannel" );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterOutputChanneledWOChannel() {
		$this->maintenance->outputChanneled( "foo" );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterOutputChanneledWNullChannel() {
		$this->maintenance->outputChanneled( "foo", null );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testCleanupChanneledAfterOutputChanneledWChannel() {
		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$this->maintenance->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutput() {
		$m2 = $this->createMaintenance();

		$this->maintenance->output( "foo" );
		$m2->output( "bar" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWNullChannel() {
		$m2 = $this->createMaintenance();

		$this->maintenance->output( "foo", null );
		$m2->output( "bar", null );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foobar", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWChannel() {
		$m2 = $this->createMaintenance();

		$this->maintenance->output( "foo", "bazChannel" );
		$m2->output( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foobar\n", true );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWNullChannelNL() {
		$m2 = $this->createMaintenance();

		$this->maintenance->output( "foo\n", null );
		$m2->output( "bar\n", null );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputWChannelNL() {
		$m2 = $this->createMaintenance();

		$this->maintenance->output( "foo\n", "bazChannel" );
		$m2->output( "bar\n", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foobar\n", true );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneled() {
		$m2 = $this->createMaintenance();

		$this->maintenance->outputChanneled( "foo" );
		$m2->outputChanneled( "bar" );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneledWNullChannel() {
		$m2 = $this->createMaintenance();

		$this->maintenance->outputChanneled( "foo", null );
		$m2->outputChanneled( "bar", null );

		$this->assertEquals( "foo\nbar\n", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foo\nbar\n", false );
	}

	function testMultipleMaintenanceObjectsInteractionOutputChanneledWChannel() {
		$m2 = $this->createMaintenance();

		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$m2->outputChanneled( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before shutdown simulation (m2)" );
		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foobar\n", true );
	}

	function testMultipleMaintenanceObjectsInteractionCleanupChanneledWChannel() {
		$m2 = $this->createMaintenance();

		$this->maintenance->outputChanneled( "foo", "bazChannel" );
		$m2->outputChanneled( "bar", "bazChannel" );

		$this->assertEquals( "foobar", $this->getActualOutput(),
			"Output before first cleanup" );
		$this->maintenance->cleanupChanneled();
		$this->assertEquals( "foobar\n", $this->getActualOutput(),
			"Output after first cleanup" );
		$m2->cleanupChanneled();
		$this->assertEquals( "foobar\n\n", $this->getActualOutput(),
			"Output after second cleanup" );

		$m2->cleanupChanneled();
		$this->assertOutputPrePostShutdown( "foobar\n\n", false );
	}

	/**
	 * @covers Maintenance::getConfig
	 */
	public function testGetConfig() {
		$this->assertInstanceOf( 'Config', $this->maintenance->getConfig() );
		$this->assertSame(
			MediaWikiServices::getInstance()->getMainConfig(),
			$this->maintenance->getConfig()
		);
	}

	/**
	 * @covers Maintenance::setConfig
	 */
	public function testSetConfig() {
		$conf = $this->createMock( 'Config' );
		$this->maintenance->setConfig( $conf );
		$this->assertSame( $conf, $this->maintenance->getConfig() );
	}

	function testParseArgs() {
		$m2 = $this->createMaintenance();

		// Create an option with an argument allowed to be specified multiple times
		$m2->addOption( 'multi', 'This option does stuff', false, true, false, true );
		$m2->loadWithArgv( [ '--multi', 'this1', '--multi', 'this2' ] );

		$this->assertEquals( [ 'this1', 'this2' ], $m2->getOption( 'multi' ) );
		$this->assertEquals( [ [ 'multi', 'this1' ], [ 'multi', 'this2' ] ],
			$m2->orderedOptions );

		$m2->cleanupChanneled();

		$m2 = $this->createMaintenance();

		$m2->addOption( 'multi', 'This option does stuff', false, false, false, true );
		$m2->loadWithArgv( [ '--multi', '--multi' ] );

		$this->assertEquals( [ 1, 1 ], $m2->getOption( 'multi' ) );
		$this->assertEquals( [ [ 'multi', 1 ], [ 'multi', 1 ] ], $m2->orderedOptions );

		$m2->cleanupChanneled();

		$m2 = $this->createMaintenance();

		// Create an option with an argument allowed to be specified multiple times
		$m2->addOption( 'multi', 'This option doesn\'t actually support multiple occurrences' );
		$m2->loadWithArgv( [ '--multi=yo' ] );

		$this->assertEquals( 'yo', $m2->getOption( 'multi' ) );
		$this->assertEquals( [ [ 'multi', 'yo' ] ], $m2->orderedOptions );

		$m2->cleanupChanneled();
	}
}
