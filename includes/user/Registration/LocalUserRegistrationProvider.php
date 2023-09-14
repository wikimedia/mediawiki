<?php

namespace MediaWiki\User\Registration;

use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;

class LocalUserRegistrationProvider implements IUserRegistrationProvider {

	public const TYPE = 'local';

	private UserFactory $userFactory;

	/**
	 * @param UserFactory $userFactory
	 */
	public function __construct( UserFactory $userFactory ) {
		$this->userFactory = $userFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRegistration( UserIdentity $user ) {
		// TODO: Factor this out from User::getRegistration to this method.
		$user = $this->userFactory->newFromUserIdentity( $user );
		return $user->getRegistration();
	}
}
