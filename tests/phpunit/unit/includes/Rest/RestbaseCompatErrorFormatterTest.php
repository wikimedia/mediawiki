<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RestbaseCompatErrorFormatter;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/** @covers \MediaWiki\Rest\RestbaseCompatErrorFormatter */
class RestbaseCompatErrorFormatterTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	private function newFormatter(
		array $textFormatters = [],
		?array $requestData = null
	): RestbaseCompatErrorFormatter {
		return new RestbaseCompatErrorFormatter(
			$textFormatters ?: [ $this->getDummyTextFormatter() ],
			false,
			$requestData ?? [ 'method' => 'get', 'uri' => '/rest/test' ]
		);
	}

	private function newTextFormatter( string $langCode, string $translatedText ): ITextFormatter {
		return new class( $langCode, $translatedText ) implements ITextFormatter {
			public function __construct(
				private readonly string $langCode,
				private readonly string $translatedText
			) {
			}

			public function getLangCode(): string {
				return $this->langCode;
			}

			public function format( MessageSpecifier $message ): string {
				return $this->translatedText;
			}
		};
	}

	public function testFormatLocalizedHttpExceptionAddsRestbaseFields() {
		$requestData = [ 'method' => 'delete', 'uri' => '/rest/page/Foo' ];
		$ef = $this->newFormatter( [], $requestData );
		$exception = new LocalizedHttpException( new MessageValue( 'rest-nonexistent-title', [ 'Foo' ] ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( 'MediaWikiError/Not_Found', $body['type'] );
		$this->assertSame( 'rest-nonexistent-title', $body['title'] );
		$this->assertSame( 'delete', $body['method'] );
		$this->assertSame( '/rest/page/Foo', $body['uri'] );
		$this->assertArrayHasKey( 'detail', $body );
	}

	public function testDetailPrefersEnTranslation() {
		$ef = $this->newFormatter( [
			$this->newTextFormatter( 'fr', 'traduit' ),
			$this->newTextFormatter( 'en', 'translated' ),
		] );
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( 'translated', $body['detail'] );
	}

	public function testDetailFallsBackToFirstAvailableTranslationWhenEnMissing() {
		$ef = $this->newFormatter( [ $this->newTextFormatter( 'fr', 'traduit' ) ] );
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( 'traduit', $body['detail'] );
	}

	public function testDetailFallsBackToKeyWithNoTextFormatters() {
		$ef = new RestbaseCompatErrorFormatter(
			[], false, [ 'method' => 'get', 'uri' => '/rest/test' ]
		);
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( 'rest-test-key', $body['detail'] );
	}

	public function testFormatLocalizedHttpExceptionCallerExtraDataTakesPrecedence() {
		$ef = $this->newFormatter();
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception, [ 'uri' => 'https://overridden.example/' ] );

		$this->assertSame( 'https://overridden.example/', $body['uri'] );
	}

	public function testFormatHttpExceptionDoesNotAddRestbaseFields() {
		$ef = $this->newFormatter();
		$exception = new HttpException( 'denied', 403 );

		$body = $ef->formatHttpException( 403, $exception );

		$this->assertArrayNotHasKey( 'type', $body );
		$this->assertArrayNotHasKey( 'uri', $body );
	}
}
