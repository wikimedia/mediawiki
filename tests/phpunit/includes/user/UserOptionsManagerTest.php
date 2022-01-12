<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @covers MediaWiki\User\UserOptionsManager
 */
class UserOptionsManagerTest extends UserOptionsLookupTest {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'user';
		$this->tablesUsed[] = 'user_properties';
	}

	/**
	 * @param array $overrides supported keys:
	 *  - 'language' - string language code
	 *  - 'defaults' - array default preferences
	 *  - 'lb' - ILoadBalancer
	 *  - 'hookContainer' - HookContainer
	 * @return UserOptionsManager
	 */
	private function getManager( array $overrides = [] ) {
		$services = $this->getServiceContainer();
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
			$overrides['hookContainer'] ?? $services->getHookContainer(),
			$services->getUserFactory()
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
			'language' => 'en',
			'variant' => 'en',
			'new_option' => 'new_value',
		];
		$this->assertSame( $expected, $manager->getOptions( $user, UserOptionsManager::EXCLUDE_DEFAULTS ) );
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
		$this->assertSame( 'user_value', $manager->getOption( $user, 'string_option' ) );
		$this->assertSame( 42, $manager->getIntOption( $user, 'int_option' ) );
		$this->assertSame( true, $manager->getBoolOption( $user, 'bool_option' ) );
		$manager = $this->getManager();
		$this->assertSame( 'user_value', $manager->getOption( $user, 'string_option' ) );
		$this->assertSame( 42, $manager->getIntOption( $user, 'int_option' ) );
		$this->assertSame( true, $manager->getBoolOption( $user, 'bool_option' ) );
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
		$mockDb = $this->createMock( DBConnRef::class );
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

	/**
	 * @covers \MediaWiki\User\UserOptionsManager::saveOptions
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
