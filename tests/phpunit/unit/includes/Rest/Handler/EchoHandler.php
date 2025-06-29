<?php

namespace MediaWiki\Tests\Rest\Handler;

use LogicException;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Test mock for asserting parameter processing and validation in Handler and Router.
 */
class EchoHandler extends Handler {
	/** @var bool */
	private $postValidationSetupCalled = false;

	/** @inheritDoc */
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

	public function getBodyParamSettings(): array {
		if ( $this->getConfig()['postParam'] ?? false ) {
			// Don't mix "post" and "body" params, it confuses
			// Validator::detectExtraneousBodyFields()
			return [];
		}

		return [
			'bodyParam' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false
			],
		];
	}

	/** @inheritDoc */
	public function getParamSettings() {
		$paramSettings = [
			'q' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
			],
			'pathParam' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
			],
		];

		if ( $this->getConfig()['postParam'] ?? false ) {
			// Deprecated, will trigger a warning!
			$paramSettings['postParam'] = [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
			];
		}

		return $paramSettings;
	}

	protected function postValidationSetup() {
		$this->postValidationSetupCalled = true;
	}

	public function getSupportedRequestTypes(): array {
		return [
			RequestInterface::JSON_CONTENT_TYPE,
			RequestInterface::FORM_URLENCODED_CONTENT_TYPE,
			RequestInterface::MULTIPART_FORM_DATA_CONTENT_TYPE,
		];
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
	}
}
