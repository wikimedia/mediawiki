<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers MediaWiki\Storage\NameTableStoreFactory
 * @group Database
 */
class NameTableStoreFactoryTest extends MediaWikiIntegrationTestCase {
	/**
	 * @param string $localDomain
	 * @return MockObject|ILoadBalancer
	 */
	private function getMockLoadBalancer( $localDomain ) {
		$mock = $this->getMockBuilder( ILoadBalancer::class )
			->disableOriginalConstructor()->getMock();

		$mock->method( 'getLocalDomainID' )
			->willReturn( $localDomain );

		return $mock;
	}

	/**
	 * @param string $expectedWiki
	 * @return MockObject|ILBFactory
	 */
	private function getMockLoadBalancerFactory( $expectedWiki ) {
		$mock = $this->getMockBuilder( ILBFactory::class )
			->disableOriginalConstructor()->getMock();

		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$localDomain = $lbFactory->getLocalDomainID();

		$mock->method( 'getLocalDomainID' )->willReturn( $localDomain );

		$mock->expects( $this->once() )
			->method( 'getMainLB' )
			->with( $expectedWiki )
			->willReturnCallback( function ( $domain ) use ( $localDomain ) {
				return $this->getMockLoadBalancer( $localDomain );
			} );

		return $mock;
	}

	public static function provideTestGet() {
		return [
			[
				'change_tag_def',
				false,
				false,
			],
			[
				'content_models',
				false,
				false,
			],
			[
				'slot_roles',
				false,
				false,
			],
			[
				'change_tag_def',
				'test7245',
				'test7245',
			],
		];
	}

	/** @dataProvider provideTestGet */
	public function testGet( $tableName, $wiki, $expectedWiki ) {
		$services = $this->getServiceContainer();
		$wiki2 = ( $wiki === false )
			? $services->getDBLoadBalancerFactory()->getLocalDomainID()
			: $wiki;
		$names = new NameTableStoreFactory(
			$this->getMockLoadBalancerFactory( $expectedWiki ),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableStoreFactory' )
		);

		$table = $names->get( $tableName, $wiki );
		$table2 = $names->get( $tableName, $wiki2 );
		$this->assertSame( $table, $table2 );
		$this->assertInstanceOf( NameTableStore::class, $table );
	}

	/*
	 * The next three integration tests verify that the schema information is correct by loading
	 * the relevant information from the database.
	 */

	public function testIntegratedGetChangeTagDef() {
		$services = $this->getServiceContainer();
		$factory = $services->getNameTableStoreFactory();
		$store = $factory->getChangeTagDef();
		$this->assertIsArray( $store->getMap() );
	}

	public function testIntegratedGetContentModels() {
		$services = $this->getServiceContainer();
		$factory = $services->getNameTableStoreFactory();
		$store = $factory->getContentModels();
		$this->assertIsArray( $store->getMap() );
	}

	public function testIntegratedGetSlotRoles() {
		$services = $this->getServiceContainer();
		$factory = $services->getNameTableStoreFactory();
		$store = $factory->getSlotRoles();
		$this->assertIsArray( $store->getMap() );
	}
}
