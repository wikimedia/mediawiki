<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\MessageValue;

class LocalizedHttpException extends HttpException {
	private $messageValue;

	public function __construct( MessageValue $messageValue, $code = 500, $errorData = null ) {
		parent::__construct(
			'Localized exception with key ' . $messageValue->getKey(), $code, $errorData
		);
		$this->messageValue = $messageValue;
	}

	public function getMessageValue() {
		return $this->messageValue;
	}
}
