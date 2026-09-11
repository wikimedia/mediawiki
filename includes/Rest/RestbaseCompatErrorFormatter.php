<?php

namespace MediaWiki\Rest;

use Wikimedia\Http\HttpStatus;
use Wikimedia\Message\MessageSpecifier;

/**
 * @internal
 */
class RestbaseCompatErrorFormatter extends ErrorFormatterV1 {

	/**
	 * @param array $textFormatters
	 * @param bool $showExceptionDetails
	 * @param array $tracingData Pre-computed request tracing data, as built by
	 *        Router::getTracingData(). Keeps this formatter decoupled from
	 *        UrlUtils and the request.
	 */
	public function __construct(
		array $textFormatters,
		bool $showExceptionDetails,
		private readonly array $tracingData,
	) {
		parent::__construct( $textFormatters, $showExceptionDetails, $tracingData );
	}

	public function formatLocalizedHttpException(
		int $statusCode,
		LocalizedHttpException $exception,
		array $extraData = []
	): array {
		$msg = $exception->getMessageSpecifier();

		// Match error fields emitted by the RESTBase endpoints.
		$restbaseFields = [
			'type' => 'MediaWikiError/' .
				str_replace( ' ', '_', HttpStatus::getMessage( $statusCode ) ),
			'title' => $msg->getKey(),
			'method' => $this->tracingData['method'],
			'detail' => $this->getPreferredTranslation( $msg ),
			'uri' => $this->tracingData['uri'],
		];

		return parent::formatLocalizedHttpException( $statusCode, $exception, $extraData + $restbaseFields );
	}

	/**
	 * Mirrors ResponseFactory::getFormattedMessage( $msg, 'en' ): prefer 'en',
	 * fall back to whatever translation is available, then to the message key.
	 */
	private function getPreferredTranslation( MessageSpecifier $msg ): string {
		$translations = $this->formatMessage( $msg )['messageTranslations'] ?? [];
		if ( isset( $translations['en'] ) ) {
			return $translations['en'];
		}
		return $translations ? reset( $translations ) : $msg->getKey();
	}
}
