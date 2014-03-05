<?php

/**
 * @covers MediaWikiTestCase
 * @author Adam Shorland
 */
class MediaWikiTestCaseTest extends MediaWikiTestCase {

	const GLOBAL_KEY_EXISTING = 'MediaWikiTestCaseTestGLOBAL-Existing';
	const GLOBAL_KEY_NONEXISTING = 'MediaWikiTestCaseTestGLOBAL-NonExisting';

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		$GLOBALS[self::GLOBAL_KEY_EXISTING] = 'foo';
	}

	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();
		unset( $GLOBALS[self::GLOBAL_KEY_EXISTING] );
	}

	/**
	 * @covers MediaWikiTestCase::setMwGlobals
	 * @covers MediaWikiTestCase::tearDown
	 */
	public function testSetGlobalsAreRestoredOnTearDown() {
		$this->setMwGlobals( self::GLOBAL_KEY_EXISTING, 'bar' );
		$this->assertEquals(
			'bar',
			$GLOBALS[self::GLOBAL_KEY_EXISTING],
			'Global failed to correctly set'
		);

		$this->tearDown();

		$this->assertEquals(
			'foo',
			$GLOBALS[self::GLOBAL_KEY_EXISTING],
			'Global failed to be restored on tearDown'
		);
	}

	/**
	 * @covers MediaWikiTestCase::stashMwGlobals
	 * @covers MediaWikiTestCase::tearDown
	 */
	public function testStashedGlobalsAreRestoredOnTearDown() {
		$this->stashMwGlobals( self::GLOBAL_KEY_EXISTING );
		$GLOBALS[self::GLOBAL_KEY_EXISTING] = 'bar';
		$this->assertEquals(
			'bar',
			$GLOBALS[self::GLOBAL_KEY_EXISTING],
			'Global failed to correctly set'
		);

		$this->tearDown();

		$this->assertEquals(
			'foo',
			$GLOBALS[self::GLOBAL_KEY_EXISTING],
			'Global failed to be restored on tearDown'
		);
	}

	/**
	 * @covers MediaWikiTestCase::stashMwGlobals
	 * @covers MediaWikiTestCase::tearDown
	 */
	public function testUnsetStashedGlobalsAreUnsetOnTearDown() {
		$this->stashMwGlobals( self::GLOBAL_KEY_NONEXISTING );
		$GLOBALS[self::GLOBAL_KEY_NONEXISTING] = 'bar';
		$this->assertEquals(
			'bar',
			$GLOBALS[self::GLOBAL_KEY_NONEXISTING],
			'Global failed to correctly set'
		);

		$this->tearDown();

		$this->assertFalse(
			array_key_exists( self::GLOBAL_KEY_NONEXISTING, $GLOBALS ),
			'Global failed to be restored on tearDown'
		);
	}

}
