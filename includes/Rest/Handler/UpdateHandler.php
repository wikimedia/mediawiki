<?php

namespace MediaWiki\Rest\Handler;

use FormatJson;
use IApiMessage;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use TextContent;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Core REST API endpoint that handles page updates (main slot only)
 */
class UpdateHandler extends EditHandler {

	/**
	 * @var callable
	 */
	private $jsonDiffFunction;

	/**
	 * @inheritDoc
	 */
	protected function getTitleParameter() {
		return $this->getValidatedParams()['title'];
	}

	/**
	 * Sets the function to use for JSON diffs, for testing.
	 *
	 * @param callable $jsonDiffFunction
	 */
	public function setJsonDiffFunction( callable $jsonDiffFunction ) {
		$this->jsonDiffFunction = $jsonDiffFunction;
	}

	/**
	 * @inheritDoc
	 */
	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
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
			'latest' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'array',
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
		$baseRevId = $body['latest']['id'] ?? 0;

		$contentmodel = $body['content_model'] ?: null;

		if ( $contentmodel !== null && !$this->contentHandlerFactory->isDefinedModel( $contentmodel ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-bad-content-model', [ $contentmodel ] ), 400
			);
		}

		$token = $this->getActionModuleToken();

		$params = [
			'action' => 'edit',
			'title' => $title,
			'text' => $body['source'],
			'summary' => $body['comment'],
			'token' => $token
		];

		if ( $contentmodel !== null ) {
			$params['contentmodel'] = $contentmodel;
		}

		if ( $baseRevId > 0 ) {
			$params['baserevid'] = $baseRevId;
			$params['nocreate'] = true;
		} else {
			$params['createonly'] = true;
		}

		return $params;
	}

	/**
	 * @inheritDoc
	 */
	protected function throwHttpExceptionForActionModuleError( IApiMessage $msg, $statusCode = 400 ) {
		$code = $msg->getApiCode();

		// Provide a message instructing the client to provide the base revision ID for updates.
		if ( $code === 'articleexists' ) {
			$title = $this->getTitleParameter();
			throw new LocalizedHttpException(
				new MessageValue( 'rest-update-cannot-create-page', [ $title ] ),
				409
			);
		}

		if ( $code === 'editconflict' ) {
			$data = $this->getConflictData();
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 409, $data );
		}

		parent::throwHttpExceptionForActionModuleError( $msg, $statusCode );
	}

	/**
	 * Returns an associative array to be used in the response in the event of edit conflicts.
	 *
	 * The resulting array contains the following keys:
	 * - base: revision ID of the base revision
	 * - current: revision ID of the current revision (new base after resolving the conflict)
	 * - local: the difference between the content submitted and the base revision
	 * - remote: the difference between the latest revision of the page and the base revision
	 *
	 * If the differences cannot be determined, an empty array is returned.
	 *
	 * @return array
	 */
	private function getConflictData() {
		$body = $this->getValidatedBody();
		$baseRevId = $body['latest']['id'] ?? 0;
		$title = $this->titleParser->parseTitle( $this->getValidatedParams()['title'] );

		$baseRev = $this->revisionLookup->getRevisionById( $baseRevId );
		$currentRev = $this->revisionLookup->getRevisionByTitle( $title );

		if ( !$baseRev || !$currentRev ) {
			return [];
		}

		$baseContent = $baseRev->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getUser()
		);
		$currentContent = $currentRev->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getUser()
		);

		if ( !$baseContent || !$currentContent ) {
			return [];
		}

		$model = $body['content_model'] ?: $baseContent->getModel();
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $model );
		$newContent = $contentHandler->unserializeContent( $body['source'] );

		if ( !$baseContent instanceof TextContent
			|| !$currentContent instanceof TextContent
			|| !$newContent instanceof TextContent
		) {
			return [];
		}

		$localDiff = $this->getDiff( $baseContent, $newContent );
		$remoteDiff = $this->getDiff( $baseContent, $currentContent );

		if ( !$localDiff || !$remoteDiff ) {
			return [];
		}

		return [
			'base' => $baseRev->getId(),
			'current' => $currentRev->getId(),
			'local' => $localDiff,
			'remote' => $remoteDiff,
		];
	}

	/**
	 * Returns a text diff encoded as an array, to be included in the response data.
	 *
	 * @param TextContent $from
	 * @param TextContent $to
	 *
	 * @return array|null
	 */
	private function getDiff( TextContent $from, TextContent $to ) {
		if ( !is_callable( $this->jsonDiffFunction ) ) {
			return null;
		}

		$json = ( $this->jsonDiffFunction )( $from->getText(), $to->getText(), 2 );
		return FormatJson::decode( $json, FormatJson::FORCE_ASSOC );
	}
}
