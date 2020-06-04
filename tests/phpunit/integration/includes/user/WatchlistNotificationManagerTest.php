<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\WatchlistNotificationManager;

/**
 * @covers \MediaWiki\User\WatchlistNotificationManager
 *
 * @author DannyS712
 * @group Database
 */
class WatchlistNotificationManagerTest extends MediaWikiIntegrationTestCase {

	public function testClearTitleUserNotifications() {
		$options = new ServiceOptions(
			WatchlistNotificationManager::CONSTRUCTOR_OPTIONS,
			[
				'UseEnotif' => false,
				'ShowUpdatedMarker' => false
			]
		);

		$revisionLookup = $this->createMock( RevisionStore::class );
		$talkPageNotificationManager = $this->createMock( TalkPageNotificationManager::class );
		$watchedItemStore = $this->createNoOpAbstractMock( WatchedItemStoreInterface::class );

		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		$readOnlyMode->expects( $this->once() )
			->method( 'isReadOnly' )
			->willReturn( false );

		$user = $this->createMock( User::class );
		$user->expects( $this->once() )
			->method( 'getName' )
			->willReturn( 'UserNameIsAlsoTitle' );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getText' ] )
			->getMockForAbstractClass();
		$title->expects( $this->any() )
			->method( 'getNamespace' )
			->willReturn( NS_USER_TALK );
		$title->expects( $this->any() )
			->method( 'getText' )
			->willReturn( 'UserNameIsAlsoTitle' );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );

		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->expects( $this->once() )
			->method( 'run' )
			->with(
				$this->equalTo( 'UserClearNewTalkNotification' ),
				$this->equalTo( [
					$user,
					0
				] )
			)
			->willReturn( true );

		$manager = new WatchlistNotificationManager(
			$options,
			$hookContainer,
			$permissionManager,
			$readOnlyMode,
			$revisionLookup,
			$talkPageNotificationManager,
			$watchedItemStore
		);

		$this->db->startAtomic( __METHOD__ ); // let deferred updates queue up

		$manager->clearTitleUserNotifications( $user, $title );

		$updateCount = DeferredUpdates::pendingUpdatesCount();
		$this->assertGreaterThan( 0, $updateCount, 'An update should have been queued' );

		$this->db->endAtomic( __METHOD__ ); // run deferred updates

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No pending updates' );
	}

}
