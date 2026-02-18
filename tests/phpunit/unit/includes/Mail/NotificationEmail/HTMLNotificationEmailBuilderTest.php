<?php

namespace MediaWiki\Tests\Unit\Mail\NotificationEmail;

use MediaWiki\Config\HashConfig;
use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Mail\NotificationEmail\HTMLNotificationEmailBuilder;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\WebRequest;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\Mail\NotificationEmail\HTMLNotificationEmailBuilder
 * @group medium
 */
class HTMLNotificationEmailBuilderTest extends MediaWikiUnitTestCase {

	private function getMockContext( array $config = [] ): IContextSource {
		$config = new HashConfig( array_merge( [
			MainConfigNames::Logo => null,
			MainConfigNames::Logos => [],
		], $config ) );

		$language = $this->createNoOpMock( Language::class, [ 'getCode' ] );
		$language->method( 'getCode' )->willReturn( 'en' );

		$request = $this->createNoOpMock( WebRequest::class, [ 'getIP' ] );
		$request->method( 'getIP' )->willReturn( '192.0.2.1' );

		$context = $this->createNoOpMock(
			IContextSource::class,
			[ 'getConfig', 'getLanguage', 'getRequest', 'msg' ]
		);
		$context->method( 'getConfig' )->willReturn( $config );
		$context->method( 'getLanguage' )->willReturn( $language );
		$context->method( 'getRequest' )->willReturn( $request );
		$context->method( 'msg' )
			->willReturnCallback( function ( $key, ...$params ) {
				return $this->getMockMessage( $key . ':' . implode( ',', $params ) );
			} );

		return $context;
	}

	public function testBuildNotificationEmailChanged() {
		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'expand' ] );
		$urlUtils->method( 'expand' )
			->with( $this->isType( 'string' ), PROTO_CANONICAL )
			->willReturnCallback( static function ( $url ) {
				return 'https://example.com' . $url;
			} );

		$builder = new HTMLNotificationEmailBuilder(
			$this->getMockContext(),
			new HashBagOStuff(),
			$urlUtils
		);

		$result = $builder->buildNotificationEmailChanged( 'TestUser', 'new@example.com' );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'subject', $result );
		$this->assertArrayHasKey( 'body', $result );
		$this->assertArrayHasKey( 'text', $result['body'] );
		$this->assertArrayHasKey( 'html', $result['body'] );
		$this->assertIsString( $result['subject'] );
		$this->assertIsString( $result['body']['text'] );
		$this->assertIsString( $result['body']['html'] );
		$this->assertStringContainsString( 'TestUser', $result['body']['html'] );
		$this->assertStringContainsString( 'new@example.com', $result['body']['html'] );
	}

	public function testBuildNotificationEmailRemoved() {
		$urlUtils = $this->createNoOpMock( UrlUtils::class, [ 'expand' ] );
		$urlUtils->method( 'expand' )
			->with( $this->isType( 'string' ), PROTO_CANONICAL )
			->willReturnCallback( static function ( $url ) {
				return 'https://example.com' . $url;
			} );

		$builder = new HTMLNotificationEmailBuilder(
			$this->getMockContext(),
			new HashBagOStuff(),
			$urlUtils
		);

		$result = $builder->buildNotificationEmailRemoved( 'TestUser' );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'subject', $result );
		$this->assertArrayHasKey( 'body', $result );
		$this->assertArrayHasKey( 'text', $result['body'] );
		$this->assertArrayHasKey( 'html', $result['body'] );
		$this->assertIsString( $result['subject'] );
		$this->assertIsString( $result['body']['text'] );
		$this->assertIsString( $result['body']['html'] );
		$this->assertStringContainsString( 'TestUser', $result['body']['html'] );
	}

}
