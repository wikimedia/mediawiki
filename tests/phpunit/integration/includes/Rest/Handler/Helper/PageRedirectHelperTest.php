<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Tests\Rest\Handler\PageHandlerTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\PageRedirectHelper
 * @group Database
 */
class PageRedirectHelperTest extends MediaWikiIntegrationTestCase {
	use PageHandlerTestTrait;

	private function newRedirectHelper( $queryParams = [], $headers = [] ) {
		$services = $this->getServiceContainer();

		$redirectStore = $this->createNoOpMock( RedirectStore::class, [ 'getRedirectTarget' ] );
		$redirectStore->method( 'getRedirectTarget' )
			->willReturnCallback( static function ( PageIdentity $page ) use ( $services ) {
				if ( $page->getDBkey() === 'Redirect_to_self' ) {
					return TitleValue::newFromPage( $page );
				}

				if ( str_starts_with( $page->getDBkey(), 'Redirect_to_' ) ) {
					$titleParser = $services->getTitleParser();
					return $titleParser->parseTitle( substr( $page->getDBkey(), 12 ), $page->getNamespace() );
				}

				return null;
			} );

		$responseFactory = new ResponseFactory( [] );

		$router = $this->newRouterForPageHandler( 'https://example.test', '/api' );
		$request = new RequestData( [ 'queryParams' => $queryParams, 'headers' => $headers ] );

		return new PageRedirectHelper(
			$redirectStore,
			$services->getTitleFormatter(),
			$responseFactory,
			$router,
			'/test/{title}',
			$request,
			$services->getLanguageConverterFactory()
		);
	}

	public static function provideGetTargetUrl() {
		yield 'Simple' => [
			'Föö+Bar',
			null,
			false,
			'https://example.test/api/test/F%C3%B6%C3%B6%2BBar?redirect=no',
		];

		yield 'Relative' => [
			'Föö+Bar',
			null,
			true,
			'/api/test/F%C3%B6%C3%B6%2BBar?redirect=no',
		];

		yield 'Query Params' => [
			'Föö+Bar',
			[ 'a' => 1 ],
			true,
			'/api/test/F%C3%B6%C3%B6%2BBar?a=1&redirect=no',
		];

		$page = PageReferenceValue::localReference(
			NS_TALK,
			'Q/A'
		);
		yield 'Slash Encoding' => [
			$page,
			null,
			false,
			'https://example.test/api/test/Talk%3AQ%2FA?redirect=no',
		];
	}

	/**
	 * @dataProvider provideGetTargetUrl
	 */
	public function testGetTargetUrl( $title, $queryParams, $relative, $expectedUrl ) {
		$helper = $this->newRedirectHelper( $queryParams ?: [] );
		$helper->setUseRelativeRedirects( $relative );
		$this->assertSame( $expectedUrl, $helper->getTargetUrl( $title ) );
	}

	public static function provideNormalizationRedirect() {
		$page = new PageIdentityValue( 7, NS_MAIN, 'Foo', false );
		yield [ $page, 'foo', '/api/test/Foo' ];

		$page = new PageIdentityValue( 7, NS_MAIN, 'Foo', false );
		yield [ $page, 'Foo', null ];

		$page = new PageIdentityValue( 7, NS_TALK, 'Foo_bar/baz', false );
		yield [ $page, 'Talk:Foo bar/baz', '/api/test/Talk%3AFoo_bar%2Fbaz' ];

		$page = new PageIdentityValue( 7, NS_TALK, 'Foo_bar/baz', false );
		yield [ $page, 'Talk:Foo_bar/baz', null ];
	}

	/**
	 * @dataProvider provideNormalizationRedirect
	 */
	public function testNormalizationRedirect(
		PageIdentity $page,
		string $title,
		?string $expectedUrl
	) {
		$helper = $this->newRedirectHelper();

		$resp = $helper->createNormalizationRedirectResponseIfNeeded( $page, $title );

		if ( $expectedUrl === null ) {
			$this->assertNull( $resp );
		} else {
			$this->assertNotNull( $resp );
			$this->assertSame( $expectedUrl, $resp->getHeaderLine( 'Location' ) );
			$this->assertSame( 301, $resp->getStatusCode() );
		}
	}

	public function testNormalizationRedirect_absolute() {
		$helper = $this->newRedirectHelper( [] );
		$helper->setUseRelativeRedirects( false );

		$page = new PageIdentityValue( 7, NS_MAIN, 'Foo', false );
		$resp = $helper->createNormalizationRedirectResponseIfNeeded( $page, 'foo' );

		$this->assertNotNull( $resp );
		$this->assertStringStartsWith( 'https://', $resp->getHeaderLine( 'Location' ) );
	}

	public static function provideWikiRedirect() {
		$page = new PageIdentityValue( 7, NS_MAIN, 'Redirect_to_foo', false );
		yield 'Wiki redirect' => [ $page, '/api/test/Foo?redirect=no', 'https://example.test/api/test/Foo?redirect=no' ];

		$page = new PageIdentityValue( 7, NS_MAIN, 'Redirect_to_self', false );
		yield 'Self-redirect (T353688)' => [ $page, null ];

		$page = new PageIdentityValue( 7, NS_MAIN, 'foo', false );
		yield 'no redirect' => [ $page, null ];
	}

	/**
	 * @dataProvider provideWikiRedirect
	 */
	public function testWikiRedirect(
		PageIdentity $page,
		?string $expectedUrl
	) {
		$helper = $this->newRedirectHelper();
		$helper->setFollowWikiRedirects( true );

		$target = $helper->getWikiRedirectTargetUrl( $page );
		$resp1 = $helper->createWikiRedirectResponseIfNeeded( $page );
		$resp2 = $helper->createRedirectResponseIfNeeded( $page, $page->getDBkey() );

		if ( $expectedUrl === null ) {
			$this->assertNull( $target );
			$this->assertNull( $resp1 );
			$this->assertNull( $resp2 );
		} else {
			$this->assertSame( $expectedUrl, $target );

			$this->assertNotNull( $resp1 );
			$this->assertSame( $expectedUrl, $resp1->getHeaderLine( 'Location' ) );
			$this->assertSame( 307, $resp1->getStatusCode() );

			$this->assertNotNull( $resp2 );
			$this->assertSame( $expectedUrl, $resp2->getHeaderLine( 'Location' ) );
			$this->assertSame( 307, $resp2->getStatusCode() );
		}
	}

	public function testVariantRedirect() {
		$page = $this->getNonexistingTestPage( 'TestPage' );
		// NOTE: "TestPage" variant to en-x-piglatin is "EsttayAgepay"
		$this->insertPage( Title::newFromText( 'EsttayAgepay' ) );

		$helper = $this->newRedirectHelper();
		$helper->setFollowWikiRedirects( true );

		$resp = $helper->createRedirectResponseIfNeeded( $page, $page->getDBkey() );

		$this->assertNotNull( $resp );
		$this->assertSame(
			'/api/test/EsttayAgepay?redirect=no',
			$resp->getHeaderLine( 'Location' )
		);
		$this->assertSame( 307, $resp->getStatusCode() );
	}

	public function testWikiRedirectDisabled() {
		$page = new PageIdentityValue( 7, NS_MAIN, 'Redirect_to_foo', false );

		// We assume that wiki redirect handling is disabled by default.
		$helper = $this->newRedirectHelper();

		$target = $helper->getWikiRedirectTargetUrl( $page );
		$this->assertNotNull( $target, 'getWikiRedirectTargetUrl() should not be disabled' );

		$resp = $helper->createWikiRedirectResponseIfNeeded( $page );
		$this->assertNotNull( $resp, 'createWikiRedirectResponseIfNeeded() should not be disabled' );

		$resp = $helper->createRedirectResponseIfNeeded( $page, null );
		$this->assertNull( $resp, 'createRedirectResponseIfNeeded() should not follow wiki redirect' );

		$resp = $helper->createRedirectResponseIfNeeded( $page, 'redirect to foo' );
		$this->assertNotNull( $resp, 'createRedirectResponseIfNeeded() should still follow normalization redirect' );
	}

}
