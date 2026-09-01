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
	 * @param array $requestData Pre-computed request data ('method', 'uri'), as built by
	 *   Router::getRestbaseCompatData(). Keeps this formatter decoupled from the request.
	 */
	public function __construct(
		array $textFormatters,
		bool $showExceptionDetails,
		private readonly array $requestData,
	) {
		parent::__construct( $textFormatters, $showExceptionDetails );
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
			'method' => $this->requestData['method'],
			'detail' => $this->getPreferredTranslation( $msg ),
			'uri' => $this->requestData['uri'],
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
