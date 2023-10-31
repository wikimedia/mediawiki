<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\AutoblockExemptionList;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockRestrictionStoreFactory;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\DatabaseBlockStoreFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWikiUnitTestCase;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @covers \MediaWiki\Block\DatabaseBlockStoreFactory
 */
class DatabaseBlockStoreFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideDomains
	 */
	public function testGetDatabaseBlockStore( $domain ) {
		$lb = $this->createMock( LoadBalancer::class );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory
			->method( 'getMainLB' )
			->with( $domain )
			->willReturn( $lb );

		$blockRestrictionStore = $this->createMock( BlockRestrictionStore::class );
		$blockRestrictionStoreFactory = $this->createMock(
			BlockRestrictionStoreFactory::class
		);
		$blockRestrictionStoreFactory
			->method( 'getBlockRestrictionStore' )
			->with( $domain )
			->willReturn( $blockRestrictionStore );

		$factory = new DatabaseBlockStoreFactory(
			$this->createMock( ServiceOptions::class ),
			$this->createMock( LoggerInterface::class ),
			$this->createMock( ActorStoreFactory::class ),
			$blockRestrictionStoreFactory,
			$this->createMock( CommentStore::class ),
			$this->createMock( HookContainer::class ),
			$lbFactory,
			$this->createMock( ReadOnlyMode::class ),
			$this->createMock( UserFactory::class ),
			$this->createMock( TempUserConfig::class ),
			$this->createMock( BlockUtils::class ),
			$this->createMock( AutoblockExemptionList::class )
		);

		$databaseBlockStore = $factory->getDatabaseBlockStore( $domain );
		$this->assertInstanceOf( DatabaseBlockStore::class, $databaseBlockStore );
	}

	public static function provideDomains() {
		yield 'local wiki' => [ WikiAwareEntity::LOCAL ];
		yield 'foreign wiki' => [ 'meta' ];
	}

}
