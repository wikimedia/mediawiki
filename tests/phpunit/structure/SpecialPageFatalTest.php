<?php

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserIdentityValue;

/**
 * Test that runs against all registered special pages to make sure that regular
 * execution of the special page does not cause a fatal error.
 *
 * UltimateAuthority is used to run as much of the special page code as possible without
 * actually knowing the details of the special page.
 *
 * @since 1.32
 * @author Addshore
 * @coversNothing
 * @group Database
 */
class SpecialPageFatalTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		// Deprecations don't matter for what this test cares about. This made browser tests fail
		// on many occasions already. (T236809)
		$this->filterDeprecated( '//' );
	}

	public static function provideSpecialPageDoesNotFatal() {
		$spf = MediaWikiServices::getInstance()->getSpecialPageFactory();
		foreach ( $spf->getNames() as $name ) {
			yield $name => [ $name ];
		}
	}

	/**
	 * @dataProvider provideSpecialPageDoesNotFatal
	 */
	public function testSpecialPageDoesNotFatal( string $name ) {
		$spf = $this->getServiceContainer()->getSpecialPageFactory();

		$page = $spf->getPage( $name );
		if ( !$page ) {
			$this->markTestSkipped( "Could not create special page $name" );
		}

		$executor = new SpecialPageExecutor();
		$authority = new UltimateAuthority( new UserIdentityValue( 42, 'SpecialPageTester' ) );

		try {
			$executor->executeSpecialPage( $page, '', null, 'qqx', $authority );
		} catch ( ErrorPageError ) {
			// Only checked exceptions are allowed
		}

		// If the page fataled phpunit will have already died
		$this->addToAssertionCount( 1 );
	}

}
