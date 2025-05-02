<?php

namespace MediaWiki\Tests\Maintenance;

use CleanupCaps;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use StatusValue;

/**
 * @covers \CleanupCaps
 * @group Database
 * @author Dreamy Jazz
 */
class CleanupCapsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CleanupCaps::class;
	}

	protected function setUp(): void {
		parent::setUp();
		// We clear the title cache because these tests will create title objects with lowercase form and uppercase
		// form
		Title::clearCaches();
	}

	/** @dataProvider provideCapitalLinksValues */
	public function testWhenNamespaceEmpty( bool $capitalLinks, string $stringToExpectInOutput ) {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		$this->expectOutputRegex( '/Processing page[\s\S]*Finished page.*0 of 0 rows updated/' );
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( $stringToExpectInOutput, $actualOutput );
	}

	public static function provideCapitalLinksValues() {
		return [
			'$wgCapitalLinks is false' => [
				false, 'Will be moving pages to first letter lowercase titles',
			],
			'$wgCapitalLinks is true' => [
				true, 'Will be moving pages to first letter capitalized titles',
			],
		];
	}

	/** @dataProvider provideCapitalLinksValues */
	public function testWhenPagesExistButDoNotNeedMoving( bool $capitalLinks, string $stringToExpectInOutput ) {
		// Generate a test page which does not need moving
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
		$titleBeforeCall = 'test';
		if ( $capitalLinks ) {
			$titleBeforeCall = ucfirst( $titleBeforeCall );
		}
		$this->getExistingTestPage( Title::newFromText( $titleBeforeCall ) );

		// Verify that the DB has been set up correctly for this test, in case Title::newFromText does
		// normalisation that causes the title to be in a different form to what we want.
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValue( $titleBeforeCall );

		// Run the maintenance script
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		// Assert on the output, checking that the script says the page are already fixed.
		$this->expectOutputRegex( '/Processing page[\s\S]*Finished page.*0 of.*rows updated/' );
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( $stringToExpectInOutput, $actualOutput );
		if ( $capitalLinks ) {
			$expectedMoveLineOutput = '"Test" already uppercase';
		} else {
			$expectedMoveLineOutput = '"test" already lowercase';
		}
		$this->assertStringContainsString( $expectedMoveLineOutput, $actualOutput );

		// Expect that no moves have occurred, as the page were already in the correct form
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [ 'actor_name' => 'Conversion script' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValue( $titleBeforeCall );
	}

	/** @dataProvider provideCapitalLinksValues */
	public function testWhenPagesExistAndNeedToBeMoved( bool $capitalLinks, string $stringToExpectInOutput ) {
		// Generate three testing titles when $wgCapitalLinks is reversed from the value
		// provided in $capitalLinks (so we can test that the script does convert).
		$titles = [ 'a', 'test', 'test_abc' ];
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, !$capitalLinks );

		if ( $capitalLinks ) {
			$titlesBeforeCall = array_map( 'lcfirst', $titles );
		} else {
			$titlesBeforeCall = array_map( 'ucfirst', $titles );
		}
		foreach ( $titlesBeforeCall as $title ) {
			$this->getExistingTestPage( Title::newFromText( $title ) );
		}

		// Check that the titles in the DB have the correct form (either uppercase or lowercase first character),
		// as Title::newFromText could have modified this.
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValues( $titlesBeforeCall );

		// Make the change in $wgCapitalLinks and run the maintenance script to do the conversion.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		// Assert that the script outputs it has renamed all of the pages
		$this->expectOutputRegex( '/Processing page[\s\S]*Finished page.*3 of.*rows updated/' );
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( $stringToExpectInOutput, $actualOutput );

		foreach ( $titles as $title ) {
			$title = str_replace( '_', ' ', $title );
			if ( $capitalLinks ) {
				$originTitle = lcfirst( $title );
				$destTitle = ucfirst( $title );
			} else {
				$originTitle = ucfirst( $title );
				$destTitle = lcfirst( $title );
			}
			$this->assertStringContainsString( "\"$originTitle\" -> \"$destTitle\": OK", $actualOutput );
		}

		// Assert that the pages have actually been moved in the DB
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [ 'actor_name' => 'Conversion script' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 3 );

		if ( $capitalLinks ) {
			$expectedTitles = array_map( 'ucfirst', $titles );
		} else {
			// When $wgCapitalLinks is false, redirects are left from the old name
			// as they are still valid titles.
			$expectedTitles = array_merge(
				array_map( 'ucfirst', $titles ),
				array_map( 'lcfirst', $titles )
			);
		}
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValues( $expectedTitles );
	}

	/** @dataProvider provideCapitalLinksValues */
	public function testWhenPageExistsWithTalkPageThatNeedsMove( bool $capitalLinks, string $stringToExpectInOutput ) {
		// Generate a page and an associated talk page that will need to be moved.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, !$capitalLinks );
		$originTitle = 'abc';
		if ( !$capitalLinks ) {
			$originTitle = ucfirst( $originTitle );
		}
		$this->getExistingTestPage( Title::newFromText( $originTitle ) );
		$this->getExistingTestPage( Title::newFromText( $originTitle, NS_TALK ) );

		// Verify that the page_title column is set up correctly for the test.
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => [ 0, 1 ] ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ $originTitle, $originTitle ] );

		// Make the change in $wgCapitalLinks and run the maintenance script to do the conversion.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		// Assert that the script says both the mainspace page and it's associated talk page have moved.
		$this->expectOutputRegex( '/Processing page[\s\S]*Finished page.*2 of.*rows updated/' );
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( $stringToExpectInOutput, $actualOutput );

		$destTitle = $capitalLinks ? ucfirst( $originTitle ) : lcfirst( $originTitle );
		$this->assertStringContainsString( "\"$originTitle\" -> \"$destTitle\": OK", $actualOutput );
		$this->assertStringContainsString( "\"Talk:$originTitle\" -> \"Talk:$destTitle\": OK", $actualOutput );

		// Assert that what the script says it has done has been applied in the DB
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [ 'actor_name' => 'Conversion script' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 2 );

		if ( $capitalLinks ) {
			$expectedTitles = [ $destTitle, $destTitle ];
		} else {
			// When $wgCapitalLinks is false, redirects are left from the old name
			// as they are still valid titles.
			$expectedTitles = [ $originTitle, $originTitle, $destTitle, $destTitle ];
		}
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => [ 0, 1 ] ] )
			->caller( __METHOD__ )
			->assertFieldValues( $expectedTitles );
	}

	/** @dataProvider provideCapitalLinksValues */
	public function testWhenPagesExistForDryRun( bool $capitalLinks, string $stringToExpectInOutput ) {
		// Generate a page that needs moving
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, !$capitalLinks );
		$originTitle = 'abc';
		if ( !$capitalLinks ) {
			$originTitle = ucfirst( $originTitle );
		}
		$this->getExistingTestPage( Title::newFromText( $originTitle ) );
		$this->getExistingTestPage( Title::newFromText( $originTitle, NS_TALK ) );

		// Verify the page_title column before running the script as we want to assert no change by the
		// script, so need to know the form before calling the script.
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => [ 0, 1 ] ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ $originTitle, $originTitle ] );

		// Make the change in $wgCapitalLinks and run the maintenance script to do the conversion.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->setOption( 'dry-run', 1 );
		$this->maintenance->execute();

		// Expect that the script processes the pages, but does not move them as it's a dry run.
		$this->expectOutputRegex( '/Processing page[\s\S]*Finished page.*2 of.*rows updated/' );
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( $stringToExpectInOutput, $actualOutput );

		$destTitle = $capitalLinks ? ucfirst( $originTitle ) : lcfirst( $originTitle );
		$this->assertStringContainsString(
			"\"$originTitle\" -> \"$destTitle\": DRY RUN, NOT MOVED", $actualOutput
		);
		$this->assertStringContainsString(
			"\"Talk:$originTitle\" -> \"Talk:$destTitle\": DRY RUN, NOT MOVED", $actualOutput
		);

		// Expect that the DB is untouched as this was a dry-run.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [ 'actor_name' => 'Conversion script' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );

		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => [ 0, 1 ] ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ $originTitle, $originTitle ] );
	}

	/** @dataProvider provideCapitalLinksValues */
	public function testWhenMovePageIsValidMoveHookDeniesMove( bool $capitalLinks, string $stringToExpectInOutput ) {
		// Generate a page that needs moving
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, !$capitalLinks );
		$originTitle = 'abc';
		if ( !$capitalLinks ) {
			$originTitle = ucfirst( $originTitle );
		}
		$this->getExistingTestPage( Title::newFromText( $originTitle ) );

		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => [ 0, 1 ] ] )
			->caller( __METHOD__ )
			->assertFieldValue( $originTitle );

		// Add a hook for MovePageIsValidMove that denies all attempts to move pages
		$this->setTemporaryHook(
			'MovePageIsValidMove',
			static function ( $unusedOne, $unusedTwo, StatusValue $status ) {
				$status->fatal( 'test-status' );
			}
		);

		// Make the change in $wgCapitalLinks and run the maintenance script to do the conversion.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, $capitalLinks );
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		// Assert that the output indicates the move failed
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( $stringToExpectInOutput, $actualOutput );
		$destTitle = $capitalLinks ? ucfirst( $originTitle ) : lcfirst( $originTitle );
		$this->assertStringContainsString(
			"\"$originTitle\" -> \"$destTitle\": FAILED", $actualOutput
		);

		// Expect that the titles have not been moved because the move was disallowed
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [ 'actor_name' => 'Conversion script' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => [ 0, 1 ] ] )
			->caller( __METHOD__ )
			->assertFieldValue( $originTitle );
	}

	public function testWhenToLowercaseFindsNewTitleAlreadyExists() {
		// Get two pages which have the same name except for the capitalisation of the first character.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, false );
		$this->getExistingTestPage( Title::newFromText( 'test' ) );
		$this->getExistingTestPage( Title::newFromText( 'Test' ) );
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ 'Test', 'test' ] );

		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		// Check that the maintenance script skipped the uppercase page because the lowercase version already exists.
		$this->expectOutputRegex( '/"Test" skipped; "test" already exists/' );
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ 'Test', 'test' ] );
	}

	public function testWhenToUppercaseFindsNewTitleAlreadyExists() {
		// Get two pages which have the same name except for the capitalisation of the first character.
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, false );
		$this->getExistingTestPage( Title::newFromText( 'test' ) );
		$this->getExistingTestPage( Title::newFromText( 'Test' ) );
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ 'Test', 'test' ] );

		$this->overrideConfigValue( MainConfigNames::CapitalLinks, true );
		$this->maintenance->setOption( 'namespace', 0 );
		$this->maintenance->execute();

		// Check that the lowercase version is renamed to 'CleanupCaps/test' because the uppercase version already
		// exists. This is done to ensure that the title is not considered invalid by the Title class
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString(
			"\"test\" -> \"CapsCleanup/test\": OK", $actualOutput
		);
		$this->newSelectQueryBuilder()
			->select( 'page_title' )
			->from( 'page' )
			->where( [ 'page_namespace' => 0 ] )
			->caller( __METHOD__ )
			->assertFieldValues( [ 'CapsCleanup/test', 'Test' ] );
	}
}
