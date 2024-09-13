<?php

abstract class LockManagerIntegrationTestBase extends MediaWikiIntegrationTestCase {

	/** @var string */
	protected $class;
	/** @var LockManager */
	protected $managerA;
	/** @var LockManager */
	protected $managerB;

	/**
	 * @param string $threadName
	 * @return LockManager
	 */
	abstract protected function getManager( $threadName );

	protected function setUp(): void {
		$this->managerA = $this->getManager( 'A' );
		$this->managerB = $this->getManager( 'B' );
		$this->class = get_class( $this->managerA );
	}

	public function testLockUnlockEx() {
		$managerA = $this->managerA;
		$managerB = $this->managerB;
		$rand = wfRandomString();
		$path = "unit_testing://resource/$rand/photo.jpeg";

		$status = $managerA->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Lock (outer) succeeded ({$this->class})." );

		$status = $managerA->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Lock (inner) succeeded ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusNotGood( $status, "Lock (EX) conflicted ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusNotGood( $status, "Lock (SH) conflicted ({$this->class})." );

		$status = $managerA->unlock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Unlock (inner) succeeded ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusNotGood( $status, "Lock still conflicted ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusNotGood( $status, "Lock still conflicted ({$this->class})." );

		$status = $managerA->unlock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Unlock (outer) succeeded ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Lock now succeeded ({$this->class})." );

		$status = $managerB->unlock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Unlock succeeded ({$this->class})." );
	}

	public function testLockUnlockSh() {
		$managerA = $this->managerA;
		$managerB = $this->managerB;
		$rand = wfRandomString();
		$path = "unit_testing://resource/$rand/file.png";

		$status = $managerA->lock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusGood( $status, "Lock (outer) succeeded ({$this->class})." );

		$status = $managerA->lock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusGood( $status, "Lock (inner) succeeded ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusNotGood( $status, "Lock (EX) conflicted ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusGood( $status, "Lock (SH) obtained ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusNotGood( $status, "Lock upgrade (EX) conflicted ({$this->class})." );

		$status = $managerA->unlock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusGood( $status, "Unlock (inner) succeeded ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusNotGood( $status, "Lock (EX) still conflicted ({$this->class})." );

		$status = $managerA->unlock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusGood( $status, "Unlock (outer) succeeded ({$this->class})." );

		$status = $managerB->lock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Lock upgrade (EX) now obtained ({$this->class})." );

		$status = $managerB->unlock( [ $path ], LockManager::LOCK_EX );
		$this->assertStatusGood( $status, "Unlock (EX) succeeded ({$this->class})." );

		$status = $managerB->unlock( [ $path ], LockManager::LOCK_SH );
		$this->assertStatusGood( $status, "Unlock (SH) succeeded ({$this->class})." );
	}
}
