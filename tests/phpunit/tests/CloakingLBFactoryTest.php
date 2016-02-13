<?php
use MediaWiki\MediaWikiServices;

/**
 * @covers CloakingLBFactory
 * @author Daniel Kinzler
 */
class CloakingLBFactoryTest extends MediaWikiTestCase {

	public function testCloak() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$lbFactoryConf = $config->get( 'LBFactoryConf' );
		$prefix = isset( $lbFactoryConf['tablePrefix'] ) ? $lbFactoryConf['tablePrefix'] : '';
		$lbFactory = new CloakingLBFactory( $lbFactoryConf );

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
		$this->assertNotSame( $oldConnection, $newConnection, 'Connections should be reset by cloaking' );
		$this->assertSame( $oldBalancer, $newBalancer, 'LoadBalancer should survive cloaking' );
		$this->assertSame( 'cloaktest_', $newConnection->tablePrefix() );
	}

	public function testUncloak() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$lbFactoryConf = $config->get( 'LBFactoryConf' );
		$prefix = isset( $lbFactoryConf['tablePrefix'] ) ? $lbFactoryConf['tablePrefix'] : '';
		$lbFactory = new CloakingLBFactory( $lbFactoryConf );

		$cloak = [
			'testDbPrefix' => 'cloaktest_',
			'useTemporaryTables' => true,
			'reuseDB' => false,
		];
		$lbFactory->cloakDatabase( $cloak );

		$oldBalancer = $lbFactory->getMainLB();
		$oldConnection = $oldBalancer->getConnection( DB_MASTER );
		$this->assertSame( 'cloaktest_', $oldConnection->tablePrefix() );

		// cloaking worked, now test uncloak
		$lbFactory->uncloakDatabase();

		$newBalancer = $lbFactory->getMainLB();
		$newConnection = $newBalancer->getConnection( DB_MASTER );
		$this->assertNotSame( $oldConnection, $newConnection, 'Connections should be reset by uncloaking' );
		$this->assertSame( $prefix, $newConnection->tablePrefix(), 'restore old table prefix' );
	}

}
