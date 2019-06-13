<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\SimpleHandler;

/**
 * Example handler
 * @unstable
 */
class HelloHandler extends SimpleHandler {
	public function run( $name ) {
		return [ 'message' => "Hello, $name!" ];
	}
}
