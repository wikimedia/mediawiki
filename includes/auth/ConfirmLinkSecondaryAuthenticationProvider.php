<?php

namespace MediaWiki\Auth;

use StatusValue;
use User;

/**
 * Links third-party authentication to the user's account
 *
 * If the user logged into linking provider accounts that aren't linked to a
 * local user, this provider will prompt the user to link them after a
 * successful login or account creation.
 *
 * To avoid confusing behavior, this provider should be later in the
 * configuration list than any provider that can abort the authentication
 * process, so that it is only invoked for successful authentication.
 */
class ConfirmLinkSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	public function getAuthenticationRequests( $action, array $options ) {
		return array();
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->beginLinkAttempt( $user, 'AuthManager::authnState' );
	}

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		return $this->continueLinkAttempt( $user, 'AuthManager::authnState', $reqs );
	}

	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return $this->beginLinkAttempt( $user, 'AuthManager::accountCreationState' );
	}

	public function continueSecondaryAccountCreation( $user, array $reqs ) {
		return $this->continueLinkAttempt( $user, 'AuthManager::accountCreationState', $reqs );
	}

	/**
	 * Begin the link attempt
	 * @param User $user
	 * @param string $key Session key to look in
	 * @return AuthenticationResponse
	 */
	protected function beginLinkAttempt( $user, $key ) {
		$session = $this->manager->getRequest()->getSession();
		$state = $session->get( $key );
		if ( !is_array( $state ) ) {
			return AuthenticationResponse::newAbstain();
		}
		$maybeLink = $state['maybeLink'];
		if ( !$maybeLink ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = new ConfirmLinkAuthenticationRequest( $maybeLink );
		return AuthenticationResponse::newUI(
			array( $req ),
			wfMessage( 'authprovider-confirmlink-message' )
		);
	}

	/**
	 * Continue the link attempt
	 * @param User $user
	 * @param string $key Session key to look in
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	protected function continueLinkAttempt( $user, $key, array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, 'MediaWiki\\Auth\\ConfirmLinkAuthenticationRequest'
		);
		if ( !$req ) {
			// WTF? Retry.
			return $this->beginLinkAttempt( $user, $key );
		}

		$session = $this->manager->getRequest()->getSession();
		$state = $session->get( $key );
		if ( !is_array( $state ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$maybeLink = array();
		foreach ( $state['maybeLink'] as $linkReq ) {
			$maybeLink[$linkReq->getUniqueId()] = $linkReq;
		}
		if ( !$maybeLink ) {
			return AuthenticationResponse::newAbstain();
		}

		$state['maybeLink'] = array();
		$session->set( $key, $state );

		foreach ( $req->confirmedLinkIDs as $id ) {
			if ( isset( $maybeLink[$id] ) ) {
				$this->manager->changeAuthenticationData( $maybeLink[$id] );
			}
		}

		return AuthenticationResponse::newPass();
	}
}
