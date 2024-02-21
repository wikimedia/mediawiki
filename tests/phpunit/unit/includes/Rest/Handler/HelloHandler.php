<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler;

/**
 * Example handler
 * @unstable
 */
class HelloHandler extends Handler {
	public function execute() {
		$hello = $this->getConfig()['hello'] ?? 'Hello!';
		return [ 'message' => $hello ];
	}

	public function needsWriteAccess() {
		return false;
	}
}
