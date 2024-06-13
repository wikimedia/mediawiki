<?php

use MediaWiki\Cache\GenderCache;

/**
 * @group Database
 * @group Cache
 */
class GenderCacheTest extends MediaWikiLangTestCase {

	/** @var string[] User key => username */
	private static $nameMap;

	public function addDBDataOnce() {
		// ensure the correct default gender
		$this->mergeMwGlobalArrayValue( 'wgDefaultUserOptions', [ 'gender' => 'unknown' ] );

		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();

		$male = $this->getMutableTestUser()->getUser();
		$userOptionsManager->setOption( $male, 'gender', 'male' );
		$male->saveSettings();

		$female = $this->getMutableTestUser()->getUser();
		$userOptionsManager->setOption( $female, 'gender', 'female' );
		$female->saveSettings();

		$default = $this->getMutableTestUser()->getUser();
		$userOptionsManager->setOption( $default, 'gender', null );
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
	 * @covers \MediaWiki\Cache\GenderCache::getGenderOf
	 */
	public function testUserName( $userKey, $expectedGender ) {
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$username = self::$nameMap[$userKey] ?? $userKey;
		$gender = $genderCache->getGenderOf( $username );
		$this->assertEquals( $expectedGender, $gender, "GenderCache normal" );
	}

	/**
	 * genderCache should work with user objects, too
	 *
	 * @dataProvider provideUserGenders
	 * @covers \MediaWiki\Cache\GenderCache::getGenderOf
	 */
	public function testUserObjects( $userKey, $expectedGender ) {
		$username = self::$nameMap[$userKey] ?? $userKey;
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$gender = $genderCache->getGenderOf( $username );
		$this->assertEquals( $expectedGender, $gender, "GenderCache normal" );
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
	 * @covers \MediaWiki\Cache\GenderCache::getGenderOf
	 */
	public function testStripSubpages( $userKey, $expectedGender ) {
		$username = self::$nameMap[$userKey] ?? $userKey;
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$gender = $genderCache->getGenderOf( "$username/subpage" );
		$this->assertEquals( $expectedGender, $gender, "GenderCache must strip of subpages" );
	}

	/**
	 * GenderCache must work without database (like Installer)
	 * @coversNothing
	 */
	public function testWithoutDB() {
		$this->overrideMwServices();

		$services = $this->getServiceContainer();
		$services->disableService( 'DBLoadBalancer' );
		$services->disableService( 'DBLoadBalancerFactory' );

		// Make sure the disable works
		$this->assertTrue( $services->isServiceDisabled( 'DBLoadBalancer' ) );

		// Test, if it is possible to create the gender cache
		$genderCache = $services->getGenderCache();
		$this->assertInstanceOf( GenderCache::class, $genderCache );
	}
}
