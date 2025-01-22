<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\IApiMessage;
use MediaWiki\Content\TextContent;
use MediaWiki\Json\FormatJson;
use MediaWiki\ParamValidator\TypeDef\ArrayDef;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;
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
				self::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-update-title' ),
			],
		] + parent::getParamSettings();
	}

	/**
	 * @inheritDoc
	 */
	public function getBodyParamSettings(): array {
		return [
			'source' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-source' )
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
			'latest' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'array',
				ParamValidator::PARAM_REQUIRED => false,
				ArrayDef::PARAM_SCHEMA => ArrayDef::makeObjectSchema(
					[ 'id' => 'integer' ],
					[ 'timestamp' => 'string' ], // from GET response, will be ignored
				),
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-update-latest' )
			],
		] + $this->getTokenParamDefinition();
	}

	/**
	 * @inheritDoc
	 */
	protected function getActionModuleParameters() {
		$body = $this->getValidatedBody();
		'@phan-var array $body';

		$title = $this->getTitleParameter();
		$baseRevId = $body['latest']['id'] ?? 0;

		$contentmodel = $body['content_model'] ?: null;

		if ( $contentmodel !== null && !$this->contentHandlerFactory->isDefinedModel( $contentmodel ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-bad-content-model', [ $contentmodel ] ), 400
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
	protected function mapActionModuleResult( array $data ) {
		if ( isset( $data['edit']['nochange'] ) ) {
			// Null-edit, no new revision was created. The new revision is the same as the old.
			// We may want to signal this more explicitly to the client in the future.

			$title = $this->titleParser->parseTitle( $this->getValidatedParams()['title'] );
			$title = Title::newFromLinkTarget( $title );
			$currentRev = $this->revisionLookup->getRevisionByTitle( $title );

			$data['edit']['newrevid'] = $currentRev->getId();
			$data['edit']['newtimestamp']
				= MWTimestamp::convert( TS_ISO_8601, $currentRev->getTimestamp() );
		}

		return parent::mapActionModuleResult( $data );
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
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 409, $data );
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
		'@phan-var array $body';
		$baseRevId = $body['latest']['id'] ?? 0;
		$title = $this->titleParser->parseTitle( $this->getValidatedParams()['title'] );

		$baseRev = $this->revisionLookup->getRevisionById( $baseRevId );
		$title = Title::newFromLinkTarget( $title );
		$currentRev = $this->revisionLookup->getRevisionByTitle( $title );

		if ( !$baseRev || !$currentRev ) {
			return [];
		}

		$baseContent = $baseRev->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getAuthority()
		);
		$currentContent = $currentRev->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getAuthority()
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
		return FormatJson::decode( $json, true );
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/ExistingPageSource.json';
	}
}
