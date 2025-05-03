<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialMovePage
 * @group Database
 */
class SpecialMovePageTest extends SpecialPageTestBase {

	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Movepage' );
	}

	public function testNoDefinedOldTitle() {
		$this->expectException( ErrorPageError::class );
		// The expected exception message will be in English because of T46111
		$this->expectExceptionMessage( wfMessage( 'notargettext' )->inLanguage( 'en' )->text() );
		$this->executeSpecialPage( '', null, null, $this->getTestSysop()->getUser() );
	}

	public function testOldTitleDoesNotExist() {
		$this->expectException( ErrorPageError::class );
		// The expected exception message will be in English because of T46111
		$this->expectExceptionMessage( wfMessage( 'nopagetext' )->inLanguage( 'en' )->text() );
		$this->executeSpecialPage( $this->getNonexistingTestPage()->getTitle(), null, null, $this->getTestSysop()->getUser() );
	}

	/** @dataProvider provideLoadFormForOldTitleWithSubpages */
	public function testLoadFormForOldTitleWithSubpages( $subpageCount, $maximumMovedPages, $shouldShowLimitedMessage ) {
		// Tests that the security patch for T357760 works.
		$this->overrideConfigValue( MainConfigNames::MaximumMovedPages, $maximumMovedPages );
		// NS_TALK supports subpages, so we can use that namespace for testing.
		$testPage = $this->getExistingTestPage( Title::newFromText( 'Test page for old title', NS_TALK ) );
		// Create a few testing subpages
		for ( $i = 0; $i < $subpageCount; $i++ ) {
			$this->getExistingTestPage( Title::newFromText( "Test page for old title/$i", NS_TALK ) );
		}
		// Load Special:MovePage with $testPage as the old title
		[ $html ] = $this->executeSpecialPage( $testPage->getTitle(), null, 'qqx', $this->getTestSysop()->getUser() );
		if ( $shouldShowLimitedMessage ) {
			$this->assertStringContainsString(
				'movesubpagetext-truncated',
				$html,
				'The the truncated subpage message should have been shown'
			);
			// This works because the subpages start from 0 and increase by 1. As such, the subpage with the number in
			// $maximumMovedPages will not be displayed (because it would cause the limit to be broken).
			$this->assertStringNotContainsString(
				"Talk:Test_page_for_old_title/$maximumMovedPages",
				$html,
				'The subpages list was not properly truncated.'
			);
		} else {
			$this->assertStringContainsString(
				'movesubpagetext',
				$html,
				'The the subpage message should have been shown'
			);
			$this->assertStringNotContainsString(
				'movesubpagetext-truncated',
				$html,
				'The the subpage message should have been shown'
			);
		}
	}

	public static function provideLoadFormForOldTitleWithSubpages() {
		return [
			'1 subpage, max subpages at 2' => [ 1, 2, false ],
			'3 subpages, max subpages at 2' => [ 3, 2, true ],
		];
	}

	public function testWatchlistExpiry(): void {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$user = $this->getTestSysop()->getUser();
		$testPage = $this->getExistingTestPage( Title::newFromText( 'Test page for watchlist expiry' ) );
		$this->getServiceContainer()->getWatchlistManager()->setWatch( true, $user, $testPage->getTitle(), '7 days' );
		[ $html ] = $this->executeSpecialPage( $testPage->getTitle()->getPrefixedDBkey(), null, 'qqx', $user );
		$this->assertStringContainsString( 'watchlist-expiry-days-left: 7', $html );
	}
}
