<?php

namespace MediaWiki\Tests\Rest\Handler;

use LogicException;
use MediaWiki\Rest\Handler;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Test mock for asserting parameter processing and validation in Handler and Router.
 */
class EchoHandler extends Handler {
	private $postValidationSetupCalled = false;

	public function execute() {
		if ( !$this->postValidationSetupCalled ) {
			throw new LogicException( 'postValidationSetup was not called' );
		}

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
				ParamValidator::PARAM_REQUIRED => false
			],
		];
	}

	protected function postValidationSetup() {
		$this->postValidationSetupCalled = true;
	}

	public function needsWriteAccess() {
		return false;
	}
}
