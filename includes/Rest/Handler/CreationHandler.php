<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use WebResponse;
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
		return $this->getValidatedBody()['title'];
	}

	/**
	 * @inheritDoc
	 */
	public function getBodyValidator( $contentType ) {
		if ( $contentType !== 'application/json' ) {
			throw new HttpException( "Unsupported Content-Type",
				415,
				[ 'content_type' => $contentType ]
			);
		}

		return new JsonBodyValidator( [
			'source' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'title' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'comment' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'content_model' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'token' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '',
			],
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function getActionModuleParameters() {
		$body = $this->getValidatedBody();

		$title = $this->getTitleParameter();

		$contentmodel = $body['content_model'] ?: null;

		if ( $contentmodel !== null && !$this->contentHandlerFactory->isDefinedModel( $contentmodel ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-bad-content-model', [ $body['content_model'] ] ), 400
			);
		}

		$token = $this->getActionModuleToken();

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

}
