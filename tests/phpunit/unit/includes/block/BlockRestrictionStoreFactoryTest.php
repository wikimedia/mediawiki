<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockRestrictionStoreFactory;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers \MediaWiki\Block\BlockRestrictionStoreFactory
 */
class BlockRestrictionStoreFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideDomains
	 */
	public function testGetBlockRestrictionStore( $domain ) {
		$lb = $this->createMock( LoadBalancer::class );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory
			->method( 'getMainLB' )
			->with( $domain )
			->willReturn( $lb );
		$factory = new BlockRestrictionStoreFactory( $lbFactory );

		$restrictionStore = $factory->getBlockRestrictionStore( $domain );
		$this->assertInstanceOf( BlockRestrictionStore::class, $restrictionStore );
	}

	public static function provideDomains() {
		yield 'local wiki' => [ WikiAwareEntity::LOCAL ];
		yield 'foreign wiki' => [ 'meta' ];
	}

}
