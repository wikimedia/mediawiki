<?php

namespace MediaWiki\Tests\Rest;

use InvalidArgumentException;
use MediaWiki\Rest\ErrorFormatter;
use MediaWiki\Rest\ErrorFormatterV1;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiUnitTestCase;
use RuntimeException;
use Wikimedia\Message\MessageValue;

/** @covers \MediaWiki\Rest\ErrorFormatter */
class ErrorFormatterTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	private function newFormatter( array $textFormatters = [], bool $showExceptionDetails = false ): ErrorFormatter {
		return new ErrorFormatterV1( $textFormatters, $showExceptionDetails );
	}

	public function testFormatErrorBodyRejectsNonErrorCodes() {
		$ef = $this->newFormatter();
		$this->expectException( InvalidArgumentException::class );
		$ef->formatErrorBody( 200 );
	}

	public function testFormatErrorBodyIncludesHttpCodeAndReason() {
		$ef = $this->newFormatter();
		$body = $ef->formatErrorBody( 404, [ 'message' => 'not found' ] );

		$this->assertSame( 'not found', $body['message'] );
		$this->assertSame( 404, $body['httpCode'] );
		$this->assertArrayHasKey( 'httpReason', $body );
	}

	public function testFormatErrorBodyIncludesReqIdOnlyFor5xx() {
		$ef = $this->newFormatter();

		$this->assertArrayNotHasKey( 'reqId', $ef->formatErrorBody( 404 ) );
		$this->assertArrayHasKey( 'reqId', $ef->formatErrorBody( 500 ) );
	}

	public function testFormatExceptionHidesDetailsByDefault() {
		$ef = $this->newFormatter( [], false );
		$body = $ef->formatException( 500, new RuntimeException( 'secret' ) );

		$this->assertStringNotContainsString( 'secret', $body['message'] );
		$this->assertArrayNotHasKey( 'exception', $body );
		$this->assertSame( 500, $body['httpCode'] );
	}

	public function testFormatExceptionShowsDetailsWhenEnabled() {
		$ef = $this->newFormatter( [], true );
		$body = $ef->formatException( 500, new RuntimeException( 'secret' ) );

		$this->assertStringContainsString( 'secret', $body['message'] );
		$this->assertArrayHasKey( 'exception', $body );
	}

	public function testFormatHttpExceptionMergesErrorDataAndExtraData() {
		$ef = $this->newFormatter();
		$exception = new HttpException( 'denied', 403, [ 'reason' => 'forbidden' ] );

		$body = $ef->formatHttpException( 403, $exception, [ 'extra' => 'yes' ] );

		$this->assertSame( 'forbidden', $body['reason'] );
		$this->assertSame( 'yes', $body['extra'] );
		$this->assertSame( 'denied', $body['message'] );
		$this->assertSame( 403, $body['httpCode'] );
	}

	public function testFormatLocalizedHttpExceptionIncludesErrorKeyAndTranslations() {
		$ef = $this->newFormatter( [ $this->getDummyTextFormatter() ] );
		$exception = new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 );

		$body = $ef->formatLocalizedHttpException( 404, $exception );

		$this->assertSame( 'rest-test-key', $body['errorKey'] );
		$this->assertArrayHasKey( 'messageTranslations', $body );
		$this->assertSame( 404, $body['httpCode'] );
	}

	public function testFormatLocalizedHttpErrorMergesExtraDataAndTranslations() {
		$ef = $this->newFormatter( [ $this->getDummyTextFormatter() ] );

		$body = $ef->formatLocalizedHttpError( 404, new MessageValue( 'rest-test-key' ), [ 'extra' => 'yes' ] );

		$this->assertSame( 'yes', $body['extra'] );
		$this->assertArrayHasKey( 'messageTranslations', $body );
		$this->assertSame( 404, $body['httpCode'] );
	}

	public function testFormatMessageReturnsEmptyArrayWithoutTextFormatters() {
		$ef = $this->newFormatter( [] );
		$this->assertSame( [], $ef->formatMessage( new MessageValue( 'rest-test-key' ) ) );
	}

	public function testFormatMessageReturnsTranslationsKeyedByLangCode() {
		$ef = $this->newFormatter( [ $this->getDummyTextFormatter() ] );
		$result = $ef->formatMessage( new MessageValue( 'rest-test-key' ) );

		$this->assertArrayHasKey( 'messageTranslations', $result );
		$this->assertSame( 'rest-test-key', $result['messageTranslations']['qqx'] );
	}
}
