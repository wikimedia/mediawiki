<?php

use MediaWiki\MediaWikiServices;

/**
 * Wraps the user object, so we can also retain full access to properties
 * like password if we log in via the API.
 */
class TestUser {
	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var User
	 */
	private $user;

	private function assertNotReal() {
		global $wgDBprefix;
		if ( $wgDBprefix !== MediaWikiTestCase::DB_PREFIX &&
			$wgDBprefix !== MediaWikiTestCase::ORA_DB_PREFIX
		) {
			throw new MWException( "Can't create user on real database" );
		}
	}

	public function __construct( $username, $realname = 'Real Name',
		$email = 'sample@example.com', $groups = []
	) {
		$this->assertNotReal();

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
				$this->username, [
					"email" => $email,
					"real_name" => $realname
				]
			);

			if ( !$this->user ) {
				throw new MWException( "Error creating TestUser " . $username );
			}
		}

		// Update the user to use the password and other details
		$this->setPassword( $this->password );
		$change = $this->setEmail( $email ) ||
			$this->setRealName( $realname );

		// Adjust groups by adding any missing ones and removing any extras
		$currentGroups = $this->user->getGroups();
		foreach ( array_diff( $groups, $currentGroups ) as $group ) {
			$this->user->addGroup( $group );
		}
		foreach ( array_diff( $currentGroups, $groups ) as $group ) {
			$this->user->removeGroup( $group );
		}
		if ( $change ) {
			// Disable CAS check before saving. The User object may have been initialized from cached
			// information that may be out of whack with the database during testing. If tests were
			// perfectly isolated, this would not happen. But if it does happen, let's just ignore the
			// inconsistency, and just write the data we want - during testing, we are not worried
			// about data loss.
			$this->user->mTouched = '';
			$this->user->saveSettings();
		}
	}

	/**
	 * @param string $realname
	 * @return bool
	 */
	private function setRealName( $realname ) {
		if ( $this->user->getRealName() !== $realname ) {
			$this->user->setRealName( $realname );
			return true;
		}

		return false;
	}

	/**
	 * @param string $email
	 * @return bool
	 */
	private function setEmail( $email ) {
		if ( $this->user->getEmail() !== $email ) {
			$this->user->setEmail( $email );
			return true;
		}

		return false;
	}

	/**
	 * @param string $password
	 */
	private function setPassword( $password ) {
		self::setPasswordForUser( $this->user, $password );
	}

	/**
	 * Set the password on a testing user
	 *
	 * This assumes we're still using the generic AuthManager config from
	 * PHPUnitMaintClass::finalSetup(), and just sets the password in the
	 * database directly.
	 * @param User $user
	 * @param string $password
	 */
	public static function setPasswordForUser( User $user, $password ) {
		if ( !$user->getId() ) {
			throw new MWException( "Passed User has not been added to the database yet!" );
		}

		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			[ 'user_password' ],
			[ 'user_id' => $user->getId() ],
			__METHOD__
		);
		if ( !$row ) {
			throw new MWException( "Passed User has an ID but is not in the database?" );
		}

		$passwordFactory = MediaWikiServices::getInstance()->getPasswordFactory();
		if ( !$passwordFactory->newFromCiphertext( $row->user_password )->equals( $password ) ) {
			$passwordHash = $passwordFactory->newFromPlaintext( $password );
			$dbw->update(
				'user',
				[ 'user_password' => $passwordHash->toString() ],
				[ 'user_id' => $user->getId() ],
				__METHOD__
			);
		}
	}

	/**
	 * @since 1.25
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @since 1.25
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
}
