<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use Wikimedia\Message\MessageValue;

/**
 * @since 1.35
 */
abstract class AbstractContributionHandler extends Handler {

	/**
	 * @var ContributionsLookup
	 */
	protected $contributionsLookup;

	/** Hard limit results to 20 contributions */
	protected const MAX_LIMIT = 20;

	/**
	 * @var bool User is requesting their own contributions
	 */
	protected $me;

	/**
	 * @var UserNameUtils
	 */
	protected $userNameUtils;

	/**
	 * @param ContributionsLookup $contributionsLookup
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ContributionsLookup $contributionsLookup,
		UserNameUtils $userNameUtils
	) {
		$this->contributionsLookup = $contributionsLookup;
		$this->userNameUtils = $userNameUtils;
	}

	protected function postInitSetup() {
		$this->me = $this->getConfig()['mode'] === 'me';
	}

	/**
	 * Returns the user whose contributions we are requesting.
	 * Either me (requesting user) or another user.
	 *
	 * @return UserIdentity
	 * @throws LocalizedHttpException
	 */
	protected function getTargetUser() {
		if ( $this->me ) {
			$user = $this->getAuthority()->getUser();
			if ( !$user->isRegistered() ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-permission-denied-anon' ), 401
				);
			}
			return $user;
		}

		/** @var UserIdentity $user */
		$user = $this->getValidatedParams()['user'];
		$name = $user->getName();
		if ( !$this->userNameUtils->isIP( $name ) && !$user->isRegistered() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-user', [ $name ] ), 404
			);
		}
		return $user;
	}
}
