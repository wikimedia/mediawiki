<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * @newable
 */
class LocalizedHttpException extends HttpException {
	private MessageSpecifier $messageSpecifier;
	private string $errorKey;

	/**
	 * @stable to call
	 *
	 * @param MessageSpecifier $messageValue Prior to MediaWiki 1.47 this had to be a MessageValue
	 * @param int $code
	 * @param array $errorData
	 */
	public function __construct( MessageSpecifier $messageValue, $code = 500, $errorData = [] ) {
		if ( $messageValue instanceof DataMessageValue ) {
			$errorKey = $messageValue->getCode();
			$errorData += $messageValue->getData() ?? [];
		} else {
			$errorKey = $messageValue->getKey();
		}
		parent::__construct(
			'Localized exception with key ' . $messageValue->getKey(), $code, $errorData
		);
		$this->messageSpecifier = $messageValue;
		$this->errorKey = $errorKey;
	}

	public function getMessageValue(): MessageValue {
		return MessageValue::newFromSpecifier( $this->messageSpecifier );
	}

	/**
	 * @since 1.47
	 * @return MessageSpecifier
	 */
	public function getMessageSpecifier(): MessageSpecifier {
		return $this->messageSpecifier;
	}

	public function getErrorKey(): string {
		return $this->errorKey;
	}
}
