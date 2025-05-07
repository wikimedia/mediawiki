<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Api\IApiMessage;
use MediaWiki\Config\Config;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\WebResponse;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\TokenAwareHandlerTrait;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;
use RuntimeException;
use Wikimedia\Message\MessageValue;

/**
 * Base class for REST API handlers that perform page edits (main slot only).
 */
abstract class EditHandler extends ActionModuleBasedHandler {
	use TokenAwareHandlerTrait;

	protected Config $config;
	protected IContentHandlerFactory $contentHandlerFactory;
	protected TitleParser $titleParser;
	protected TitleFormatter $titleFormatter;
	protected RevisionLookup $revisionLookup;

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
	public function validate( Validator $restValidator ) {
			parent::validate( $restValidator );
			$this->validateToken( true );
	}

	/**
	 * @inheritDoc
	 */
	protected function mapActionModuleResult( array $data ) {
		if ( isset( $data['error'] ) ) {
			throw new LocalizedHttpException( new MessageValue( 'apierror-' . $data['error'] ), 400 );
		}

		if ( !isset( $data['edit'] ) || !$data['edit']['result'] ) {
			throw new RuntimeException( 'Bad result structure received from ApiEditPage' );
		}

		if ( $data['edit']['result'] !== 'Success' ) {
			// Probably an edit conflict
			// TODO: which code for null edits?
			throw new LocalizedHttpException(
				new MessageValue( "rest-edit-conflict", [ $data['edit']['result'] ] ),
				409
			);
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
				'url' => $this->config->get( MainConfigNames::RightsUrl ),
				'title' => $this->config->get( MainConfigNames::RightsText )
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
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 403 );
		}

		if ( $code === 'badtoken' ) {
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 403 );
		}

		if ( $code === 'missingtitle' ) {
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 404 );
		}

		if ( $code === 'articleexists' ) {
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 409 );
		}

		if ( $code === 'editconflict' ) {
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 409 );
		}

		if ( $code === 'ratelimited' ) {
			throw new LocalizedHttpException( MessageValue::newFromSpecifier( $msg ), 429 );
		}

		// Fall through to generic handling of the error (status 400).
		parent::throwHttpExceptionForActionModuleError( $msg, $statusCode );
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

	protected function generateResponseSpec( string $method ): array {
		$spec = parent::generateResponseSpec( $method );

		$spec['201'][parent::OPENAPI_DESCRIPTION_KEY] = 'OK';
		$spec['201']['content']['application/json']['schema'] =
			$spec['200']['content']['application/json']['schema'];

		return $spec;
	}

}
