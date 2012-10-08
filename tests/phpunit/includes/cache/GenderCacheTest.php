<?php

/**
 * @group Database
 * @group Cache
 */
class GenderCacheTest extends MediaWikiLangTestCase {

	protected function setUp() {
		global $wgDefaultUserOptions;
		parent::setUp();
		//ensure the correct default gender
		$wgDefaultUserOptions['gender'] = 'unknown';
	}

	function addDBData() {
		$user = User::newFromName( 'UTMale' );
		if( $user->getID() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTMalePassword' );
		}
		//ensure the right gender
		$user->setOption( 'gender', 'male' );
		$user->saveSettings();

		$user = User::newFromName( 'UTFemale' );
		if( $user->getID() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTFemalePassword' );
		}
		//ensure the right gender
		$user->setOption( 'gender', 'female' );
		$user->saveSettings();

		$user = User::newFromName( 'UTDefaultGender' );
		if( $user->getID() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTDefaultGenderPassword' );
		}
		//ensure the default gender
		$user->setOption( 'gender', null );
		$user->saveSettings();
	}

	/**
	 * test usernames
	 *
	 * @dataProvider provideUserGenders
	 */
	function testUserName( $username, $expectedGender ) {
		$genderCache = GenderCache::singleton();
		$gender = $genderCache->getGenderOf( $username );
		$this->assertEquals( $gender, $expectedGender, "GenderCache normal" );
	}

	/**
	 * genderCache should work with user objects, too
	 *
	 * @dataProvider provideUserGenders
	 */
	function testUserObjects( $username, $expectedGender ) {
		$genderCache = GenderCache::singleton();
		$user = User::newFromName( $username );
		$gender = $genderCache->getGenderOf( $user );
		$this->assertEquals( $gender, $expectedGender, "GenderCache normal" );
	}

	public static function provideUserGenders() {
		return array(
			array( 'UTMale', 'male' ),
			array( 'UTFemale', 'female' ),
			array( 'UTDefaultGender', 'unknown' ),
			array( 'UTNotExist', 'unknown' ),
			//some not valid user
			array( '127.0.0.1', 'unknown' ),
			array( 'user@test', 'unknown' ),
		);
	}

	/**
	 * test strip of subpages to avoid unnecessary queries
	 * against the never existing username
	 *
	 * @dataProvider provideStripSubpages
	 */
	function testStripSubpages( $pageWithSubpage, $expectedGender ) {
		$genderCache = GenderCache::singleton();
		$gender = $genderCache->getGenderOf( $pageWithSubpage );
		$this->assertEquals( $gender, $expectedGender, "GenderCache must strip of subpages" );
	}

	public static function provideStripSubpages() {
		return array(
			array( 'UTMale/subpage', 'male' ),
			array( 'UTFemale/subpage', 'female' ),
			array( 'UTDefaultGender/subpage', 'unknown' ),
			array( 'UTNotExist/subpage', 'unknown' ),
			array( '127.0.0.1/subpage', 'unknown' ),
		);
	}
}
