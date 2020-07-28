<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * Test class for SpecialRecentchanges class
 *
 * @group Database
 *
 * @covers SpecialRecentChanges
 */
class SpecialRecentchangesTest extends AbstractChangesListSpecialPageTestCase {
	protected function getPage() {
		return TestingAccessWrapper::newFromObject(
			new SpecialRecentChanges
		);
	}

	// Below providers should only be for features specific to
	// RecentChanges.  Otherwise, it should go in ChangesListSpecialPageTest

	public function provideParseParameters() {
		return [
			[ 'limit=123', [ 'limit' => '123' ] ],

			[ '234', [ 'limit' => '234' ] ],

			[ 'days=3', [ 'days' => '3' ] ],

			[ 'days=0.25', [ 'days' => '0.25' ] ],

			[ 'namespace=5', [ 'namespace' => '5' ] ],

			[ 'namespace=5|3', [ 'namespace' => '5|3' ] ],

			[ 'tagfilter=foo', [ 'tagfilter' => 'foo' ] ],

			[ 'tagfilter=foo;bar', [ 'tagfilter' => 'foo;bar' ] ],
		];
	}

	public function validateOptionsProvider() {
		return [
			[
				// hidebots=1 is default for Special:RecentChanges
				[ 'hideanons' => 1, 'hideliu' => 1 ],
				true,
				[ 'hideliu' => 1 ],
				false,
			],
		];
	}

	public function testAddWatchlistJoins() {
		// Edit a test page so that it shows up in RC.
		$testTitle = Title::newFromText( 'Test page' );
		$testPage = WikiPage::factory( $testTitle );
		$testPage->doEditContent( ContentHandler::makeContent( 'Test content', $testTitle ), '' );

		// Set up RC.
		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		// Confirm that the test page is in RC.
		$rc1 = new SpecialRecentChanges;
		$rc1->setContext( $context );
		$rc1->execute( null );
		$this->assertStringContainsString( 'Test page', $rc1->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-not-watched', $rc1->getOutput()->getHTML() );

		// Watch the page, and check that it's now watched in RC.
		$watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		$watchedItemStore->addWatch( $context->getUser(), $testTitle );
		$rc2 = new SpecialRecentChanges;
		$rc2->setContext( $context );
		$rc2->execute( null );
		$this->assertStringContainsString( 'Test page', $rc2->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-watched', $rc2->getOutput()->getHTML() );

		// Force a past expiry date on the watchlist item.
		$db = wfGetDB( DB_MASTER );
		$queryConds = [ 'wl_namespace' => $testTitle->getNamespace(), 'wl_title' => $testTitle->getDBkey() ];
		$watchedItemId = $db->selectField( 'watchlist', 'wl_id', $queryConds, __METHOD__ );
		$db->update(
			'watchlist_expiry',
			[ 'we_expiry' => $db->timestamp( '20200101000000' ) ],
			[ 'we_item' => $watchedItemId ],
			__METHOD__
		);

		// Check that the page is still in RC, but that it's no longer watched.
		$rc3 = new SpecialRecentChanges;
		$rc3->setContext( $context );
		$rc3->execute( null );
		$this->assertStringContainsString( 'Test page', $rc3->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-not-watched', $rc3->getOutput()->getHTML() );
	}
}
