<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\User\TempUser\TempUserCreator;

/**
 * Acquire a temporary user username and stash it in the current session, if temp account creation
 * is enabled and the current user is logged out. If a name has already been stashed, returns the
 * same name.
 *
 * If the user later performs an action that results in temp account creation, the stashed username
 * will be used for their account. It may also be used in previews. However, the account is not
 * created yet, and the name is not visible to other users.
 *
 * @ingroup API
 */
class ApiAcquireTempUserName extends ApiBase {

	private TempUserCreator $tempUserCreator;

	public function __construct(
		ApiMain $main,
		string $action,
		TempUserCreator $tempUserCreator
	) {
		parent::__construct( $main, $action );
		$this->tempUserCreator = $tempUserCreator;
	}

	public function execute() {
		// Like TempUserCreator::shouldAutoCreate(), but without the action check
		if ( !$this->tempUserCreator->isEnabled() ) {
			$this->dieWithError( 'apierror-tempuserdisabled', 'tempuserdisabled' );
		}
		if ( $this->getUser()->isRegistered() ) {
			$this->dieWithError( 'apierror-alreadyregistered', 'alreadyregistered' );
		}
		$this->checkUserRightsAny( 'createaccount' );

		// Checks passed, acquire the name
		$session = $this->getRequest()->getSession();
		$name = $this->tempUserCreator->acquireAndStashName( $session );
		if ( $name === null ) {
			$this->dieWithError( 'apierror-tempuseracquirefailed', 'tempuseracquirefailed' );
		}

		$session->persist();
		$this->getResult()->addValue( null, $this->getModuleName(), $name );
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiAcquireTempUserName::class, 'ApiAcquireTempUserName' );
