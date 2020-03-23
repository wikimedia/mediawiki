<?php

namespace MediaWiki\Rest\Handler;

use Config;
use FormatJson;
use IApiMessage;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use TextContent;
use TitleFormatter;
use TitleParser;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler class for Core REST API endpoint that handles page updates (main slot only)
 */
class UpdateHandler extends ActionModuleBasedHandler {

	/** @var Config */
	protected $config;

	/**
	 * @var IContentHandlerFactory
	 */
	private $contentHandlerFactory;

	/**
	 * @var TitleParser
	 */
	private $titleParser;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var RevisionLookup
	 */
	private $revisionLookup;

	/**
	 * Function for generating a JSON diff
	 *
	 * @var callable
	 */
	private $jsonDiffFunction = 'wikidiff2_inline_json_diff';

	/**
	 * @param Config $config
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param TitleParser $titleParser
	 * @param TitleFormatter $titleFormatter
	 * @param RevisionLookup $revisionLookup
	 */
	public function __construct(
		Config $config,
		IContentHandlerFactory $contentHandlerFactory,
		TitleParser $titleParser,
		TitleFormatter $titleFormatter,
		RevisionLookup $revisionLookup
	) {
		$this->config = $config;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->titleParser = $titleParser;
		$this->titleFormatter = $titleFormatter;
		$this->revisionLookup = $revisionLookup;
	}

	/**
	 * Sets the function to use for JSON diffs, for testing.
	 *
	 * @param callable $jsonDiffFunction
	 */
	public function setJsonDiffFunction( callable $jsonDiffFunction ) {
		$this->jsonDiffFunction = $jsonDiffFunction;
	}

	public function needsWriteAccess() {
		return true;
	}

	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

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

		$title = $this->getValidatedParams()['title'];
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
	protected function mapActionModuleResult( array $data ) {
		if ( isset( $data['error'] ) ) {
			throw new LocalizedHttpException( new MessageValue( 'apierror-' . $data['error'] ), 400 );
		}

		if ( !isset( $data['edit'] ) || !$data['edit']['result'] ) {
			throw new HttpException( 'Bad result structure received from ApiEditPage' );
		}

		if ( $data['edit']['result'] !== 'Success' ) {
			// Probably an edit conflict
			// TODO: which code for null edits?
			throw new HttpException( $data['edit']['result'], 409 );
		}

		$title = $this->titleParser->parseTitle( $this->getValidatedParams()['title'] );

		// This seems wasteful. This is the downside of delegating to the action API module:
		// if we need additional data in the response, we have to load it.
		$revision = $this->revisionLookup->getRevisionById( (int)$data['edit']['newrevid'] );
		$content = $revision->getContent( SlotRecord::MAIN );

		return [
			'id' => $data['edit']['pageid'],
			'title' => $this->titleFormatter->getPrefixedText( $title ),
			'key' => $this->titleFormatter->getPrefixedDBkey( $title ),
			'latest' => [
				'id' => $data['edit']['newrevid'],
				'timestamp' => $data['edit']['newtimestamp'],
			],
			'license' => [
				'url' => $this->config->get( 'RightsUrl' ),
				'title' => $this->config->get( 'RightsText' )
			],
			'content_model' => $data['edit']['contentmodel'],
			'source' => $content->serialize(),
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function throwHttpExceptionForActionModuleError( IApiMessage $msg, $statusCode = 400 ) {
		$code = $msg->getApiCode();
		if ( $code === 'missingtitle' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 404 );
		}

		if ( $code === 'protectedpage' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 403 );
		}

		if ( $code === 'articleexists' ) {
			// The original message is not very helpful.
			$title = $this->getValidatedParams()['title'];
			throw new LocalizedHttpException(
				new MessageValue( 'rest-update-cannot-create-page', [ $title ] ),
				409
			);
		}

		if ( $code === 'editconflict' ) {
			$data = $this->getConflictData();
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 409, $data );
		}

		if ( $code === 'ratelimited' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 429 );
		}

		if ( $code === 'badtoken' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 403 );
		}

		// Fall through to generic handling of the error (status 400).
		parent::throwHttpExceptionForActionModuleError( $msg, $statusCode );
	}

	/**
	 * Determines the CSRF token to be passed to the action module.
	 *
	 * This could be taken from a request parameter, or a known-good token
	 * can be computed, if the request has been determined to be safe against
	 * CSRF attacks, e.g. when an OAuth Authentication header is present.
	 *
	 * Most return an empty string if the request isn't known to be safe and
	 * no token was supplied by the client.
	 *
	 * @return string
	 */
	protected function getActionModuleToken() {
		// TODO: if the request is known to be safe, return $this->getUser()->getEditToken();

		$body = $this->getValidatedBody();
		return $body['token'] ?? '';
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
