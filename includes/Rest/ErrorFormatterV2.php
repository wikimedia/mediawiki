<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\MessageSpecifier;

/**
 * @internal
 */
class ErrorFormatterV2 extends ErrorFormatter {

	/** Body keys set by the shared formatter and Validator, renamed to snake_case (T444406) */
	private const SNAKE_CASE_KEYS = [
		'httpCode' => 'http_code',
		'errorCode' => 'error_code',
		'messageTranslations' => 'message_translations',
		'reqId' => 'request_id',
		'failureCode' => 'failure_code',
		'failureData' => 'failure_data',
	];

	/**
	 * @param array $textFormatters
	 * @param bool $showExceptionDetails
	 * @param array $tracingData Pre-computed request tracing data, as built by
	 *   Router::getTracingData(). Keeps this formatter decoupled from UrlUtils
	 *   and the request.
	 */
	public function __construct(
		array $textFormatters,
		bool $showExceptionDetails,
		private readonly array $tracingData,
	) {
		parent::__construct( $textFormatters, $showExceptionDetails );
	}

	public function formatLocalizedHttpException(
		int $statusCode,
		LocalizedHttpException $exception,
		array $extraData = []
	): array {
		$formattedMessage = $this->formatMessage( $exception->getMessageSpecifier() );
		$mergedExtraData = $exception->getErrorData() + $extraData + [
			'errorCode' => $exception->getErrorKey(),
			'message' => $formattedMessage['messageTranslations']['en'] ?? $exception->getMessage(),
		];
		return $this->formatErrorBody( $statusCode, $mergedExtraData + $formattedMessage );
	}

	public function formatLocalizedHttpError(
		int $statusCode,
		MessageSpecifier $messageValue,
		array $extraData = []
	): array {
		$formattedMessage = $this->formatMessage( $messageValue );
		$mergedExtraData = $extraData + [
			'errorCode' => $messageValue->getKey(),
			'message' => $formattedMessage['messageTranslations']['en'] ?? $messageValue->getKey(),
		];
		return $this->formatErrorBody( $statusCode, $mergedExtraData + $formattedMessage );
	}

	public function formatErrorBody( int $statusCode, array $bodyData = [] ): array {
		$body = [];
		foreach ( parent::formatErrorBody( $statusCode, $bodyData ) as $key => $value ) {
			$body[self::SNAKE_CASE_KEYS[$key] ?? $key] = $value;
		}

		// Tracing data is included for all errors (4xx and 5xx) to aid client-side
		// diagnostics and upstream error reporting.
		$body['tracing'] = ( $body['tracing'] ?? [] ) + $this->tracingData;

		return $body;
	}

	public function getOpenApiSchema(): array {
		return [ '$ref' => self::SCHEMA_BASE_URL . 'fragments/error-v2-beta.json' ];
	}
}
