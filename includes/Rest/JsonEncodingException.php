<?php

namespace MediaWiki\Rest;

class JsonEncodingException extends \RuntimeException {
	public function __construct( $message, $code ) {
		parent::__construct( "JSON encoding error: $message", $code );
	}
}
