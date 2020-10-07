<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * @group Preferences
 * @coversDefaultClass MediaWiki\Preferences\DefaultPreferencesFactory
 */
class DefaultPreferencesFactoryTest extends \MediaWikiIntegrationTestCase {
	use TestAllServiceOptionsUsed;

	/** @var IContextSource */
	protected $context;

	/** @var Config */
	protected $config;

	protected function setUp() : void {
		parent::setUp();
		$this->context = new RequestContext();
		$this->context->setTitle( Title::newFromText( self::class ) );

		$services = MediaWikiServices::getInstance();

		$this->setMwGlobals( 'wgParser', $services->getParserFactory()->create() );
		$this->setMwGlobals( 'wgDisableLangConversion', false );
		$this->config = $services->getMainConfig();
	}

	/**
	 * Get a basic PreferencesFactory for testing with.
	 * @param PermissionManager $mockPM
	 * @param Language $language
	 * @return DefaultPreferencesFactory
	 */
	protected function getPreferencesFactory( PermissionManager $mockPM, Language $language ) {
		$mockNsInfo = $this->createMock( NamespaceInfo::class );
		$mockNsInfo->method( 'getValidNamespaces' )->willReturn( [
			NS_MAIN, NS_TALK, NS_USER, NS_USER_TALK
		] );
		$mockNsInfo->expects( $this->never() )
			->method( $this->anythingBut( 'getValidNamespaces', '__destruct' ) );

		$services = MediaWikiServices::getInstance();

		return new DefaultPreferencesFactory(
			new LoggedServiceOptions( self::$serviceOptionsAccessLog,
				DefaultPreferencesFactory::CONSTRUCTOR_OPTIONS, $this->config ),
			$language,
			$services->getAuthManager(),
			$services->getLinkRenderer(),
			$mockNsInfo,
			$mockPM,
			$services->getLanguageConverterFactory()->getLanguageConverter( $language ),
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);
	}

	/**
	 * @covers ::getForm
	 * @covers ::searchPreferences
	 */
	public function testGetForm() {
		$this->setTemporaryHook( 'GetPreferences', null );

		$testUser = $this->getTestUser();
		$pm = $this->createMock( PermissionManager::class );
		$pm->method( 'userHasRight' )->willReturn( true );
		$prefFactory = $this->getPreferencesFactory( $pm, new Language() );
		$form = $prefFactory->getForm( $testUser->getUser(), $this->context );
		$this->assertInstanceOf( PreferencesFormOOUI::class, $form );
		$this->assertCount( 5, $form->getPreferenceSections() );
	}

	/**
	 * CSS classes for emailauthentication preference field when there's no email.
	 * @see https://phabricator.wikimedia.org/T36302
	 *
	 * @covers ::profilePreferences
	 * @dataProvider emailAuthenticationProvider
	 */
	public function testEmailAuthentication( $user, $cssClass ) {
		$pm = $this->createMock( PermissionManager::class );
		$pm->method( 'userHasRight' )->willReturn( true );
		$prefs = $this->getPreferencesFactory( $pm, new Language() )
			->getFormDescriptor( $user, $this->context );
		$this->assertArrayHasKey( 'cssclass', $prefs['emailauthentication'] );
		$this->assertEquals( $cssClass, $prefs['emailauthentication']['cssclass'] );
	}

	/**
	 * @covers ::renderingPreferences
	 */
	public function testShowRollbackConfIsHiddenForUsersWithoutRollbackRights() {
		$userMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
		$userMock->method( 'getEffectiveGroups' )
			->willReturn( [] );
		$userMock->method( 'getGroupMemberships' )
			->willReturn( [] );
		$userMock->method( 'getOptions' )
			->willReturn( [ 'test' => 'yes' ] );
		$pm = $this->createMock( PermissionManager::class );
		$pm->method( 'userHasRight' )
			->will( $this->returnValueMap( [
				[ $userMock, 'editmyoptions', true ]
			] ) );
		$prefs = $this->getPreferencesFactory( $pm, new Language() )
			->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayNotHasKey( 'showrollbackconfirmation', $prefs );
	}

	/**
	 * @covers ::renderingPreferences
	 */
	public function testShowRollbackConfIsShownForUsersWithRollbackRights() {
		$userMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
		$userMock->method( 'getEffectiveGroups' )
			->willReturn( [] );
		$userMock->method( 'getGroupMemberships' )
			->willReturn( [] );
		$userMock->method( 'getOptions' )
			->willReturn( [ 'test' => 'yes' ] );
		$pm = $this->createMock( PermissionManager::class );
		$pm->method( 'userHasRight' )
			->will( $this->returnValueMap( [
				[ $userMock, 'editmyoptions', true ],
				[ $userMock, 'rollback', true ]
			] ) );
		$prefs = $this->getPreferencesFactory( $pm, new Language() )
			->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayHasKey( 'showrollbackconfirmation', $prefs );
		$this->assertEquals(
			'rendering/advancedrendering',
			$prefs['showrollbackconfirmation']['section']
		);
	}

	public function emailAuthenticationProvider() {
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
	 * @covers ::submitForm
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
		$configMock = new HashConfig( [
			'HiddenPrefs' => []
		] );
		$form = $this->getMockBuilder( PreferencesFormOOUI::class )
			->disableOriginalConstructor()
			->getMock();

		$userMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
		$userMock->method( 'getOptions' )
			->willReturn( $oldOptions );

		$userMock->expects( $this->exactly( 2 ) )
			->method( 'setOption' )
			->withConsecutive(
				[ $this->equalTo( 'test' ), $this->equalTo( $newOptions[ 'test' ] ) ],
				[ $this->equalTo( 'option' ), $this->equalTo( $newOptions[ 'option' ] ) ]
			);

		$form->method( 'getModifiedUser' )
			->willReturn( $userMock );

		$form->method( 'getContext' )
			->willReturn( $this->context );

		$form->method( 'getConfig' )
			->willReturn( $configMock );

		$pm = $this->createMock( PermissionManager::class );
		$pm->method( 'userHasAnyRight' )
			->will( $this->returnValueMap( [
				[ $userMock, 'editmyprivateinfo', 'editmyoptions', true ]
			] ) );
		$pm->method( 'userHasRight' )
			->will( $this->returnValueMap( [
				[ $userMock, 'editmyoptions', true ]
			] ) );

		$this->setTemporaryHook( 'PreferencesFormPreSave',
			function ( $formData, $form, $user, &$result, $oldUserOptions )
				use ( $newOptions, $oldOptions, $userMock ) {
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
			$this->getPreferencesFactory( $pm, new Language() )
		);
		$factory->saveFormData( $newOptions, $form, [] );
	}

	/**
	 * The rclimit preference should accept non-integer input and filter it to become an integer.
	 *
	 * @covers ::saveFormData
	 */
	public function testIntvalFilter() {
		// Test a string with leading zeros (i.e. not octal) and spaces.
		$this->context->getRequest()->setVal( 'wprclimit', ' 0012 ' );
		$user = new User;
		$pm = $this->createMock( PermissionManager::class );
		$pm->method( 'userHasAnyRight' )
			->willReturn( true );
		$pm->method( 'userHasRight' )
			->will( $this->returnValueMap( [
				[ $user, 'editmyoptions', true ]
			] ) );
		$prefFactory = $this->getPreferencesFactory( $pm, new Language() );
		$form = $prefFactory->getForm( $user, $this->context );
		$form->show();
		$form->trySubmit();
		$this->assertEquals( 12, $user->getOption( 'rclimit' ) );
	}

	/**
	 * @covers ::profilePreferences
	 */
	public function testVariantsSupport() {
		$userMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
		$userMock->method( 'getEffectiveGroups' )
			->willReturn( [] );
		$userMock->method( 'getGroupMemberships' )
			->willReturn( [] );
		$userMock->method( 'getOptions' )
			->willReturn( [ 'LanguageCode' => 'sr', 'variant' => 'sr' ] );
		$pm = $this->createMock( PermissionManager::class );

		$pm->method( 'userHasRight' )
			->will( $this->returnValue( true ) );

		$language = $this->createMock( Language::class );
		$language->expects( $this->any() )->method( 'getCode' )
			->willReturn( 'sr' );

		$prefs = $this->getPreferencesFactory( $pm, $language )
			->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayHasKey( 'default', $prefs['variant'] );
		$this->assertEquals( 'sr', $prefs['variant']['default'] );
	}

	/**
	 * @covers ::profilePreferences
	 */
	public function testUserGroupMemberships() {
		$userMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
		$userMock->method( 'getEffectiveGroups' )
			->willReturn( [ 'users' ] );
		$userMock->method( 'getGroupMemberships' )
			->willReturn( [ 'users' ] );
		$userMock->method( 'getOptions' )
			->willReturn( [] );
		$pm = $this->createMock( PermissionManager::class );

		$pm->method( 'userHasRight' )
			->will( $this->returnValue( true ) );

		$language = $this->createMock( Language::class );
		$language->expects( $this->any() )->method( 'getCode' )
			->willReturn( 'en' );

		$prefs = $this->getPreferencesFactory( $pm, $language )
			->getFormDescriptor( $userMock, $this->context );
		$this->assertArrayHasKey( 'default', $prefs['usergroups'] );
		$this->assertEquals( 'users', $prefs['usergroups']['default'] );
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
}
