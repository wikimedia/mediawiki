<?php

use MediaWiki\Request\FauxRequest;
use MediaWiki\Upload\UploadFromStash;

/**
 * @group Database
 *
 * @covers \MediaWiki\Upload\UploadFromStash
 */
class UploadFromStashTest extends MediaWikiIntegrationTestCase {

	public function testIsValid() {
		$validKey = 'aaa.aaa.tmp';
		$this->assertTrue( UploadFromStash::isValidKey( $validKey ) );

		$request = new FauxRequest( [ 'wpFileKey' => $validKey ], true );
		$this->assertTrue( UploadFromStash::isValidRequest( $request ) );
		$request = new FauxRequest( [ 'wpSessionKey' => $validKey ], true );
		$this->assertTrue( UploadFromStash::isValidRequest( $request ) );
	}

	public function testUploadStash() {
		$user = $this->getTestUser()->getUser();
		$filepath = __DIR__ . '/../../data/media/1bit-png.png';
		$filename = 'TestStash.png';

		// file is removed after upload, provide a copy to allow the delete
		$tempFile = $this->getNewTempFile();
		file_put_contents( $tempFile, file_get_contents( $filepath ) );

		$stash = $this->getServiceContainer()->getRepoGroup()
			->getLocalRepo()->getUploadStash( $user );
		$file = $stash->stashFile( $tempFile, 'image/png' );
		$filekey = $file->getFileKey();

		$upload = new UploadFromStash( $user );
		$upload->initialize(
			$filekey,
			$filename
		);
		$status = $upload->performUpload( 'comment', 'page', false, $user );
		$this->assertStatusGood( $status );
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
}
