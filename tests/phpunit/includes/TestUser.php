<?php

/**
 * Wraps the user object, so we can also retain full access to properties
 * like password if we log in via the API.
 */
class TestUser {
	/**
	 * @deprecated Use TestUser::getUser()->getName()
	 * @private
	 * @var string
	 */
	public $username;

	/**
	 * @deprecated Use TestUser::getPassword()
	 * @private
	 * @var string
	 */
	public $password;

	/**
	 * @deprecated Use TestUser::getUser()
	 * @private
	 * @var User
	 */
	public $user;

	public function __construct( $username, $realname = 'Real Name',
		$email = 'sample@example.com', $groups = array()
	) {
		$this->username = $username;
		$this->password = 'TestUser';

		$this->user = User::newFromName( $this->username );
		$this->user->load();

		// In an ideal world we'd have a new wiki (or mock data store) for every single test.
		// But for now, we just need to create or update the user with the desired properties.
		// we particularly need the new password, since we just generated it randomly.
		// In core MediaWiki, there is no functionality to delete users, so this is the best we can do.
		if ( !$this->user->isLoggedIn() ) {
			// create the user
			$this->user = User::createNew(
				$this->username, array(
					"email" => $email,
					"real_name" => $realname
				)
			);

			if ( !$this->user ) {
				throw new Exception( "error creating user" );
			}
		}

		// Update the user to use the password and other details
		$this->user->setPassword( $this->password );
		$this->user->setEmail( $email );
		$this->user->setRealName( $realname );

		// Adjust groups by adding any missing ones and removing any extras
		$currentGroups = $this->user->getGroups();
		foreach ( array_diff( $groups, $currentGroups ) as $group ) {
			$this->user->addGroup( $group );
		}
		foreach ( array_diff( $currentGroups, $groups ) as $group ) {
			$this->user->removeGroup( $group );
		}
		$this->user->saveSettings();
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	public function __destruct() {
		if ( $this->user ) {
			// To make sure the user is not usable, even if it leaked in the real DB
			$this->user->setPassword( null );
			$this->user->saveSettings();
		}
	}
}
