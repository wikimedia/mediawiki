<?php

namespace MediaWiki\Rest;

class JsonEncodingException extends \RuntimeException {
	public function __construct( string $message, int $code ) {
		parent::__construct( "JSON encoding error: $message", $code );
	}
}
