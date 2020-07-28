<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use RequestContext;
use Wikimedia\Message\MessageValue;

/**
 * @since 1.35
 */
abstract class AbstractContributionHandler extends Handler {

	/**
	 * @var ContributionsLookup
	 */
	protected $contributionsLookup;

	/**
	 * @var UserFactory
	 */
	protected $userFactory;

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
	 * @param UserFactory $userFactory
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ContributionsLookup $contributionsLookup,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils
	) {
		$this->contributionsLookup = $contributionsLookup;
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
	}

	protected function postInitSetup() {
		$this->me = $this->getConfig()['mode'] === 'me';
	}

	/**
	 * Returns the user who's contributions we are requesting.
	 * Either me (requesting user) or another user.
	 *
	 * @return UserIdentity
	 * @throws LocalizedHttpException
	 */
	protected function getTargetUser() {
		if ( $this->me ) {
			$user = RequestContext::getMain()->getUser();
			if ( $user->isAnon() ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-permission-denied-anon' ), 401
				);
			}

			return $user;
		}

		$name = $this->getValidatedParams()['name'] ?? null;
		if ( $this->userNameUtils->isIP( $name ) ) {
			// Create an anonymous user instance for the given IP
			// NOTE: We can't use a UserIdentityValue, because we might need the actor ID
			$user = $this->userFactory->newAnonymous( $name );
			return $user;
		}

		$user = $this->userFactory->newFromName( $name );
		if ( !$user ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-invalid-user', [ $name ] ), 400
			);
		}

		if ( !$user->isRegistered() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-user', [ $user->getName() ] ), 404
			);
		}

		return $user;
	}

}
