<?php

use MediaWiki\MediaWikiServices;

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

	/**
	 * A lot of this code is based on SpecialChangeContentModel
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$wikiPage = $this->getTitleOrPageId( $params );
		$title = $wikiPage->getTitle();

		if ( !$title->exists() ) {
			$this->dieWithError( 'apierror-changecontentmodel-missingtitle' );
		}
		$plainTitle = Message::plaintextParam( $title->getPrefixedText() );

		$newModel = $params['model'];
		$user = $this->getUser();

		$this->checkUserRightsAny( 'editcontentmodel' );
		$changer = MediaWikiServices::getInstance()
			->getContentModelChangeFactory()
			->newContentModelChange(
				$user,
				$wikiPage,
				$newModel
			);
		// Status messages should be apierror-*
		$changer->setMessagePrefix( 'apierror-' );
		$errors = $changer->checkPermissions();
		if ( $errors !== [] ) {
			$this->dieStatus( $this->errorArrayToStatus( $errors, $user ) );
		}

		if ( $params['tags'] ) {
			$tagStatus = $changer->setTags( $params['tags'] );
			if ( !$tagStatus->isGood() ) {
				$this->dieStatus( $tagStatus );
			}
		}

		// Everything passed, make the conversion
		try {
			$status = $changer->doContentModelChange(
				$this->getContext(),
				$params['summary'],
				$params['bot']
			);
		} catch ( ThrottledError $te ) {
			$this->dieWithError( 'apierror-ratelimited' );
		}

		if ( !$status->isGood() ) {
			// Failed
			$this->dieStatus( $status );
		}
		$logid = $status->getValue()['logid'];

		$result = [
			'result' => 'Success',
			'title' => $title->getPrefixedText(),
			'pageid' => $title->getArticleID(),
			'contentmodel' => $title->getContentModel( Title::READ_LATEST ),
			'logid' => $logid,
			'revid' => $title->getLatestRevID( Title::READ_LATEST ),
		];

		$this->getResult()->addValue( null, $this->getModuleName(), $result );
	}

	public function getAllowedParams() {
		$models = ContentHandler::getContentModels();
		$modelOptions = [];
		foreach ( $models as $model ) {
			$handler = ContentHandler::getForModelID( $model );
			if ( !$handler->supportsDirectEditing() ) {
				continue;
			}
			$modelOptions[] = $model;
		}

		return [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'summary' => null,
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
			'model' => [
				ApiBase::PARAM_TYPE => $modelOptions,
				ApiBase::PARAM_REQUIRED => true,
			],
			'bot' => false,
		];
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:ChangeContentModel';
	}

	protected function getExamplesMessages() {
		return [
			'action=changecontentmodel&title=Main Page&model=text&token=123ABC'
				=> 'apihelp-changecontentmodel-example'
		];
	}
}
