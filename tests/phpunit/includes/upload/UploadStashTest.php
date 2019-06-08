<?php

/**
 * @group Database
 *
 * @covers UploadStash
 */
class UploadStashTest extends MediaWikiTestCase {
	/**
	 * @var TestUser[] Array of UploadStashTestUser
	 */
	public static $users;

	/**
	 * @var string
	 */
	private $tmpFile;

	protected function setUp() {
		parent::setUp();

		$this->tmpFile = $this->getNewTempFile();
		file_put_contents( $this->tmpFile, "\x00" );

		self::$users = [
			'sysop' => new TestUser(
				'Uploadstashtestsysop',
				'Upload Stash Test Sysop',
				'upload_stash_test_sysop@example.com',
				[ 'sysop' ]
			),
			'uploader' => new TestUser(
				'Uploadstashtestuser',
				'Upload Stash Test User',
				'upload_stash_test_user@example.com',
				[]
			)
		];
	}

	/**
	 * @todo give this test a real name explaining what is being tested here
	 */
	public function testT31408() {
		$this->setMwGlobals( 'wgUser', self::$users['uploader']->getUser() );

		$repo = RepoGroup::singleton()->getLocalRepo();
		$stash = new UploadStash( $repo );

		// Throws exception caught by PHPUnit on failure
		$file = $stash->stashFile( $this->tmpFile );
		// We'll never reach this point if we hit T31408
		$this->assertTrue( true, 'Unrecognized file without extension' );

		$stash->removeFile( $file->getFileKey() );
	}

	public static function provideInvalidRequests() {
		return [
			'Check failure on bad wpFileKey' =>
				[ new FauxRequest( [ 'wpFileKey' => 'foo' ] ) ],
			'Check failure on bad wpSessionKey' =>
				[ new FauxRequest( [ 'wpSessionKey' => 'foo' ] ) ],
		];
	}

	/**
	 * @dataProvider provideInvalidRequests
	 */
	public function testValidRequestWithInvalidRequests( $request ) {
		$this->assertFalse( UploadFromStash::isValidRequest( $request ) );
	}

	public static function provideValidRequests() {
		return [
			'Check good wpFileKey' =>
				[ new FauxRequest( [ 'wpFileKey' => 'testkey-test.test' ] ) ],
			'Check good wpSessionKey' =>
				[ new FauxRequest( [ 'wpFileKey' => 'testkey-test.test' ] ) ],
			'Check key precedence' =>
				[ new FauxRequest( [
					'wpFileKey' => 'testkey-test.test',
					'wpSessionKey' => 'foo'
				] ) ],
		];
	}

	/**
	 * @dataProvider provideValidRequests
	 */
	public function testValidRequestWithValidRequests( $request ) {
		$this->assertTrue( UploadFromStash::isValidRequest( $request ) );
	}

	public function testExceptionWhenStoreTempFails() {
		$mockRepoStoreStatusResult = Status::newFatal( 'TEST_ERROR' );
		$mockRepo = $this->getMockBuilder( FileRepo::class )
			->disableOriginalConstructor()
			->getMock();
		$mockRepo->expects( $this->once() )
			->method( 'storeTemp' )
			->willReturn( $mockRepoStoreStatusResult );

		$stash = new UploadStash( $mockRepo );
		try {
			$stash->stashFile( $this->tmpFile );
			$this->fail( 'Expected UploadStashFileException not thrown' );
		} catch ( UploadStashFileException $e ) {
			$this->assertInstanceOf( ILocalizedException::class, $e );
		} catch ( Exception $e ) {
			$this->fail( 'Unexpected exception class ' . get_class( $e ) );
		}
	}
}
