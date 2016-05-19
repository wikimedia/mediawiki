<?php
use MediaWiki\MediaWikiServices;

/**
 * @covers CloakingLBFactory
 * @author Daniel Kinzler
 */
class CloakingLBFactoryTest extends MediaWikiTestCase {

	/**
	 * @return array
	 */
	private function getLBFactoryConf() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$lbFactoryConf = $config->get( 'LBFactoryConf' );
		return $lbFactoryConf;
	}

	/**
	 * @return CloakingLBFactory
	 */
	private function newLBFactory() {
		$lbFactoryConf = $this->getLBFactoryConf();
		$lbFactory = new CloakingLBFactory( $lbFactoryConf );
		return $lbFactory;
	}

	/**
	 * @covers CloakingLBFactory::cloakDatabase
	 * @covers CloakingLBFactory::getMainLB
	 * @covers CloakingLBFactory::newLoadBalancer
	 */
	public function testCloak() {
		$lbFactoryConf = $lbFactoryConf = $this->getLBFactoryConf();
		$prefix = isset( $lbFactoryConf['tablePrefix'] ) ? $lbFactoryConf['tablePrefix'] : '';
		$lbFactory = $this->newLBFactory();

		$oldBalancer = $lbFactory->getMainLB();
		$oldConnection = $oldBalancer->getConnection( DB_MASTER );
		$this->assertSame( $prefix, $oldConnection->tablePrefix() );

		$cloak = [
			'testDbPrefix' => 'cloaktest_',
			'useTemporaryTables' => true,
			'reuseDB' => false,
		];
		$lbFactory->cloakDatabase( $cloak );

		$newBalancer = $lbFactory->getMainLB();
		$newConnection = $newBalancer->getConnection( DB_MASTER );
		$this->assertNotSame(
			$oldConnection,
			$newConnection,
			'Connections should be reset by cloaking'
		);
		$this->assertSame( $oldBalancer, $newBalancer, 'LoadBalancer should survive cloaking' );
		$this->assertSame( 'cloaktest_', $newConnection->tablePrefix() );
	}

	/**
	 * @covers CloakingLBFactory::uncloakDatabase
	 * @covers CloakingLBFactory::isCloaked
	 */
	public function testUncloak() {
		$lbFactoryConf = $lbFactoryConf = $this->getLBFactoryConf();
		$prefix = isset( $lbFactoryConf['tablePrefix'] ) ? $lbFactoryConf['tablePrefix'] : '';
		$lbFactory = $this->newLBFactory();

		$cloak = [
			'testDbPrefix' => 'cloaktest_',
			'useTemporaryTables' => true,
			'reuseDB' => false,
		];
		$lbFactory->cloakDatabase( $cloak );

		$this->assertTrue( $lbFactory->isCloaked(), 'isCloaked()' );

		$oldBalancer = $lbFactory->getMainLB();
		$oldConnection = $oldBalancer->getConnection( DB_MASTER );
		$this->assertSame( 'cloaktest_', $oldConnection->tablePrefix() );

		// cloaking worked, now test uncloak
		$lbFactory->uncloakDatabase();

		$this->assertFalse( $lbFactory->isCloaked(), 'isCloaked()' );

		$newBalancer = $lbFactory->getMainLB();
		$newConnection = $newBalancer->getConnection( DB_MASTER );
		$this->assertNotSame(
			$oldConnection,
			$newConnection,
			'Connections should be reset by uncloaking'
		);
		$this->assertSame( $prefix, $newConnection->tablePrefix(), 'restore old table prefix' );
	}

}
