<?php
namespace Wikimedia\Tests\Integration\LockManager;

use Wikimedia\LockManager\DatabaseLockManager;

/**
 * Integration tests for DatabaseLockManager.
 * @group Database
 * @group LockManager
 * @covers \Wikimedia\LockManager\DatabaseLockManager
 */
class DatabaseLockManagerTest extends \MediaWikiIntegrationTestCase {
	/**
	 * Helper method to create a fresh instance of the lock manager.
	 */
	private function getLockManager(): DatabaseLockManager {
		return new DatabaseLockManager( [
			'domain' => 'test_domain',
			'dbProvider' => $this->getServiceContainer()->getConnectionProvider()
		] );
	}

	/**
	 * Test that we can successfully acquire a lock on a single path.
	 */
	public function testLockAcquisition() {
		$manager = $this->getLockManager();
		$path = 'mwstore://test_domain/test_container/test_file.txt';

		$status = $manager->lock( [ $path ] );

		$this->assertTrue( $status->isGood(), 'Lock should be successfully acquired' );
		$this->assertTrue( $status->isOK(), 'Status should be OK' );

		// Clean up
		$manager->unlock( [ $path ] );
	}

	/**
	 * Test that we can lock and unlock multiple paths simultaneously.
	 */
	public function testMultiplePathLockAndUnlock() {
		$manager = $this->getLockManager();
		$paths = [
			'mwstore://test_domain/test_container/file1.txt',
			'mwstore://test_domain/test_container/file2.txt'
		];

		// Acquire
		$lockStatus = $manager->lock( $paths );
		$this->assertTrue( $lockStatus->isGood(), 'Multiple locks should be acquired' );

		// Release
		$unlockStatus = $manager->unlock( $paths );
		$this->assertTrue( $unlockStatus->isGood(), 'Multiple locks should be released' );
	}

	/**
	 * Test that the destructor automatically releases locks.
	 */
	public function testDestructorReleasesLocks() {
		$path = 'mwstore://test_domain/test_container/destructor_file.txt';
		$manager1 = $this->getLockManager();

		// Manager 1 acquires lock
		$manager1->lock( [ $path ] );

		// Destroy Manager 1 (triggering __destruct)
		unset( $manager1 );

		// Instantiate Manager 2 on the same connection pool to verify the DB
		// state has been cleared by the destructor.
		$manager2 = $this->getLockManager();
		$status = $manager2->lock( [ $path ] );

		$this->assertTrue(
			$status->isGood(),
			'Manager 2 should acquire the lock because Manager 1 freed it upon destruction'
		);

		$manager2->unlock( [ $path ] );
	}
}
