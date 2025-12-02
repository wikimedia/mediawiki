<?php
namespace Wikimedia\Tests\Integration\LockManager;

use MediaWiki\MainConfigNames;
use Wikimedia\LockManager\RedisLockManager;

/**
 * @group LockManager
 * @covers \Wikimedia\LockManager\RedisLockManager
 */
class RedisLockManagerIntegrationTest extends LockManagerIntegrationTestBase {
	/** @var LockManager[] */
	private static $managersToUse = [];

	private function getManagerConfig(): ?array {
		$backends = $this->getServiceContainer()->getMainConfig()->get( MainConfigNames::LockManagers );
		foreach ( $backends as $conf ) {
			if ( $conf['class'] === RedisLockManager::class ) {
				return $conf;
			}
		}

		return null;
	}

	protected function getManager( $threadName ) {
		if ( !isset( self::$managersToUse[$threadName] ) ) {
			$conf = $this->getManagerConfig();
			if ( $conf === null ) {
				$this->markTestSkipped(
					'Configure a RedisLockManager in $wgLockManagers to enable this test' );
			}
			$conf['name'] = 'localtesting'; // swap name
			$conf['domain'] = 'testingdomain';
			$conf['lockTTL'] = 60;
			self::$managersToUse[$threadName] = new RedisLockManager( $conf );
		}

		return self::$managersToUse[$threadName];
	}
}
