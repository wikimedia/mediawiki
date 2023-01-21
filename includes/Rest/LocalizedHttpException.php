<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;

/**
 * @newable
 */
class LocalizedHttpException extends HttpException {
	private MessageValue $messageValue;
	private string $errorKey;

	/**
	 * @stable to call
	 *
	 * @param MessageValue $messageValue
	 * @param int $code
	 * @param array $errorData
	 */
	public function __construct( MessageValue $messageValue, $code = 500, $errorData = [] ) {
		if ( $messageValue instanceof DataMessageValue ) {
			$errorKey = $messageValue->getCode();
			$errorData += $messageValue->getData() ?? [];
		} else {
			$errorKey = $messageValue->getKey();
		}
		parent::__construct(
			'Localized exception with key ' . $messageValue->getKey(), $code, $errorData
		);
		$this->messageValue = $messageValue;
		$this->errorKey = $errorKey;
	}

	public function getMessageValue(): MessageValue {
		return $this->messageValue;
	}

	public function getErrorKey(): string {
		return $this->errorKey;
	}
}
