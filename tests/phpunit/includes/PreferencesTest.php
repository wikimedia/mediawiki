<?php

/**
 * @group Database
 */
class PreferencesTest extends MediaWikiTestCase {
	/**
	 * @var User[]
	 */
	private $prefUsers;
	/**
	 * @var RequestContext
	 */
	private $context;

	public function __construct() {
		parent::__construct();

		$this->prefUsers['noemail'] = new User;

		$this->prefUsers['notauth'] = new User;
		$this->prefUsers['notauth']
			->setEmail( 'noauth@example.org' );

		$this->prefUsers['auth'] = new User;
		$this->prefUsers['auth']
			->setEmail( 'noauth@example.org' );
		$this->prefUsers['auth']
			->setEmailAuthenticationTimestamp( 1330946623 );

		$this->context = new RequestContext;
		$this->context->setTitle( Title::newFromText( 'PreferencesTest' ) );
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgEnableEmail' => true,
			'wgEmailAuthentication' => true,
		] );
	}

	/**
	 * Placeholder to verify T36302
	 * @covers Preferences::profilePreferences
	 */
	public function testEmailAuthenticationFieldWhenUserHasNoEmail() {
		$prefs = $this->prefsFor( 'noemail' );
		$this->assertArrayHasKey( 'cssclass',
			$prefs['emailauthentication']
		);
		$this->assertEquals( 'mw-email-none', $prefs['emailauthentication']['cssclass'] );
	}

	/**
	 * Placeholder to verify T36302
	 * @covers Preferences::profilePreferences
	 */
	public function testEmailAuthenticationFieldWhenUserEmailNotAuthenticated() {
		$prefs = $this->prefsFor( 'notauth' );
		$this->assertArrayHasKey( 'cssclass',
			$prefs['emailauthentication']
		);
		$this->assertEquals( 'mw-email-not-authenticated', $prefs['emailauthentication']['cssclass'] );
	}

	/**
	 * Placeholder to verify T36302
	 * @covers Preferences::profilePreferences
	 */
	public function testEmailAuthenticationFieldWhenUserEmailIsAuthenticated() {
		$prefs = $this->prefsFor( 'auth' );
		$this->assertArrayHasKey( 'cssclass',
			$prefs['emailauthentication']
		);
		$this->assertEquals( 'mw-email-authenticated', $prefs['emailauthentication']['cssclass'] );
	}

	/**
	 * Test that PreferencesFormPreSave hook has correct data:
	 *  - user Object is passed
	 *  - oldUserOptions contains previous user options (before save)
	 *  - formData and User object have set up new properties
	 *
	 * @see https://phabricator.wikimedia.org/T169365
	 * @covers Preferences::tryFormSubmit
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
		$form = $this->getMockBuilder( PreferencesForm::class )
			->disableOriginalConstructor()
			->getMock();

		$userMock = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();
		$userMock->method( 'getOptions' )
			->willReturn( $oldOptions );
		$userMock->method( 'isAllowedAny' )
			->willReturn( true );
		$userMock->method( 'isAllowed' )
			->willReturn( true );

		$userMock->expects( $this->exactly( 2 ) )
			->method( 'setOption' )
			->withConsecutive(
				[ $this->equalTo( 'test' ), $this->equalTo( $newOptions[ 'test' ] ) ],
				[ $this->equalTo( 'option' ), $this->equalTo( $newOptions[ 'option' ] ) ]
			);

		$form->expects( $this->any() )
			->method( 'getModifiedUser' )
			->willReturn( $userMock );

		$form->expects( $this->any() )
			->method( 'getContext' )
			->willReturn( $this->context );

		$form->expects( $this->any() )
			->method( 'getConfig' )
			->willReturn( $configMock );

		$this->setTemporaryHook( 'PreferencesFormPreSave', function (
			$formData, $form, $user, &$result, $oldUserOptions )
			use ( $newOptions, $oldOptions, $userMock ) {
			$this->assertSame( $userMock, $user );
			foreach ( $newOptions as $option => $value ) {
				$this->assertSame( $value, $formData[ $option ] );
			}
			foreach ( $oldOptions as $option => $value ) {
				$this->assertSame( $value, $oldUserOptions[ $option ] );
			}
			$this->assertEquals( true, $result );
		}
		);

		Preferences::tryFormSubmit( $newOptions, $form );
	}

	/** Helper */
	protected function prefsFor( $user_key ) {
		$preferences = [];
		Preferences::profilePreferences(
			$this->prefUsers[$user_key],
			$this->context,
			$preferences
		);

		return $preferences;
	}
}
