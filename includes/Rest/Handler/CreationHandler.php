<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Request\WebResponse;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Core REST API endpoint that handles page creation (main slot only)
 */
class CreationHandler extends EditHandler {

	/**
	 * @inheritDoc
	 */
	protected function getTitleParameter() {
		$body = $this->getValidatedBody();
		'@phan-var array $body';
		return $body['title'];
	}

	/**
	 * @inheritDoc
	 * @return array
	 */
	public function getBodyParamSettings(): array {
		return [
			'source' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-source' )
			],
			'title' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-create-title' )
			],
			'comment' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-comment' )
			],
			'content_model' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-contentmodel' )
			],
		]
		+ $this->getTokenParamDefinition();
	}

	/**
	 * @inheritDoc
	 */
	protected function getActionModuleParameters() {
		$body = $this->getValidatedBody();
		'@phan-var array $body';

		$title = $this->getTitleParameter();

		$contentmodel = $body['content_model'] ?: null;

		if ( $contentmodel !== null && !$this->contentHandlerFactory->isDefinedModel( $contentmodel ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-bad-content-model', [ $body['content_model'] ] ), 400
			);
		}

		// Use a known good CSRF token if a token is not needed because we are
		// using a method of authentication that protects against CSRF, like OAuth.
		$token = $this->needsToken() ? $this->getToken() : $this->getUser()->getEditToken();

		$params = [
			'action' => 'edit',
			'title' => $title,
			'text' => $body['source'],
			'summary' => $body['comment'],
			'token' => $token,
			'createonly' => true,
		];

		if ( $contentmodel !== null ) {
			$params['contentmodel'] = $contentmodel;
		}

		return $params;
	}

	protected function mapActionModuleResponse(
		WebResponse $actionModuleResponse,
		array $actionModuleResult,
		Response $response
	) {
		parent::mapActionModuleResponse(
			$actionModuleResponse,
			$actionModuleResult,
			$response
		);

		$title = $this->urlEncodeTitle( $actionModuleResult['edit']['title'] );

		$url = $this->getRouter()->getRouteUrl( '/v1/page/' . $title );
		$response->setHeader( 'Location', $url );
	}

	/**
	 * This method specifies the JSON schema file for the response
	 * body when creating a new page.
	 *
	 * @return ?string The file path to the NewPage JSON schema.
	 */
	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/NewPage.json';
	}
}
