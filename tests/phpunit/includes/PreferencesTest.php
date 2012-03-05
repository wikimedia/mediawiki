<?php

class PreferencesTest extends MediaWikiTestCase {

	/** Array of User objects */
	private $users ;
	private $context ;

	function __construct() {
		parent::__construct();

		$this->users['noemail'] = new User;

		$this->users['notauth'] = new User;
		$this->users['notauth']
			->setEmail( 'noauth@example.org' );

		$this->users['auth']    = new User;
		$this->users['auth']
			->setEmail( 'noauth@example.org' );
		$this->users['auth']
			->setEmailAuthenticationTimestamp( 1330946623 );

		$this->context = new RequestContext;
		$this->context->setTitle( Title::newFromText('PreferencesTest') );
	}

	/**
	 * Placeholder to verify bug 34919
	 * @covers Preferences::profilePreferences
	 */
	function testEmailFieldsWhenUserHasNoEmail() {
		$prefs = $this->prefsFor( 'noemail' );
		$this->assertArrayNotHasKey( 'class',
			$prefs['emailaddress']
		);
	}
	/**
	 * Placeholder to verify bug 34919
	 * @covers Preferences::profilePreferences
	 */
	function testEmailFieldsWhenUserEmailNotAuthenticated() {
		$prefs = $this->prefsFor( 'notauth' );
		$this->assertArrayNotHasKey( 'class',
			$prefs['emailaddress']
		);
	}
	/**
	 * Placeholder to verify bug 34919
	 * @covers Preferences::profilePreferences
	 */
	function testEmailFieldsWhenUserEmailIsAuthenticated() {
		$prefs = $this->prefsFor( 'auth' );
		$this->assertArrayNotHasKey( 'class',
			$prefs['emailaddress']
		);
	}


	/** Helper */
	function prefsFor( $user_key ) {
		$preferences = array();
		Preferences::profilePreferences(
			$this->users[$user_key]
			, $this->context
			, $preferences
		);
		return $preferences;
	}

}
