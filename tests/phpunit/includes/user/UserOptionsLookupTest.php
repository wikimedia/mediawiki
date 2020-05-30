<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\DefaultOptionsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserOptionsLookup;

/**
 * @covers MediaWiki\User\DefaultOptionsLookup
 * @covers MediaWiki\User\UserOptionsManager
 * @covers MediaWiki\User\UserOptionsLookup
 */
abstract class UserOptionsLookupTest extends MediaWikiIntegrationTestCase {
	use MediaWikiCoversValidator;

	protected function getAnon(
		string $name = 'anon'
	) : UserIdentity {
		return new UserIdentityValue( 0, $name, 0 );
	}

	abstract protected function getLookup(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	) : UserOptionsLookup;

	protected function getDefaultManager(
		string $langCode = 'qqq',
		array $defaultOptionsOverrides = []
	) : DefaultOptionsLookup {
		$lang = $this->createMock( Language::class );
		$lang->method( 'getCode' )->willReturn( $langCode );
		return new DefaultOptionsLookup(
			new ServiceOptions(
				DefaultOptionsLookup::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					'DefaultSkin' => 'test',
					'DefaultUserOptions' => array_merge( [
						'default_string_option' => 'string_value',
						'default_int_option' => 1,
						'default_bool_option' => true
					], $defaultOptionsOverrides ),
					'NamespacesToBeSearchedDefault' => [
						NS_MAIN => true,
						NS_TALK => true
					]
				] )
			),
			$lang,
			MediaWikiServices::getInstance()->getHookContainer()
		);
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsLookup::getDefaultOptions
	 * @covers MediaWiki\User\UserOptionsManager::getDefaultOptions
	 */
	public function testGetDefaultOptions() {
		$options = $this->getLookup()->getDefaultOptions();
		$this->assertSame( 'string_value', $options['default_string_option'] );
		$this->assertSame( 1, $options['default_int_option'] );
		$this->assertSame( true, $options['default_bool_option'] );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsLookup::getDefaultOption
	 * @covers MediaWiki\User\UserOptionsManager::getDefaultOption
	 */
	public function testGetDefaultOption() {
		$manager = $this->getLookup();
		$this->assertSame( 'string_value', $manager->getDefaultOption( 'default_string_option' ) );
		$this->assertSame( 1, $manager->getDefaultOption( 'default_int_option' ) );
		$this->assertSame( true, $manager->getDefaultOption( 'default_bool_option' ) );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsLookup::getOptions
	 * @covers MediaWiki\User\UserOptionsManager::getOptions
	 */
	public function testGetOptions() {
		$options = $this->getLookup()->getOptions( $this->getAnon() );
		$this->assertSame( 'string_value', $options['default_string_option'] );
		$this->assertSame( 1, $options['default_int_option'] );
		$this->assertSame( true, $options['default_bool_option'] );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsLookup::getOption
	 * @covers MediaWiki\User\UserOptionsManager::getOption
	 */
	public function testGetOptionDefault() {
		$manager = $this->getLookup();
		$this->assertSame( 'string_value',
			$manager->getOption( $this->getAnon(), 'default_string_option' ) );
		$this->assertSame( 1, $manager->getOption( $this->getAnon(), 'default_int_option' ) );
		$this->assertSame( true, $manager->getOption( $this->getAnon(), 'default_bool_option' ) );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsLookup::getOption
	 * @covers MediaWiki\User\UserOptionsManager::getOption
	 */
	public function testGetOptionDefaultNotExist() {
		$this->assertNull( $this->getLookup()
			->getOption( $this->getAnon(), 'this_option_does_not_exist' ) );
	}

	/**
	 * @covers MediaWiki\User\DefaultOptionsLookup::getOption
	 * @covers MediaWiki\User\UserOptionsManager::getOption
	 */
	public function testGetOptionDefaultNotExistDefaultOverride() {
		$this->assertSame( 'override', $this->getLookup()
			->getOption( $this->getAnon(), 'this_option_does_not_exist', 'override' ) );
	}

	/**
	 * @covers MediaWiki\User\UserOptionsLookup::getIntOption
	 */
	public function testGetIntOption() {
		$this->assertSame(
			2,
			$this->getLookup( 'qqq', [ 'default_int_option' => '2' ] )
				->getIntOption( $this->getAnon(), 'default_int_option' )
		);
	}

	/**
	 * @covers MediaWiki\User\UserOptionsLookup::getBoolOption
	 */
	public function testGetBoolOption() {
		$this->assertSame(
			true,
			$this->getLookup( 'qqq', [ 'default_bool_option' => 'true' ] )
				->getBoolOption( $this->getAnon(), 'default_bool_option' )
		);
	}
}
