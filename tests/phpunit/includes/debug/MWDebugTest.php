<?php

class MWDebugTest extends MediaWikiTestCase {


	function setUp() {
		// Make sure MWDebug class is enabled
		static $MWDebugEnabled = false;
		if( !$MWDebugEnabled ) {
			MWDebug::init();
			$MWDebugEnabled = true;
		}
	}

	function tearDown() {
		/** Clear log before each test */
		MWDebug::clearLog();
	}

	/**
	 * @group Broken
	 */
	function testAddLog() {
		MWDebug::log( 'logging a string' );
		$this->assertEquals( array( array(
			'msg' => 'logging a string',
			'type' => 'log',
			'caller' => __METHOD__ ,
			) ),
			MWDebug::getLog()
		);
	}

	/**
	 * @group Broken
	 */
	function testAddWarning() {
		MWDebug::warning( 'Warning message' );
		$this->assertEquals( array( array(
			'msg' => 'Warning message',
			'type' => 'warn',
			'caller' => 'MWDebug::warning',
			) ),
			MWDebug::getLog()
		);
	}

	/**
	 * Broken on gallium which use an old PHPUnit version
	 * @group Broken
	 */
	function testAvoidDuplicateDeprecations() {
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		$this->assertCount( 1,
			MWDebug::getLog(),
			"Only one deprecated warning per function should be kept"
		);
	}

	/**
	 * Broken on gallium which use an old PHPUnit version
	 * @group Broken
	 */
	function testAvoidNonConsecutivesDuplicateDeprecations() {
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );
		MWDebug::warning( 'some warning' );
		MWDebug::log( 'we could have logged something too' );
		// Another deprecation
		MWDebug::deprecated( 'wfOldFunction', '1.0', 'component' );

		$this->assertCount( 3,
			MWDebug::getLog(),
			"Only one deprecated warning per function should be kept"
		);
	}
}
