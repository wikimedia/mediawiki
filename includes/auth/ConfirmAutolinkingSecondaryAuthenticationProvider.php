<?php

namespace MediaWiki\Auth;

use LogicException;
use StatusValue;
use User;

/**
 * Requires autolinked accounts to be confirmed by the user before they are
 * passed to finishAccountLink(). This prevents "autolink fixation" attacks
 * where the attacker does a partial login (successful authentication but the
 * local account does not exist and cannot be autocreated, so the remote
 * account is stored for autolinking) then leaves the machine with the login
 * form around for the victim to log in (after which the attacker has a linked
 * account with which they can authenticate as the victim).
 *
 * To prevent this, the user needs to confirm all to-be-linked accounts on a
 * checkbox form. The labels are constructed from the data returned by the
 * describe() method of the autolink request.
 *
 * To avoid confusing behavior, this provider should be later in the
 * configuration list than any provider that can abort the authentication
 * process, so that it is only invoked for successful authentication.
 *
 * @see PrimaryAuthenticationProvider::finishAccountLink()
 * @see AuthenticationResponse::newPass()
 */
class ConfirmAutolinkingSecondaryAuthenticationProvider
	extends AbstractSecondaryAuthenticationProvider
{
	public function getAuthenticationRequests( $action ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN_CONTINUE:
			case AuthManager::ACTION_CREATE_CONTINUE:
				// FIXME this is ugly. Couldn't AuthManager just calculate
				// _CONTINUE calls from saved state?
				$req = AuthManager::singleton()->getAuthenticationSessionData( 'ConfirmAutolinking:req' );
				return $req ? array( $req ) : array();

			default:
				return array();
		}
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		$session = AuthManager::singleton()->getRequest()->getSession();
		$maybeLink = $session->get( 'AuthManager::maybeLink', array() );

		if ( !$maybeLink ) {
			return AuthenticationResponse::newAbstain();
		}

		$reqs = array_map( function ( $item ) {
			return $item['req'];
		}, $maybeLink );
		$req = new ConfirmAutolinkingAuthenticationRequest( $reqs );
		AuthManager::singleton()->setAuthenticationSessionData( 'ConfirmAutolinking:req', $req );
		return AuthenticationResponse::newUI( array( $req ),
			wfMessage( 'authprovider-confirmautolinking-message' ) );
	}

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\ConfirmAutolinkingAuthenticationRequest' );
		if ( !$req instanceof ConfirmAutolinkingAuthenticationRequest ) {
			throw new LogicException( __METHOD__ . ': called without a request' );
		}

		$authManager = AuthManager::singleton();
		$session = $authManager->getRequest()->getSession();
		$maybeLink = $session->get( 'AuthManager::maybeLink', array() );
		foreach ( $req->getConfirmedAccounts() as $reqId ) {
			if ( isset( $maybeLink[$reqId] ) ) {
				$authManager->changeAuthenticationData( $maybeLink[$reqId] );
			}
		}
		// TODO preserve some success message for post-login notification

		$session->remove( 'AuthManager::maybeLink' );
		return AuthenticationResponse::newPass();
	}


	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return $this->beginSecondaryAuthentication( $user, $reqs );
	}

	public function continueSecondaryAccountCreation( $user, array $reqs ) {
		return $this->continueSecondaryAuthentication( $user, $reqs );
	}

	/**
	 * Process logins stashed by stashRemoteLogin().
	 * @param User $user
	 * @param AuthenticationRequest[] $reallyLink
	 */
	protected function processRemoteLogins( User $user, array $reallyLink ) {
	}
}
