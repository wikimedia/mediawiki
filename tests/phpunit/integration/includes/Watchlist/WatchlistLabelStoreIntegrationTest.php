<?php

use MediaWiki\Title\Title;
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
		$this->assertSame( 1, $loaded[1]->getId() );
		// Add another label, and check count.
		$label2 = new WatchlistLabel( $user, 'Test two' );
		$store->save( $label2 );
		$this->assertSame( 2, $store->countAllForUser( $user ) );
	}

	/**
	 * Labels can be deleted in batches.
	 */
	public function testDeleteLabels(): void {
		// Create 10 labels for a test user.
		$user = $this->getTestUser()->getUser();
		$store = $this->getServiceContainer()->getWatchlistLabelStore();
		for ( $i = 0; $i < 10; $i++ ) {
			$label = new WatchlistLabel( $user, "Test label $i" );
			$store->save( $label );
		}
		// Create a label for a different user.
		$otherUser = TestUserRegistry::getMutableTestUser( 'Other user' )->getUser();
		$otherUserLabel = new WatchlistLabel( $otherUser, 'Test label 1' );
		$store->save( $otherUserLabel );

		$this->assertCount( 10, $store->loadAllForUser( $user ) );
		$deleted = $store->delete( $user, [ 2, 3, 4, 6 ] );
		$this->assertSame( true, $deleted );
		$this->assertCount( 6, $store->loadAllForUser( $user ) );
		$notDeleted = $store->delete( $user, [ 9, 10, $otherUserLabel->getId() ] );
		$this->assertSame( false, $notDeleted );
	}

	public function testCountItems(): void {
		$this->overrideConfigValues( [ 'EnableWatchlistLabels' => true ] );
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();
		$itemStore = $this->getServiceContainer()->getWatchedItemStore();
		$user = $this->getTestUser()->getUser();

		// Empty list.
		$this->assertSame( [], $labelStore->countItems( [] ) );

		// Non-existent IDs.
		$this->assertSame( [ 34 => 0, 43 => 0 ], $labelStore->countItems( [ 34, 43 ] ) );

		// Watch some pages, and label some of them.
		$title1 = Title::newFromText( 'Test page 1' );
		$title2 = Title::newFromText( 'Test page 2' );
		$itemStore->addWatchBatchForUser( $user, [ $title1, $title2 ] );
		$label = new WatchlistLabel( $user, 'Test label' );
		$labelStore->save( $label );
		$itemStore->addLabels( $user, [ $title1 ], [ $label ] );
		$this->assertSame( [ 1 => 1, 2 => 0 ], $labelStore->countItems( [ 1, 2 ] ) );
	}

	/**
	 * @dataProvider provideSorting
	 */
	public function testSorting( array $labelNames, array $sortedLabelNames ): void {
		$user = $this->getTestUser()->getUser();
		$store = $this->getServiceContainer()->getWatchlistLabelStore();
		foreach ( $labelNames as $labelName ) {
			$store->save( new WatchlistLabel( $user, $labelName ) );
		}
		$loaded = $store->loadAllForUser( $user );
		$this->assertSame(
			$sortedLabelNames,
			array_values( array_map( static fn ( WatchlistLabel $label ) => $label->getName(), $loaded ) )
		);
	}

	public static function provideSorting(): array {
		return [
			[
				[ 'pear', 'Banana', 'PEAR', 'Apple' ],
				[ 'Apple', 'Banana', 'PEAR', 'pear' ],
			],
		];
	}
}
