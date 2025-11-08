<?php

use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistLabel;

/**
 * @group Database
 * @group Watchlist
 *
 * @covers \MediaWiki\Watchlist\WatchedItemStore
 */
class WatchlistLabelStoreIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * Watchlist labels can be created, retrieved, and edited.
	 */
	public function testCreateLabels() {
		$user = new UserIdentityValue( 42, 'WatchlistLabelStoreIntegrationTestUser' );
		// Create a new label.
		$label = new WatchlistLabel( $user, 'Test one' );
		$store = $this->getServiceContainer()->getWatchlistLabelStore();
		$store->save( $label );
		$this->assertCount( 1, $store->loadAllForUser( $user ) );
		// Saving the same label doesn't create a duplicate.
		$store->save( $label );
		$this->assertCount( 1, $store->loadAllForUser( $user ) );
		// Retrieve the label by ID.
		$fetchedLabel = $store->loadById( $user, 1 );
		$this->assertSame( $label->getId(), $fetchedLabel->getId() );
		$this->assertSame( $label->getName(), $fetchedLabel->getName() );
		$this->assertSame( $label->getUser(), $fetchedLabel->getUser() );
		// Alter the label's name and save it again.
		$label->setName( 'Test one changed' );
		$store->save( $label );
		$loaded = $store->loadAllForUser( $user );
		$this->assertCount( 1, $loaded );
		$this->assertSame( 1, $loaded[0]->getId() );
		// Add another label, and check count.
		$label2 = new WatchlistLabel( $user, 'Test two' );
		$store->save( $label2 );
		$this->assertSame( 2, $store->countAllForUser( $user ) );
	}
}
