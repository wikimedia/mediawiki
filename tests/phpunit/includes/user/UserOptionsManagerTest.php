<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use Psr\Log\NullLogger;

/**
 * @group Database
 * @covers MediaWiki\User\UserOptionsManager
 */
class UserOptionsManagerTest extends UserOptionsLookupTest {

	private function getManager(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	) {
		$services = MediaWikiServices::getInstance();
		return new UserOptionsManager(
			new ServiceOptions(
				UserOptionsManager::CONSTRUCTOR_OPTIONS,
				new HashConfig( [ 'HiddenPrefs' => [ 'hidden_user_option' ] ] )
			),
			$this->getDefaultManager( $langCode, $defaultOptionsOverrides ),
			$services->getLanguageConverterFactory(),
			$services->getDBLoadBalancer(),
			new NullLogger()
		);
	}

	protected function getLookup(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	) : UserOptionsLookup {
		return $this->getManager( $langCode, $defaultOptionsOverrides );
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
	public function testLoadUserOptionsHook() {
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserLoadOptions',
			function ( User $hookUser, &$options ) use ( $user ) {
				if ( $hookUser->equals( $user ) ) {
					$options['from_hook'] = 'value_from_hook';
				}
			}
		);
		$this->assertSame( 'value_from_hook', $this->getManager()->getOption( $user, 'from_hook' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsManager::saveOptions
	 */
	public function testSaveUserOptionsHookAbort() {
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserSaveOptions',
			function () {
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
	public function testSaveUserOptionsHookModify() {
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserLoadOptions',
			function ( User $hookUser, &$options ) use ( $user ) {
				if ( $hookUser->equals( $user ) ) {
					$options['from_hook'] = 'value_from_hook';
				}
			}
		);
		$manager = $this->getManager();
		$manager->saveOptions( $user );
		$this->assertSame( 'value_from_hook', $manager->getOption( $user, 'from_hook' ) );
		$this->assertSame( 'value_from_hook', $this->getManager()->getOption( $user, 'from_hook' ) );
	}
}
