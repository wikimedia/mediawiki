<?php
use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @group Cache
 */
class GenderCacheTest extends MediaWikiLangTestCase {

	/** @var string[] User key => username */
	private static $nameMap;

	function addDBDataOnce() {
		// ensure the correct default gender
		$this->mergeMwGlobalArrayValue( 'wgDefaultUserOptions', [ 'gender' => 'unknown' ] );

		$male = $this->getMutableTestUser()->getUser();
		$male->setOption( 'gender', 'male' );
		$male->saveSettings();

		$female = $this->getMutableTestUser()->getUser();
		$female->setOption( 'gender', 'female' );
		$female->saveSettings();

		$default = $this->getMutableTestUser()->getUser();
		$default->setOption( 'gender', null );
		$default->saveSettings();

		self::$nameMap = [
			'UTMale'          => $male->getName(),
			'UTFemale'        => $female->getName(),
			'UTDefaultGender' => $default->getName()
		];
	}

	/**
	 * test usernames
	 *
	 * @dataProvider provideUserGenders
	 * @covers GenderCache::getGenderOf
	 */
	public function testUserName( $userKey, $expectedGender ) {
		$genderCache = MediaWikiServices::getInstance()->getGenderCache();
		$username = isset( self::$nameMap[$userKey] ) ? self::$nameMap[$userKey] : $userKey;
		$gender = $genderCache->getGenderOf( $username );
		$this->assertEquals( $gender, $expectedGender, "GenderCache normal" );
	}

	/**
	 * genderCache should work with user objects, too
	 *
	 * @dataProvider provideUserGenders
	 * @covers GenderCache::getGenderOf
	 */
	public function testUserObjects( $userKey, $expectedGender ) {
		$username = isset( self::$nameMap[$userKey] ) ? self::$nameMap[$userKey] : $userKey;
		$genderCache = MediaWikiServices::getInstance()->getGenderCache();
		$gender = $genderCache->getGenderOf( $username );
		$this->assertEquals( $gender, $expectedGender, "GenderCache normal" );
	}

	public static function provideUserGenders() {
		return [
			[ 'UTMale', 'male' ],
			[ 'UTFemale', 'female' ],
			[ 'UTDefaultGender', 'unknown' ],
			[ 'UTNotExist', 'unknown' ],
			// some not valid user
			[ '127.0.0.1', 'unknown' ],
			[ 'user@test', 'unknown' ],
		];
	}

	/**
	 * test strip of subpages to avoid unnecessary queries
	 * against the never existing username
	 *
	 * @dataProvider provideUserGenders
	 * @covers GenderCache::getGenderOf
	 */
	public function testStripSubpages( $userKey, $expectedGender ) {
		$username = isset( self::$nameMap[$userKey] ) ? self::$nameMap[$userKey] : $userKey;
		$genderCache = MediaWikiServices::getInstance()->getGenderCache();
		$gender = $genderCache->getGenderOf( "$username/subpage" );
		$this->assertEquals( $gender, $expectedGender, "GenderCache must strip of subpages" );
	}
}
