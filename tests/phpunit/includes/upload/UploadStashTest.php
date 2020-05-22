<?php

use MediaWiki\MediaWikiServices;

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

	protected function setUp() : void {
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

	public function testExceptionNoFileExtension() {
		$this->setMwGlobals( 'wgUser', self::$users['uploader']->getUser() );

		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$stash = new UploadStash( $repo );

		$this->expectExceptionMessage( wfMessage( 'uploadstash-no-extension' )->text() );
		$file = $stash->stashFile( $this->tmpFile );
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
