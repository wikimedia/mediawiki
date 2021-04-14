<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistManager;

/**
 * @covers \MediaWiki\Watchlist\WatchlistManager
 *
 * @author DannyS712
 * @group Database
 */
class WatchlistManagerTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	public function testClearTitleUserNotifications() {
		$options = new ServiceOptions(
			WatchlistManager::CONSTRUCTOR_OPTIONS,
			[
				'UseEnotif' => false,
				'ShowUpdatedMarker' => false
			]
		);

		$services = $this->getServiceContainer();
		$manager = new WatchlistManager(
			$options,
			$this->createHookContainer(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup(),
			$services->getTalkPageNotificationManager(),
			$services->getWatchedItemStore(),
			$services->getUserFactory()
		);

		$username = 'User Name';
		$user = $this->mockUserAuthorityWithPermissions(
			new UserIdentityValue( 100, $username ),
			[ 'editmywatchlist' ]
		);
		$title = new PageIdentityValue( 100, NS_USER_TALK, $username, PageIdentityValue::LOCAL );

		$this->db->startAtomic( __METHOD__ ); // let deferred updates queue up

		$updateCountBefore = DeferredUpdates::pendingUpdatesCount();
		$manager->clearTitleUserNotifications( $user, $title );
		$updateCountAfter = DeferredUpdates::pendingUpdatesCount();
		$this->assertGreaterThan( $updateCountBefore, $updateCountAfter, 'An update should have been queued' );

		$this->db->endAtomic( __METHOD__ ); // run deferred updates

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No pending updates' );
	}

}
