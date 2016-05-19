<?php
use MediaWiki\MediaWikiServices;

/**
 * @covers CloakingLoadBalancer
 * @author Daniel Kinzler
 */
class CloakingLoadBalancerTest extends MediaWikiTestCase {

	/**
	 * @return array
	 */
	private function getLBFactoryConf() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$lbFactoryConf = $config->get( 'LBFactoryConf' );
		return $lbFactoryConf;
	}

	/**
	 * @return CloakingLoadBalancer
	 */
	private function newLoadBalancer() {
		$trxProfiler = $this->getMock( 'TransactionProfiler' );
		$servers = LBFactorySimple::getConfiguredDBServers();

		$params = [
			'servers' => $servers,
			'loadMonitor' => null,
			'readOnlyReason' => '',
			'trxProfiler' => $trxProfiler
		];
		return new CloakingLoadBalancer( $params );
	}

	/**
	 * @covers CloakingLBFactory::cloakDatabase
	 * @covers CloakingLBFactory::getMainLB
	 * @covers CloakingLBFactory::newLoadBalancer
	 */
	public function testCloak() {
		$lbFactoryConf = $lbFactoryConf = $this->getLBFactoryConf();
		$prefix = isset( $lbFactoryConf['tablePrefix'] ) ? $lbFactoryConf['tablePrefix'] : '';
		$balancer = $this->newLoadBalancer();

		$oldConnection = $balancer->getConnection( DB_MASTER );
		$this->assertSame( $prefix, $oldConnection->tablePrefix() );

		$cloak = [
			'testDbPrefix' => 'cloaktest_',
			'useTemporaryTables' => true,
			'reuseDB' => false,
		];
		$balancer->cloakDatabase( $cloak );

		$newConnection = $balancer->getConnection( DB_MASTER );
		$this->assertNotSame(
			$oldConnection,
			$newConnection,
			'Connections should be reset by cloaking'
		);
		$this->assertSame( 'cloaktest_', $newConnection->tablePrefix() );
	}

	/**
	 * @covers CloakingLBFactory::uncloakDatabase
	 * @covers CloakingLBFactory::isCloaked
	 */
	public function testUncloak() {
		$lbFactoryConf = $lbFactoryConf = $this->getLBFactoryConf();
		$prefix = isset( $lbFactoryConf['tablePrefix'] ) ? $lbFactoryConf['tablePrefix'] : '';
		$balancer = $this->newLoadBalancer();

		$cloak = [
			'testDbPrefix' => 'cloaktest_',
			'useTemporaryTables' => true,
			'reuseDB' => false,
		];
		$balancer->cloakDatabase( $cloak );

		$this->assertTrue( $balancer->isCloaked(), 'isCloaked()' );

		$oldConnection = $balancer->getConnection( DB_MASTER );
		$this->assertSame( 'cloaktest_', $oldConnection->tablePrefix() );

		// cloaking worked, now test uncloak
		$balancer->uncloakDatabase();

		$this->assertFalse( $balancer->isCloaked(), 'isCloaked()' );

		$newConnection = $balancer->getConnection( DB_MASTER );
		$this->assertNotSame(
			$oldConnection,
			$newConnection,
			'Connections should be reset by uncloaking'
		);
		$this->assertSame( $prefix, $newConnection->tablePrefix(), 'restore old table prefix' );
	}

}
