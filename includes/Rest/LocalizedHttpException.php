<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * @newable
 */
class LocalizedHttpException extends HttpException {
	private readonly string $errorKey;

	/**
	 * @stable to call
	 * @param MessageSpecifier $messageSpecifier Prior to MediaWiki 1.47 this had to be a MessageValue
	 * @param int $code
	 * @param array $errorData
	 */
	public function __construct(
		private readonly MessageSpecifier $messageSpecifier,
		int $code = 500,
		array $errorData = [],
	) {
		if ( $messageSpecifier instanceof DataMessageValue ) {
			$errorKey = $messageSpecifier->getCode();
			$errorData += $messageSpecifier->getData() ?? [];
		} else {
			$errorKey = $messageSpecifier->getKey();
		}
		parent::__construct(
			'Localized exception with key ' . $messageSpecifier->getKey(), $code, $errorData
		);
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
