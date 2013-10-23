<?php

class MWDebugTest extends MediaWikiTestCase {


	protected function setUp() {
		parent::setUp();
		// Make sure MWDebug class is enabled
		static $MWDebugEnabled = false;
		if ( !$MWDebugEnabled ) {
			MWDebug::init();
			$MWDebugEnabled = true;
		}
		/** Clear log before each test */
		MWDebug::clearLog();
		wfSuppressWarnings();
	}

	protected function tearDown() {
		wfRestoreWarnings();
		parent::tearDown();
	}

	public function testAddLog() {
		MWDebug::log( 'logging a string' );
		$this->assertEquals(
			array( array(
				'msg' => 'logging a string',
				'type' => 'log',
				'caller' => __METHOD__,
			) ),
			MWDebug::getLog()
		);
	}

	public function testAddWarning() {
		MWDebug::warning( 'Warning message' );
		$this->assertEquals(
			array( array(
				'msg' => 'Warning message',
				'type' => 'warn',
				'caller' => 'MWDebugTest::testAddWarning',
			) ),
			MWDebug::getLog()
		);
	}

	public function testAvoidDuplicateDeprecations() {
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		// assertCount() not available on WMF integration server
		$this->assertEquals( 1,
			count( MWDebug::getLog() ),
			"Only one deprecated warning per function should be kept"
		);
	}

	public function testAvoidNonConsecutivesDuplicateDeprecations() {
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::warning( 'some warning' );
		MWDebug::log( 'we could have logged something too' );
		// Another deprecation
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		// assertCount() not available on WMF integration server
		$this->assertEquals( 3,
			count( MWDebug::getLog() ),
			"Only one deprecated warning per function should be kept"
		);
	}
}
