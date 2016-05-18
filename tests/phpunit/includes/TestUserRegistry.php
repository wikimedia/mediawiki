<?php

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

	public static function getNextId() {
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
	 * @param string $testName Caller's __CLASS__. Used to generate the
	 *  user's username.
	 * @param string[] $groups Groups the test user should be added to.
	 * @return TestUser
	 */
	public static function getMutableTestUser( $testName, $groups = [] ) {
		$id = self::getNextId();
		$password = wfRandomString( 20 );
		$testUser = new TestUser(
			"TestUser $testName $id",  // username
			"Name $id",                // real name
			"$id@mediawiki.test",      // e-mail
			$groups,                   // groups
			$password                  // password
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
	 * @param string[] $groups Groups the test user should be added to.
	 * @return TestUser
	 */
	public static function getImmutableTestUser( $groups = [] ) {
		$groups = array_unique( $groups );
		sort( $groups );
		$key = implode( ',', $groups );

		$testUser = isset( self::$testUsers[$key] )
			? self::$testUsers[$key]
			: false;

		if ( !$testUser || !$testUser->getUser()->isLoggedIn() ) {
			$id = self::getNextId();
			// Hack! If this is the primary sysop account, make the username
			// be 'UTSysop', for back-compat, and for the sake of PHPUnit data
			// provider methods, which are executed before the test database
			// is set up. See T136348.
			if ( $groups === [ 'bureaucrat', 'sysop' ] ) {
				$username = 'UTSysop';
				$password = 'UTSysopPassword';
			} else {
				$username = "TestUser $id";
				$password = wfRandomString( 20 );
			}
			self::$testUsers[$key] = $testUser = new TestUser(
				$username,            // username
				"Name $id",           // real name
				"$id@mediawiki.test", // e-mail
				$groups,              // groups
				$password             // password
			);
		}

		$testUser->getUser()->clearInstanceCache();
		return self::$testUsers[$key];
	}

	/**
	 * Clear the registry.
	 *
	 * TestUsers created by this class will not be deleted, but any handles
	 * to existing immutable TestUsers will be deleted, ensuring these users
	 * are not reused. We don't reset the counter or random string by design.
	 *
	 * @since 1.28
	 *
	 * @param string[] $groups Groups the test user should be added to.
	 * @return TestUser
	 */
	public static function clear() {
		self::$testUsers = [];
	}
}
