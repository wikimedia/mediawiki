<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use WANObjectCache;
use Wikimedia\Rdbms\LoadBalancer;

class RevisionStoreTest extends MediaWikiTestCase {

	/**
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param WANObjectCache $WANObjectCache
	 *
	 * @return RevisionStore
	 */
	private function getRevisionStore(
		$loadBalancer = null,
		$blobStore = null,
		$WANObjectCache = null
	) {

		return new RevisionStore(
			$loadBalancer ? $loadBalancer : $this->getMockLoadBalancer(),
			$blobStore ? $blobStore : $this->getMockSqlBlobStore(),
			$WANObjectCache ? $WANObjectCache : $this->getHashWANObjectCache()
		);
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer() {
		return $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|SqlBlobStore
	 */
	private function getMockSqlBlobStore() {
		return $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()
			->getMock();
	}

	private function getHashWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] );
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::getContentHandlerUseDB
	 * @covers \MediaWiki\Storage\RevisionStore::setContentHandlerUseDB
	 */
	public function testGetSetContentHandlerDb() {
		$store = $this->getRevisionStore();
		$this->assertTrue( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( false );
		$this->assertFalse( $store->getContentHandlerUseDB() );
		$store->setContentHandlerUseDB( true );
		$this->assertTrue( $store->getContentHandlerUseDB() );
	}

	public function testGetQueryInfo() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetArchiveQueryInfo() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

}
