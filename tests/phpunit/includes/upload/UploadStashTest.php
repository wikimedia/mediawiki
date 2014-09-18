<?php

/**
 * @group Database
 *
 * @covers UploadStash
 */
class UploadStashTest extends MediaWikiTestCase {
	/**
	 * @var array Array of UploadStashTestUser
	 */
	public static $users;

	/**
	 * @var string
	 */
	private $bug29408File;

	protected function setUp() {
		parent::setUp();

		// Setup a file for bug 29408
		$this->bug29408File = __DIR__ . '/bug29408';
		file_put_contents( $this->bug29408File, "\x00" );

		self::$users = array(
			'sysop' => new TestUser(
				'Uploadstashtestsysop',
				'Upload Stash Test Sysop',
				'upload_stash_test_sysop@example.com',
				array( 'sysop' )
			),
			'uploader' => new TestUser(
				'Uploadstashtestuser',
				'Upload Stash Test User',
				'upload_stash_test_user@example.com',
				array()
			)
		);
	}

	protected function tearDown() {
		if ( file_exists( $this->bug29408File . "." ) ) {
			unlink( $this->bug29408File . "." );
		}

		if ( file_exists( $this->bug29408File ) ) {
			unlink( $this->bug29408File );
		}

		parent::tearDown();
	}

	/**
	 * @todo give this test a real name explaining what is being tested here
	 */
	public function testBug29408() {
		$this->setMwGlobals( 'wgUser', self::$users['uploader']->user );

		$repo = RepoGroup::singleton()->getLocalRepo();
		$stash = new UploadStash( $repo );

		// Throws exception caught by PHPUnit on failure
		$file = $stash->stashFile( $this->bug29408File );
		// We'll never reach this point if we hit bug 29408
		$this->assertTrue( true, 'Unrecognized file without extension' );

		$stash->removeFile( $file->getFileKey() );
	}

	public static function provideInvalidRequests() {
		return array(
			'Check failure on bad wpFileKey' =>
				array( new FauxRequest( array( 'wpFileKey' => 'foo' ) ) ),
			'Check failure on bad wpSessionKey' =>
				array( new FauxRequest( array( 'wpSessionKey' => 'foo' ) ) ),
		);
	}

	/**
	 * @dataProvider provideInvalidRequests
	 */
	public function testValidRequestWithInvalidRequests( $request ) {
		$this->assertFalse( UploadFromStash::isValidRequest( $request ) );
	}

	public static function provideValidRequests() {
		return array(
			'Check good wpFileKey' =>
				array( new FauxRequest( array( 'wpFileKey' => 'testkey-test.test' ) ) ),
			'Check good wpSessionKey' =>
				array( new FauxRequest( array( 'wpFileKey' => 'testkey-test.test' ) ) ),
			'Check key precedence' =>
				array( new FauxRequest( array(
					'wpFileKey' => 'testkey-test.test',
					'wpSessionKey' => 'foo'
				) ) ),
		);
	}
	/**
	 * @dataProvider provideValidRequests
	 */
	public function testValidRequestWithValidRequests( $request ) {
		$this->assertTrue( UploadFromStash::isValidRequest( $request ) );
	}

}
