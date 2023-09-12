<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\PageContentHelper
 * @group Database
 */
class PageContentHelperTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private const NO_REVISION_ETAG = '"7afa43d0f642f1fda1b8e30f4f67243049f5fe77"';

	protected function setUp(): void {
		parent::setUp();

		// Clean up these tables after each test
		$this->tablesUsed = [
			'page',
			'revision',
			'comment',
			'text',
			'content'
		];
	}

	/**
	 * @param array $params
	 * @param Authority|null $authority
	 * @return PageContentHelper
	 * @throws \Exception
	 */
	private function newHelper(
		array $params = [],
		Authority $authority = null
	): PageContentHelper {
		$helper = new PageContentHelper(
			new ServiceOptions(
				PageContentHelper::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::RightsUrl => 'https://example.com/rights',
					MainConfigNames::RightsText => 'some rights',
				]
			),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getTitleFormatter(),
			$this->getServiceContainer()->getPageStore()
		);

		$authority = $authority ?: $this->mockRegisteredUltimateAuthority();
		$helper->init( $authority, $params );
		return $helper;
	}

	public function testGetRole() {
		$helper = $this->newHelper();
		$this->assertSame( SlotRecord::MAIN, $helper->getRole() );
	}

	public function testGetTitle() {
		$this->getExistingTestPage( 'Foo' );

		$helper = $this->newHelper( [ 'title' => 'Foo' ] );
		$this->assertSame( 'Foo', $helper->getTitleText() );

		$this->assertInstanceOf( ExistingPageRecord::class, $helper->getPage() );
		$this->assertSame(
			'Foo',
			$this->getServiceContainer()->getTitleFormatter()->getPrefixedDBkey( $helper->getPage() )
		);
	}

	public function testGetTargetRevisionAndContent() {
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $page->getRevisionRecord();

		$helper = $this->newHelper( [ 'title' => $page->getTitle()->getPrefixedDBkey() ] );

		$targetRev = $helper->getTargetRevision();
		$this->assertInstanceOf( RevisionRecord::class, $targetRev );
		$this->assertSame( $rev->getId(), $targetRev->getId() );

		$pageContent = $helper->getContent();
		$this->assertSame(
			$rev->getContent( SlotRecord::MAIN )->serialize(),
			$pageContent->serialize()
		);
	}

	public function testNoTitle() {
		$helper = $this->newHelper();

		$this->assertNull( $helper->getTitleText() );
		$this->assertNull( $helper->getPage() );

		$this->assertFalse( $helper->hasContent() );

		$this->assertNull( $helper->getTargetRevision() );

		try {
			$helper->getContent();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}
	}

	public function testCheckHasContent() {
		$page = $this->getNonexistingTestPage( __METHOD__ )->getDBkey();
		$helper = $this->newHelper( [ 'title' => $page ] );

		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 404 );

		$helper->checkHasContent();
	}

	public function testCheckAccessPermission() {
		$helper = $this->newHelper();

		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 403 );

		$helper->checkAccessPermission();
	}

	public function testNonExistingPage() {
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$title = $page->getTitle();
		$helper = $this->newHelper( [ 'title' => $title->getPrefixedDBkey() ] );

		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitleText() );
		$this->assertNull( $helper->getPage() );

		$this->assertFalse( $helper->hasContent() );
		$this->assertTrue( $helper->isAccessible() );

		$this->assertNull( $helper->getTargetRevision() );

		$this->assertNull( $helper->getLastModified() );
		$this->assertSame( self::NO_REVISION_ETAG, $helper->getETag() );

		try {
			$helper->getContent();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}
	}

	public function testForbidenPage() {
		$page = $this->getExistingTestPage( __METHOD__ );
		$title = $page->getTitle();
		$helper = $this->newHelper(
			[ 'title' => $title->getPrefixedDBkey() ],
			$this->mockAnonNullAuthority()
		);

		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitleText() );
		$this->assertTrue( $helper->getPage()->isSamePageAs( $title ) );

		$this->assertTrue( $helper->hasContent() );
		$this->assertFalse( $helper->useDefaultSystemMessage() );
		$this->assertNull( $helper->getDefaultSystemMessage() );
		$this->assertFalse( $helper->isAccessible() );

		$this->assertNull( $helper->getLastModified() );

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}
	}

	public function testForbiddenMessagePage() {
		$page = $this->getNonexistingTestPage(
			Title::makeTitle( NS_MEDIAWIKI, 'Logouttext' )
		);
		$title = $page->getTitle();
		$helper = $this->newHelper(
			[ 'title' => $title->getPrefixedDBkey() ],
			$this->mockAnonNullAuthority()
		);

		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitleText() );
		$this->assertNull( $helper->getPage() );

		$this->assertTrue( $helper->hasContent() );
		$this->assertTrue( $helper->useDefaultSystemMessage() );
		$this->assertNotNull( $helper->getDefaultSystemMessage() );
		$this->assertFalse( $helper->isAccessible() );

		$this->assertNull( $helper->getLastModified() );

		$this->expectException( HttpException::class );
		$this->expectExceptionCode( 403 );
		$helper->checkAccess();
	}

	public function testMessagePage() {
		$page = $this->getNonexistingTestPage(
			Title::makeTitle( NS_MEDIAWIKI, 'Logouttext' )
		);
		$title = $page->getTitle();
		$helper = $this->newHelper(
			[ 'title' => $title->getPrefixedDBkey() ],
			$this->mockAnonUltimateAuthority()
		);

		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitleText() );
		$this->assertNull( $helper->getPage() );

		$this->assertTrue( $helper->hasContent() );
		$this->assertTrue( $helper->useDefaultSystemMessage() );
		$this->assertNotNull( $helper->getDefaultSystemMessage() );
		$this->assertTrue( $helper->isAccessible() );

		$this->assertNull( $helper->getLastModified() );

		// The line below should not throw any exception
		$helper->checkAccess();
	}

	public function testParameterSettings() {
		$helper = $this->newHelper();
		$settings = $helper->getParamSettings();
		$this->assertArrayHasKey( 'title', $settings );
	}

	public function testCacheControl() {
		$helper = $this->newHelper();

		$response = new Response();

		$helper->setCacheControl( $response ); // default
		$this->assertStringContainsString( 'max-age=5', $response->getHeaderLine( 'Cache-Control' ) );

		$helper->setCacheControl( $response, 2 ); // explicit
		$this->assertStringContainsString( 'max-age=2', $response->getHeaderLine( 'Cache-Control' ) );

		$helper->setCacheControl( $response, 1000 * 1000 ); // too big
		$this->assertStringContainsString( 'max-age=5', $response->getHeaderLine( 'Cache-Control' ) );
	}

	public function testConstructMetadata() {
		$page = $this->getExistingTestPage( __METHOD__ );
		$title = $page->getTitle();

		$revision = $page->getRevisionRecord();
		$content = $revision->getContent( SlotRecord::MAIN );
		$expected = [
			'id' => $title->getArticleID(),
			'key' => $title->getPrefixedDBkey(),
			'title' => $title->getPrefixedText(),
			'latest' => [
				'id' => $revision->getId(),
				'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() )
			],
			'content_model' => $content->getModel(),
			'license' => [
				'url' => 'https://example.com/rights',
				'title' => 'some rights',
			]
		];

		$helper = $this->newHelper( [ 'title' => $title->getPrefixedDBkey() ] );
		$data = $helper->constructMetadata();

		$this->assertEquals( $expected, $data );
	}

}
