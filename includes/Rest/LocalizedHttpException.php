<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\MessageValue;

class LocalizedHttpException extends HttpException {
	public function __construct( MessageValue $message, $code = 500 ) {
		parent::__construct( 'Localized exception with key ' . $message->getKey(), $code );
	}
}
