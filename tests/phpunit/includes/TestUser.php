<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * Wraps the user object, so we can also retain full access to properties
 * like password if we log in via the API.
 */
class TestUser {
	private string $username;

	private string $password;

	private User $user;

	private function assertNotReal(): void {
		global $wgDBprefix;
		if (
			$wgDBprefix !== MediaWikiIntegrationTestCase::DB_PREFIX &&
			$wgDBprefix !== ParserTestRunner::DB_PREFIX
		) {
			throw new RuntimeException( "Can't create user on real database" );
		}
	}

	public function __construct(
		string $username,
		string $realname = 'Real Name',
		string $email = 'sample@example.com',
		array $groups = [],
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
		if ( !$this->user->isRegistered() ) {
			$user = User::createNew(
				$this->username, [
					"email" => $email,
					"real_name" => $realname,
				]
			);

			if ( !$user ) {
				throw new RuntimeException( "Error creating TestUser " . $username );
			}
			$this->user = $user;
		}

		// Update the user to use the password and other details
		$this->setPassword( $this->password );
		$change = $this->setEmail( $email ) ||
			$this->setRealName( $realname );

		// Adjust groups by adding any missing ones and removing any extras
		$userGroupManager = MediaWikiServices::getInstance()->getUserGroupManager();
		$currentGroups = $userGroupManager->getUserGroups( $this->user );
		$userGroupManager->addUserToMultipleGroups( $this->user, array_diff( $groups, $currentGroups ) );
		foreach ( array_diff( $currentGroups, $groups ) as $group ) {
			$userGroupManager->removeUserFromGroup( $this->user, $group );
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

	private function setRealName( string $realname ): bool {
		if ( $this->user->getRealName() !== $realname ) {
			$this->user->setRealName( $realname );
			return true;
		}

		return false;
	}

	private function setEmail( string $email ): bool {
		if ( $this->user->getEmail() !== $email ) {
			$this->user->setEmail( $email );
			return true;
		}

		return false;
	}

	private function setPassword( string $password ): void {
		self::setPasswordForUser( $this->user, $password );
	}

	/**
	 * Set the password on a testing user
	 *
	 * This assumes we're still using the generic AuthManager config from
	 * {@link TestSetup::applyInitialConfig()}, and just sets the password in the
	 * database directly.
	 */
	public static function setPasswordForUser( User $user, string $password ): void {
		if ( !$user->getId() ) {
			throw new InvalidArgumentException( "Passed User has not been added to the database yet!" );
		}

		$services = MediaWikiServices::getInstance();

		$dbw = $services->getConnectionProvider()->getPrimaryDatabase();
		$row = $dbw->newSelectQueryBuilder()
			->select( [ 'user_password' ] )
			->from( 'user' )
			->where( [ 'user_id' => $user->getId() ] )
			->caller( __METHOD__ )->fetchRow();
		if ( !$row ) {
			throw new RuntimeException( "Passed User has an ID but is not in the database?" );
		}

		$passwordFactory = $services->getPasswordFactory();
		if ( !$passwordFactory->newFromCiphertext( $row->user_password )->verify( $password ) ) {
			$passwordHash = $passwordFactory->newFromPlaintext( $password );
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [ 'user_password' => $passwordHash->toString() ] )
				->where( [ 'user_id' => $user->getId() ] )
				->caller( __METHOD__ )->execute();
		}
	}

	/**
	 * @since 1.25
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * @since 1.39
	 */
	public function getAuthority(): Authority {
		return $this->user;
	}

	/**
	 * @since 1.36
	 */
	public function getUserIdentity(): UserIdentity {
		return new UserIdentityValue( $this->user->getId(), $this->user->getName() );
	}

	/**
	 * @since 1.25
	 */
	public function getPassword(): string {
		return $this->password;
	}
}
