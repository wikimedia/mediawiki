<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler;
use Wikimedia\ParamValidator\ParamValidator;

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
			'validatedBody' => $this->getValidatedBody(),
			'validatedParams' => $this->getValidatedParams(),
		];
	}

	public function getParamSettings() {
		return [
			'q' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
			],
			'pathParam' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
			],
			'postParam' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'bodyParam' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
			],
		];
	}

	public function needsWriteAccess() {
		return false;
	}
}
