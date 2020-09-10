<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MediaWikiServices;
use Psr\Log\NullLogger;

/**
 * @group large
 * @group Upload
 * @group Database
 *
 * @covers UploadFromUrl
 */
class UploadFromUrlTest extends ApiTestCase {
	private $user;

	protected function setUp() : void {
		parent::setUp();
		$this->user = self::$users['sysop']->getUser();

		$this->setMwGlobals( [
			'wgEnableUploads' => true,
			'wgAllowCopyUploads' => true,
		] );
		$this->setGroupPermissions( 'sysop', 'upload_by_url', true );

		if ( MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
			->newFile( 'UploadFromUrlTest.png' )->exists()
		) {
			$this->deleteFile( 'UploadFromUrlTest.png' );
		}

		$this->installMockHttp();
	}

	/**
	 * @param null|string|array| $request
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function installMockHttp( $request = null ) {
		$options = new ServiceOptions( HttpRequestFactory::CONSTRUCTOR_OPTIONS, [
			'HTTPTimeout' => 1,
			'HTTPConnectTimeout' => 1,
			'HTTPMaxTimeout' => 1,
			'HTTPMaxConnectTimeout' => 1,
		] );

		$mockHttpRequestFactory = $this->getMockBuilder( HttpRequestFactory::class )
			->setConstructorArgs( [ $options, new NullLogger() ] )
			->onlyMethods( [ 'create' ] )
			->getMock();

		if ( $request === null ) {
			$mockHttpRequestFactory->method( 'create' )
				->willThrowException( new LogicException( 'No Fake MWHttpRequest provided!' ) );
		} elseif ( $request instanceof MWHttpRequest ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturn( $request );
		} elseif ( is_callable( $request ) ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnCallback( $request );
		} elseif ( is_array( $request ) ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturnOnConsecutiveCalls( $request );
		} elseif ( is_string( $request ) ) {
			$mockHttpRequestFactory->method( 'create' )
				->willReturn( $this->makeFakeHttpRequest( $request ) );
		}

		$this->setService( 'HttpRequestFactory', function () use ( $mockHttpRequestFactory ) {
			return $mockHttpRequestFactory;
		} );
	}

	/**
	 * @param string $body
	 * @param int $status
	 * @param array $headers
	 *
	 * @return MWHttpRequest
	 */
	private function makeFakeHttpRequest( $body = 'Lorem Ipsum', $statusCode = 200, $headers = [] ) {
		$options = [
			'timeout' => 1,
			'connectTimeout' => 1,
		];

		$mockHttpRequest = $this->getMockBuilder( MWHttpRequest::class )
			->setConstructorArgs( [ 'http://www.example.com/test.png', $options ] )
			->onlyMethods( [ 'execute', 'setCallback' ] )
			->getMock();

		$dataCallback = null;
		$mockHttpRequest->method( 'setCallback' )
			->willReturnCallback(
				function ( $callback ) use ( &$dataCallback ) {
					$dataCallback = $callback;
				}
			);

		$status = Status::newGood( $statusCode );
		$mockHttpRequest->method( 'execute' )
			->willReturnCallback(
				function () use ( &$dataCallback, $body, $status ) {
					if ( $dataCallback ) {
						$dataCallback( $this, $body );
					}
					return $status;
				}
			);

		return $mockHttpRequest;
	}

	/**
	 * Ensure that the job queue is empty before continuing
	 */
	public function testClearQueue() {
		$job = JobQueueGroup::singleton()->pop();
		while ( $job ) {
			$job = JobQueueGroup::singleton()->pop();
		}
		$this->assertFalse( $job );
	}

	public function testIsAllowedHostEmpty() {
		$this->setMwGlobals( [
			'wgCopyUploadsDomains' => [],
		] );

		$this->assertTrue( UploadFromUrl::isAllowedHost( 'https://foo.bar' ) );
	}

	public function testIsAllowedHostDirectMatch() {
		$this->setMwGlobals( [
			'wgCopyUploadsDomains' => [
				'foo.baz',
				'bar.example.baz',
			],
		] );

		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://example.com' ) );

		$this->assertTrue( UploadFromUrl::isAllowedHost( 'https://foo.baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://.foo.baz' ) );

		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://example.baz' ) );
		$this->assertTrue( UploadFromUrl::isAllowedHost( 'https://bar.example.baz' ) );
	}

	public function testIsAllowedHostLastWildcard() {
		$this->setMwGlobals( [
			'wgCopyUploadsDomains' => [
				'*.baz',
			],
		] );

		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo.example' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo.example.baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo/bar.baz' ) );

		$this->assertTrue( UploadFromUrl::isAllowedHost( 'https://foo.baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://subdomain.foo.baz' ) );
	}

	public function testIsAllowedHostWildcardInMiddle() {
		$this->setMwGlobals( [
			'wgCopyUploadsDomains' => [
				'foo.*.baz',
			],
		] );

		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo.baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo.bar.bar.baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo.bar.baz.baz' ) );
		$this->assertFalse( UploadFromUrl::isAllowedHost( 'https://foo.com/.baz' ) );

		$this->assertTrue( UploadFromUrl::isAllowedHost( 'https://foo.example.baz' ) );
		$this->assertTrue( UploadFromUrl::isAllowedHost( 'https://foo.bar.baz' ) );
	}

	/**
	 * @depends testClearQueue
	 */
	public function testSetupUrlDownload( $data ) {
		$token = $this->user->getEditToken();
		$exception = false;

		try {
			$this->doApiRequest( [
				'action' => 'upload',
			] );
		} catch ( ApiUsageException $e ) {
			$exception = true;
			$this->assertEquals( 'The "token" parameter must be set.', $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$exception = false;
		try {
			$this->doApiRequest( [
				'action' => 'upload',
				'token' => $token,
			], $data );
		} catch ( ApiUsageException $e ) {
			$exception = true;
			$this->assertEquals( 'One of the parameters "filekey", "file" and "url" is required.',
				$e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$exception = false;
		try {
			$this->doApiRequest( [
				'action' => 'upload',
				'url' => 'http://www.example.com/test.png',
				'token' => $token,
			], $data );
		} catch ( ApiUsageException $e ) {
			$exception = true;
			$this->assertEquals( 'The "filename" parameter must be set.', $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$this->user->removeGroup( 'sysop' );
		$exception = false;
		try {
			$this->doApiRequest( [
				'action' => 'upload',
				'url' => 'http://www.example.com/test.png',
				'filename' => 'UploadFromUrlTest.png',
				'token' => $token,
			], $data );
		} catch ( ApiUsageException $e ) {
			$exception = true;
			$this->assertStringStartsWith( "The action you have requested is limited to users in the group:",
				$e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );
	}

	/**
	 * @depends testClearQueue
	 */
	public function testSyncDownload( $data ) {
		$file = __DIR__ . '/../../data/upload/png-plain.png';
		$this->installMockHttp( file_get_contents( $file ) );

		$token = $this->user->getEditToken();

		$this->user->addGroup( 'users' );
		$data = $this->doApiRequest( [
			'action' => 'upload',
			'filename' => 'UploadFromUrlTest.png',
			'url' => 'http://upload.wikimedia.org/wikipedia/mediawiki/b/bc/Wiki.png',
			'ignorewarnings' => true,
			'token' => $token,
		], $data );

		$this->assertEquals( 'Success', $data[0]['upload']['result'] );
		$this->deleteFile( 'UploadFromUrlTest.png' );

		return $data;
	}

	protected function deleteFile( $name ) {
		$t = Title::newFromText( $name, NS_FILE );
		$this->assertTrue( $t->exists(), "File '$name' exists" );

		if ( $t->exists() ) {
			$file = MediaWikiServices::getInstance()->getRepoGroup()
				->findFile( $name, [ 'ignoreRedirect' => true ] );
			$empty = "";
			FileDeleteForm::doDelete( $t, $file, $empty, "none", true, $this->user );
			$page = WikiPage::factory( $t );
			$page->doDeleteArticleReal( "testing", $this->user );
		}
		$t = Title::newFromText( $name, NS_FILE );

		$this->assertFalse( $t->exists(), "File '$name' was deleted" );
	}
}
