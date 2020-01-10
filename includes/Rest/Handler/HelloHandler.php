<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\SimpleHandler;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Example handler
 * @unstable
 */
class HelloHandler extends SimpleHandler {
	public function run( $name ) {
		return [ 'message' => "Hello, $name!" ];
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'name' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
