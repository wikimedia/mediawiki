<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\DeleteQueryBuilder;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\InsertQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @covers \MediaWiki\User\Options\UserOptionsManager
 */
class UserOptionsManagerTest extends UserOptionsLookupTestBase {

	/**
	 * @param array $overrides supported keys:
	 *  - 'language' - string language code
	 *  - 'defaults' - array default preferences
	 *  - 'dbp' - IConnectionProvider
	 *  - 'hookContainer' - HookContainer
	 * @return UserOptionsManager
	 */
	private function getManager( array $overrides = [] ) {
		$services = $this->getServiceContainer();
		return new UserOptionsManager(
			new ServiceOptions(
				UserOptionsManager::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::HiddenPrefs => [ 'hidden_user_option' ],
					MainConfigNames::LocalTZoffset => 0,
				] )
			),
			$this->getDefaultManager(
				$overrides['language'] ?? 'qqq',
				$overrides['defaults'] ?? []
			),
			$services->getLanguageConverterFactory(),
			$overrides['dbp'] ?? $services->getConnectionProvider(),
			new NullLogger(),
			$overrides['hookContainer'] ?? $services->getHookContainer(),
			$services->getUserFactory(),
			$services->getUserNameUtils()
		);
	}

	protected function getLookup(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	): UserOptionsLookup {
		return $this->getManager( [
			'language' => $langCode,
			'defaults' => $defaultOptionsOverrides,
		] );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOption
	 */
	public function testGetOptionsExcludeDefaults() {
		$manager = $this->getManager( [ 'defaults' => [
			'null_vs_false' => null,
			'null_vs_string' => null,
			'false_vs_int' => false,
			'false_vs_string' => false,
			'int_vs_string' => 0,
			'true_vs_int' => true,
			'true_vs_string' => true,
		] ] );
		$user = $this->getAnon( __METHOD__ );
		$manager->setOption( $user, 'null_vs_false', false );
		$manager->setOption( $user, 'null_vs_string', '' );
		$manager->setOption( $user, 'false_vs_int', 0 );
		$manager->setOption( $user, 'false_vs_string', '0' );
		$manager->setOption( $user, 'int_vs_string', '0' );
		$manager->setOption( $user, 'true_vs_int', 1 );
		$manager->setOption( $user, 'true_vs_string', '1' );
		$manager->setOption( $user, 'new_option', 'new_value' );
		$expected = [
			// Note that the old, relaxed array_diff-approach considered null equal to false and ""
			'null_vs_false' => false,
			'null_vs_string' => '',
			'language' => 'en',
			'variant' => 'en',
			'new_option' => 'new_value',
		];
		$this->assertSame( $expected, $manager->getOptions( $user, UserOptionsManager::EXCLUDE_DEFAULTS ) );
	}

	/**
	 * @param bool $expected
	 * @param string $property
	 * @param int $userId
	 * @dataProvider provideConditionalDefaults
	 */
	public function testGetConditionalOption( bool $expected, string $property, int $userId ) {
		$this->assertSame(
			$expected,
			$this->getLookup()->getOption(
				new UserIdentityValue( $userId, 'Admin' ),
				$property
			)
		);
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOption
	 */
	public function testGetOptionHiddenPref() {
		$user = $this->getAnon( __METHOD__ );
		$manager = $this->getManager();
		$manager->setOption( $user, 'hidden_user_option', 'hidden_value' );
		$this->assertNull( $manager->getOption( $user, 'hidden_user_option' ) );
		$this->assertSame( 'hidden_value',
			$manager->getOption( $user, 'hidden_user_option', null, true ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::setOption
	 */
	public function testSetOptionNullIsDefault() {
		$user = $this->getAnon( __METHOD__ );
		$manager = $this->getManager();
		$manager->setOption( $user, 'default_string_option', 'override_value' );
		$this->assertSame( 'override_value', $manager->getOption( $user, 'default_string_option' ) );
		$manager->setOption( $user, 'default_string_option', null );
		$this->assertSame( 'string_value', $manager->getOption( $user, 'default_string_option' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::getOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::setOption
	 * @covers \MediaWiki\User\Options\UserOptionsManager::saveOptions
	 */
	public function testGetSetSave() {
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$this->assertSame( [], $manager->getOptions( $user, UserOptionsManager::EXCLUDE_DEFAULTS ) );
		$manager->setOption( $user, 'string_option', 'user_value' );
		$manager->setOption( $user, 'int_option', 42 );
		$manager->setOption( $user, 'bool_option', true );
		$this->assertSame( 'user_value', $manager->getOption( $user, 'string_option' ) );
		$this->assertSame( 42, $manager->getIntOption( $user, 'int_option' ) );
		$this->assertSame( true, $manager->getBoolOption( $user, 'bool_option' ) );
		$manager->saveOptions( $user );
		$this->assertSame( 'user_value', $manager->getOption( $user, 'string_option' ) );
		$this->assertSame( 42, $manager->getIntOption( $user, 'int_option' ) );
		$this->assertSame( true, $manager->getBoolOption( $user, 'bool_option' ) );
		$manager = $this->getManager();
		$this->assertSame( 'user_value', $manager->getOption( $user, 'string_option' ) );
		$this->assertSame( 42, $manager->getIntOption( $user, 'int_option' ) );
		$this->assertSame( true, $manager->getBoolOption( $user, 'bool_option' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::loadUserOptions
	 */
	public function testLoadUserOptionsHook() {
		$user = UserIdentityValue::newRegistered( 42, 'Test' );
		$manager = $this->getManager( [
			'hookContainer' => $this->createHookContainer( [
				'LoadUserOptions' => function ( UserIdentity $hookUser, array &$options ) use ( $user ) {
					$this->assertTrue( $hookUser->equals( $user ) );
					$options['from_hook'] = 'value_from_hook';
				}
			] )
		] );
		$this->assertSame( 'value_from_hook', $manager->getOption( $user, 'from_hook' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::saveOptions
	 */
	public function testSaveUserOptionsHookAbort() {
		$manager = $this->getManager( [
			'hookContainer' => $this->createHookContainer( [
				'SaveUserOptions' => static function () {
					return false;
				}
			] )
		] );
		$user = UserIdentityValue::newRegistered( 42, 'Test' );
		$manager->setOption( $user, 'will_be_aborted_by_hook', 'value' );
		$manager->saveOptions( $user );
		$this->assertNull( $this->getManager()->getOption( $user, 'will_be_aborted_by_hook' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::saveOptions
	 */
	public function testSaveUserOptionsHookModify() {
		$user = UserIdentityValue::newRegistered( 42, 'Test' );
		$manager = $this->getManager( [
			'defaults' => [
				'reset_to_default_by_hook' => 'default',
			],
			'hookContainer' => $this->createHookContainer( [
				'SaveUserOptions' => function ( UserIdentity $hookUser, array &$modifiedOptions ) use ( $user ) {
					$this->assertTrue( $user->equals( $hookUser ) );
					$modifiedOptions['reset_to_default_by_hook'] = null;
					unset( $modifiedOptions['blocked_by_hook'] );
					$modifiedOptions['new_from_hook'] = 'value_from_hook';
				}
			] ),
		] );
		$manager->setOption( $user, 'reset_to_default_by_hook', 'not default' );
		$manager->setOption( $user, 'blocked_by_hook', 'blocked value' );
		$manager->saveOptions( $user );
		$this->assertSame( 'value_from_hook', $manager->getOption( $user, 'new_from_hook' ) );
		$this->assertSame( 'default', $manager->getOption( $user, 'reset_to_default_by_hook' ) );
		$this->assertNull( $manager->getOption( $user, 'blocked_by_hook' ) );
		$manager->clearUserOptionsCache( $user );
		$this->assertSame( 'value_from_hook', $manager->getOption( $user, 'new_from_hook' ) );
		$this->assertSame( 'default', $manager->getOption( $user, 'reset_to_default_by_hook' ) );
		$this->assertNull( $manager->getOption( $user, 'blocked_by_hook' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::saveOptions
	 */
	public function testSaveUserOptionsHookOriginal() {
		$user = UserIdentityValue::newRegistered( 42, 'Test' );
		$manager = $this->getManager( [
			'language' => 'ja',
			'hookContainer' => $this->createHookContainer( [
				'SaveUserOptions' => function (
					UserIdentity $hookUser,
					array &$modifiedOptions,
					array $originalOptions
				) use ( $user ) {
					if ( $hookUser->equals( $user ) ) {
						$this->assertSame( 'ja', $originalOptions['language'] );
						$this->assertSame( 'ru', $modifiedOptions['language'] );
						$modifiedOptions['language'] = 'tr';
					}
					return true;
				}
			] ),
		] );
		$manager->setOption( $user, 'language', 'ru' );
		$manager->saveOptions( $user );
		$this->assertSame( 'tr', $manager->getOption( $user, 'language' ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::loadUserOptions
	 */
	public function testInfiniteRecursionOnLoadUserOptionsHook() {
		$user = UserIdentityValue::newRegistered( 42, 'Test' );
		$manager = $this->getManager( [
			'hookContainer' => $this->createHookContainer( [
				'LoadUserOptions' => function ( UserIdentity $hookUser ) use ( $user, &$manager, &$recursionCounter ) {
					if ( $hookUser->equals( $user ) ) {
						$recursionCounter += 1;
						$this->assertSame( 1, $recursionCounter );
						$manager->loadUserOptions( $hookUser );
					}
				}

			] )
		] );
		$recursionCounter = 0;
		$manager->loadUserOptions( $user, IDBAccessObject::READ_LATEST );
		$this->assertSame( 1, $recursionCounter );
	}

	public function testSaveOptionsForAnonUser() {
		$this->expectException( InvalidArgumentException::class );
		$this->getManager()->saveOptions( $this->getAnon( __METHOD__ ) );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::resetOptionsByName
	 */
	public function testUserOptionsSaveAfterReset() {
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$manager->setOption( $user, 'test_option', 'test_value' );
		$manager->saveOptions( $user );
		$manager->clearUserOptionsCache( $user );
		$this->assertSame( 'test_value', $manager->getOption( $user, 'test_option' ) );
		$optionNames = array_keys( $manager->getOptions( $user ) );
		$manager->resetOptionsByName( $user, $optionNames );
		$this->assertNull( $manager->getOption( $user, 'test_option' ) );
		$manager->saveOptions( $user );
		$manager->clearUserOptionsCache( $user );
		$this->assertNull( $manager->getOption( $user, 'test_option' ) );
	}

	public function testOptionsForUpdateNotRefetchedBeforeInsert() {
		$mockDb = $this->createMock( IDatabase::class );
		$mockDb->expects( $this->once() ) // This is critical what we are testing
			->method( 'select' )
			->willReturn( new FakeResultWrapper( [
				[
					'up_value' => 'blabla',
					'up_property' => 'test_option',
				]
			] ) );
		$mockDb->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $mockDb ) );
		$mockDb->method( 'newInsertQueryBuilder' )->willReturnCallback( static fn () => new InsertQueryBuilder( $mockDb ) );
		$mockDbProvider = $this->createMock( IConnectionProvider::class );
		$mockDbProvider
			->method( 'getPrimaryDatabase' )
			->willReturn( $mockDb );
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager( [
			'dbp' => $mockDbProvider,
		] );
		$manager->getOption(
			$user,
			'test_option',
			null,
			false,
			IDBAccessObject::READ_LOCKING
		);
		$manager->getOption( $user, 'test_option2' );
		$manager->setOption( $user, 'test_option', 'test_value' );
		$manager->setOption( $user, 'test_option2', 'test_value2' );
		$manager->saveOptions( $user );
	}

	public function testOptionsNoDeleteSetDefaultValue() {
		$mockDb = $this->createMock( IDatabase::class );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturn( new FakeResultWrapper( [
				[
					'up_value' => 'unchanged',
					'up_property' => 'unchanged_option',
				]
			] ) );
		$mockDb->expects( $this->never() ) // This is critical what we are testing
			->method( 'delete' );
		$mockDb->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $mockDb ) );
		$mockDbProvider = $this->createMock( IConnectionProvider::class );
		$mockDbProvider
			->method( 'getPrimaryDatabase' )
			->willReturn( $mockDb );
		$mockDbProvider
			->method( 'getReplicaDatabase' )
			->willReturn( $mockDb );
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager( [
			'dbp' => $mockDbProvider,
			'defaults' => [
				'set_default' => 'default',
				'set_default_null' => null,
			]
		] );
		// Resetting an option with default value to the default value does not trigger delete
		$manager->setOption( $user, 'set_default', 'default' );
		$manager->setOption( $user, 'set_default_null', null );
		$manager->saveOptions( $user );
	}

	public function testOptionsDeleteSetDefaultValue() {
		$user = $this->getTestUser()->getUser();
		$mockDb = $this->createMock( IDatabase::class );
		$mockDb
			->method( 'newDeleteQueryBuilder' )
			->willReturnCallback( static fn () => new DeleteQueryBuilder( $mockDb ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturn( new FakeResultWrapper( [
				[
					'up_property' => 'unchanged',
					'up_value' => 'unchanged_option',
				],
				[
					'up_property' => 'set_default',
					'up_value' => 'non_default',
				],
				[
					'up_property' => 'set_default_null',
					'up_value' => 'not_null',
				],
				[
					'up_property' => 'set_default_not_null',
					'up_value' => null,
				]
			] ) );
		$mockDb->expects( $this->once() ) // This is critical what we are testing
			->method( 'delete' )
			->with(
				'user_properties',
				[
					'up_user' => $user->getId(),
					'up_property' => [ 'set_default', 'set_default_null', 'set_default_not_null' ]
				]
			);
		$mockDb->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $mockDb ) );
		$mockDbProvider = $this->createMock( IConnectionProvider::class );
		$mockDbProvider
			->method( 'getPrimaryDatabase' )
			->willReturn( $mockDb );
		$mockDbProvider
			->method( 'getReplicaDatabase' )
			->willReturn( $mockDb );
		$manager = $this->getManager( [
			'dbp' => $mockDbProvider,
			'defaults' => [
				'set_default' => 'default',
				'set_default_null' => null,
				'set_default_not_null' => 'not_null',
			]
		] );
		// Set every of the options to its default value must trigger a delete for each option
		$manager->setOption( $user, 'set_default', 'default' );
		$manager->setOption( $user, 'set_default_null', null );
		$manager->setOption( $user, 'set_default_not_null', 'not_null' );
		$manager->saveOptions( $user );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::saveOptionsInternal
	 */
	public function testOptionsInsertFromDefaultValue() {
		$user = $this->getTestUser()->getUser();
		$mockDb = $this->createMock( IDatabase::class );
		$mockDb
			->method( 'newSelectQueryBuilder' )
			->willReturnCallback( static fn () => new SelectQueryBuilder( $mockDb ) );
		$mockDb
			->method( 'newInsertQueryBuilder' )
			->willReturnCallback( static fn () => new InsertQueryBuilder( $mockDb ) );
		$mockDb
			->method( 'select' )
			->willReturn( new FakeResultWrapper( [] ) );

		// This is critical what we are testing
		$mockDb->expects( $this->once() )
			->method( 'insert' )
			->with(
				'user_properties',
				[
					[
						'up_user' => $user->getId(),
						'up_property' => 'set_empty',
						'up_value' => '',
					]
				]
			);

		$mockDbProvider = $this->createMock( IConnectionProvider::class );
		$mockDbProvider
			->method( 'getPrimaryDatabase' )
			->willReturn( $mockDb );
		$mockDbProvider
			->method( 'getReplicaDatabase' )
			->willReturn( $mockDb );
		$manager = $this->getManager( [
			'dbp' => $mockDbProvider,
			'defaults' => [
				'set_empty' => 123,
			]
		] );

		$manager->setOption( $user, 'set_empty', '' );
		$this->assertSame( '', $manager->getOption( $user, 'set_empty' ) );
		$manager->saveOptions( $user );
	}

	/**
	 * @covers \MediaWiki\User\Options\UserOptionsManager::saveOptions
	 */
	public function testUpdatesUserTouched() {
		$user = $this->getTestUser()->getUser();
		$userTouched = $user->getDBTouched();
		$newTouched = ConvertibleTimestamp::convert(
			TS_MW,
			intval( ConvertibleTimestamp::convert( TS_UNIX, $userTouched ) ) + 100
		);
		ConvertibleTimestamp::setFakeTime( $newTouched );

		$manager = $this->getManager();
		$manager->setOption( $user, 'test_option', 'test_value' );
		$manager->saveOptions( $user );
		$this->assertSame( $newTouched, $user->getDBTouched() );
		$user->clearInstanceCache();
		$this->assertSame( $newTouched, $user->getDBTouched() );
	}
}
