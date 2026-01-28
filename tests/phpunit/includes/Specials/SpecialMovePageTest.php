<?php

namespace MediaWiki\Tests\Specials;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Integration tests for moving pages.
 *
 * Tests like this are quite slow. They should focus on behaviors unique to the Special:MovePage interface.
 * Simple tests for validity/permissions of a single page move should be unit tests in MovePageTest.
 *
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
		$this->getServiceContainer()->getWatchlistManager()->setWatch( true, $user, $testPage->getTitle(), '7 days 1 hour' );
		[ $html ] = $this->executeSpecialPage( $testPage->getTitle()->getPrefixedDBkey(), null, 'qqx', $user );
		$this->assertStringContainsString( 'watchlist-expiry-days-left: 7', $html );
	}

	/**
	 * Assert that the page content is the given string, or that the page doesn't exist if null is given.
	 * Assumes that the page content model is wikitext.
	 */
	private function assertPageContent( string $title, ?string $expectedContent, string $message = '' ) {
		$p = $this->getServiceContainer()->getPageStore()->getPageByText( $title );
		$r = $this->getServiceContainer()->getRevisionLookup()->getRevisionByTitle( $p );
		$this->assertEquals( $expectedContent, $r?->getMainContentRaw()?->getNativeData(), $message );
	}

	/**
	 * Execute Special:MovePage without form submission (GET).
	 *
	 * @return array{string, WebResponse|ErrorPageError}
	 */
	private function getSpecialMovePage( User $user, string $from, array $query = [] ): array {
		try {
			return $this->executeSpecialPage( $from, new FauxRequest( [
				...$query,
			], wasPosted: false ), 'qqx', $user );
		} catch ( ErrorPageError $error ) {
			return [ '', $error ];
		}
	}

	/**
	 * Execute Special:MovePage with correctly submitted form (POST).
	 *
	 * @return array{string, WebResponse|ErrorPageError}
	 */
	private function postSpecialMovePage( User $user, string $from, string $to, array $query = [] ): array {
		try {
			return $this->executeSpecialPage( '', new FauxRequest( [
				'wpOldTitle' => $from,
				'wpNewTitle' => $to,
				...$query,
				'action' => 'submit',
				'wpEditToken' => $user->getEditToken(),
			], wasPosted: true ), 'qqx', $user );
		} catch ( ErrorPageError $error ) {
			return [ '', $error ];
		}
	}

	public function testMoveWithRedirects() {
		$this->overrideConfigValue( MainConfigNames::NamespacesWithSubpages, [
			NS_MAIN => true,
			NS_TALK => true,
		] );

		// Create page A and related pages
		$this->assertStatusGood( $this->editPage( 'A', 'w' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A', 'x' ) );
		$this->assertStatusGood( $this->editPage( 'A/1', 'y' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A/1', 'z' ) );

		[ $html ] = $this->postSpecialMovePage( $this->getTestUser()->getUser(), 'A', 'B', [
			'wpMovetalk' => 1,
			'wpMovesubpages' => 1,
			'wpLeaveRedirect' => 1,
		] );

		$this->assertStringContainsString( '(movepage-moved-redirect)', $html );

		// Redirects from A to B have been created
		$this->assertPageContent( 'A', '#REDIRECT [[B]]' );
		$this->assertPageContent( 'Talk:A', '#REDIRECT [[Talk:B]]' );
		$this->assertPageContent( 'A/1', '#REDIRECT [[B/1]]' );
		$this->assertPageContent( 'Talk:A/1', '#REDIRECT [[Talk:B/1]]' );

		// Page contents have been moved to B
		$this->assertPageContent( 'B', 'w' );
		$this->assertPageContent( 'Talk:B', 'x' );
		$this->assertPageContent( 'B/1', 'y' );
		$this->assertPageContent( 'Talk:B/1', 'z' );
	}

	public function testMoveNamespacesWithSubpages() {
		$this->overrideConfigValue( MainConfigNames::NamespacesWithSubpages, [
			NS_MAIN => false,
			NS_TALK => true,
		] );

		// Create page A and related pages
		$this->assertStatusGood( $this->editPage( 'A', 'w' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A', 'x' ) );
		$this->assertStatusGood( $this->editPage( 'A/1', 'y' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A/1', 'z' ) );

		[ $html ] = $this->postSpecialMovePage( $this->getTestUser()->getUser(), 'A', 'B', [
			'wpMovetalk' => 1,
			'wpMovesubpages' => 1,
			'wpLeaveRedirect' => 1,
		] );

		// Redirects from A to B have been created - excluding the subpage-like title which is not a subpage
		$this->assertPageContent( 'A', '#REDIRECT [[B]]' );
		$this->assertPageContent( 'Talk:A', '#REDIRECT [[Talk:B]]' );
		$this->assertPageContent( 'A/1', 'y' ); // unchanged
		$this->assertPageContent( 'Talk:A/1', '#REDIRECT [[Talk:B/1]]' );

		// Page contents have been moved to B - excluding the subpage-like title which is not a subpage
		$this->assertPageContent( 'B', 'w' );
		$this->assertPageContent( 'Talk:B', 'x' );
		$this->assertPageContent( 'B/1', false ); // unchanged
		$this->assertPageContent( 'Talk:B/1', 'z' );
	}

	public function testMoveWithoutRedirects() {
		$this->overrideConfigValue( MainConfigNames::NamespacesWithSubpages, [
			NS_MAIN => true,
			NS_TALK => true,
		] );

		// Create page A and related pages
		$this->assertStatusGood( $this->editPage( 'A', 'w' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A', 'x' ) );
		$this->assertStatusGood( $this->editPage( 'A/1', 'y' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A/1', 'z' ) );

		// Must use getTestSysop(), as the checkbox is ignored for users without the permission
		[ $html ] = $this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'A', 'B', [
			'wpMovetalk' => 1,
			'wpMovesubpages' => 1,
		] );

		$this->assertStringContainsString( '(movepage-moved-noredirect)', $html );

		// Redirects from A have not been created, the page has been deleted instead
		$this->assertPageContent( 'A', null );
		$this->assertPageContent( 'Talk:A', null );
		$this->assertPageContent( 'A/1', null );
		$this->assertPageContent( 'Talk:A/1', null );

		// Page contents have been moved to B
		$this->assertPageContent( 'B', 'w' );
		$this->assertPageContent( 'Talk:B', 'x' );
		$this->assertPageContent( 'B/1', 'y' );
		$this->assertPageContent( 'Talk:B/1', 'z' );
	}

	public function testMoveWithoutRedirectsPermissions() {
		// Users with permissions can choose whether to create a redirect
		$this->assertStatusGood( $this->editPage( 'A1', 'a' ) );
		$this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'A1', 'B1' );
		$this->assertPageContent( 'A1', null );
		$this->assertPageContent( 'B1', 'a' );

		$this->assertStatusGood( $this->editPage( 'A2', 'a' ) );
		$this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'A2', 'B2', [
			'wpLeaveRedirect' => 1,
		] );
		$this->assertPageContent( 'A2', '#REDIRECT [[B2]]' );
		$this->assertPageContent( 'B2', 'a' );

		// Users without permissions always move with redirect
		$this->assertStatusGood( $this->editPage( 'A3', 'a' ) );
		$this->postSpecialMovePage( $this->getTestUser()->getUser(), 'A3', 'B3' );
		$this->assertPageContent( 'A3', '#REDIRECT [[B3]]' );
		$this->assertPageContent( 'B3', 'a' );

		// Pages where redirects are prohibited are always moved without redirect
		$this->assertStatusGood( $this->editPage( 'MediaWiki:A', 'a' ) );
		$this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'MediaWiki:A', 'MediaWiki:B', [
			'wpLeaveRedirect' => 1,
		] );
		$this->assertPageContent( 'MediaWiki:A', null );
		$this->assertPageContent( 'MediaWiki:B', 'a' );
	}

	public function testMoveWithRedirectsAndFix() {
		$this->overrideConfigValue( MainConfigNames::NamespacesWithSubpages, [
			NS_MAIN => true,
			NS_TALK => true,
		] );
		$this->overrideConfigValue( MainConfigNames::FixDoubleRedirects, true );

		// Create redirects from A to B and related pages (as if the page has been moved)
		$this->assertStatusGood( $this->editPage( 'A', '#REDIRECT [[B]]' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A', '#REDIRECT [[Talk:B]]' ) );
		$this->assertStatusGood( $this->editPage( 'A/1', '#REDIRECT [[B/1]]' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:A/1', '#REDIRECT [[Talk:B/1]]' ) );

		// Create page B and related pages (as if the page has been moved)
		$this->assertStatusGood( $this->editPage( 'B', 'w' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:B', 'x' ) );
		$this->assertStatusGood( $this->editPage( 'B/1', 'y' ) );
		$this->assertStatusGood( $this->editPage( 'Talk:B/1', 'z' ) );

		[ $html ] = $this->postSpecialMovePage( $this->getTestUser()->getUser(), 'B', 'C', [
			'wpMovetalk' => 1,
			'wpMovesubpages' => 1,
			'wpFixRedirects' => 1,
			'wpLeaveRedirect' => 1,
		] );

		// Redirect fixing happens in jobs, run them
		$this->runJobs( [ 'numJobs' => 4 ], [ 'type' => 'fixDoubleRedirect' ] );

		// Redirects from A to B have been updated to point to C instead
		$this->assertPageContent( 'A', '#REDIRECT [[C]]' );
		$this->assertPageContent( 'Talk:A', '#REDIRECT [[Talk:C]]' );
		$this->assertPageContent( 'A/1', '#REDIRECT [[C/1]]' );
		$this->assertPageContent( 'Talk:A/1', '#REDIRECT [[Talk:C/1]]' );

		// Redirects from B to C have been created
		$this->assertPageContent( 'B', '#REDIRECT [[C]]' );
		$this->assertPageContent( 'Talk:B', '#REDIRECT [[Talk:C]]' );
		$this->assertPageContent( 'B/1', '#REDIRECT [[C/1]]' );
		$this->assertPageContent( 'Talk:B/1', '#REDIRECT [[Talk:C/1]]' );

		// Page contents have been moved to C
		$this->assertPageContent( 'C', 'w' );
		$this->assertPageContent( 'Talk:C', 'x' );
		$this->assertPageContent( 'C/1', 'y' );
		$this->assertPageContent( 'Talk:C/1', 'z' );
	}

	public function testMoveProtection() {
		$this->assertStatusGood( $this->editPage( 'A', 'a' ) );

		$wikiPage = $this->getExistingTestPage( 'A' );
		$this->assertStatusGood( $wikiPage->doUpdateRestrictions(
			[ 'edit' => 'sysop', 'move' => 'sysop' ], [], $cascade, '', $this->getTestSysop()->getUser()
		) );

		// Users without permissions can not move a protected page
		[ $html, $error ] = $this->getSpecialMovePage( $this->getTestUser()->getUser(), 'A' );
		$this->assertInstanceOf( PermissionsError::class, $error );
		[ $html, $error ] = $this->postSpecialMovePage( $this->getTestUser()->getUser(), 'A', 'B' );
		$this->assertInstanceOf( PermissionsError::class, $error );

		// Users with permissions can move a protected page after seeing a warning
		[ $html ] = $this->getSpecialMovePage( $this->getTestSysop()->getUser(), 'A' );
		$this->assertStringContainsString( '(protectedpagemovewarning)', $html );
		[ $html ] = $this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'A', 'B' );
		$this->assertStringContainsString( '(movepage-moved-noredirect)', $html );
	}

	public function testMoveOverExisting() {
		$this->assertStatusGood( $this->editPage( 'A', 'a' ) );
		$this->assertStatusGood( $this->editPage( 'B', 'b' ) );

		// Users without permissions can not move over an existing page
		[ $html ] = $this->postSpecialMovePage( $this->getTestUser()->getUser(), 'A', 'B' );
		$this->assertStringContainsString( '(articleexists: B)', $html );

		// Users with permissions can move over an existing page after seeing a warning and confirming a checkbox
		[ $html ] = $this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'A', 'B' );
		$this->assertStringContainsString( '(delete_and_move_text: B)', $html );
		[ $html ] = $this->postSpecialMovePage( $this->getTestSysop()->getUser(), 'A', 'B', [
			'wpDeleteAndMove' => 1,
		] );
		$this->assertStringContainsString( '(movepage-moved-noredirect)', $html );
	}

	// TODO: Some workflows not covered by tests:
	// (many of these should have separate cases depending on whether the user can override the warnings)
	// Depending on target page contents:
	// - Moving over a redirect pointing to another page
	// - Moving the talk page over a redirect pointing to another page
	// - Moving over a shared repo file
	// - Moving over an existing page where the deletion fails
	// - Moving over an existing page where the deletion succeeds, but then the move fails
	// Depending on target title:
	// - Moving from subject to talk namespace with talk/subpages
	// - Moving from talk to subject namespace with talk/subpages
	// Depending on related page protection levels:
	// - Moving when the old title is protected against moving
	// - Moving when a subpage of the old title is protected against moving
	// - Moving when the talk page of the old title is protected against moving
	// - Moving when the destination page or its talk page is protected against creation
	// Involving subpages:
	// - Moving with subpages where talk doesn't exist but talk subpages do
	// - Moving with subpages where target's subpages already exist
	// - Moving with subpages where subpage title max length is exceeded
}
