<?php

namespace MediaWiki\Tests\Rest\Helper;

use BagOStuff;
use DeferredUpdates;
use EmptyBagOStuff;
use Exception;
use ExtensionRegistry;
use HashBagOStuff;
use MediaWiki\Rest\Handler\ParsoidHTMLHelper;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use NullStatsdDataFactory;
use ParserCache;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
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

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '<p>Hello <b>World</b></p>';

	protected function setUp(): void {
		parent::setUp();

		if ( !ExtensionRegistry::getInstance()->isLoaded( 'Parsoid' ) ) {
			$this->markTestSkipped( 'Parsoid is not configured' );
		}

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
		$parserCache = new ParserCache(
			'Test',
			$cache ?: new EmptyBagOStuff(),
			0,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getJsonCodec(),
			new NullStatsdDataFactory(),
			new NullLogger()
		);

		$helper = new ParsoidHTMLHelper(
			$parserCache,
			$this->getServiceContainer()->getWikiPageFactory()
		);

		if ( $parsoid !== null ) {
			$wrapper = TestingAccessWrapper::newFromObject( $helper );
			$wrapper->parsoid = $parsoid;
		}

		return $helper;
	}

	public function testGetHtml() {
		$page = $this->getExistingTestPage( 'HtmlHelperTestPage/with/slashes' );
		$this->assertTrue(
			$this->editPage( $page, self::WIKITEXT )->isGood(),
			'Sanity: edited a page'
		);

		$helper = $this->newHelper();
		$helper->init( $page->getTitle() );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( '<!DOCTYPE html>', $htmlresult );
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( self::HTML, $htmlresult );
	}

	public function testHtmlIsCached() {
		$page = $this->getExistingTestPage( 'HtmlHelperTestPage/with/slashes' );

		$cache = new HashBagOStuff();
		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willReturn( new PageBundle( 'mocked HTML', null, null, '1.0' ) );

		$helper = $this->newHelper( $cache, $parsoid );

		$helper->init( $page->getTitle() );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );

		// check that we can run the test again and ensure that the parse is only run once
		$helper = $this->newHelper( $cache, $parsoid );
		$helper->init( $page->getTitle() );
		$htmlresult = $helper->getHtml()->getRawText();
		$this->assertStringContainsString( 'mocked HTML', $htmlresult );
	}

	public function testEtagLastModified() {
		$time = time();
		MWTimestamp::setFakeTime( $time );

		$page = $this->getExistingTestPage( 'HtmlHelperTestPage/with/slashes' );

		$cache = new HashBagOStuff();

		// First, test it works if nothing was cached yet.
		// Make some time pass since page was created:
		MWTimestamp::setFakeTime( $time + 10 );
		$helper = $this->newHelper( $cache );
		$helper->init( $page->getTitle() );
		$etag = $helper->getETag(); // remember etag using LastModified
		$helper->getHtml(); // put HTML into the cache

		// Now, test that headers work when getting from cache too.
		$helper = $this->newHelper( $cache );
		$helper->init( $page->getTitle() );

		$this->assertNotSame( $etag, $helper->getETag() );
		$etag = $helper->getETag();
		$this->assertSame(
			MWTimestamp::convert( TS_RFC2822, $time + 10 ),
			MWTimestamp::convert( TS_RFC2822, $helper->getLastModified() )
		);

		// Now, expire the cache
		$time += 1000;
		MWTimestamp::setFakeTime( $time );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $time ) ),
			'Sanity: can invalidate cache'
		);
		DeferredUpdates::doUpdates();

		$helper = $this->newHelper( $cache );
		$helper->init( $page->getTitle() );

		$this->assertNotSame( $etag, $helper->getETag() );
		$this->assertSame(
			MWTimestamp::convert( TS_RFC2822, $time ),
			MWTimestamp::convert( TS_RFC2822, $helper->getLastModified() )
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
	 * @param Exception $parsoidException
	 * @param Exception $expectedException
	 */
	public function testHandlesParsoidError(
		Exception $parsoidException,
		Exception $expectedException
	) {
		$page = $this->getExistingTestPage( 'HtmlHelperTestPage/with/slashes' );

		$parsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$parsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		$helper = $this->newHelper( null, $parsoid );
		$helper->init( $page->getTitle() );

		$this->expectExceptionObject( $expectedException );
		$helper->getHtml();
	}

}
