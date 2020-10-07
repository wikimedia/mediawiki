<?php

use MediaWiki\MediaWikiServices;

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
