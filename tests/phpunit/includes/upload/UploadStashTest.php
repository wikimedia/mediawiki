<?php

use MediaWiki\Request\FauxRequest;

/**
 * @group Database
 *
 * @covers \UploadStash
 */
class UploadStashTest extends MediaWikiIntegrationTestCase {
	/**
	 * @var string
	 */
	private $tmpFile;

	protected function setUp(): void {
		parent::setUp();

		$this->tmpFile = $this->getNewTempFile();
		file_put_contents( $this->tmpFile, "\x00" );
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
