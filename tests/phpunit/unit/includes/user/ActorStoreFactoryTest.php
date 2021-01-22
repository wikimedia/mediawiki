<?php

namespace MediaWiki\Tests\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
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
 * @package MediaWiki\Tests\User
 */
class ActorStoreFactoryTest extends MediaWikiUnitTestCase {

	public function provideGetActorStore() {
		yield 'local, no shared' => [
			false, // $domain,
			[
				'SharedDB' => false,
				'SharedTables' => false
			], // $config
			false, // $expectedDomain
		];
		yield 'foreign, no shared' => [
			'acmewiki', // $domain,
			[
				'SharedDB' => false,
				'SharedTables' => false
			], //
			'acmewiki', // $expectedDomain
		];
		yield 'local, shared' => [
			false, // $domain,
			[
				'SharedDB' => [ 'sharedwiki' ],
				'SharedTables' => [ 'user', 'actor' ]
			], // $config
			false, // $expectedDomain
		];
		yield 'foreign, shared' => [
			'acmewiki', // $domain,
			[
				'SharedDB' => [ 'sharedwiki' ],
				'SharedTables' => [ 'user', 'actor' ]
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
			new NullLogger()
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
			new NullLogger()
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
			new NullLogger()
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
		$mock = $this->getMockBuilder( ILBFactory::class )
			->disableOriginalConstructor()->getMock();

		$mock->method( 'getMainLB' )
			->willReturnCallback( function ( $domain ) use ( $expectDomain ) {
				$this->assertSame( $expectDomain, $domain );
				return $this->getMockBuilder( ILoadBalancer::class )
					->disableOriginalConstructor()->getMock();
			} );

		return $mock;
	}
}
