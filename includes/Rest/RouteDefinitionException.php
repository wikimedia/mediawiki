<?php

namespace MediaWiki\Rest;

class RouteDefinitionException extends \RuntimeException {
	public function __construct( $message, $code = 0 ) {
		parent::__construct( "Bad route definition: $message", $code );
	}
}
