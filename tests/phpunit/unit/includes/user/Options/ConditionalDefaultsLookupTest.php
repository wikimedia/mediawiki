<?php

namespace MediaWiki\Tests\User\Options;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Options\ConditionalDefaultsLookup;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\Options\ConditionalDefaultsLookup
 */
class ConditionalDefaultsLookupTest extends MediaWikiUnitTestCase {

	private const CONDITIONAL_USER_DEFAULTS = [
		[
			'new accounts',
			[ CUDCOND_AFTER, '20231215000000' ],
		],
		[
			'anonymous users',
			[ CUDCOND_ANON ],
		],
		[
			'named users',
			[ CUDCOND_NAMED ],
		],
		[
			'sysop users',
			[ CUDCOND_USERGROUP, 'sysop' ],
		],
	];
	private const CONDITIONAL_USER_DEFAULTS_AFTER = [
		[
			'new accounts',
			[ CUDCOND_AFTER, '20231215000000' ],
		]
	];
	private const CONDITIONAL_USER_DEFAULTS_ANON = [
		[
			'anonymous users',
			[ CUDCOND_ANON ],
		]
	];
	private const CONDITIONAL_USER_DEFAULTS_NAMED = [
		[
			'named users',
			[ CUDCOND_NAMED ],
		]
	];
	private const CONDITIONAL_USER_DEFAULTS_USERGROUP = [
		[
			'sysop users',
			[ CUDCOND_USERGROUP, 'sysop' ],
		]
	];

	/**
	 * Construct ServiceOptions using a HashConfig
	 *
	 * @param array $configOverrides
	 * @return ServiceOptions
	 */
	private function getServiceOptions( array $configOverrides = [] ) {
		return new ServiceOptions(
			ConditionalDefaultsLookup::CONSTRUCTOR_OPTIONS,
			new HashConfig( $configOverrides + [
				MainConfigNames::ConditionalUserOptions => [],
			] )
		);
	}

	/**
	 * @dataProvider provideIsConditionallyDefault
	 * @param bool $expected
	 * @param string $option
	 * @return void
	 */
	public function testIsConditionallyDefault( bool $expected, string $option ) {
		$lookup = new ConditionalDefaultsLookup(
			$this->createNoOpMock( HookRunner::class ),
			$this->getServiceOptions( [
				MainConfigNames::ConditionalUserOptions => [
					'foo-option' => self::CONDITIONAL_USER_DEFAULTS,
					'bar-option' => [
						[ 'all accounts' ]
					],
				]
			] ),
			$this->createNoOpMock( UserRegistrationLookup::class ),
			$this->createNoOpMock( UserIdentityUtils::class ),
			static function () {
			}
		);

		$this->assertSame(
			$expected,
			$lookup->hasConditionalDefault( $option )
		);
	}

	public static function provideIsConditionallyDefault() {
		return [
			'foo-option' => [ true, 'foo-option' ],
			'bar-option' => [ true, 'bar-option' ],
			'some-option' => [ false, 'some-option' ],
		];
	}

	public function testGetOptionDefaultForUser__notConditionallyDefault() {
		$lookup = new ConditionalDefaultsLookup(
			$this->createNoOpMock( HookRunner::class ),
			$this->getServiceOptions(),
			$this->createNoOpMock( UserRegistrationLookup::class ),
			$this->createNoOpMock( UserIdentityUtils::class ),
			static function () {
			}
		);

		$this->assertNull( $lookup->getOptionDefaultForUser(
			'foo-option',
			new UserIdentityValue( 1, 'Admin' )
		) );
	}

	/**
	 * @dataProvider provideGetOptionDefaultForUser__registration
	 * @param string|null $expected
	 * @param string|null $registrationTS
	 * @param array $conditions
	 */
	public function testGetOptionDefaultForUser__registration(
		?string $expected,
		?string $registrationTS,
		array $conditions
	) {
		$userIdentity = new UserIdentityValue( 1, 'User' );

		$registrationLookup = $this->createMock( UserRegistrationLookup::class );
		$registrationLookup->expects( $this->once() )
			->method( 'getRegistration' )
			->with( $userIdentity )
			->willReturn( $registrationTS );

		$lookup = new ConditionalDefaultsLookup(
			$this->createNoOpMock( HookRunner::class ),
			$this->getServiceOptions( [
				MainConfigNames::ConditionalUserOptions => [
					'foo-option' => $conditions,
				]
			] ),
			$registrationLookup,
			$this->createNoOpMock( UserIdentityUtils::class ),
			static function () {
			}
		);

		$this->assertSame(
			$expected,
			$lookup->getOptionDefaultForUser( 'foo-option', $userIdentity )
		);
	}

	public static function provideGetOptionDefaultForUser__registration(): array {
		return [
			[ null, '20231101000000', self::CONDITIONAL_USER_DEFAULTS_AFTER ],
			[ 'new accounts', '20241101000000', self::CONDITIONAL_USER_DEFAULTS_AFTER ],
			[ null, null, self::CONDITIONAL_USER_DEFAULTS_AFTER ],
		];
	}

	/**
	 * @dataProvider provideGetOptionDefaultForUser__anon
	 * @param int $id the user ID
	 * @param string|null $expected the default option or null if none apply
	 */
	public function testGetOptionDefaultForUser__anon( int $id, ?string $expected ) {
		$userIdentity = new UserIdentityValue( $id, 'test user' );

		$hookRunner = $this->createNoOpMock( HookRunner::class );
		$options = $this->getServiceOptions( [
			MainConfigNames::ConditionalUserOptions => [
				'test-option' => self::CONDITIONAL_USER_DEFAULTS_ANON,
			]
		] );
		$registrationLookup = $this->createNoOpMock( UserRegistrationLookup::class );
		$userIdentityUtils = $this->createNoOpMock( UserIdentityUtils::class );

		$lookup = new ConditionalDefaultsLookup( $hookRunner, $options, $registrationLookup, $userIdentityUtils,
			static function () {
			} );

		$this->assertSame( $expected, $lookup->getOptionDefaultForUser( 'test-option', $userIdentity ) );
	}

	public static function provideGetOptionDefaultForUser__anon(): array {
		return [
			[ 0, 'anonymous users' ],
			[ 1, null ],
		];
	}

	/**
	 * @dataProvider provideGetOptionDefaultForUser__named
	 * @param bool $isNamed whether the user is named or not (logged in and not temporary)
	 * @param string|null $expected the default option or null if none apply
	 */
	public function testGetOptionDefaultForUser__named( bool $isNamed, ?string $expected ) {
		$userIdentity = new UserIdentityValue( 1, 'test user' );

		$hookRunner = $this->createNoOpMock( HookRunner::class );
		$options = $this->getServiceOptions( [
			MainConfigNames::ConditionalUserOptions => [
				'test-option' => self::CONDITIONAL_USER_DEFAULTS_NAMED,
			]
		] );
		$registrationLookup = $this->createNoOpMock( UserRegistrationLookup::class );
		$userIdentityUtils = $this->createMock( UserIdentityUtils::class );
		$userIdentityUtils->expects( $this->once() )
			->method( 'isNamed' )
			->with( $userIdentity )
			->willReturn( $isNamed );

		$lookup = new ConditionalDefaultsLookup( $hookRunner, $options, $registrationLookup, $userIdentityUtils,
			static function () {
			} );

		$this->assertSame( $expected, $lookup->getOptionDefaultForUser( 'test-option', $userIdentity ) );
	}

	public static function provideGetOptionDefaultForUser__named(): array {
		return [
			[ true, 'named users' ],
			[ false, null ],
		];
	}

	/**
	 * @dataProvider provideGetOptionDefaultForUser__usergroup
	 * @param array[string] $usergroups the user groups the user has
	 * @param string|null $expected the default option or null if none apply
	 */
	public function testGetOptionDefaultForUser__usergroup( array $usergroups, ?string $expected ) {
		$userIdentity = new UserIdentityValue( 1, 'test user' );

		$hookRunner = $this->createNoOpMock( HookRunner::class );
		$options = $this->getServiceOptions( [
			MainConfigNames::ConditionalUserOptions => [
				'test-option' => self::CONDITIONAL_USER_DEFAULTS_USERGROUP,
			]
		] );
		$registrationLookup = $this->createNoOpMock( UserRegistrationLookup::class );
		$userIdentityUtils = $this->createMock( UserIdentityUtils::class );
		$userGroupManager = $this->createMock( UserGroupManager::class );
		$userGroupManager->expects( $this->once() )
			->method( 'getUserEffectiveGroups' )
			->with( $userIdentity )
			->willReturn( $usergroups );

		$lookup = new ConditionalDefaultsLookup( $hookRunner, $options, $registrationLookup, $userIdentityUtils,
			static function () use ( $userGroupManager ) {
				return $userGroupManager;
			} );

		$this->assertSame( $expected, $lookup->getOptionDefaultForUser( 'test-option', $userIdentity ) );
	}

	public static function provideGetOptionDefaultForUser__usergroup(): array {
		return [
			[ [ '*', 'user', 'sysop' ], 'sysop users' ],
			[ [ 'user' ], null ],
		];
	}

	/**
	 * @dataProvider provideGetOptionDefaultForUser__extraCondition
	 * @param array $configConditions An array of condition descriptors as described for $wgConditionalUserOptions
	 * @param array $extraConditions Key is the condition name and value is a callable
	 * @param string|null $expected the default option or null if none apply
	 */
	public function testGetOptionDefaultForUser__extraCondition(
		array $configConditions, array $extraConditions, ?string $expected
	) {
		$userIdentity = new UserIdentityValue( 1, 'test user' );

		$hookRunner = $this->createNoOpMock( HookRunner::class, [ 'onConditionalDefaultOptionsAddCondition' ] );
		$hookRunner->expects( $this->once() )
			->method( 'onConditionalDefaultOptionsAddCondition' )
			->willReturnCallback(
				static function ( array &$outExtraConditions ) use ( $extraConditions ) {
					$outExtraConditions = $extraConditions;
				}
			);
		$options = $this->getServiceOptions( [
			MainConfigNames::ConditionalUserOptions => [
				'test-option' => $configConditions
			]
		] );
		$registrationLookup = $this->createNoOpMock( UserRegistrationLookup::class );
		$userIdentityUtils = $this->createMock( UserIdentityUtils::class );

		$lookup = new ConditionalDefaultsLookup( $hookRunner, $options, $registrationLookup, $userIdentityUtils,
			static function () {
			}
		);

		$this->assertSame( $expected, $lookup->getOptionDefaultForUser( 'test-option', $userIdentity ) );
	}

	public static function provideGetOptionDefaultForUser__extraCondition(): array {
		return [
			[
				[
					[ 'No', [ 'if-condition', false ] ],
					[ 'Yes', [ 'if-condition', true ] ],
				],
				[
					'if-condition' => static function ( $userIdentity, $args ) {
						return (bool)$args[0];
					},
				],
				'Yes'
			],
			[
				[
					[ 'Yes', [ 'if-condition', true ] ],
					[ 'No', [ 'if-condition', false ] ],
				],
				[
					'if-condition' => static function ( $userIdentity, $args ) {
						return !(bool)$args[0];
					},
				],
				'No'
			]
		];
	}
}
