<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Options\DefaultOptionsLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\User\Options\DefaultOptionsLookup
 * @covers \MediaWiki\User\Options\UserOptionsManager
 * @covers \MediaWiki\User\Options\UserOptionsLookup
 */
abstract class UserOptionsLookupTestBase extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$registrationTimestampsById = [
			1 => null,
			2 => '20231220160000',
			3 => '20230101000000'
		];

		$registrationLookup = $this->createMock( UserRegistrationLookup::class );
		$registrationLookup->method( 'getRegistration' )
			->willReturnCallback(
				static fn ( UserIdentity $user ) => $registrationTimestampsById[$user->getId()] ?? false
			);
		$this->setService( 'UserRegistrationLookup', $registrationLookup );

		$this->overrideConfigValues( [
			MainConfigNames::ConditionalUserOptions => [
				'conditional_option' => [
					[
						true,
						[ CUDCOND_AFTER, '20230624000000' ]
					]
				]
			]
		] );
	}

	protected function getAnon(
		string $name = '127.0.0.1'
	): UserIdentity {
		return new UserIdentityValue( 0, $name );
	}

	abstract protected function getLookup(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	): UserOptionsLookup;

	protected function getDefaultManager(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	): DefaultOptionsLookup {
		$lang = new LanguageCode( $langCode );
		return new DefaultOptionsLookup(
			new ServiceOptions(
				DefaultOptionsLookup::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::DefaultSkin => 'test',
					MainConfigNames::DefaultUserOptions => array_merge( [
						'conditional_option' => false,
						'default_string_option' => 'string_value',
						'default_int_option' => 1,
						'default_bool_option' => true
					], $defaultOptionsOverrides ),
					MainConfigNames::NamespacesToBeSearchedDefault => [
						NS_MAIN => true,
						NS_TALK => true,
						NS_MEDIAWIKI => false,
					]
				] )
			),
			$lang,
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getNamespaceInfo(),
			$this->getServiceContainer()->get( '_ConditionalDefaultsLookup' ),
			$this->getServiceContainer()->getUserIdentityLookup(),
			$this->getServiceContainer()->get( 'UserNameUtils' )
		);
	}

	/**
	 * @return array[]
	 */
	public static function provideConditionalDefaults() {
		// NOTE: Definition of user_registration timestamp is in ::setUp(); search for
		// a IUserRegistrationProvider implementation.
		return [
			'user_registration null' => [ false, 'conditional_option', 1 ],
			'user_registration recent' => [ true, 'conditional_option', 2 ],
			'user_registration old' => [ false, 'conditional_option', 3 ],
		];
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getDefaultOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getDefaultOption
	 * @dataProvider provideConditionalDefaults
	 */
	public function testGetConditionalDefaults( bool $expected, string $property, int $userId ) {
		$this->assertSame(
			$expected,
			$this->getLookup()->getDefaultOption(
				$property,
				new UserIdentityValue( $userId, 'Admin' )
			)
		);
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getDefaultOptions
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getDefaultOptions
	 */
	public function testGetDefaultOptions() {
		$options = $this->getLookup()->getDefaultOptions();
		$this->assertSame( 'string_value', $options['default_string_option'] );
		$this->assertSame( 1, $options['default_int_option'] );
		$this->assertSame( true, $options['default_bool_option'] );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getDefaultOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getDefaultOption
	 */
	public function testGetDefaultOption() {
		$manager = $this->getLookup();
		$this->assertSame( 'string_value', $manager->getDefaultOption( 'default_string_option' ) );
		$this->assertSame( 1, $manager->getDefaultOption( 'default_int_option' ) );
		$this->assertSame( true, $manager->getDefaultOption( 'default_bool_option' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getOptions
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOptions
	 */
	public function testGetOptions() {
		$options = $this->getLookup()->getOptions( $this->getAnon() );
		$this->assertSame( 'string_value', $options['default_string_option'] );
		$this->assertSame( 1, $options['default_int_option'] );
		$this->assertSame( true, $options['default_bool_option'] );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOption
	 */
	public function testGetOptionDefault() {
		$manager = $this->getLookup();
		$this->assertSame( 'string_value',
			$manager->getOption( $this->getAnon(), 'default_string_option' ) );
		$this->assertSame( 1, $manager->getOption( $this->getAnon(), 'default_int_option' ) );
		$this->assertSame( true, $manager->getOption( $this->getAnon(), 'default_bool_option' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOption
	 */
	public function testGetOptionDefaultNotExist() {
		$this->assertNull( $this->getLookup()
			->getOption( $this->getAnon(), 'this_option_does_not_exist' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\DefaultOptionsLookup::getOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOption
	 */
	public function testGetOptionDefaultNotExistDefaultOverride() {
		$this->assertSame( 'override', $this->getLookup()
			->getOption( $this->getAnon(), 'this_option_does_not_exist', 'override' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsLookup::getIntOption
	 */
	public function testGetIntOption() {
		$this->assertSame(
			2,
			$this->getLookup( 'qqq', [ 'default_int_option' => '2' ] )
				->getIntOption( $this->getAnon(), 'default_int_option' )
		);
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsLookup::getBoolOption
	 */
	public function testGetBoolOption() {
		$this->assertSame(
			true,
			$this->getLookup( 'qqq', [ 'default_bool_option' => 'true' ] )
				->getBoolOption( $this->getAnon(), 'default_bool_option' )
		);
	}
}
