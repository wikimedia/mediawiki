<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use Exception;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\Helper\PageContentHelper;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper
 * @group Database
 */
class RevisionContentHelperTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private const NO_REVISION_ETAG = '"b620cd7841f9ea8f545f11cc44ce794f848fa2d3"';

	/**
	 * @param array $params
	 * @param Authority|null $authority
	 * @return RevisionContentHelper
	 * @throws Exception
	 */
	private function newHelper(
		array $params = [],
		?Authority $authority = null
	): RevisionContentHelper {
		$helper = new RevisionContentHelper(
			new ServiceOptions(
				PageContentHelper::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::RightsUrl => 'https://example.com/rights',
					MainConfigNames::RightsText => 'some rights',
				]
			),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getTitleFormatter(),
			$this->getServiceContainer()->getPageStore(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getChangeTagsStore()
		);

		$authority = $authority ?: $this->mockRegisteredUltimateAuthority();
		$helper->init( $authority, $params );
		return $helper;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		$this->editPage( $page, 'First revision of ' . $name );
		$revisions['first'] = $page->getRevisionRecord();

		$this->editPage( $page, 'DEAD BEEF' );
		$revisions['latest'] = $page->getRevisionRecord();

		return [ $page, $revisions ];
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getRole()
	 */
	public function testGetRole() {
		$helper = $this->newHelper();
		$this->assertSame( SlotRecord::MAIN, $helper->getRole() );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getPage()
	 */
	public function testGetPage() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$helper = $this->newHelper( [ 'id' => $revisions['first']->getId() ] );
		$this->assertSame( $page->getTitle()->getPrefixedDBKey(), $helper->getTitleText() );

		$this->assertInstanceOf( ExistingPageRecord::class, $helper->getPage() );
		$this->assertTrue( $helper->getPage()->isSamePageAs( $page->getTitle() ) );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getContent()
	 */
	public function testGetTargetRevisionAndContent() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$helper = $this->newHelper( [ 'id' => $revisions['first']->getId() ] );

		$targetRev = $helper->getTargetRevision();
		$this->assertInstanceOf( RevisionRecord::class, $targetRev );
		$this->assertSame( $revisions['first']->getId(), $targetRev->getId() );

		$pageContent = $helper->getContent();
		$this->assertSame(
			$revisions['first']->getContent( SlotRecord::MAIN )->serialize(),
			$pageContent->serialize()
		);
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getPage()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::checkAccess()
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
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getPage()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::checkAccess()
	 */
	public function testNonExistingRevision() {
		$helper = $this->newHelper( [ 'id' => 287436534 ] );

		$this->assertSame( 287436534, $helper->getRevisionId() );
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
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getPage()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::checkAccess()
	 */
	public function testForbidenPage() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$title = $page->getTitle();
		$helper = $this->newHelper(
			[ 'id' => $revisions['first']->getId() ],
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
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::getParamSettings()
	 */
	public function testParameterSettings() {
		$helper = $this->newHelper();
		$settings = $helper->getParamSettings();
		$this->assertArrayHasKey( 'id', $settings );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::setCacheControl()
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
	 * @covers \MediaWiki\Rest\Handler\Helper\RevisionContentHelper::constructMetadata()
	 */
	public function testConstructMetadata() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$title = $page->getTitle();

		$revision = $revisions['first'];
		$content = $revision->getContent( SlotRecord::MAIN );
		$expected = [
			'id' => $revision->getId(),
			'size' => $revision->getSize(),
			'minor' => $revision->isMinor(),
			'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() ),
			'content_model' => $content->getModel(),
			'page' => [
				'id' => $title->getArticleID(),
				'key' => $title->getPrefixedDBkey(),
				'title' => $title->getPrefixedText(),
			],
			'license' => [
				'url' => 'https://example.com/rights',
				'title' => 'some rights',
			],
			'user' => [
				'id' => $revision->getUser()->getId(),
				'name' => $revision->getUser()->getName(),
			],
			'comment' => '',
			'delta' => null, // first revision doesn't have a delta for now
		];

		$helper = $this->newHelper( [ 'id' => $revision->getId() ] );
		$data = $helper->constructMetadata();
		$this->assertEquals( $expected, $data );
	}

}
