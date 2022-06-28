<?php

namespace MediaWiki\Tests\Integration\Permissions;

use CentralIdLookup;
use HashBagOStuff;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @coversDefaultClass \MediaWiki\Permissions\RateLimiter
 */
class RateLimiterTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return MockObject|CentralIdLookup
	 */
	private function getMockContralIdProvider() {
		$mockCentralIdLookup = $this->createNoOpMock(
			CentralIdLookup::class,
			[ 'centralIdFromLocalUser', 'getProviderId' ]
		);

		$mockCentralIdLookup->method( 'centralIdFromLocalUser' )
			->willReturnCallback( static function ( UserIdentity $user ) {
				return $user->getId() % 100;
			} );
		$mockCentralIdLookup->method( 'getProviderId' )
			->willReturn( 'test' );

		return $mockCentralIdLookup;
	}

	/**
	 * @covers User::pingLimiter
	 */
	public function testPingLimiterGlobal() {
		$limits = [
			'edit' => [
				'anon' => [ 1, 60 ],
			],
			'purge' => [
				'ip' => [ 1, 60 ],
				'subnet' => [ 1, 60 ],
			],
			'rollback' => [
				'user' => [ 1, 60 ],
			],
			'move' => [
				'user-global' => [ 1, 60 ],
			],
			'delete' => [
				'ip-all' => [ 1, 60 ],
				'subnet-all' => [ 1, 60 ],
			],
		];

		// Set up a fake cache for storing limits
		$cache = new HashBagOStuff( [ 'keyspace' => 'xwiki' ] );

		$cacheAccess = TestingAccessWrapper::newFromObject( $cache );
		$cacheAccess->keyspace = 'xwiki';

		$services = $this->getServiceContainer();

		$limiter = new RateLimiter(
			new ServiceOptions( RateLimiter::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::RateLimits => $limits,
				MainConfigNames::RateLimitsExcludedIPs => [], // TODO
			] ),
			$cache,
			$this->getMockContralIdProvider(),
			$services->getUserFactory(),
			$services->getUserGroupManager(),
			$services->getHookContainer()
		);

		// Set up some fake users
		$anon1 = $this->newFakeAnon( '1.2.3.4' );
		$anon2 = $this->newFakeAnon( '1.2.3.8' );
		$anon3 = $this->newFakeAnon( '6.7.8.9' );
		$anon4 = $this->newFakeAnon( '6.7.8.1' );

		// The mock ContralIdProvider uses the local id MOD 10 as the global ID.
		// So Frank has global ID 11, and Jane has global ID 56.
		// Kara's global ID is 0, which means no global ID.
		$frankX1 = $this->newFakeUser( 'Frank', '1.2.3.4', 111 );
		$frankX2 = $this->newFakeUser( 'Frank', '1.2.3.8', 111 );
		$frankY1 = $this->newFakeUser( 'Frank', '1.2.3.4', 211 );
		$janeX1 = $this->newFakeUser( 'Jane', '1.2.3.4', 456 );
		$janeX3 = $this->newFakeUser( 'Jane', '6.7.8.9', 456 );
		$janeY1 = $this->newFakeUser( 'Jane', '1.2.3.4', 756 );
		$karaX1 = $this->newFakeUser( 'Kara', '5.5.5.5', 100 );
		$karaY1 = $this->newFakeUser( 'Kara', '5.5.5.5', 200 );

		// Test limits on wiki X
		$this->assertFalse( $limiter->limit( $anon1, 'edit' ), 'First anon edit' );
		$this->assertTrue( $limiter->limit( $anon2, 'edit' ), 'Second anon edit' );

		$this->assertFalse( $limiter->limit( $anon1, 'purge' ), 'Anon purge' );
		$this->assertTrue( $limiter->limit( $anon1, 'purge' ), 'Anon purge via same IP' );

		$this->assertFalse( $limiter->limit( $anon3, 'purge' ), 'Anon purge via different subnet' );
		$this->assertTrue( $limiter->limit( $anon2, 'purge' ), 'Anon purge via same subnet' );

		$this->assertFalse( $limiter->limit( $frankX1, 'rollback' ), 'First rollback' );
		$this->assertTrue( $limiter->limit( $frankX2, 'rollback' ), 'Second rollback via different IP' );
		$this->assertFalse( $limiter->limit( $janeX1, 'rollback' ), 'Rlbk by different user, same IP' );

		$this->assertFalse( $limiter->limit( $frankX1, 'move' ), 'First move' );
		$this->assertTrue( $limiter->limit( $frankX2, 'move' ), 'Second move via different IP' );
		$this->assertFalse( $limiter->limit( $janeX1, 'move' ), 'Move by different user, same IP' );
		$this->assertFalse( $limiter->limit( $karaX1, 'move' ), 'Move by another user' );
		$this->assertTrue( $limiter->limit( $karaX1, 'move' ), 'Second move by another user' );

		$this->assertFalse( $limiter->limit( $frankX1, 'delete' ), 'First delete' );
		$this->assertTrue( $limiter->limit( $janeX1, 'delete' ), 'Delete via same IP' );

		$this->assertTrue( $limiter->limit( $frankX2, 'delete' ), 'Delete via same subnet' );
		$this->assertFalse( $limiter->limit( $janeX3, 'delete' ), 'Delete via different subnet' );

		// Now test how limits carry over to wiki Y
		$cacheAccess->keyspace = 'ywiki';

		$this->assertFalse( $limiter->limit( $anon3, 'edit' ), 'Anon edit on wiki Y' );
		$this->assertTrue( $limiter->limit( $anon4, 'purge' ), 'Anon purge on wiki Y, same subnet' );
		$this->assertFalse( $limiter->limit( $frankY1, 'rollback' ), 'Rollback on wiki Y, same name' );
		$this->assertTrue( $limiter->limit( $frankY1, 'move' ), 'Move on wiki Y, same name' );
		$this->assertTrue( $limiter->limit( $janeY1, 'move' ), 'Move on wiki Y, different user' );
		$this->assertTrue( $limiter->limit( $frankY1, 'delete' ), 'Delete on wiki Y, same IP' );

		// For a user without a global ID, user-global acts as a local restriction
		$this->assertFalse( $limiter->limit( $karaY1, 'move' ), 'Move by another user' );
		$this->assertTrue( $limiter->limit( $karaY1, 'move' ), 'Second move by another user' );
	}

	private function newFakeAnon( string $ip ) {
		return new RateLimitSubject(
			new UserIdentityValue( 0, $ip ),
			$ip,
			[ RateLimitSubject::NEWBIE => true ] // TODO
		);
	}

	private function newFakeUser( string $name, string $ip, int $id ) {
		return new RateLimitSubject(
			new UserIdentityValue( $id, $name ),
			$ip,
			[] // TODO
		);
	}

}
