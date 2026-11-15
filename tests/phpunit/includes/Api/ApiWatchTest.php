<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\Watchlist\WatchlistLabel;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiWatch
 */
class ApiWatchTest extends ApiTestCase {
	protected function setUp(): void {
		parent::setUp();

		// Fake current time to be 2019-06-05T19:50:42Z
		ConvertibleTimestamp::setFakeTime( 1559764242 );

		$this->overrideConfigValues( [
			MainConfigNames::WatchlistExpiry => true,
			MainConfigNames::WatchlistExpiryMaxDuration => '6 months',
		] );
	}

	public function testWatch() {
		// Watch for a duration greater than the max ($wgWatchlistExpiryMaxDuration),
		// which should get changed to the max
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => '99990101000000',
			'formatversion' => 2
		] );

		$res = $data[0]['watch'][0];
		$this->assertSame( 'Talk:Test page', $res['title'] );
		$this->assertSame( 1, $res['ns'] );
		$this->assertTrue( $res['watched'] );
		$this->assertSame( '2019-12-05T19:50:42Z', $res['expiry'] );

		// Re-watch, changing the expiry to indefinite.
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => 'indefinite',
			'formatversion' => 2
		] );
		$res = $data[0]['watch'][0];
		$this->assertSame( 'infinity', $res['expiry'] );
	}

	public function testWatchWithExpiry() {
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$user = $this->getTestUser()->getUser();
		$pageTitle = 'TestWatchWithExpiry';

		// First watch without expiry (indefinite).
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $pageTitle,
		], null, $user );

		// Ensure page was added to the user's watchlist, and expiry is null (not set).
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( $pageTitle, $item->getTarget()->getDBkey() );
		$this->assertNull( $item->getExpiry() );

		// Re-watch, setting an expiry.
		$expiry = '2 weeks';
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $pageTitle,
			'expiry' => $expiry,
		], null, $user );
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( '20190619195042', $item->getExpiry() );

		// Re-watch again, providing no expiry parameter, so expiry should remain unchanged.
		$oldExpiry = $item->getExpiry();
		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => $pageTitle,
		], null, $user );
		[ $item ] = $store->getWatchedItemsForUser( $user );
		$this->assertSame( $oldExpiry, $item->getExpiry() );
	}

	public function testWatchInvalidExpiry() {
		$this->expectApiErrorCode( 'badexpiry' );

		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => 'invalid expiry',
			'formatversion' => 2
		] );
	}

	public function testWatchExpiryInPast() {
		$this->expectApiErrorCode( 'badexpiry-past' );

		$this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Talk:Test page',
			'expiry' => '20010101000000',
			'formatversion' => 2
		] );
	}

	public function testWatchEdit() {
		$data = $this->doApiRequestWithToken( [
			'action' => 'edit',
			'title' => 'Help:TestWatchEdit', // Help namespace is hopefully wikitext
			'text' => 'new text',
			'watchlist' => 'watch'
		] );

		$this->assertArrayHasKey( 'edit', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['edit'] );
		$this->assertEquals( 'Success', $data[0]['edit']['result'] );

		return $data;
	}

	/**
	 * @depends testWatchEdit
	 */
	public function testWatchClear() {
		$data = $this->doApiRequest( [
			'action' => 'query',
			'wllimit' => 'max',
			'list' => 'watchlist' ] );

		if ( isset( $data[0]['query']['watchlist'] ) ) {
			$wl = $data[0]['query']['watchlist'];

			foreach ( $wl as $page ) {
				$data = $this->doApiRequestWithToken( [
					'action' => 'watch',
					'title' => $page['title'],
					'unwatch' => true,
				] );
			}
		}
		$data = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'watchlist' ] );
		$this->assertArrayHasKey( 'query', $data[0] );
		$this->assertArrayHasKey( 'watchlist', $data[0]['query'] );
		foreach ( $data[0]['query']['watchlist'] as $index => $item ) {
			// Previous tests may insert an invalid title
			// like ":ApiEditPageTest testNonTextEdit", which
			// can't be cleared.
			if ( str_starts_with( $item['title'], ':' ) ) {
				unset( $data[0]['query']['watchlist'][$index] );
			}
		}
		$this->assertSame( [], $data[0]['query']['watchlist'] );

		return $data;
	}

	public function testWatchProtect() {
		$pageTitle = 'Help:TestWatchProtect';
		$this->getExistingTestPage( $pageTitle );
		$data = $this->doApiRequestWithToken( [
			'action' => 'protect',
			'title' => $pageTitle,
			'protections' => 'edit=sysop',
			'watchlist' => 'unwatch'
		] );

		$this->assertArrayHasKey( 'protect', $data[0] );
		$this->assertArrayHasKey( 'protections', $data[0]['protect'] );
		$this->assertCount( 1, $data[0]['protect']['protections'] );
		$this->assertArrayHasKey( 'edit', $data[0]['protect']['protections'][0] );
	}

	public function testWatchRollback() {
		$titleText = 'Help:TestWatchRollback';
		$title = Title::makeTitle( NS_HELP, 'TestWatchRollback' );
		$revertingUser = $this->getTestSysop()->getUser();
		$revertedUser = $this->getTestUser()->getUser();
		$this->editPage( $title, 'Edit 1', '', NS_MAIN, $revertingUser );
		$this->editPage( $title, 'Edit 2', '', NS_MAIN, $revertedUser );

		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		// This (and assertTrue below) are mostly for completeness.
		$this->assertFalse( $watchlistManager->isWatched( $revertingUser, $title ) );

		$data = $this->doApiRequestWithToken( [
			'action' => 'rollback',
			'title' => $titleText,
			'user' => $revertedUser,
			'watchlist' => 'watch'
		] );

		$this->assertArrayHasKey( 'rollback', $data[0] );
		$this->assertArrayHasKey( 'title', $data[0]['rollback'] );
		$this->assertTrue( $watchlistManager->isWatched( $revertingUser, $title ) );
	}

	public function testWatchWithWatchlistLabelsDisabled() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => false,
		] );

		$user = $this->getTestUser()->getUser();
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:LabelsDisabled',
			'labels' => 1,
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertArrayHasKey( 'errors', $res );
		$this->assertSame( 'labels-disabled', $res['errors'][0]['code'] );
	}

	public function testWatchWithInvalidWatchlistLabelId() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => true,
		] );

		$user = $this->getTestUser()->getUser();
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:InvalidLabel',
			'labels' => 99999, // Non-existent label ID
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertArrayHasKey( 'errors', $res );
		$this->assertSame( 'invalid-label-id', $res['errors'][0]['code'] );
	}

	public function testWatchWithValidWatchlistLabelId() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => true,
		] );

		$user = $this->getTestUser()->getUser();
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();

		// Create a label for the user
		$label = new WatchlistLabel( $user, 'Test Label' );
		$saveStatus = $labelStore->save( $label );

		// Watch a page with the valid label
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:ValidLabel',
			'labels' => $label->getId()
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertTrue( $res['watched'] );
		$this->assertStatusOK( $saveStatus );
		$this->assertArrayHasKey( 'labels', $res );
		$this->assertCount( 1, $res['labels'] );
		$this->assertSame( 'Test Label', $res['labels'][0]['name'] );
		$this->assertFalse( isset( $res['errors'] ) );
	}

	public function testWatchWithMixOfValidAndInvalidWatchlistLabelIds() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => true,
		] );

		$user = $this->getTestUser()->getUser();
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();

		// Create a label for the user
		$validLabel = new WatchlistLabel( $user, 'Valid Label' );
		$saveStatus = $labelStore->save( $validLabel );

		// Watch a page with a mix of valid and invalid label IDs
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:MixValidInvalidLabels',
			'labels' => implode( '|', [ $validLabel->getId(), 99999 ] ) // 99999 is a non-existent label ID
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertTrue( $res['watched'] );
		$this->assertStatusOK( $saveStatus );
		$this->assertArrayHasKey( 'labels', $res );
		$this->assertCount( 1, $res['labels'] );
		$this->assertSame( 'Valid Label', $res['labels'][0]['name'] );
		$this->assertArrayHasKey( 'errors', $res );
		$this->assertSame( 'invalid-label-id', $res['errors'][0]['code'] );
	}

	public function testWatchWithMultipleValidWatchlistLabelIds() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => true,
		] );

		$user = $this->getTestUser()->getUser();
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();

		// Create multiple labels for the user
		$label1 = new WatchlistLabel( $user, 'Label 1' );
		$label2 = new WatchlistLabel( $user, 'Label 2' );
		$saveStatus1 = $labelStore->save( $label1 );
		$saveStatus2 = $labelStore->save( $label2 );

		// Watch a page with the valid labels
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:MultipleValidLabels',
			'labels' => implode( '|', [ $label1->getId(), $label2->getId() ] )
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertTrue( $res['watched'] );
		$this->assertStatusOK( $saveStatus1 );
		$this->assertStatusOK( $saveStatus2 );
		$this->assertArrayHasKey( 'labels', $res );
		$this->assertCount( 2, $res['labels'] );
		$labelNames = array_column( $res['labels'], 'name' );
		$this->assertContains( 'Label 1', $labelNames );
		$this->assertContains( 'Label 2', $labelNames );
	}

	public function testWatchMultiplePagesWithValidWatchlistLabelId() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => true,
		] );

		$user = $this->getTestUser()->getUser();
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();

		// Create a label for the user
		$label = new WatchlistLabel( $user, 'Test Label For Multiple Pages' );
		$saveStatus = $labelStore->save( $label );

		// Watch multiple pages with the valid label
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:Page1|Test:Page2',
			'labels' => $label->getId()
		], null, $user );

		foreach ( [ 'Test:Page1', 'Test:Page2' ] as $index => $pageTitle ) {
			$res = $data[0]['watch'][$index];
			$this->assertSame( $pageTitle, $res['title'] );
			$this->assertTrue( $res['watched'] );
			$this->assertStatusOK( $saveStatus );
			$this->assertArrayHasKey( 'labels', $res );
			$this->assertCount( 1, $res['labels'] );
			$this->assertSame( 'Test Label For Multiple Pages', $res['labels'][0]['name'] );
			$this->assertFalse( isset( $res['errors'] ) );
		}
	}

	public function testWatchReplacesExistingLabels() {
		$this->overrideConfigValues( [
			MainConfigNames::EnableWatchlistLabels => true,
		] );

		$user = $this->getTestUser()->getUser();
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();

		// Create three labels for the user
		$label1 = new WatchlistLabel( $user, 'Initial Label 1' );
		$label2 = new WatchlistLabel( $user, 'Initial Label 2' );
		$label3 = new WatchlistLabel( $user, 'Replacement Label' );
		$labelStore->save( $label1 );
		$labelStore->save( $label2 );
		$labelStore->save( $label3 );

		// Watch a page with the first two labels
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:LabelsReplaceTest',
			'labels' => implode( '|', [ $label1->getId(), $label2->getId() ] )
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertTrue( $res['watched'] );
		$this->assertArrayHasKey( 'labels', $res );
		$this->assertCount( 2, $res['labels'] );
		$labelNames = array_column( $res['labels'], 'name' );
		$this->assertContains( 'Initial Label 1', $labelNames );
		$this->assertContains( 'Initial Label 2', $labelNames );

		// Re-watch the same page with a different label, which should replace the existing labels
		$data = $this->doApiRequestWithToken( [
			'action' => 'watch',
			'titles' => 'Test:LabelsReplaceTest',
			'labels' => $label3->getId()
		], null, $user );

		$res = $data[0]['watch'][0];
		$this->assertTrue( $res['watched'] );
		$this->assertArrayHasKey( 'labels', $res );
		$this->assertCount( 1, $res['labels'], 'Should only have one label after replacement' );
		$this->assertSame( 'Replacement Label', $res['labels'][0]['name'] );
		$labelNames = array_column( $res['labels'], 'name' );
		$this->assertNotContains( 'Initial Label 1', $labelNames, 'Old label 1 should be removed' );
		$this->assertNotContains( 'Initial Label 2', $labelNames, 'Old label 2 should be removed' );
	}
}
