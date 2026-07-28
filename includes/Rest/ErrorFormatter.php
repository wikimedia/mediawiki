<?php

namespace MediaWiki\Rest;

use InvalidArgumentException;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Http\Telemetry;
use MediaWiki\Language\LanguageCode;
use Throwable;
use Wikimedia\Http\HttpStatus;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;

/**
 * @internal
 */
abstract class ErrorFormatter {

	/**
	 * @param ITextFormatter[] $textFormatters
	 * @param bool $showExceptionDetails
	 */
	public function __construct(
		private readonly array $textFormatters,
		private readonly bool $showExceptionDetails,
	) {
	}

	public function formatException( int $statusCode, Throwable $exception ): array {
		$bodyData = [
			'message' => 'Error: exception of type ' . get_class( $exception ),
			'reqId' => Telemetry::getInstance()->getRequestId(),
		];
		if ( $this->showExceptionDetails ) {
			$bodyData['message'] = 'Error: exception of type ' . get_class( $exception ) . ': '
				. $exception->getMessage();
			$bodyData['exception'] = MWExceptionHandler::getStructuredExceptionData(
				$exception,
				MWExceptionHandler::CAUGHT_BY_OTHER
			);
		}
		return $this->formatErrorBody( $statusCode, $bodyData );
	}

	public function formatHttpException(
		int $statusCode,
		HttpException $exception,
		array $extraData = []
	): array {
		return $this->formatErrorBody(
			$statusCode,
			$exception->getErrorData() + $extraData + [ 'message' => $exception->getMessage() ]
		);
	}

	public function formatLocalizedHttpException(
		int $statusCode,
		LocalizedHttpException $exception,
		array $extraData = []
	): array {
		$mergedExtraData = $exception->getErrorData() + $extraData + [
			'errorKey' => $exception->getErrorKey(),
		];
		return $this->formatErrorBody(
			$statusCode,
			array_merge( $mergedExtraData, $this->formatMessage( $exception->getMessageSpecifier() ) )
		);
	}

	public function formatLocalizedHttpError(
		int $statusCode,
		MessageSpecifier $messageValue,
		array $extraData = []
	): array {
		return $this->formatErrorBody(
			$statusCode,
			array_merge( $extraData, $this->formatMessage( $messageValue ) )
		);
	}

	/**
	 * Tries to return the formatted string(s) for a message object using the
	 * response factory's text formatters. The returned array will either be empty (if there are
	 * no text formatters), or have exactly one key, "messageTranslations", whose value
	 * is an array of formatted strings, keyed by the associated language code.
	 *
	 * @param MessageSpecifier $messageValue The message object to format.
	 *
	 * @return array
	 */
	public function formatMessage( MessageSpecifier $messageValue ): array {
		if ( !$this->textFormatters ) {
			// For unit tests
			return [];
		}
		$translations = [];
		foreach ( $this->textFormatters as $formatter ) {
			$lang = LanguageCode::bcp47( $formatter->getLangCode() );
			$messageText = $formatter->format( $messageValue );
			$translations[$lang] = $messageText;
		}
		return [ 'messageTranslations' => $translations ];
	}

	/**
	 * Returns an array of all language codes supported by this instance's text formatters,
	 * in fallback order. Useful for constructing cache keys.
	 *
	 * @return string[]
	 */
	public function getLangCodes(): array {
		$codes = [];
		foreach ( $this->textFormatters as $formatter ) {
			$codes[] = $formatter->getLangCode();
		}
		return $codes;
	}

	public function formatErrorBody( int $statusCode, array $bodyData = [] ): array {
		if ( $statusCode < 400 || $statusCode >= 600 ) {
			throw new InvalidArgumentException( 'error code must be 4xx or 5xx' );
		}
		$extra = [
			'httpCode' => $statusCode,
			'httpReason' => HttpStatus::getMessage( $statusCode )
		];

		if ( $statusCode >= 500 ) {
			$extra['reqId'] = Telemetry::getInstance()->getRequestId();
		}

		return $bodyData + $extra;
	}

}
