<?php

use MediaWiki\User\User;

/**
 * @since 1.28
 */
class TestUserRegistry {

	/** @var TestUser[] (group key => TestUser) */
	private static $testUsers = [];

	/** @var int Count of users that have been generated */
	private static $counter = 0;

	/** @var int Random int, included in IDs */
	private static $randInt;

	public static function getNextId(): string {
		if ( !self::$randInt ) {
			self::$randInt = mt_rand( 1, 0xFFFFFF );
		}
		return sprintf( '%06x.%03x', self::$randInt, ++self::$counter );
	}

	/**
	 * Get a TestUser object that the caller may modify.
	 *
	 * @since 1.28
	 *
	 * @param string $testName Caller's __CLASS__ or arbitrary string. Used to generate the
	 *  user's username.
	 * @param string|string[] $groups Groups the test user should be added to.
	 * @param string|null $userPrefix if non-null, the user prefix will be as specified instead of "TestUser"
	 * @return TestUser
	 */
	public static function getMutableTestUser( $testName, $groups = [], $userPrefix = null ) {
		$id = self::getNextId();
		$testUserName = "$testName $id";
		$userPrefix ??= "TestUser";
		$testUser = new TestUser(
			"$userPrefix $testName $id",
			"Name $id",
			"$id@mediawiki.test",
			(array)$groups
		);
		$testUser->getUser()->clearInstanceCache();
		return $testUser;
	}

	/**
	 * Get a TestUser object that the caller may not modify.
	 *
	 * Whenever possible, unit tests should use immutable users, because
	 * immutable users can be reused in multiple tests, which helps keep
	 * the unit tests fast.
	 *
	 * @since 1.28
	 *
	 * @param string|string[] $groups Groups the test user should be added to.
	 * @return TestUser
	 */
	public static function getImmutableTestUser( $groups = [] ) {
		$groups = array_unique( (array)$groups );
		sort( $groups );
		$key = implode( ',', $groups );

		$testUser = self::$testUsers[$key] ?? false;

		if ( !$testUser || !$testUser->getUser()->isRegistered() ) {
			$id = self::getNextId();
			// Hack! If this is the primary sysop account, make the username
			// be 'UTSysop', for back-compat, and for the sake of PHPUnit data
			// provider methods, which are executed before the test database
			// is set up. See T136348.
			if ( $groups === [ 'bureaucrat', 'sysop' ] ) {
				$username = 'UTSysop';
			} else {
				$username = "TestUser $id";
			}
			self::$testUsers[$key] = $testUser = new TestUser(
				$username,
				"Name $id",
				"$id@mediawiki.test",
				$groups
			);
		}

		$testUser->getUser()->clearInstanceCache();
		return self::$testUsers[$key];
	}

	/**
	 * TestUsers created by this class will not be deleted, but any handles
	 * to existing immutable TestUsers will be deleted, ensuring these users
	 * are not reused. We don't reset the counter or random string by design.
	 *
	 * @since 1.28
	 */
	public static function clear() {
		self::$testUsers = [];
	}

	/**
	 * Call clearInstanceCache() on all User objects known to the registry.
	 * This ensures that the User objects do not retain stale references
	 * to service objects.
	 *
	 * @since 1.39
	 */
	public static function clearInstanceCaches() {
		foreach ( self::$testUsers as $user ) {
			$user->getUser()->clearInstanceCache();
		}
	}

	/**
	 * @todo It would be nice if this were a non-static method of TestUser
	 * instead, but that doesn't seem possible without friends?
	 *
	 * @param User $user
	 * @return bool True if it's safe to modify the user
	 */
	public static function isMutable( User $user ) {
		foreach ( self::$testUsers as $key => $testUser ) {
			if ( $user === $testUser->getUser() ) {
				return false;
			}
		}
		return true;
	}
}
