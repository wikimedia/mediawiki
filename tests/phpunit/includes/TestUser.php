<?php

/**
 * Wraps the user object, so we can also retain full access to properties like password if we log in via the API
 */
class TestUser {
	public $username;
	public $password;
	public $email;
	public $groups;
	public $user;

	public function __construct( $username, $realname = 'Real Name', $email = 'sample@example.com', $groups = array() ) {
		$this->username = $username;
		$this->realname = $realname;
		$this->email = $email;
		$this->groups = $groups;

		// don't allow user to hardcode or select passwords -- people sometimes run tests
		// on live wikis. Sometimes we create sysop users in these tests. A sysop user with
		// a known password would be a Bad Thing.
		$this->password = User::randomPassword();

		$this->user = User::newFromName( $this->username );
		$this->user->load();

		// In an ideal world we'd have a new wiki (or mock data store) for every single test.
		// But for now, we just need to create or update the user with the desired properties.
		// we particularly need the new password, since we just generated it randomly.
		// In core MediaWiki, there is no functionality to delete users, so this is the best we can do.
		if ( !$this->user->getID() ) {
			// create the user
			$this->user = User::createNew(
				$this->username, array(
					"email" => $this->email,
					"real_name" => $this->realname
				)
			);
			if ( !$this->user ) {
				throw new Exception( "error creating user" );
			}
		}

		// update the user to use the new random password and other details
		$this->user->setPassword( $this->password );
		$this->user->setEmail( $this->email );
		$this->user->setRealName( $this->realname );
		// remove all groups, replace with any groups specified
		foreach ( $this->user->getGroups() as $group ) {
			$this->user->removeGroup( $group );
		}
		if ( count( $this->groups ) ) {
			foreach ( $this->groups as $group ) {
				$this->user->addGroup( $group );
			}
		}
		$this->user->saveSettings();
	}
}
