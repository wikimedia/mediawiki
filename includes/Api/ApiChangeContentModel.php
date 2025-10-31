<?php

namespace MediaWiki\Api;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\ContentModelChangeFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Api module to change the content model of existing pages
 *
 * For new pages, use the edit api and specify the desired content model and format.
 *
 * @since 1.35
 * @ingroup API
 * @author DannyS712
 */
class ApiChangeContentModel extends ApiBase {

	private IContentHandlerFactory $contentHandlerFactory;
	private ContentModelChangeFactory $contentModelChangeFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		IContentHandlerFactory $contentHandlerFactory,
		ContentModelChangeFactory $contentModelChangeFactory
	) {
		parent::__construct( $main, $action );
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->contentModelChangeFactory = $contentModelChangeFactory;
	}

	/**
	 * A lot of this code is based on SpecialChangeContentModel
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$wikiPage = $this->getTitleOrPageId( $params );
		$title = $wikiPage->getTitle();
		$this->getErrorFormatter()->setContextTitle( $title );

		if ( !$title->exists() ) {
			$this->dieWithError( 'apierror-changecontentmodel-missingtitle' );
		}

		$newModel = $params['model'];

		$this->checkUserRightsAny( 'editcontentmodel' );
		$changer = $this->contentModelChangeFactory->newContentModelChange(
			$this->getAuthority(),
			$wikiPage,
			$newModel
		);
		// Status messages should be apierror-*
		$changer->setMessagePrefix( 'apierror-' );
		$permissionStatus = $changer->authorizeChange();
		if ( !$permissionStatus->isGood() ) {
			$this->dieStatus( $permissionStatus );
		}

		if ( $params['tags'] ) {
			$tagStatus = $changer->setTags( $params['tags'] );
			if ( !$tagStatus->isGood() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		// Everything passed, make the conversion
		$status = $changer->doContentModelChange(
			$this->getContext(),
			$params['summary'] ?? '',
			$params['bot']
		);

		if ( !$status->isGood() ) {
			// Failed
			$this->dieStatus( $status );
		}
		$logid = $status->getValue()['logid'];

		$result = [
			'result' => 'Success',
			'title' => $title->getPrefixedText(),
			'pageid' => $title->getArticleID(),
			'contentmodel' => $title->getContentModel( IDBAccessObject::READ_LATEST ),
			'logid' => $logid,
			'revid' => $title->getLatestRevID( IDBAccessObject::READ_LATEST ),
		];

		$this->getResult()->addValue( null, $this->getModuleName(), $result );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$models = $this->contentHandlerFactory->getContentModels();
		$modelOptions = [];
		foreach ( $models as $model ) {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
			if ( !$handler->supportsDirectEditing() ) {
				continue;
			}
			$modelOptions[] = $model;
		}

		return [
			'title' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'summary' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
			'model' => [
				ParamValidator::PARAM_TYPE => $modelOptions,
				ParamValidator::PARAM_REQUIRED => true,
			],
			'bot' => false,
		];
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:ChangeContentModel';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=changecontentmodel&title=Main Page&model=text&token=123ABC'
				=> 'apihelp-changecontentmodel-example'
		];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiChangeContentModel::class, 'ApiChangeContentModel' );
