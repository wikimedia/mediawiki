<?php

namespace MediaWiki\Rest;

class RouteDefinitionException extends \RuntimeException {
	public function __construct( string $message, int $code = 0 ) {
		parent::__construct( "Bad route definition: $message", $code );
	}
}
