<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use HashBagOStuff;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\Handler\Helper\RevisionContentHelper;
use MediaWiki\Rest\Handler\RevisionHTMLHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use Psr\Http\Message\StreamInterface;
use ReflectionClass;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;

/**
 * @covers \MediaWiki\Rest\Handler\RevisionHTMLHandler
 * @group Database
 */
class RevisionHTMLHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use HTMLHandlerTestTrait;

	private const WIKITEXT = 'Hello \'\'\'World\'\'\'';

	private const HTML = '>World<';

	/** @var HashBagOStuff */
	private $parserCacheBagOStuff;

	/** @var int */
	private static $uuidCounter = 0;

	protected function setUp(): void {
		parent::setUp();

		$this->parserCacheBagOStuff = new HashBagOStuff();
	}

	/**
	 * @return RevisionHTMLHandler
	 */
	private function newHandler(): RevisionHTMLHandler {
		$services = $this->getServiceContainer();
		$config = [
			'RightsUrl' => 'https://example.com/rights',
			'RightsText' => 'some rights',
			'ParsoidCacheConfig' =>
				MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		];

		$parsoidOutputAccess = new ParsoidOutputAccess(
			$services->getParsoidParserFactory(),
			$services->getParserOutputAccess(),
			$services->getPageStore(),
			$services->getRevisionLookup(),
			$services->getParsoidSiteConfig(),
			$services->getContentHandlerFactory()
		);

		$helperFactory = $this->createNoOpMock(
			PageRestHelperFactory::class,
			[ 'newRevisionContentHelper', 'newHtmlOutputRendererHelper' ]
		);

		$helperFactory->method( 'newRevisionContentHelper' )
			->willReturn( new RevisionContentHelper(
				new ServiceOptions( RevisionContentHelper::CONSTRUCTOR_OPTIONS, $config ),
				$services->getRevisionLookup(),
				$services->getTitleFormatter(),
				$services->getPageStore()
			) );

		$helperFactory->method( 'newHtmlOutputRendererHelper' )
			->willReturn( new HtmlOutputRendererHelper(
				$this->getParsoidOutputStash(),
				$services->getStatsdDataFactory(),
				$parsoidOutputAccess,
				$services->getParserOutputAccess(),
				$services->getPageStore(),
				$services->getRevisionLookup(),
				$services->getParsoidSiteConfig(),
				$services->getHtmlTransformFactory(),
				$services->getContentHandlerFactory(),
				$services->getLanguageFactory()
			) );

		$handler = new RevisionHTMLHandler(
			$helperFactory
		);

		return $handler;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		$this->editPage( $page, self::WIKITEXT );
		$revisions['first'] = $page->getRevisionRecord();

		$this->editPage( $page, 'DEAD BEEF' );
		$revisions['latest'] = $page->getRevisionRecord();

		return [ $page, $revisions ];
	}

	public function testExecuteWithHtml() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [
			'format' => 'with_html'
		] );

		$this->assertResponseData( $revisions['first'], $data );
		$this->assertStringContainsString( '<!DOCTYPE html>', $data['html'] );
		$this->assertStringContainsString( '<html', $data['html'] );
		$this->assertStringContainsString( self::HTML, $data['html'] );
	}

	public function testExecuteHtmlOnly() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$this->assertStatusGood( $this->editPage( $page, self::WIKITEXT ),
			'Edited a page'
		);

		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );

		$htmlResponse = (string)$response->getBody();
		$this->assertStringContainsString( '<!DOCTYPE html>', $htmlResponse );
		$this->assertStringContainsString( '<html', $htmlResponse );
		$this->assertStringContainsString( self::HTML, $htmlResponse );
	}

	public function testEtagLastModified() {
		$time = time();
		MWTimestamp::setFakeTime( $time );

		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		// First, test it works if nothing was cached yet.
		// Make some time pass since page was created:
		MWTimestamp::setFakeTime( $time + 10 );
		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time + 10 ),
			$response->getHeaderLine( 'Last-Modified' ) );

		$etag = $response->getHeaderLine( 'ETag' );

		// Now, test that headers work when getting from cache too.
		MWTimestamp::setFakeTime( $time + 20 );
		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertSame( $etag, $response->getHeaderLine( 'ETag' ) );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time + 10 ),
			$response->getHeaderLine( 'Last-Modified' ) );

		// Now, expire the cache, and assert we are getting a new timestamp back
		MWTimestamp::setFakeTime( $time + 10000 );
		$this->assertTrue(
			$page->getTitle()->invalidateCache( MWTimestamp::convert( TS_MW, $time ) ),
			'Can invalidate cache'
		);
		DeferredUpdates::doUpdates();

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
		$this->assertArrayHasKey( 'ETag', $response->getHeaders() );
		$this->assertNotSame( $etag, $response->getHeaderLine( 'ETag' ) );
		$this->assertArrayHasKey( 'Last-Modified', $response->getHeaders() );
		$this->assertSame( MWTimestamp::convert( TS_RFC2822, $time + 10000 ),
			$response->getHeaderLine( 'Last-Modified' ) );
	}

	public static function provideHandlesParsoidError() {
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
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$request = new RequestData(
			[ 'pathParams' => [ 'id' => $revisions['first']->getId() ] ]
		);

		$services = $this->getServiceContainer();
		$parsoidParser = $services->getParsoidParserFactory()->create();

		// Mock Parsoid
		$mockParsoid = $this->createNoOpMock( Parsoid::class, [ 'wikitext2html' ] );
		$mockParsoid->expects( $this->once() )
			->method( 'wikitext2html' )
			->willThrowException( $parsoidException );

		// Install it in the ParsoidParser object
		$reflector = new ReflectionClass( ParsoidParser::class );
		$prop = $reflector->getProperty( 'parsoid' );
		$prop->setAccessible( true );
		$prop->setValue( $parsoidParser, $mockParsoid );
		$this->assertEquals( $prop->getValue( $parsoidParser ), $mockParsoid );

		// Create a mock Parsoid factory that returns the ParsoidParser object
		// with the mocked Parsoid object.
		$mockParsoidParserFactory = $this->createNoOpMock( ParsoidParserFactory::class, [ 'create' ] );
		$mockParsoidParserFactory->expects( $this->once() )
			->method( 'create' )
			->willReturn( $parsoidParser );

		// Ensure WiktiextContentHandler has the mock ParsoidParserFactory
		$wtHandler = $services->getContentHandlerFactory()->getContentHandler( 'wikitext' );
		$reflector = new ReflectionClass( 'WikitextContentHandler' );
		$prop = $reflector->getProperty( 'parsoidParserFactory' );
		$prop->setAccessible( true );
		$prop->setValue( $wtHandler, $mockParsoidParserFactory );
		$this->assertEquals( $prop->getValue( $wtHandler ), $mockParsoidParserFactory );

		$handler = $this->newHandler();
		$this->expectExceptionObject( $expectedException );
		$this->executeHandler( $handler, $request, [
			'format' => 'html'
		] );
	}

	public function testExecute_missingparam() {
		$request = new RequestData();

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "paramvalidator-missingparam", [ 'revision' ] ),
				400
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_error() {
		$request = new RequestData( [ 'pathParams' => [ 'id' => '2076419894' ] ] );

		$this->expectExceptionObject(
			new LocalizedHttpException(
				new MessageValue( "rest-nonexistent-revision", [ 'testing' ] ),
				404
			)
		);

		$handler = $this->newHandler();
		$this->executeHandler( $handler, $request );
	}

	/**
	 * @param RevisionRecord $rev
	 * @param array $data
	 */
	private function assertResponseData( RevisionRecord $rev, array $data ): void {
		$title = $rev->getPageAsLinkTarget();

		$this->assertSame( $rev->getId(), $data['id'] );
		$this->assertSame( $rev->getSize(), $data['size'] );
		$this->assertSame( $rev->isMinor(), $data['minor'] );
		$this->assertSame(
			wfTimestampOrNull( TS_ISO_8601, $rev->getTimestamp() ),
			$data['timestamp']
		);
		$this->assertSame( $title->getArticleID(), $data['page']['id'] );
		$this->assertSame( $title->getDBkey(), $data['page']['key'] ); // assume main namespace
		$this->assertSame( $title->getText(), $data['page']['title'] ); // assume main namespace
		$this->assertSame( CONTENT_MODEL_WIKITEXT, $data['content_model'] );
		$this->assertSame( 'https://example.com/rights', $data['license']['url'] );
		$this->assertSame( 'some rights', $data['license']['title'] );
		$this->assertSame( $rev->getComment()->text, $data['comment'] );
		$this->assertSame( $rev->getUser()->getId(), $data['user']['id'] );
		$this->assertSame( $rev->getUser()->getName(), $data['user']['name'] );
	}

	/**
	 * The below 2 request are described as follows;
	 *
	 * Request One:
	 *   This request stashes data-parsoid to the parsoid output stash and caches the
	 *   stash key in ::cachedStashedKey so that we can use to perform a stash lookup
	 *   in the near future.
	 *
	 * Request Two:
	 *   This request then uses the request header ETag which is the same as that in
	 *   the cached stashed key container because during the second request, no stashing
	 *   was done and the page revision is the same. So what is in the output response headers
	 *   in the user's browser will be exactly what is in the parsoid output stash.
	 *
	 * NOTE: if we make another request which actually stashes, that cached stash key will
	 *   be updated, and we can use it to access the stash's latest entry.
	 */
	public function testExecuteStashParsoidOutput() {
		[ /* page */, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$outputStash = $this->getParsoidOutputStash();

		[ /* $html1 */, $etag1, $stashKey1 ] = $this->executeRevisionHTMLRequest(
			$revisions['first']->getId(),
			[ 'stash' => true ]
		);
		$this->assertNotNull( $outputStash->get( $stashKey1 ) );

		[ /* $html2 */, $etag2, $stashKey2 ] = $this->executeRevisionHTMLRequest(
			$revisions['first']->getId(),
			[ 'stash' => false ]
		);

		// The etags should be different, but the stash key should be identicl
		$this->assertNotSame( $etag1, $etag2 );
		$this->assertSame( $stashKey1->getKey(), $stashKey2->getKey() );

		// Ensure nothing has changed with the output stash
		$this->assertNotNull( $outputStash->get( $stashKey1 ) );

		// Make sure the output for stashed and unstashed doesn't have the same tag,
		// since it will actually be different!
		// FIXME: implement flavors
	}

	public function testETagVariesOnFormat() {
		$page = $this->getExistingTestPage();

		[ /* $html1 */, $etag1 ] =
			$this->executeRevisionHTMLRequest( $page->getLatest(), [], [ 'format' => 'html' ] );

		[ /* $html2 */, $etag2 ] =
			$this->executeRevisionHTMLRequest( $page->getLatest(), [], [ 'format' => 'with_html' ] );

		$this->assertNotSame( $etag1, $etag2 );
	}

	public function testStashingWithRateLimitExceeded() {
		// Set the rate limit to 1 request per minute
		$this->overrideConfigValue(
			MainConfigNames::RateLimits,
			[
				'stashbasehtml' => [
					'&can-bypass' => false,
					'ip' => [ 1, 60 ],
					'newbie' => [ 1, 60 ]
				]
			]
		);

		$page = $this->getExistingTestPage();

		$authority = $this->getAuthority();
		$this->executeRevisionHTMLRequest( $page->getLatest(), [ 'stash' => true ], [], $authority );
		// In this request, the rate limit has been exceeded, so it should throw.
		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( 429 );
		$this->executeRevisionHTMLRequest( $page->getLatest(), [ 'stash' => true ], [], $authority );
	}

	/**
	 * @dataProvider provideExecuteWithVariant
	 */
	public function testExecuteWithVariant(
		string $format,
		callable $bodyHtmlHandler,
		string $expectedContentLanguage,
		string $expectedVaryHeader
	) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$this->editPage( $page, '<p>test language conversion</p>', 'Edited a page' );
		$revRecord = $page->getRevisionRecord();

		$acceptLanguage = 'en-x-piglatin';
		$request = new RequestData(
			[
				'pathParams' => [ 'id' => $revRecord->getId() ],
				'headers' => [
					'Accept-Language' => $acceptLanguage
				]
			]
		);

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request, [
			'format' => $format
		] );

		$responseBody = json_decode( $response->getBody(), true );
		$htmlBody = $bodyHtmlHandler( $response->getBody() );
		$contentLanguageHeader = $response->getHeaderLine( 'Content-Language' );
		$varyHeader = $response->getHeaderLine( 'Vary' );

		// html format doesn't return a response in JSON format
		if ( $responseBody ) {
			$this->assertResponseData( $revRecord, $responseBody );
		}
		$this->assertStringContainsString( '>esttay anguagelay onversioncay<', $htmlBody );
		$this->assertEquals( $expectedContentLanguage, $contentLanguageHeader );
		$this->assertStringContainsStringIgnoringCase( $expectedVaryHeader, $varyHeader );
		$this->assertStringContainsString( $acceptLanguage, $response->getHeaderLine( 'ETag' ) );
	}

	public static function provideExecuteWithVariant() {
		yield 'with_html request should contain accept language but not content language' => [
			'with_html',
			static function ( StreamInterface $response ) {
				return json_decode( $response->getContents(), true )['html'];
			},
			'',
			'accept-language'
		];
		yield 'html request should contain accept and content language' => [
			'html',
			static function ( StreamInterface $response ) {
				return $response->getContents();
			},
			'en-x-piglatin',
			'accept-language'
		];
	}
}
