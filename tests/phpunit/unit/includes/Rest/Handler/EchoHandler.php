<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler;

/**
 * Example handler
 * @unstable
 */
class EchoHandler extends Handler {
	public function execute() {
		$request = $this->getRequest();
		return [
			'method' => $request->getMethod(),
			'uri' => $request->getUri(),
			'protocolVersion' => $request->getProtocolVersion(),
			'body' => $request->getBody()->getContents(),
			'serverParams' => $request->getServerParams(),
			'cookieParams' => $request->getCookieParams(),
			'queryParams' => $request->getQueryParams(),
			'postParams' => $request->getPostParams(),
			'pathParams' => $request->getPathParams(),
			'headers' => $request->getHeaders(),
			'parsedBody' => $request->getParsedBody(),
		];
	}

	public function needsWriteAccess() {
		return false;
	}
}
