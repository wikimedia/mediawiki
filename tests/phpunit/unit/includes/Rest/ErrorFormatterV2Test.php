<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\ErrorFormatterV2;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/** @covers \MediaWiki\Rest\ErrorFormatterV2 */
class ErrorFormatterV2Test extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/**
	 * ErrorFormatterV2 prefers the 'en' translation for the top-level "message"
	 * field; getDummyTextFormatter() only ever provides 'qqx'.
	 */
	private function newEnTextFormatter(): ITextFormatter {
		return new class implements ITextFormatter {
			public function getLangCode(): string {
				return 'en';
			}

			public function format( MessageSpecifier $message ): string {
				return $message->getKey();
			}
		};
	}

	private function newFormatter(
		array $textFormatters = [],
		bool $showExceptionDetails = false,
		array $tracingData = [ 'url' => 'https://wiki.example.com/rest/test', 'tracing' => [ 'module' => 'mediawiki' ] ]
	): ErrorFormatterV2 {
		return new ErrorFormatterV2(
			$textFormatters,
			$showExceptionDetails,
			$tracingData
		);
	}

	public function testFormatErrorBodyAddsUrlFromTracingData() {
		$ef = $this->newFormatter();

		$body = $ef->formatErrorBody( 404 );

		$this->assertSame( 'https://wiki.example.com/rest/test', $body['url'] );
	}

	public function testFormatErrorBodyAddsHardcodedTracingModule() {
		$ef = $this->newFormatter();

		$body = $ef->formatErrorBody( 404 );

		$this->assertSame( 'mediawiki', $body['tracing']['module'] );
	}

	public function testFormatErrorBodyMovesReqIdIntoTracing() {
		$ef = $this->newFormatter();

		$body = $ef->formatErrorBody( 500 );

		$this->assertArrayNotHasKey( 'reqId', $body );
		$this->assertArrayHasKey( 'request_id', $body['tracing'] );
	}

	public function testFormatErrorBodyRemovesHttpReason() {
		$ef = $this->newFormatter();

		$body = $ef->formatErrorBody( 404 );

		$this->assertArrayNotHasKey( 'httpReason', $body );
	}

	public function testFormatLocalizedHttpExceptionIncludesErrorCodeAndMessage() {
		$ef = $this->newFormatter( [ $this->newEnTextFormatter() ] );
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( 'rest-test-key', $body['errorCode'] );
		$this->assertSame( 'rest-test-key', $body['message'] );
		$this->assertArrayHasKey( 'messageTranslations', $body );
	}

	public function testFormatLocalizedHttpExceptionFallsBackToExceptionMessage() {
		$ef = $this->newFormatter( [ $this->getDummyTextFormatter() ] );
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( $exception->getMessage(), $body['message'] );
	}

	public function testFormatLocalizedHttpErrorIncludesErrorCodeAndMessage() {
		$ef = $this->newFormatter( [ $this->newEnTextFormatter() ] );

		$body = $ef->formatLocalizedHttpError( 404, new MessageValue( 'rest-test-key' ) );

		$this->assertSame( 'rest-test-key', $body['errorCode'] );
		$this->assertSame( 'rest-test-key', $body['message'] );
		$this->assertArrayHasKey( 'messageTranslations', $body );
	}

	public function testFormatHttpExceptionOmitsErrorCode() {
		$ef = $this->newFormatter();
		$exception = new HttpException( 'denied', 403 );

		$body = $ef->formatHttpException( 403, $exception );

		$this->assertArrayNotHasKey( 'errorCode', $body );
		$this->assertSame( 'denied', $body['message'] );
	}
}
