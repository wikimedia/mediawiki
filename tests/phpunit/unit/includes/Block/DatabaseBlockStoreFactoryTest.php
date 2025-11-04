<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\AutoblockExemptionList;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockRestrictionStoreFactory;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\CrossWikiBlockTargetFactory;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\DatabaseBlockStoreFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Session\SessionManager;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @covers \MediaWiki\Block\DatabaseBlockStoreFactory
 */
class DatabaseBlockStoreFactoryTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/**
	 * @dataProvider provideDomains
	 */
	public function testGetDatabaseBlockStore( $domain ) {
		$lbFactory = $this->getDummyDBLoadBalancerFactory();

		$blockRestrictionStore = $this->createMock( BlockRestrictionStore::class );
		$blockRestrictionStoreFactory = $this->createMock(
			BlockRestrictionStoreFactory::class
		);
		$blockRestrictionStoreFactory
			->method( 'getBlockRestrictionStore' )
			->with( $domain )
			->willReturn( $blockRestrictionStore );

		$blockTargetFactory = $this->createNoOpMock( BlockTargetFactory::class );
		$crossWikiBlockTargetFactory = $this->createMock( CrossWikiBlockTargetFactory::class );
		$crossWikiBlockTargetFactory
			->method( 'getFactory' )
			->with( $domain )
			->willReturn( $blockTargetFactory );

		$factory = new DatabaseBlockStoreFactory(
			new ServiceOptions(
				DatabaseBlockStore::CONSTRUCTOR_OPTIONS,
				new HashConfig(
					array_fill_keys( DatabaseBlockStore::CONSTRUCTOR_OPTIONS, null )
				)
			),
			new NullLogger(),
			$this->createMock( ActorStoreFactory::class ),
			$blockRestrictionStoreFactory,
			$this->createMock( CommentStore::class ),
			$this->createMock( HookContainer::class ),
			$lbFactory,
			$this->createMock( ReadOnlyMode::class ),
			$this->createMock( UserFactory::class ),
			$this->createMock( TempUserConfig::class ),
			$crossWikiBlockTargetFactory,
			$this->createMock( AutoblockExemptionList::class ),
			$this->createMock( SessionManager::class )
		);

		$databaseBlockStore = $factory->getDatabaseBlockStore( $domain );
		$this->assertInstanceOf( DatabaseBlockStore::class, $databaseBlockStore );
	}

	public static function provideDomains() {
		yield 'local wiki' => [ WikiAwareEntity::LOCAL ];
		yield 'foreign wiki' => [ 'meta' ];
	}

}
