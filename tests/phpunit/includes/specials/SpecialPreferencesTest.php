<?php
/**
 * Test class for SpecialPreferences class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Wikimedia Foundation Inc.
 */

/**
 * @group Preferences
 * @group Database
 *
 * @covers SpecialPreferences
 */
class SpecialPreferencesTest extends MediaWikiIntegrationTestCase {

	/**
	 * Make sure a nickname which is longer than $wgMaxSigChars
	 * is not throwing a fatal error.
	 *
	 * Test specifications by Alexandre "ialex" Emsenhuber.
	 * @todo give this test a real name explaining what is being tested here
	 */
	public function testT43337() {
		// Set a low limit
		$this->setMwGlobals( 'wgMaxSigChars', 2 );
		$user = $this->createMock( User::class );
		$user->expects( $this->any() )
			->method( 'isAnon' )
			->will( $this->returnValue( false ) );

		# Yeah foreach requires an array, not NULL =(
		$user->expects( $this->any() )
			->method( 'getEffectiveGroups' )
			->will( $this->returnValue( [] ) );

		# The mocked user has a long nickname
		$user->expects( $this->any() )
			->method( 'getOption' )
			->will( $this->returnValueMap( [
				[ 'nickname', null, false, 'superlongnickname' ],
			]
			) );

		# Needs to return something
		$user->method( 'getOptions' )
			->willReturn( [] );

		// isAnyAllowed used to return null from the mock,
		// thus revoke it's permissions.
		$this->overrideUserPermissions( $user, [] );

		# Forge a request to call the special page
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );
		$context->setUser( $user );
		$context->setTitle( Title::newFromText( 'Test' ) );

		# Do the call, should not spurt a fatal error.
		$special = new SpecialPreferences();
		$special->setContext( $context );
		$this->assertNull( $special->execute( [] ) );
	}

}
