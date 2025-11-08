<?php

use MediaWiki\Cache\GenderCache;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Cache\GenderCache
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
	 * @dataProvider provideUserGenders
	 */
	public function testUserName( $userKey, $expectedGender ) {
		$username = self::$nameMap[$userKey] ?? $userKey;

		$genderCache = $this->getServiceContainer()->getGenderCache();
		$gender = $genderCache->getGenderOf( $username );
		$this->assertEquals( $expectedGender, $gender );
	}

	/**
	 * @dataProvider provideUserGenders
	 */
	public function testUserObjects( $userKey, $expectedGender ) {
		$username = self::$nameMap[$userKey] ?? $userKey;
		$user = User::newFromName( $username );

		$genderCache = $this->getServiceContainer()->getGenderCache();
		$gender = $genderCache->getGenderOf( $user );
		$this->assertEquals( $expectedGender, $gender );
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
	 * Strip subpages to avoid unnecessary queries against the never existing username
	 *
	 * @dataProvider provideUserGenders
	 */
	public function testStripSubpages( $userKey, $expectedGender ) {
		$username = self::$nameMap[$userKey] ?? $userKey;
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$gender = $genderCache->getGenderOf( "$username/subpage" );
		$this->assertEquals( $expectedGender, $gender, "GenderCache must strip of subpages" );
	}

	public function testDoLinkBatch() {
		$users = self::provideUserGenders();
		$batch = [];
		$expected = [];
		foreach ( $users as [ $id, $gender ] ) {
			$name = self::$nameMap[$id] ?? $id;
			$batch[NS_USER][$name] = true;
			$expected[$name] = $gender;
		}
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$genderCache->doLinkBatch( $batch );
		$this->assertSame(
			$expected,
			TestingAccessWrapper::newFromObject( $genderCache )->cache
		);
	}

	public function testDoTitlesArray() {
		$users = self::provideUserGenders();
		$batch = [];
		$expected = [];
		foreach ( $users as [ $id, $gender ] ) {
			$name = self::$nameMap[$id] ?? $id;
			$batch[] = new TitleValue( NS_USER, $name );
			$expected[$name] = $gender;
		}
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$genderCache->doTitlesArray( $batch );
		$this->assertSame(
			$expected,
			TestingAccessWrapper::newFromObject( $genderCache )->cache
		);
	}

	public function testDoPageRows() {
		$users = self::provideUserGenders();
		$batch = [];
		$expected = [];
		foreach ( $users as [ $id, $gender ] ) {
			$name = self::$nameMap[$id] ?? $id;
			$batch[] = (object)[ 'page_namespace' => NS_USER, 'page_title' => $name ];
			$expected[$name] = $gender;
		}
		$genderCache = $this->getServiceContainer()->getGenderCache();
		$genderCache->doPageRows( $batch );
		$this->assertSame(
			$expected,
			TestingAccessWrapper::newFromObject( $genderCache )->cache
		);
	}

	/**
	 * GenderCache must work without database (like Installer)
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
