<?php

namespace MediaWiki\Auth;

use MediaWiki\User\User;

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

	/** @inheritDoc */
	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	/** @inheritDoc */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->beginLinkAttempt( $user, AuthManager::AUTHN_STATE );
	}

	/** @inheritDoc */
	public function continueSecondaryAuthentication( $user, array $reqs ) {
		return $this->continueLinkAttempt( $user, AuthManager::AUTHN_STATE, $reqs );
	}

	/** @inheritDoc */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return $this->beginLinkAttempt( $user, AuthManager::ACCOUNT_CREATION_STATE );
	}

	/** @inheritDoc */
	public function continueSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return $this->continueLinkAttempt( $user, AuthManager::ACCOUNT_CREATION_STATE, $reqs );
	}

	/**
	 * Begin the link attempt
	 * @param User $user
	 * @param string $key Session key to look in
	 * @return AuthenticationResponse
	 */
	protected function beginLinkAttempt( $user, $key ) {
		$session = $this->manager->getRequest()->getSession();
		$state = $session->getSecret( $key );
		if ( !is_array( $state ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$maybeLink = array_filter( $state['maybeLink'], function ( $req ) use ( $user ) {
			if ( !$req->action ) {
				$req->action = AuthManager::ACTION_CHANGE;
			}
			$req->username = $user->getName();
			return $this->manager->allowsAuthenticationDataChange( $req )->isGood();
		} );
		if ( !$maybeLink ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = new ConfirmLinkAuthenticationRequest( $maybeLink );
		return AuthenticationResponse::newUI(
			[ $req ],
			wfMessage( 'authprovider-confirmlink-message' ),
			'warning'
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
		$req = ButtonAuthenticationRequest::getRequestByName( $reqs, 'linkOk' );
		if ( $req ) {
			return AuthenticationResponse::newPass();
		}

		$req = AuthenticationRequest::getRequestByClass( $reqs, ConfirmLinkAuthenticationRequest::class );
		if ( !$req ) {
			// WTF? Retry.
			return $this->beginLinkAttempt( $user, $key );
		}

		$session = $this->manager->getRequest()->getSession();
		$state = $session->getSecret( $key );
		if ( !is_array( $state ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$maybeLink = [];
		foreach ( $state['maybeLink'] as $linkReq ) {
			$maybeLink[$linkReq->getUniqueId()] = $linkReq;
		}
		if ( !$maybeLink ) {
			return AuthenticationResponse::newAbstain();
		}

		$state['maybeLink'] = [];
		$session->setSecret( $key, $state );

		$statuses = [];
		$anyFailed = false;
		foreach ( $req->confirmedLinkIDs as $id ) {
			if ( isset( $maybeLink[$id] ) ) {
				$req = $maybeLink[$id];
				$req->username = $user->getName();
				if ( !$req->action ) {
					// Make sure the action is set, but don't override it if
					// the provider filled it in.
					$req->action = AuthManager::ACTION_CHANGE;
				}
				$status = $this->manager->allowsAuthenticationDataChange( $req );
				$statuses[] = [ $req, $status ];
				if ( $status->isGood() ) {
					// We're not changing credentials, just adding a new link
					// to an already-known user.
					$this->manager->changeAuthenticationData( $req, /* $isAddition */ true );
				} else {
					$anyFailed = true;
				}
			}
		}
		if ( !$anyFailed ) {
			return AuthenticationResponse::newPass();
		}

		$combinedStatus = \MediaWiki\Status\Status::newGood();
		foreach ( $statuses as [ $req, $status ] ) {
			$descriptionInfo = $req->describeCredentials();
			$description = wfMessage(
				'authprovider-confirmlink-option',
				$descriptionInfo['provider']->text(), $descriptionInfo['account']->text()
			)->text();
			if ( $status->isGood() ) {
				$combinedStatus->error( wfMessage( 'authprovider-confirmlink-success-line', $description ) );
			} else {
				$combinedStatus->error( wfMessage(
					'authprovider-confirmlink-failed-line', $description, $status->getMessage()->text()
				) );
			}
		}
		return AuthenticationResponse::newUI(
			[
				new ButtonAuthenticationRequest(
					'linkOk', wfMessage( 'ok' ), wfMessage( 'authprovider-confirmlink-ok-help' )
				)
			],
			$combinedStatus->getMessage( 'authprovider-confirmlink-failed' ),
			'error'
		);
	}
}
