<?php

namespace MediaWiki\Tests\Rest\Helper;

use HashConfig;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\PageContentHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\PageContentHelper
 * @group Database
 */
class PageContentHelperTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private const NO_REVISION_ETAG = '"b620cd7841f9ea8f545f11cc44ce794f848fa2d3"';

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
			new HashConfig( [
				'RightsUrl' => 'https://example.com/rights',
				'RightsText' => 'some rights',
			] ),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getTitleFormatter(),
			$this->getServiceContainer()->getPageStore()
		);

		$authority = $authority ?: $this->mockRegisteredUltimateAuthority();
		$helper->init( $authority, $params );
		return $helper;
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getRole()
	 */
	public function testGetRole() {
		$helper = $this->newHelper();
		$this->assertSame( SlotRecord::MAIN, $helper->getRole() );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getPage()
	 */
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

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getContent()
	 */
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

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getPage()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::checkAccess()
	 */
	public function testNoTitle() {
		$helper = $this->newHelper();

		$this->assertNull( $helper->getTitleText() );
		$this->assertNull( $helper->getPage() );

		$this->assertFalse( $helper->hasContent() );
		$this->assertFalse( $helper->isAccessible() );

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

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getPage()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::checkAccess()
	 */
	public function testNonExistingPage() {
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$title = $page->getTitle();
		$helper = $this->newHelper( [ 'title' => $title->getPrefixedDBkey() ] );

		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitleText() );
		$this->assertNull( $helper->getPage() );

		$this->assertFalse( $helper->hasContent() );
		$this->assertFalse( $helper->isAccessible() );

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

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getPage()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::checkAccess()
	 */
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
		$this->assertFalse( $helper->isAccessible() );

		$this->assertNull( $helper->getLastModified() );

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::getParamSettings()
	 */
	public function testParameterSettings() {
		$helper = $this->newHelper();
		$settings = $helper->getParamSettings();
		$this->assertArrayHasKey( 'title', $settings );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::setCacheControl()
	 */
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

	/**
	 * @covers \MediaWiki\Rest\Handler\PageContentHelper::constructMetadata()
	 */
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
