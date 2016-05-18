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
	 * @param string $testSuiteName Caller's __METHOD__. Used to generate
	 *  the test user's username.
	 * @param string[] $groups Groups the test user should be added to.
	 * @return TestUser
	 */
	public static function getMutableTestUser( $testSuiteName, $groups = [] ) {
		$id = self::getNextId();
		$password = PasswordFactory::generateRandomPasswordString( 20 );
		$testUser = new TestUser(
			"TestUser $id",       // username
			"Name $id",           // real name
			"$id@mediawiki.test", // e-mail
			$groups,              // groups
			$password             // password
		);
		$testUser->getUser()->saveSettings();
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
		$key = implode( ',', $groups );

		if ( !isset( self::$testUsers[$key] ) ) {
			$id = self::getNextId();
			// Hack! If this is the primary sysop account, make the username be
			// 'UTSysop', for back-compat. This will be removed once extensions
			// are updated not to hard-code test user names.
			$username = $groups === [ 'bureaucrat', 'sysop' ]
				? 'UTSysop'
				: "TestUser $id";
			$username = "TestUser $id";
			$password = PasswordFactory::generateRandomPasswordString( 20 );
			self::$testUsers[$key] = $testUser = new TestUser(
				"TestUser $id",       // username
				"Name $id",           // real name
				"$id@mediawiki.test", // e-mail
				$groups,              // groups
				$password             // password
			);
			$testUser->getUser()->saveSettings();
		}

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
