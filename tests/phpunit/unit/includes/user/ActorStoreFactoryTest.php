<?php

namespace MediaWiki\Tests\User;

use MediaWiki\Block\HideUserUtils;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers \MediaWiki\User\ActorStoreFactory
 * @covers \MediaWiki\User\ActorStore
 */
class ActorStoreFactoryTest extends MediaWikiUnitTestCase {

	public static function provideGetActorStore() {
		yield 'local, no shared' => [
			false, // $domain,
			[
				MainConfigNames::SharedDB => false,
				MainConfigNames::SharedTables => false
			], // $config
			false, // $expectedDomain
		];
		yield 'foreign, no shared' => [
			'acmewiki', // $domain,
			[
				MainConfigNames::SharedDB => false,
				MainConfigNames::SharedTables => false
			], //
			'acmewiki', // $expectedDomain
		];
		yield 'local, shared' => [
			false, // $domain,
			[
				MainConfigNames::SharedDB => [ 'sharedwiki' ],
				MainConfigNames::SharedTables => [ 'user', 'actor' ]
			], // $config
			false, // $expectedDomain
		];
		yield 'foreign, shared' => [
			'acmewiki', // $domain,
			[
				MainConfigNames::SharedDB => [ 'sharedwiki' ],
				MainConfigNames::SharedTables => [ 'user', 'actor' ]
			], // $config
			false, // $expectedDomain
		];
	}

	/**
	 * @dataProvider provideGetActorStore
	 */
	public function testGetActorStore( $domain, array $config, $expectedDomain ) {
		$factory = new ActorStoreFactory(
			new ServiceOptions( ActorStoreFactory::CONSTRUCTOR_OPTIONS, $config ),
			$this->getMockLoadBalancerFactory( $expectedDomain ),
			$this->createNoOpMock( UserNameUtils::class ),
			$this->createMock( TempUserConfig::class ),
			new NullLogger(),
			new HideUserUtils()
		);
		$notFromCache = $factory->getActorStore( $domain );
		$this->assertInstanceOf( ActorStore::class, $notFromCache );
		$this->assertSame( $notFromCache, $factory->getActorStore( $domain ) );
	}

	/**
	 * @dataProvider provideGetActorStore
	 */
	public function testGetActorNormalization( $domain, array $config, $expectedDomain ) {
		$factory = new ActorStoreFactory(
			new ServiceOptions( ActorStoreFactory::CONSTRUCTOR_OPTIONS, $config ),
			$this->getMockLoadBalancerFactory( $expectedDomain ),
			$this->createNoOpMock( UserNameUtils::class ),
			$this->createMock( TempUserConfig::class ),
			new NullLogger(),
			$this->createNoOpMock( HideUserUtils::class )
		);
		$notFromCache = $factory->getActorNormalization( $domain );
		$this->assertInstanceOf( ActorNormalization::class, $notFromCache );
		$this->assertSame( $notFromCache, $factory->getActorNormalization( $domain ) );
	}

	/**
	 * @dataProvider provideGetActorStore
	 */
	public function testGetUserIdentityLookup( $domain, array $config, $expectedDomain ) {
		$factory = new ActorStoreFactory(
			new ServiceOptions( ActorStoreFactory::CONSTRUCTOR_OPTIONS, $config ),
			$this->getMockLoadBalancerFactory( $expectedDomain ),
			$this->createNoOpMock( UserNameUtils::class ),
			$this->createMock( TempUserConfig::class ),
			new NullLogger(),
			$this->createNoOpMock( HideUserUtils::class )
		);
		$notFromCache = $factory->getUserIdentityLookup( $domain );
		$this->assertInstanceOf( UserIdentityLookup::class, $notFromCache );
		$this->assertSame( $notFromCache, $factory->getUserIdentityLookup( $domain ) );
	}

	/**
	 * @param string|false $expectDomain
	 * @return MockObject|ILBFactory
	 */
	private function getMockLoadBalancerFactory( $expectDomain ) {
		$mock = $this->createMock( ILBFactory::class );

		$mock->method( 'getMainLB' )
			->willReturnCallback( function ( $domain ) use ( $expectDomain ) {
				$this->assertSame( $expectDomain, $domain );
				return $this->createMock( ILoadBalancer::class );
			} );

		return $mock;
	}
}
