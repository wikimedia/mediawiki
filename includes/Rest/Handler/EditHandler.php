<?php

namespace MediaWiki\Rest\Handler;

use Config;
use IApiMessage;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use TitleFormatter;
use TitleParser;
use WebResponse;
use Wikimedia\Message\MessageValue;

/**
 * Base class for REST API handlers that perform page edits (main slot only).
 */
abstract class EditHandler extends ActionModuleBasedHandler {

	/** @var Config */
	protected $config;

	/**
	 * @var IContentHandlerFactory
	 */
	protected $contentHandlerFactory;

	/**
	 * @var TitleParser
	 */
	protected $titleParser;

	/**
	 * @var TitleFormatter
	 */
	protected $titleFormatter;

	/**
	 * @var RevisionLookup
	 */
	protected $revisionLookup;

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

	public function needsWriteAccess() {
		return true;
	}

	/**
	 * Returns the requested title.
	 *
	 * @return string
	 */
	abstract protected function getTitleParameter();

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

		$title = $this->titleParser->parseTitle( $data['edit']['title'] );

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

		if ( $code === 'protectedpage' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 403 );
		}

		if ( $code === 'badtoken' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 403 );
		}

		if ( $code === 'missingtitle' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 404 );
		}

		if ( $code === 'articleexists' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 409 );
		}

		if ( $code === 'editconflict' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 409 );
		}

		if ( $code === 'ratelimited' ) {
			throw new LocalizedHttpException( $this->makeMessageValue( $msg ), 429 );
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
		$body = $this->getValidatedBody();

		if ( $this->getSession()->getProvider()->safeAgainstCsrf() ) {
			if ( !empty( $body['token'] ) ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-extraneous-csrf-token' ),
					400
				);
			}

			// Since the session is safe against CSRF, just use a known-good token.
			return $this->getUser()->getEditToken();
		} else {
			return $body['token'] ?? '';
		}
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

		if ( $actionModuleResult['edit']['new'] ?? false ) {
			$response->setStatus( 201 );
		}
	}

}
