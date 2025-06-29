<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler;

/**
 * Example handler
 * @unstable
 */
class HelloHandler extends Handler {
	/** @inheritDoc */
	public function execute() {
		$hello = $this->getConfig()['hello'] ?? 'Hello!';
		return [ 'message' => $hello ];
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
	}
}
