<?php

namespace MediaWiki\Tests\Rest\Helper;

use BagOStuff;
use DeferredUpdates;
use EmptyBagOStuff;
use Exception;
use ExtensionRegistry;
use HashBagOStuff;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Rest\Handler\ParsoidHTMLHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use ParserCache;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Handler\ParsoidHTMLHelper
 * @group Database
 */
class ParsoidHTMLHelperTest extends MediaWikiIntegrationTestCase {

	private const CACHE_EPOCH = '20001111010101';

	private const TIMESTAMP_OLD = '20200101112233';
	private const TIMESTAMP = '20200101223344';
	private const TIMESTAMP_LATER = '20200101234200';

	private const WIKITEXT_OLD = 'Hello \'\'\'Goat\'\'\'';
	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML_OLD = '<p>Hello <b>Goat</b></p>';
	private const HTML = '<p>Hello <b>World</b></p>';

	protected function setUp(): void {
		parent::setUp();

		if ( !ExtensionRegistry::getInstance()->isLoaded( 'Parsoid' ) ) {
			$this->markTestSkipped( 'Parsoid is not configured' );
		}

		$this->setMwGlobals( 'wgCacheEpoch', self::CACHE_EPOCH );

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
	 * @param BagOStuff|null $cache
	 * @param Parsoid|MockObject|null $parsoid
	 * @return ParsoidHTMLHelper
	 * @throws Exception
	 */
	private function newHelper( BagOStuff $cache = null, Parsoid $parsoid = null ): ParsoidHTMLHelper {
		$cache = $cache ?: new EmptyBagOStuff();

		$parserCache = new ParserCache(
			'TestPCache',
			$cache,
			self::CACHE_EPOCH,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getJsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getWikiPageFactory()
		);

		$revisionOutputCache = new RevisionOutputCache(
			'TestRCache',
			new WANObjectCache( [ 'cache' => $cache ] ),
			60 * 60,
			self::CACHE_EPOCH,
			$this->getServiceContainer()->getJsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger()
		);

		$helper = new ParsoidHTMLHelper(
			$parserCache,
			$revisionOutputCache,
			$this->getServiceContainer()->getGlobalIdGenerator()
		);

		if ( $parsoid !== null ) {
			$wrapper = TestingAccessWrapper::newFromObject( $helper );
			$wrapper->parsoid = $parsoid;
		}

		return $helper;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		MWTimestamp::setFakeTime( self::TIMESTAMP_OLD );
		$this->editPage( $page, self::WIKITEXT_OLD );
		$revisions['first'] = $page->getRevisionRecord();

		MWTimestamp::setFakeTime( self::TIMESTAMP );
		$this->editPage( $page, self::WIKITEXT );
		$revisions['latest'] = $page->getRevisionRecord();

		MWTimestamp::setFakeTime( self::TIMESTAMP_LATER );
		return [ $page, $revisions ];
	}

	public function provideRevisionReferences() {
		return [
			'current' => [ null, [ 'html' => self::HTML, 'timestamp' => self::TIMESTAMP ] ],
			'old' => [ 'first', [ 'html' => self::HTML_OLD, 'timestamp' => self::TIMESTAMP_OLD ] ],
		];
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testGetHtml( $revRef, $revInfo ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$helper = $this->newHelper();
		$helper->init( $page, $rev );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( '<!DOCTYPE html>', $htmlresult );
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( $revInfo['html'], $htmlresult );
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testHtmlIsCached( $revRef, $revInfo ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$cache = new HashBagOStuff();
		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willReturn( new PageBundle( 'mocked HTML', null, null, '1.0' ) );

		$helper = $this->newHelper( $cache, $parsoid );

		$helper->init( $page, $rev );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );

		// check that we can run the test again and ensure that the parse is only run once
		$helper = $this->newHelper( $cache, $parsoid );
		$helper->init( $page, $rev );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );
	}

	/**
	 * @dataProvider provideRevisionReferences()
	 */
	public function testEtagLastModified( $revRef, $revInfo ) {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$rev = $revRef ? $revisions[ $revRef ] : null;

		$cache = new HashBagOStuff();

		// First, test it works if nothing was cached yet.
		$helper = $this->newHelper( $cache );
		$helper->init( $page, $rev );
		$etag = $helper->getETag();
		$lastModified = $helper->getLastModified();
		$helper->getHtml(); // put HTML into the cache

		// make sure the etag didn't change after getHtml();
		$this->assertSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $lastModified ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);

		// Advance the time, but not so much that caches would expire.
		// The time in the header should remain as before.
		$now = MWTimestamp::convert( TS_UNIX, self::TIMESTAMP_LATER ) + 100;
		MWTimestamp::setFakeTime( $now );
		$helper = $this->newHelper( $cache );
		$helper->init( $page, $rev );

		$this->assertSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $lastModified ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);

		// Now, expire the cache. etag and timestamp should change
		$now = MWTimestamp::convert( TS_UNIX, self::TIMESTAMP_LATER ) + 10000;
		MWTimestamp::setFakeTime( $now );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $now ) ),
			'Cannot invalidate cache'
		);
		DeferredUpdates::doUpdates();
		$page->clear();

		$helper = $this->newHelper( $cache );
		$helper->init( $page, $rev );

		$this->assertNotSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_MW, $now ),
			MWTimestamp::convert( TS_MW, $helper->getLastModified() )
		);
	}

	public function provideHandlesParsoidError() {
		yield 'ClientError' => [
			new ClientError( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-html-backend-error' ),
				400,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
		yield 'ResourceLimitExceededException' => [
			new ResourceLimitExceededException( 'TEST_TEST' ),
			new LocalizedHttpException(
				new MessageValue( 'rest-resource-limit-exceeded' ),
				413,
				[
					'reason' => 'TEST_TEST'
				]
			)
		];
	}

	/**
	 * @dataProvider provideHandlesParsoidError
	 */
	public function testHandlesParsoidError(
		Exception $parsoidException,
		Exception $expectedException
	) {
		$page = $this->getExistingTestPage( __METHOD__ );

		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$helper = $this->newHelper( null, $parsoid );
		$helper->init( $page );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
	}

}
