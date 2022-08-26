<?php
/**
 * Test class for SpecialPreferences class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Wikimedia Foundation Inc.
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\User\UserOptionsLookup;

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
		$this->overrideConfigValue( MainConfigNames::MaxSigChars, 2 );
		$user = $this->createMock( User::class );
		$user->method( 'getTitleKey' )
			->willReturn( __CLASS__ );
		$user->method( 'isAnon' )
			->willReturn( false );
		$user->method( 'isNamed' )
			->willReturn( true );

		# The mocked user has a long nickname
		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->method( 'getOption' )
			->willReturnMap( [
				[
					$user,
					'nickname',
					null,
					false,
					UserOptionsLookup::READ_NORMAL,
					'superlongnickname'
				],
				[
					$user,
					'language',
					null,
					false,
					UserOptionsLookup::READ_NORMAL,
					MainConfigSchema::LanguageCode['default']
				],
				[
					$user,
					'skin',
					null,
					false,
					UserOptionsLookup::READ_NORMAL,
					MainConfigSchema::DefaultSkin['default']
				],
				[
					$user,
					'timecorrection',
					null,
					false,
					UserOptionsLookup::READ_NORMAL,
					"System|-420"
				],
				// MessageCache::getParserOptions() uses the main context
				[
					RequestContext::getMain()->getUser(),
					'language',
					null,
					false,
					UserOptionsLookup::READ_NORMAL,
					MainConfigSchema::LanguageCode['default']
				],
			] );
		$this->setService( 'UserOptionsLookup', $userOptionsLookup );

		// isAnyAllowed used to return null from the mock,
		// thus revoke it's permissions.
		$this->overrideUserPermissions( $user, [] );

		# Forge a request to call the special page
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );
		$context->setUser( $user );
		$context->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );

		$services = $this->getServiceContainer();
		# Do the call, should not spurt a fatal error.
		$special = new SpecialPreferences(
			$services->getPreferencesFactory(),
			$services->getUserOptionsManager()
		);
		$special->setContext( $context );
		$this->assertNull( $special->execute( [] ) );
	}

}
