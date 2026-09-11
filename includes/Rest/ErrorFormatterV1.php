<?php

namespace MediaWiki\Rest;

use Wikimedia\Http\HttpStatus;

/**
 * @internal
 */
class ErrorFormatterV1 extends ErrorFormatter {

	/**
	 * @param array $textFormatters
	 * @param bool $showExceptionDetails
	 * @param array $tracingData Pre-computed request tracing data, as built by
	 *   Router::getTracingData(). Keeps this formatter decoupled from UrlUtils
	 *   and the request. Optional: defaults to empty when there is no request
	 *   context (e.g. the ResponseFactory default formatter), in which case no
	 *   request id is emitted.
	 */
	public function __construct(
		array $textFormatters,
		bool $showExceptionDetails,
		private readonly array $tracingData = [],
	) {
		parent::__construct( $textFormatters, $showExceptionDetails );
	}

	public function formatErrorBody( int $statusCode, array $bodyData = [] ): array {
		$body = parent::formatErrorBody( $statusCode, $bodyData );
		$body['httpReason'] ??= HttpStatus::getMessage( $statusCode );

		if ( $statusCode >= 500 && isset( $this->tracingData['request_id'] ) ) {
			$body['reqId'] ??= $this->tracingData['request_id'];
		}

		return $body;
	}

}
