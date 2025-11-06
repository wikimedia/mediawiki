<?php
/**
 * Test class for SpecialPreferences class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Wikimedia Foundation Inc.
 */
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialPreferences;
use MediaWiki\User\Options\UserOptionsManager;

/**
 * @group Preferences
 * @group Database
 *
 * @covers \MediaWiki\Specials\SpecialPreferences
 */
class SpecialPreferencesTest extends SpecialPageTestBase {
	/**
	 * HACK: use this variable to override UserOptionsManager for use in the special page. Ideally we'd just do
	 * $this->setService, but that's super hard because some places that use UserOptionsManager read a lot from the
	 * global state and a mock would need to be super-complex for all the various checks to work.
	 */
	private ?UserOptionsManager $userOptionsManager = null;

	protected function tearDown(): void {
		$this->userOptionsManager = null;
		parent::tearDown();
	}

	protected function newSpecialPage() {
		return new SpecialPreferences(
			$this->getServiceContainer()->getPreferencesFactory(),
			$this->userOptionsManager ?? $this->getServiceContainer()->getUserOptionsManager()
		);
	}

	/**
	 * Make sure a username which is longer than $wgMaxSigChars
	 * is not throwing a fatal error (T43337).
	 */
	public function testLongUsernameDoesNotFatal() {
		$maxSigChars = 2;
		$this->overrideConfigValue( MainConfigNames::MaxSigChars, $maxSigChars );
		$nickname = str_repeat( 'x', $maxSigChars + 1 );
		$user = $this->getMutableTestUser()->getUser();

		$this->userOptionsManager = $this->createMock( UserOptionsManager::class );
		$this->userOptionsManager->method( 'getOption' )
			->with( $user, 'nickname' )
			->willReturn( $nickname );

		$this->executeSpecialPage( '', null, null, $user );
		// We assert that no error is thrown
		$this->addToAssertionCount( 1 );
	}

}
