<?php
/**
 * Copyright © 2008 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\User;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API module to allow users to log out of the wiki. API equivalent of
 * Special:Userlogout.
 *
 * @ingroup API
 */
class ApiLogout extends ApiBase {

	public function __construct(
		ApiMain $main,
		string $action,
		private readonly SessionManager $sessionManager
	) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		if ( $this->getUser()->isAnon() ) {
			// Cannot log out an anon user, so add a warning and return early.
			$this->addWarning( 'apierror-mustbeloggedin-generic', 'notloggedin' );
			return;
		}

		if ( $this->getParameter( 'global' ) ) {
			$this->checkUserRightsAny( 'logout' );
			$this->doGlobalLogout();
		} else {
			$this->doLocalLogout();
		}
	}

	protected function doGlobalLogout(): void {
		$user = $this->getUser();
		// remove cookies
		$this->getRequest()->getSession()->unpersist();
		$this->sessionManager->invalidateSessionsForUser( $user );
		$this->callUserLogoutComplete( $user, $user->getName() );
	}

	protected function doLocalLogout(): void {
		$user = $this->getUser();
		$oldUserName = $user->getName();
		$session = $this->getRequest()->getSession();

		// Handle bot password logout specially
		if ( $session->getProvider() instanceof BotPasswordSessionProvider ) {
			$session->unpersist();
			return;
		}

		// Make sure it's possible to log out
		if ( !$session->canSetUser() ) {
			$this->dieWithError(
				[
					'cannotlogoutnow-text',
					$session->getProvider()->describe( $this->getErrorFormatter()->getLanguage() )
				],
				'cannotlogout'
			);
		}

		$user->logout();
		$this->callUserLogoutComplete( $user, $oldUserName );
	}

	protected function callUserLogoutComplete( User $user, string $oldUserName ) {
		// Give extensions to do something after user logout
		$injected_html = '';
		$this->getHookRunner()->onUserLogoutComplete( $user, $injected_html, $oldUserName );
	}

	/** @inheritDoc */
	protected function getAllowedParams() {
		return [
			'global' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
			],
		];
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	public function isWriteMode() {
		// While core is optimized by default to not require DB writes on log out,
		// these are authenticated POST requests and extensions (eg. CheckUser) are
		// allowed to perform DB writes here without warnings.
		return true;
	}

	/** @inheritDoc */
	protected function getWebUITokenSalt( array $params ) {
		return 'logoutToken';
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=logout&token=123ABC'
				=> 'apihelp-logout-example-logout',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Logout';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiLogout::class, 'ApiLogout' );
