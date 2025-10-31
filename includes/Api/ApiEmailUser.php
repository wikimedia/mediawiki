<?php
/**
 * Copyright © 2008 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Context\RequestContext;
use MediaWiki\Mail\EmailUserFactory;
use MediaWiki\Status\Status;
use MediaWiki\User\UserFactory;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API Module to facilitate sending of emails to users
 * @ingroup API
 */
class ApiEmailUser extends ApiBase {

	private EmailUserFactory $emailUserFactory;
	private UserFactory $userFactory;

	public function __construct( ApiMain $mainModule, string $moduleName,
		EmailUserFactory $emailUserFactory, UserFactory $userFactory ) {
		parent::__construct( $mainModule, $moduleName );

		$this->emailUserFactory = $emailUserFactory;
		$this->userFactory = $userFactory;
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$emailUser = $this->emailUserFactory->newEmailUser( RequestContext::getMain()->getAuthority() );
		$targetUser = $this->userFactory->newFromName( $params['target'] );

		if ( $targetUser === null ) {
			$this->dieWithError(
				[ 'apierror-baduser', 'target', wfEscapeWikiText( $params['target'] ) ],
				"baduser_target"
			);
		}

		$status = $emailUser->validateTarget( $targetUser );

		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		// Check permissions and errors
		$error = $emailUser->canSend();

		if ( !$error->isGood() ) {
			$this->dieStatus( $error );
		}

		$retval = $emailUser->sendEmailUnsafe(
			$targetUser,
			$params['subject'],
			$params['text'],
			$params['ccme'],
			$this->getLanguage()->getCode()
		);

		if ( !$retval instanceof Status ) {
			// This is probably the reason
			$retval = Status::newFatal( 'hookaborted' );
		}

		$result = array_filter( [
			'result' => $retval->isGood() ? 'Success' : ( $retval->isOK() ? 'Warnings' : 'Failure' ),
			'warnings' => $this->getErrorFormatter()->arrayFromStatus( $retval, 'warning' ),
			'errors' => $this->getErrorFormatter()->arrayFromStatus( $retval, 'error' ),
		] );

		$this->getResult()->addValue( null, $this->getModuleName(), $result );
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
	public function getAllowedParams() {
		return [
			'target' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
			],
			'subject' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
			],
			'text' => [
				ParamValidator::PARAM_TYPE => 'text',
				ParamValidator::PARAM_REQUIRED => true
			],
			'ccme' => false,
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=emailuser&target=WikiSysop&text=Content&token=123ABC'
				=> 'apihelp-emailuser-example-email',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Email';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiEmailUser::class, 'ApiEmailUser' );
