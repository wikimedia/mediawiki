<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Tests\MockEnvironment;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\EntryPoint
 */
class EntryPointTest extends MediaWikiIntegrationTestCase {
	use RestTestTrait;

	public function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::RestPath, '/rest' );
	}

	private function createRouter( RequestInterface $request ) {
		return $this->newRouter( [
			'request' => $request
		] );
	}

	/**
	 * @param RequestData $request
	 * @param MockEnvironment $env
	 *
	 * @return EntryPoint
	 */
	private function getEntryPoint( RequestData $request, MockEnvironment $env ): EntryPoint {
		$entryPoint = new EntryPoint(
			$request,
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);

		$entryPoint->setRouter( $this->createRouter( $request ) );
		return $entryPoint;
	}

	public static function mockHandlerHeader() {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$response->setHeader( 'Foo', 'Bar' );
				return $response;
			}
		};
	}

	public function testHeader() {
		$uri = '/rest/mock/v1/EntryPoint/header';
		$request = new RequestData( [ 'uri' => new Uri( $uri ) ] );

		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		$entryPoint = $this->getEntryPoint(
			$request,
			$env
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();
		$env->assertHeaderValue( 'Bar', 'Foo' );
		$env->assertStatusCode( 200 );
	}

	public static function mockHandlerBodyRewind() {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$stream = new Stream( fopen( 'php://memory', 'w+' ) );
				$stream->write( 'hello' );
				$response->setBody( $stream );
				return $response;
			}
		};
	}

	/**
	 * Make sure EntryPoint rewinds a seekable body stream before reading.
	 */
	public function testBodyRewind() {
		$uri = '/rest/mock/v1/EntryPoint/bodyRewind';
		$request = new RequestData( [ 'uri' => new Uri( $uri ) ] );

		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		$entryPoint = $this->getEntryPoint(
			$request,
			$env
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		// NOTE: MediaWikiEntryPoint::doPostOutputShutdown flushes all output buffers
		$this->assertStringContainsString( 'hello', $entryPoint->getCapturedOutput() );
	}

	public function testGetTextFormatters_lang_parameter() {
		$services = $this->getServiceContainer();
		$env = new MockEnvironment();
		$context = $env->makeFauxContext();

		// Case 1: No lang parameter
		$request = new RequestData( [] );
		$entryPoint = new EntryPoint( $request, $context, $env, $services );
		$wrapper = \Wikimedia\TestingAccessWrapper::newFromObject( $entryPoint );
		$formatters = $wrapper->getTextFormatters();
		$this->assertNotEmpty( $formatters );
		$this->assertSame( $services->getContentLanguageCode()->toString(), $formatters[0]->getLangCode() );

		// Case 2: Valid requested language (e.g. 'fr' or 'es')
		$request = new RequestData( [ 'queryParams' => [ 'lang' => 'fr' ] ] );
		$entryPoint = new EntryPoint( $request, $context, $env, $services );
		$wrapper = \Wikimedia\TestingAccessWrapper::newFromObject( $entryPoint );
		$formatters = $wrapper->getTextFormatters();
		$this->assertNotEmpty( $formatters );
		$this->assertSame( 'fr', $formatters[0]->getLangCode() );

		// Case 3: Invalid requested language code (falls back)
		$request = new RequestData( [ 'queryParams' => [ 'lang' => 'invalid-lang-code' ] ] );
		$entryPoint = new EntryPoint( $request, $context, $env, $services );
		$wrapper = \Wikimedia\TestingAccessWrapper::newFromObject( $entryPoint );
		$formatters = $wrapper->getTextFormatters();
		$this->assertNotEmpty( $formatters );
		$this->assertSame( $services->getContentLanguageCode()->toString(), $formatters[0]->getLangCode() );
	}

	/**
	 * Assert that EntryPoint constructs TextFormatters that do not check the
	 * database for local overrides, since that would be slow.
	 *
	 * NOTE: The action API allows clients to opt into local overrides with
	 * erroruselocal. The REST framework could do the same.
	 */
	public function testGetTextFormatters_useDatabase() {
		// A formatter that reports how it was obtained, so we can tell
		// a database-backed formatter from one that ignores local overrides.
		$factory = $this->createMock( IMessageFormatterFactory::class );
		$factory->method( 'getTextFormatter' )->willReturnCallback(
			function ( string $langCode, bool $useDatabase = true ) {
				$formatter = $this->createMock( ITextFormatter::class );
				$formatter->method( 'getLangCode' )->willReturn( $langCode );
				$formatter->method( 'format' )->willReturn( $useDatabase ? 'db' : 'nodb' );
				return $formatter;
			}
		);
		$this->setService( 'MessageFormatterFactory', $factory );

		$services = $this->getServiceContainer();
		$env = new MockEnvironment();
		$entryPoint = new EntryPoint( new RequestData( [] ), $env->makeFauxContext(), $env, $services );
		$formatters = TestingAccessWrapper::newFromObject( $entryPoint )->getTextFormatters();

		$this->assertNotEmpty( $formatters );
		foreach ( $formatters as $formatter ) {
			$this->assertSame( 'nodb', $formatter->format( MessageValue::new( 'test' ) ) );
		}
	}

	public static function mockHandlerCookies() {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$response->setCookie( 'TestCookie', 'cookie-val', 123456789 );
				return $response;
			}
		};
	}

	public function testCookies() {
		$this->overrideConfigValue( MainConfigNames::CookiePrefix, '' );

		$uri = '/rest/mock/v1/EntryPoint/cookies';
		$request = new RequestData( [ 'uri' => new Uri( $uri ) ] );

		$env = new MockEnvironment();
		$env->setRequestInfo( $uri );

		$entryPoint = $this->getEntryPoint(
			$request,
			$env
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		$env->assertStatusCode( 200 );
		$cookies = $env->getFauxResponse()->getCookies();
		$this->assertArrayHasKey( 'TestCookie', $cookies );
		$cookieData = $cookies['TestCookie'];
		$this->assertSame( 'cookie-val', $cookieData['value'] );
		$this->assertSame( 123456789, $cookieData['expire'] );
	}

}
