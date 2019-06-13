<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Storage\BlobStoreFactory
 */
class BlobStoreFactoryTest extends \MediaWikiUnitTestCase {

	/** @var LBFactory|\PHPUnit_Framework_MockObject_MockObject $lbFactoryMock */
	private $lbFactoryMock;

	protected function setUp() {
		parent::setUp();

		$this->lbFactoryMock = $this->createMock( LBFactory::class );

		$lbFactoryMockProvider = function (): LBFactory {
			return $this->lbFactoryMock;
		};

		$this->overrideMwServices( [ 'DBLoadBalancerFactory' => $lbFactoryMockProvider ] );
	}

	public function provideWikiIds() {
		yield [ false ];
		yield [ 'someWiki' ];
	}

	/**
	 * @dataProvider provideWikiIds
	 */
	public function testNewBlobStore( $wikiId ) {
		$this->lbFactoryMock->expects( $this->any() )
			->method( 'getMainLB' )
			->with( $wikiId )
			->willReturn( $this->createMock( \LoadBalancer::class ) );

		$factory = MediaWikiServices::getInstance()->getBlobStoreFactory();
		$store = $factory->newBlobStore( $wikiId );
		$this->assertInstanceOf( BlobStore::class, $store );

		// This only works as we currently know this is a SqlBlobStore object
		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $wikiId, $wrapper->wikiId );
	}

	/**
	 * @dataProvider provideWikiIds
	 */
	public function testNewSqlBlobStore( $wikiId ) {
		$this->lbFactoryMock->expects( $this->any() )
			->method( 'getMainLB' )
			->with( $wikiId )
			->willReturn( $this->createMock( \LoadBalancer::class ) );

		$factory = MediaWikiServices::getInstance()->getBlobStoreFactory();
		$store = $factory->newSqlBlobStore( $wikiId );
		$this->assertInstanceOf( SqlBlobStore::class, $store );

		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $wikiId, $wrapper->wikiId );
	}
}
