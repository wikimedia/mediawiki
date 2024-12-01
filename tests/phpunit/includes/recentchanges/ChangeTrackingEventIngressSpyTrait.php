<?php

namespace MediaWiki\Tests\recentchanges;

use MediaWiki\User\UserEditTracker;
use RecentChange;
use StatusValue;

/**
 * Trait providing test spies for asserting that listeners
 * ChangeTrackingEventIngress do or do not get called during
 * certain actions.
 */
trait ChangeTrackingEventIngressSpyTrait {

	private function installChangeTrackingEventIngressSpys(
		int $rcEdit,
		int $rcOther,
		int $userEditCount
	) {
		// Hack for spying on RC insertions
		$rcEditStatus = $this->createMock( StatusValue::class );
		$rcEditStatus->expects( $this->exactly( $rcEdit ) )
			->method( 'setOK' );

		$rcOtherStatus = $this->createMock( StatusValue::class );
		$rcOtherStatus->expects( $this->exactly( $rcOther ) )
			->method( 'setOK' );

		$this->setTemporaryHook(
			'RecentChange_save',
			static function ( RecentChange $rc ) use ( $rcEditStatus, $rcOtherStatus ) {
				// Only count types recorded by ChangeTrackingEventIngress
				$type = (int)$rc->getAttributes()['rc_type'];
				if ( $type === RC_EDIT || $type === RC_NEW ) {
					$rcEditStatus->setOK( true );
				} else {
					$rcOtherStatus->setOK( true );
				}
			}
		);

		// Spy for UserEditTracker
		$userEditTracker = $this->createNoOpMock(
			UserEditTracker::class,
			[ 'incrementUserEditCount', 'setCachedUserEditCount', 'clearUserEditCache' ]
		);

		$userEditTracker->expects( $this->exactly( $userEditCount ) )
			->method( 'incrementUserEditCount' );

		$this->setService( 'UserEditTracker', $userEditTracker );
	}

	private function installChangeTrackingEventIngressSpyForEdit() {
		$this->installChangeTrackingEventIngressSpys( 1, 0, 1 );
	}

	private function installChangeTrackingEventIngressSpyForDerived() {
		$this->installChangeTrackingEventIngressSpys( 0, 0, 0 );
	}

	private function installChangeTrackingEventIngressSpyForPageMove() {
		// Should be counted as user contributions (T163966)
		// Should generate an RC entry for the move log, but not for
		// the dummy revision or redirect page.
		$this->installChangeTrackingEventIngressSpys( 0, 1, 1 );
	}

	private function installChangeTrackingEventIngressSpyForUndeletion() {
		// Should generate an RC entry for undeletion,
		// but not a regular page edit.
		$this->installChangeTrackingEventIngressSpys( 0, 1, 0 );
	}

	private function installChangeTrackingEventIngressSpyForImport() {
		$this->installChangeTrackingEventIngressSpys( 0, 0, 0 );
	}

}
