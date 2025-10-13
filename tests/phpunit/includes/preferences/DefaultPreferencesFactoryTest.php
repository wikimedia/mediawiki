<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\SessionId;
use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\Tests\Session\TestUtils;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\User;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @group Preferences
 * @group Database
 * @covers \MediaWiki\Preferences\DefaultPreferencesFactory
 */
class DefaultPreferencesFactoryTest extends \MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use TestAllServiceOptionsUsed;

	/** @var IContextSource */
	protected $context;

	/** @var Config */
	protected $config;

	protected function setUp(): void {
		parent::setUp();
		$this->context = new RequestContext();
		$this->context->setTitle( Title::makeTitle( NS_MAIN, self::class ) );

		$this->overrideConfigValues( [
			MainConfigNames::DisableLangConversion => false,
			MainConfigNames::UsePigLatinVariant => false,
		] );
		$this->config = $this->getServiceContainer()->getMainConfig();
	}

	public function testConstruct() {
		// Make sure if the optional services are not provided, stuff still works, so that
		// the GlobalPreferences extension isn't broken
		$params = [
			$this->createMock( ServiceOptions::class ),
			$this->createMock( Language::class ),
			$this->createMock( AuthManager::class ),
			$this->createMock( LinkRenderer::class ),
			$this->createMock( NamespaceInfo::class ),
			$this->createMock( PermissionManager::class ),
			$this->createMock( ILanguageConverter::class ),
			$this->createMock( LanguageNameUtils::class ),
			$this->createMock( HookContainer::class ),
			$this->createMock( UserOptionsLookup::class ),
		];
		$preferencesFactory = new DefaultPreferencesFactory( ...$params );
		$this->assertInstanceOf(
			DefaultPreferencesFactory::class,
			$preferencesFactory,
			'Created with some services missing'
		);

		// Now, make sure that MediaWikiServices isn't used
		// Switch the UserOptionsLookup to a UserOptionsManager
		$params[9] = $this->createMock( UserOptionsManager::class );
		$params[] = $this->createMock( LanguageConverterFactory::class );
		$params[] = $this->createMock( ParserFactory::class );
		$params[] = $this->createMock( SkinFactory::class );
		$params[] = $this->createMock( UserGroupManager::class );
		$params[] = $this->createMock( SignatureValidatorFactory::class );
		$oldMwServices = MediaWikiServices::forceGlobalInstance(
			$this->createNoOpMock( MediaWikiServices::class )
		);
		// Wrap in a try-finally block to make sure the real MediaWikiServices is
		// always put back even if something goes wrong
		try {
			$preferencesFactory = new DefaultPreferencesFactory( ...$params );
			$this->assertInstanceOf(
				DefaultPreferencesFactory::class,
				$preferencesFactory,
				'Created with all services, MediaWikiServices not used'
			);
		} finally {
			// Put back the real MediaWikiServices
			MediaWikiServices::forceGlobalInstance( $oldMwServices );
		}
	}

	/**
	 * Get a basic PreferencesFactory for testing with.
	 * @param array $options Supported options are:
	 *    'language' - A Language object, falls back to content language
	 *    'userOptionsManager' - A UserOptionsManager service, falls back to using MediaWikiServices
	 *    'userGroupManager' - A UserGroupManager service, falls back to a mock where no users
	 *                         have any extra groups, just `*` and `user`
	 * @return DefaultPreferencesFactory
	 */
	protected function getPreferencesFactory( array $options = [] ) {
		$nsInfo = $this->getDummyNamespaceInfo();

		$services = $this->getServiceContainer();

		// The PermissionManager should not be used for anything, its only a parameter
		// until we figure out how to remove it without breaking the GlobalPreferences
		// extension (GlobalPreferencesFactory extends DefaultPreferencesFactory)
		$permissionManager = $this->createNoOpMock( PermissionManager::class );

		$language = $options['language'] ?? $services->getContentLanguage();
		$userOptionsManager = $options['userOptionsManager'] ?? $services->getUserOptionsManager();

		$userGroupManager = $options['userGroupManager'] ?? false;
		if ( !$userGroupManager ) {
			$userGroupManager = $this->createMock( UserGroupManager::class );
			$userGroupManager->method( 'getUserGroupMemberships' )->willReturn( [] );
			$userGroupManager->method( 'getUserEffectiveGroups' )->willReturnCallback(
				static function ( UserIdentity $user ) {
					return $user->isRegistered() ? [ '*', 'user' ] : [ '*' ];
				}
			);
		}

		return new DefaultPreferencesFactory(
			new LoggedServiceOptions( self::$serviceOptionsAccessLog,
				DefaultPreferencesFactory::CONSTRUCTOR_OPTIONS, $this->config ),
			$language,
			$services->getAuthManager(),
			$services->getLinkRenderer(),
			$nsInfo,
			$permissionManager,
			$services->getLanguageConverterFactory()->getLanguageConverter( $language ),
			$services->getLanguageNameUtils(),
			$services->getHookContainer(),
			$userOptionsManager,
			$services->getLanguageConverterFactory(),
			$services->getParserFactory(),
			$services->getSkinFactory(),
			$userGroupManager,
			$services->getSignatureValidatorFactory()
		);
	}

	public function testGetForm() {
		$this->setTemporaryHook( 'GetPreferences', HookContainer::NOOP );

		$testUser = $this->createMock( User::class );
		$prefFactory = $this->getPreferencesFactory();
		$form = $prefFactory->getForm( $testUser, $this->context );
		$this->assertInstanceOf( PreferencesFormOOUI::class, $form );
		$this->assertCount( 6, $form->getPreferenceSections() );
	}

	public function testSortSkinNames() {
		/** @var DefaultPreferencesFactory $factory */
		$factory = TestingAccessWrapper::newFromObject(
			$this->getPreferencesFactory()
		);
		$validSkinNames = [
			'minerva' => 'Minerva Neue',
			'monobook' => 'Monobook',
			'cologne-blue' => 'Cologne Blue',
			'vector' => 'Vector',
			'vector-2022' => 'Vector 2022',
			'timeless' => 'Timeless',
		];
		$currentSkin = 'monobook';
		$preferredSkins = [ 'vector-2022', 'invalid-skin', 'vector' ];

		uksort( $validSkinNames, static function ( $a, $b ) use ( $factory, $currentSkin, $preferredSkins ) {
			return $factory->sortSkinNames( $a, $b, $currentSkin, $preferredSkins );
		} );

		$this->assertArrayEquals( [
			'monobook' => 'Monobook',
			'vector-2022' => 'Vector 2022',
			'vector' => 'Vector',
			'cologne-blue' => 'Cologne Blue',
			'minerva' => 'Minerva Neue',
			'timeless' => 'Timeless',
		], $validSkinNames );
	}

	/**
	 * CSS classes for emailauthentication preference field when there's no email.
	 * @see https://phabricator.wikimedia.org/T36302
	 *
	 * @dataProvider emailAuthenticationProvider
	 */
	public function testEmailAuthentication( $user, $cssClass ) {
		$this->overrideConfigValue( MainConfigNames::EmailAuthentication, true );

		$prefs = $this->getPreferencesFactory()
			->getFormDescriptor( $user, $this->context );
		$this->assertArrayHasKey( 'cssclass', $prefs['emailauthentication'] );
		$this->assertEquals( $cssClass, $prefs['emailauthentication']['cssclass'] );
	}

	public function testShowRollbackConfIsHiddenForUsersWithoutRollbackRights() {
		$userMock = $this->createMock( User::class );
		$userMock->method( 'isAllowed' )->willReturnCallback(
			static function ( $permission ) {
				return $permission === 'editmyoptions';
			}
		);

		$userOptionsManagerMock = $this->createUserOptionsManagerMock( [ 'test' => 'yes' ], true );
		$userMock = $this->getUserMockWithSession( $userMock );
		$prefs = $this->getPreferencesFactory( [
			'userOptionsManager' => $userOptionsManagerMock,
		] )->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayNotHasKey( 'showrollbackconfirmation', $prefs );
	}

	public function testShowRollbackConfIsShownForUsersWithRollbackRights() {
		$userMock = $this->createMock( User::class );
		$userMock->method( 'isAllowed' )->willReturnCallback(
			static function ( $permission ) {
				return $permission === 'editmyoptions' || $permission === 'rollback';
			}
		);
		$userMock = $this->getUserMockWithSession( $userMock );

		$userOptionsManagerMock = $this->createUserOptionsManagerMock( [ 'test' => 'yes' ], true );
		$prefs = $this->getPreferencesFactory( [
			'userOptionsManager' => $userOptionsManagerMock,
		] )->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayHasKey( 'showrollbackconfirmation', $prefs );
		$this->assertEquals(
			'rendering/advancedrendering',
			$prefs['showrollbackconfirmation']['section']
		);
	}

	public static function emailAuthenticationProvider() {
		$userNoEmail = new User;
		$userEmailUnauthed = new User;
		$userEmailUnauthed->setEmail( 'noauth@example.org' );
		$userEmailAuthed = new User;
		$userEmailAuthed->setEmail( 'noauth@example.org' );
		$userEmailAuthed->setEmailAuthenticationTimestamp( wfTimestamp() );
		return [
			[ $userNoEmail, 'mw-email-none' ],
			[ $userEmailUnauthed, 'mw-email-not-authenticated' ],
			[ $userEmailAuthed, 'mw-email-authenticated' ],
		];
	}

	/**
	 * Test that PreferencesFormPreSave hook has correct data:
	 *  - user Object is passed
	 *  - oldUserOptions contains previous user options (before save)
	 *  - formData and User object have set up new properties
	 *
	 * @see https://phabricator.wikimedia.org/T169365
	 */
	public function testPreferencesFormPreSaveHookHasCorrectData() {
		$oldOptions = [
			'test' => 'abc',
			'option' => 'old'
		];
		$newOptions = [
			'test' => 'abc',
			'option' => 'new'
		];

		$this->overrideConfigValue( MainConfigNames::HiddenPrefs, [] );

		$form = $this->createMock( PreferencesFormOOUI::class );

		$userMock = $this->createMock( User::class );

		$userOptionsManagerMock = $this->createUserOptionsManagerMock( $oldOptions );
		$expectedOptions = $newOptions;
		$userOptionsManagerMock->expects( $this->exactly( count( $newOptions ) ) )
			->method( 'setOption' )
			->willReturnCallback( function ( $user, $oname, $val ) use ( $userMock, &$expectedOptions ) {
				$this->assertSame( $userMock, $user );
				$this->assertArrayHasKey( $oname, $expectedOptions );
				$this->assertSame( $expectedOptions[$oname], $val );
				unset( $expectedOptions[$oname] );
			} );
		$userMock->method( 'isAllowed' )->willReturnCallback(
			static function ( $permission ) {
				return $permission === 'editmyprivateinfo' || $permission === 'editmyoptions';
			}
		);
		$userMock->method( 'isAllowedAny' )->willReturnCallback(
			static function ( ...$permissions ) {
				foreach ( $permissions as $perm ) {
					if ( $perm === 'editmyprivateinfo' || $perm === 'editmyoptions' ) {
						return true;
					}
				}
				return false;
			}
		);

		$form->method( 'getModifiedUser' )
			->willReturn( $userMock );

		$form->method( 'getContext' )
			->willReturn( $this->context );

		$this->setTemporaryHook( 'PreferencesFormPreSave',
			function (
				$formData, $form, $user, &$result, $oldUserOptions
			) use (
				$newOptions, $oldOptions, $userMock
			) {
				$this->assertSame( $userMock, $user );
				foreach ( $newOptions as $option => $value ) {
					$this->assertSame( $value, $formData[ $option ] );
				}
				foreach ( $oldOptions as $option => $value ) {
					$this->assertSame( $value, $oldUserOptions[ $option ] );
				}
				$this->assertTrue( $result );
			}
		);

		/** @var DefaultPreferencesFactory $factory */
		$factory = TestingAccessWrapper::newFromObject(
			$this->getPreferencesFactory( [ 'userOptionsManager' => $userOptionsManagerMock ] )
		);
		$factory->saveFormData( $newOptions, $form, [] );
	}

	/**
	 * The rclimit preference should accept non-integer input and filter it to become an integer.
	 */
	public function testIntvalFilter() {
		// Test a string with leading zeros (i.e. not octal) and spaces.
		$this->context->getRequest()->setVal( 'wprclimit', ' 0012 ' );
		$user = new User;
		$prefFactory = $this->getPreferencesFactory();
		$form = $prefFactory->getForm( $user, $this->context );
		$form->show();
		$form->trySubmit();
		$userOptionsLookup = $this->getServiceContainer()->getUserOptionsLookup();
		$this->assertEquals( 12, $userOptionsLookup->getOption( $user, 'rclimit' ) );
	}

	public function testVariantsSupport() {
		$userMock = $this->createMock( User::class );
		$userMock->method( 'isAllowed' )->willReturn( true );
		$userMock = $this->getUserMockWithSession( $userMock );

		$language = $this->createMock( Language::class );
		$language->method( 'getCode' )
			->willReturn( 'sr' );

		$userOptionsManagerMock = $this->createUserOptionsManagerMock(
			[ 'LanguageCode' => 'sr', 'variant' => 'sr' ], true
		);

		$prefs = $this->getPreferencesFactory( [
			'language' => $language,
			'userOptionsManager' => $userOptionsManagerMock,
		] )->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayHasKey( 'default', $prefs['variant'] );
		$this->assertEquals( 'sr', $prefs['variant']['default'] );
	}

	public function testUserGroupMemberships() {
		$userMock = $this->createMock( User::class );
		$userMock->method( 'isAllowed' )->willReturn( true );
		$userMock->method( 'isAllowedAny' )->willReturn( true );
		$userMock->method( 'isRegistered' )->willReturn( true );
		$userMock = $this->getUserMockWithSession( $userMock );

		$language = $this->createMock( Language::class );
		$language->method( 'getCode' )
			->willReturn( 'en' );

		$userOptionsManagerMock = $this->createUserOptionsManagerMock( [], true );

		$prefs = $this->getPreferencesFactory( [
			'language' => $language,
			'userOptionsManager' => $userOptionsManagerMock,
		] )->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayHasKey( 'default', $prefs['usergroups'] );
		$this->assertEquals(
			UserGroupMembership::getLinkHTML( 'user', $this->context ),
			( $prefs['usergroups']['default'] )()
		);
	}

	/**
	 * @coversNothing
	 */
	public function testAllServiceOptionsUsed() {
		$this->assertAllServiceOptionsUsed( [
			// Only used when $wgEnotifWatchlist or $wgEnotifUserTalk is true
			'EnotifMinorEdits',
			// Only used when $wgEnotifWatchlist or $wgEnotifUserTalk is true
			'EnotifRevealEditorAddress',
			// Only used when 'fancysig' preference is enabled
			'SignatureValidation',
		] );
	}

	/**
	 * @param array $userOptions
	 * @param bool $defaultOptions
	 * @return UserOptionsManager&MockObject
	 */
	private function createUserOptionsManagerMock( array $userOptions, bool $defaultOptions = false ) {
		$services = $this->getServiceContainer();
		$defaults = $services->getMainConfig()->get( MainConfigNames::DefaultUserOptions );
		$defaults['language'] = $services->getContentLanguageCode()->toString();
		$defaults['skin'] = Skin::normalizeKey( $services->getMainConfig()->get( MainConfigNames::DefaultSkin ) );
		( new HookRunner( $services->getHookContainer() ) )->onUserGetDefaultOptions( $defaults );
		$userOptions += $defaults;

		$mock = $this->createMock( UserOptionsManager::class );
		$mock->method( 'getOptions' )->willReturn( $userOptions );
		$mock->method( 'getOption' )->willReturnCallback(
			static function ( $user, $option ) use ( $userOptions ) {
				return $userOptions[$option] ?? null;
			}
		);
		if ( $defaultOptions ) {
			$mock->method( 'getDefaultOptions' )->willReturn( $defaults );
		}
		return $mock;
	}

	private function getUserMockWithSession( MockObject $userMock ): MockObject {
		// We're mocking a stdClass because the Session class is final, and thus not mockable.
		$mock = $this->getMockBuilder( stdClass::class )
			->addMethods( [ 'getAllowedUserRights', 'deregisterSession', 'getSessionId' ] )
			->getMock();
		$mock->method( 'getSessionId' )->willReturn(
			new SessionId( str_repeat( 'X', 32 ) )
		);
		$session = TestUtils::getDummySession( $mock );
		$mockRequest = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'getSession' ] )
			->getMock();
		$mockRequest->method( 'getSession' )->willReturn( $session );
		$userMock->method( 'getRequest' )->willReturn( $mockRequest );
		$userMock->method( 'getTitleKey' )->willReturn( '' );
		return $userMock;
	}
}
