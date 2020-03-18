<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler;

/**
 * Example handler
 * @unstable
 */
class HelloHandler extends Handler {
	public function execute() {
		return [ 'message' => "Hello!" ];
	}

	public function needsWriteAccess() {
		return false;
	}
}
