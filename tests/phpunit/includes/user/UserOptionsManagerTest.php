<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @group Database
 * @covers MediaWiki\User\UserOptionsManager
 */
class UserOptionsManagerTest extends UserOptionsLookupTest {

	/**
	 * @param array $overrides supported keys:
	 *  - 'language' - string language code
	 *  - 'defaults' - array default preferences
	 *  - 'lb' - ILoadBalancer
	 *  - 'hookContainer' - HookContainer
	 * @return UserOptionsManager
	 */
	private function getManager( array $overrides = [] ) {
		$services = MediaWikiServices::getInstance();
		return new UserOptionsManager(
			new ServiceOptions(
				UserOptionsManager::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					'HiddenPrefs' => [ 'hidden_user_option' ],
					'LocalTZoffset' => 0,
				] )
			),
			$this->getDefaultManager(
				$overrides['language'] ?? 'qqq',
				$overrides['defaults'] ?? []
			),
			$services->getLanguageConverterFactory(),
			$overrides['lb'] ?? $services->getDBLoadBalancer(),
			new NullLogger(),
			$overrides['hookContainer'] ?? $services->getHookContainer()
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
	 * @covers MediaWiki\User\UserOptionsManager::getOption
	 */
	public function testGetOptionsExcludeDefaults() {
		$manager = $this->getManager();
		$manager->setOption( $this->getAnon( __METHOD__ ), 'new_option', 'new_value' );
		$this->assertSame( [
			'language' => 'en',
			'variant' => 'en',
			'new_option' => 'new_value'
		], $manager->getOptions( $this->getAnon( __METHOD__ ), UserOptionsManager::EXCLUDE_DEFAULTS ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::getOption
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
	 * @covers MediaWiki\User\UserOptionsManager::setOption
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
	 * @covers MediaWiki\User\UserOptionsManager::getOption
	 * @covers MediaWiki\User\UserOptionsManager::setOption
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
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
		$manager = $this->getManager();
		$this->assertSame( 'user_value', $manager->getOption( $user, 'string_option' ) );
		$this->assertSame( 42, $manager->getIntOption( $user, 'int_option' ) );
		$this->assertSame( true, $manager->getBoolOption( $user, 'bool_option' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::loadUserOptions
	 */
	public function testUserLoadOptionsHook() {
		$this->filterDeprecated( '/UserLoadOptions/' );
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserLoadOptions',
			static function ( User $hookUser, &$options ) use ( $user ) {
				if ( $hookUser->equals( $user ) ) {
					$options['from_hook'] = 'value_from_hook';
				}
			}
		);
		$this->assertSame( 'value_from_hook', $this->getManager()->getOption( $user, 'from_hook' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::loadUserOptions
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
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
	 */
	public function testUserSaveOptionsHookAbort() {
		$this->filterDeprecated( '/UserSaveOptions/' );
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserSaveOptions',
			static function () {
				return false;
			}
		);
		$manager = $this->getManager();
		$manager->setOption( $user, 'will_be_aborted_by_hook', 'value' );
		$manager->saveOptions( $user );
		$this->assertNull( $this->getManager()->getOption( $user, 'will_be_aborted_by_hook' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
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
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
	 */
	public function testUserSaveOptionsHookModify() {
		$this->filterDeprecated( '/UserSaveOptions/' );
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserSaveOptions',
			static function ( User $hookUser, &$options ) use ( $user ) {
				if ( $hookUser->equals( $user ) ) {
					$options['from_hook'] = 'value_from_hook';
				}
				return true;
			}
		);
		$manager = $this->getManager();
		$manager->saveOptions( $user );
		$this->assertSame( 'value_from_hook', $manager->getOption( $user, 'from_hook' ) );
		$this->assertSame( 'value_from_hook', $this->getManager()->getOption( $user, 'from_hook' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
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
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
	 */
	public function testUserSaveOptionsHookOriginal() {
		$this->filterDeprecated( '/UserSaveOptions/' );
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$originalLanguage = $manager->getOption( $user, 'language' );
		$manager->setOption( $user, 'language', 'ru' );
		$this->setTemporaryHook(
			'UserSaveOptions',
			function ( User $hookUser, &$options, $originalOptions ) use ( $user, $originalLanguage ) {
				if ( $hookUser->equals( $user ) ) {
					$this->assertSame( $originalLanguage, $originalOptions['language'] );
					$this->assertSame( 'ru', $options['language'] );
					$options['language'] = 'tr';
				}
				return true;
			}
		);
		$manager->saveOptions( $user );
		$this->assertSame( 'tr', $manager->getOption( $user, 'language' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
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
	 * @covers \MediaWiki\User\UserOptionsManager::saveOptions
	 * @covers \MediaWiki\User\UserOptionsManager::loadUserOptions
	 */
	public function testLoadOptionsHookReflectsInOriginalOptions() {
		$this->filterDeprecated( '/UserSaveOptions/' );
		$this->filterDeprecated( '/UserLoadOptions/' );
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$this->setTemporaryHook(
			'UserLoadOptions',
			static function ( User $hookUser, &$options ) use ( $user ) {
				if ( $hookUser->equals( $user ) ) {
					$options['from_load_hook'] = 'from_load_hook';
				}
			}
		);
		$this->setTemporaryHook(
			'UserSaveOptions',
			function ( User $hookUser, &$options, $originalOptions ) use ( $user ) {
				if ( $hookUser->equals( $user ) ) {
					$this->assertSame( 'from_load_hook', $options['from_load_hook'] );
					$this->assertSame( 'from_load_hook', $originalOptions['from_load_hook'] );
					$options['from_save_hook'] = 'from_save_hook';
				}
				return true;
			}
		);
		$manager->saveOptions( $user );
		$this->assertSame( 'from_load_hook', $manager->getOption( $user, 'from_load_hook' ) );
		$this->assertSame( 'from_save_hook', $manager->getOption( $user, 'from_save_hook' ) );
	}

	/**
	 * @covers \MediaWiki\User\UserOptionsManager::loadUserOptions
	 */
	public function testInfiniteRecursionOnUserLoadOptionsHook() {
		$this->filterDeprecated( '/UserLoadOptions/' );
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$recursionCounter = 0;
		$this->setTemporaryHook(
			'UserLoadOptions',
			function ( User $hookUser ) use ( $user, $manager, &$recursionCounter ) {
				if ( $hookUser->equals( $user ) ) {
					$recursionCounter += 1;
					$this->assertSame( 1, $recursionCounter );
					$manager->loadUserOptions( $hookUser );
				}
			}
		);
		$manager->loadUserOptions( $user, UserOptionsManager::READ_LATEST );
		$this->assertSame( 1, $recursionCounter );
	}

	/**
	 * @covers \MediaWiki\User\UserOptionsManager::loadUserOptions
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
		$manager->loadUserOptions( $user, UserOptionsManager::READ_LATEST );
		$this->assertSame( 1, $recursionCounter );
	}

	public function testSaveOptionsForAnonUser() {
		$this->expectException( InvalidArgumentException::class );
		$this->getManager()->saveOptions( $this->getAnon( __METHOD__ ) );
	}

	/**
	 * @covers \MediaWiki\User\UserOptionsManager::resetOptions
	 */
	public function testUserOptionsSaveAfterReset() {
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$manager->setOption( $user, 'test_option', 'test_value' );
		$manager->saveOptions( $user );
		$manager->clearUserOptionsCache( $user );
		$this->assertSame( 'test_value', $manager->getOption( $user, 'test_option' ) );
		$manager->resetOptions( $user, RequestContext::getMain(), 'all' );
		$this->assertNull( $manager->getOption( $user, 'test_option' ) );
		$manager->saveOptions( $user );
		$manager->clearUserOptionsCache( $user );
		$this->assertNull( $manager->getOption( $user, 'test_option' ) );
	}

	public function testOptionsForUpdateNotRefetchedBeforeInsert() {
		$mockDb = $this->createMock( \Wikimedia\Rdbms\IDatabase::class );
		$mockDb->expects( $this->once() ) // This is critical what we are testing
			->method( 'select' )
			->willReturn( new FakeResultWrapper( [
				[
					'up_value' => 'blabla',
					'up_property' => 'test_option',
				]
			] ) );
		$mockLoadBalancer = $this->createMock( ILoadBalancer::class );
		$mockLoadBalancer
			->method( 'getConnectionRef' )
			->willReturn( $mockDb );
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager( [
			'lb' => $mockLoadBalancer,
		] );
		$manager->getOption(
			$user,
			'test_option',
			null,
			false,
			UserOptionsManager::READ_LOCKING
		);
		$manager->getOption( $user, 'test_option2' );
		$manager->setOption( $user, 'test_option', 'test_value' );
		$manager->setOption( $user, 'test_option2', 'test_value2' );
		$manager->saveOptions( $user );
	}
}
