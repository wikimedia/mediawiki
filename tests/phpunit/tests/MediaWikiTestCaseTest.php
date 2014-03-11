<?php

/**
 * @covers MediaWikiTestCase
 * @author Adam Shorland
 */
class MediaWikiTestCaseTest extends MediaWikiTestCase {

	const GLOBAL_KEY_EXISTING = 'MediaWikiTestCaseTestGLOBAL-Existing';
	const GLOBAL_KEY_NONEXISTING = 'MediaWikiTestCaseTestGLOBAL-NONExisting';

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
	 */
	public function testExceptionThrownWhenStashingNonExistentGlobals() {
		$this->setExpectedException(
			'Exception',
			'Global with key ' . self::GLOBAL_KEY_NONEXISTING . ' doesn\'t exist and cant be stashed'
		);

		$this->stashMwGlobals( self::GLOBAL_KEY_NONEXISTING );
	}

}
