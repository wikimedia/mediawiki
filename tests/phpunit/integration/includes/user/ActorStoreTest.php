<?php

namespace MediaWiki\Tests\User;

use CannotCreateActorException;
use IDatabase;
use InvalidArgumentException;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\ActorStore;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use stdClass;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers \MediaWiki\User\ActorStore
 * @coversDefaultClass \MediaWiki\User\ActorStore
 * @group Database
 */
class ActorStoreTest extends MediaWikiIntegrationTestCase {

	private const IP = '2600:1004:B14A:5DDD:3EBE:BBA4:BFBA:F37E';

	public function addDBData() {
		$this->tablesUsed[] = 'actor';

		// Registered
		$this->assertTrue( $this->db->insert(
			'actor',
			[ 'actor_id' => '42', 'actor_user' => '24', 'actor_name' => 'TestUser' ],
			__METHOD__,
			[ 'IGNORE' ]
		) );

		// Anon
		$this->assertTrue( $this->db->insert(
			'actor',
			[ 'actor_id' => '43', 'actor_user' => null, 'actor_name' => self::IP ],
			__METHOD__,
			[ 'IGNORE' ]
		) );
	}

	/**
	 * @param string|false $wikiId
	 * @return ActorStore
	 */
	private function getStore( $wikiId = UserIdentity::LOCAL ) : ActorStore {
		return $this->getServiceContainer()->getActorStoreFactory()->getActorStore( $wikiId );
	}

	/**
	 * Execute the $callback passing it an ActorStore for $wikiId,
	 * making sure no queries are made to local DB.
	 * @param string|false $wikiId
	 * @param callable $callback ( ActorStore $store, IDatababase $db )
	 */
	private function executeWithForeignStore( $wikiId, callable $callback ) {
		$dbLoadBalancer = $this->getServiceContainer()->getDBLoadBalancer();
		$dbLoadBalancer->setDomainAliases( [ $wikiId => $dbLoadBalancer->getLocalDomainID() ] );

		$foreignLB = $this->getServiceContainer()
			->getDBLoadBalancerFactory()
			->getMainLB( $wikiId );
		$foreignLB->setDomainAliases( [ $wikiId => $dbLoadBalancer->getLocalDomainID() ] );
		$foreignDB = $foreignLB->getConnectionRef( DB_MASTER );

		$store = new ActorStore(
			$dbLoadBalancer,
			$this->getServiceContainer()->getUserNameUtils(),
			new NullLogger(),
			$wikiId
		);

		// Redefine the DBLoadBalancer service to verify we don't attempt to resolve its IDs via wfGetDB()
		$localLoadBalancerMock = $this->createNoOpMock( ILoadBalancer::class );
		try {
			$this->setService( 'DBLoadBalancer', $localLoadBalancerMock );
			$callback( $store, $foreignDB );
		} finally {
			// Restore the original loadBalancer.
			$this->setService( 'DBLoadBalancer', $dbLoadBalancer );
		}
	}

	/**
	 * Check whether two actors are the same in the context of $wikiId
	 * @param UserIdentity $expected
	 * @param UserIdentity $actor
	 * @param string|false $wikiId
	 */
	private function assertSameActors( UserIdentity $expected, UserIdentity $actor, $wikiId = UserIdentity::LOCAL ) {
		$actor->assertWiki( $wikiId );
		$this->assertSame( $expected->getActorId( $wikiId ), $actor->getActorId( $wikiId ) );
		$this->assertSame( $expected->getUserId( $wikiId ), $actor->getUserId( $wikiId ) );
		$this->assertSame( $expected->getName(), $actor->getName() );
		$this->assertSame( $expected->getWikiId(), $actor->getWikiId() );
	}

	public function provideGetActorByMethods() {
		yield 'getActorById, registered' => [
			'getActorById', // $method
			42, // $argument
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'getActorById, anon' => [
			'getActorById', // $method
			43, // $argument
			new UserIdentityValue( 0, self::IP, 43 ), // $expected
		];
		yield 'getActorById, non-existent' => [
			'getActorById', // $method
			4321231, // $argument
			null, // $expected
		];
		yield 'getUserIdentityByName, registered' => [
			'getUserIdentityByName', // $method
			'TestUser', // $argument
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'getUserIdentityByName, non-existent' => [
			'getUserIdentityByName', // $method
			'TestUser_I_Do_Not_Exist', // $argument
			null, // $expected
		];
		yield 'getUserIdentityByName, anon' => [
			'getUserIdentityByName', // $method
			self::IP, // $argument
			new UserIdentityValue( 0, self::IP, 43 ), // $expected
		];
		yield 'getUserIdentityByName, anon, non-canonicalized IP' => [
			'getUserIdentityByName', // $method
			strtolower( self::IP ), // $argument
			new UserIdentityValue( 0, self::IP, 43 ), // $expected
		];
		yield 'getUserIdentityByUserId, registered' => [
			'getUserIdentityByUserId', // $method
			24, // $argument
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'getUserIdentityByUserId, non-exitent' => [
			'getUserIdentityByUserId', // $method
			2412312, // $argument
			null, // $expected
		];
	}

	/**
	 * @dataProvider provideGetActorByMethods
	 * @covers ::getActorById
	 * @covers ::getUserIdentityByName
	 * @covers ::getUserIdentityByUserId
	 */
	public function testGetActorByMethods( string $method, $argument, ?UserIdentity $expected ) {
		$actor = $this->getStore()->$method( $argument );
		if ( $expected ) {
			$this->assertNotNull( $actor );
			$this->assertSameActors( $expected, $actor );
		} else {
			$this->assertNull( $actor );
		}
	}

	public function providegetUserIdentityByAnyId() {
		yield 'by name' => [
			24, // $userId
			null, // $name
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'by user, name wrong' => [
			24, // $userId
			'TestUser_blblblba', // $name
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'by name, user nonexistent' => [
			141312, // $userId
			'TestUser', // $name
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'non-existent' => [
			2423121, // $userId
			'TestUser_blblblba', // $name
			null, // $expected
		];
	}

	/**
	 * @dataProvider providegetUserIdentityByAnyId
	 * @covers ::getUserIdentityByAnyId
	 */
	public function testgetUserIdentityByAnyId( $userId, $name, ?UserIdentity $expected ) {
		$actor = $this->getStore()->getUserIdentityByAnyId( $userId, $name );
		if ( $expected ) {
			$this->assertNotNull( $actor );
			$this->assertSameActors( $expected, $actor );
		} else {
			$this->assertNull( $actor );
		}
	}

	/**
	 * @covers ::getActorById
	 */
	public function testGetActorByIdRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getActorById( $user->getActorId() );
		$this->assertSameActors( $user, $actor );
	}

	/**
	 * @covers ::getUserIdentityByName
	 */
	public function testgetUserIdentityByNameRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getUserIdentityByName( $user->getName() );
		$this->assertSameActors( $user, $actor );
	}

	/**
	 * @covers ::getUserIdentityByUserId
	 */
	public function testgetUserIdentityByUserIdRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getUserIdentityByUserId( $user->getUserId() );
		$this->assertSameActors( $user, $actor );
	}

	public function provideNewActorFromRow() {
		yield 'full row' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => 42, 'actor_name' => 'TestUser', 'actor_user' => 24 ], // $row
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'full row, strings' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '42', 'actor_name' => 'TestUser', 'actor_user' => '24' ], // $row
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'null user' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '42', 'actor_name' => 'TestUser', 'actor_user' => null ], // $row
			new UserIdentityValue( 0, 'TestUser', 42 ), // $expected
		];
		yield 'zero user' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '42', 'actor_name' => 'TestUser', 'actor_user' => 0 ], // $row
			new UserIdentityValue( 0, 'TestUser', 42 ), // $expected
		];
		yield 'cross-wiki' => [
			'acmewiki', // $wikiId
			(object)[ 'actor_id' => 42, 'actor_name' => 'TestUser', 'actor_user' => 24 ], // $row
			new UserIdentityValue( 24, 'TestUser', 42, 'acmewiki' ), // $expected
		];
	}

	/**
	 * @dataProvider provideNewActorFromRow
	 * @covers ::newActorFromRow
	 */
	public function testNewActorFromRow( $wikiId, stdClass $row, UserIdentity $expected ) {
		$actor = $this->getStore( $wikiId )->newActorFromRow( $row );
		$this->assertSameActors( $expected, $actor, $wikiId );
	}

	public function provideNewActorFromRow_exception() {
		yield 'null actor' => [
			(object)[ 'actor_id' => '0', 'actor_name' => 'TestUser', 'actor_user' => 0 ], // $row
		];
		yield 'zero actor' => [
			(object)[ 'actor_id' => null, 'actor_name' => 'TestUser', 'actor_user' => 0 ], // $row
		];
	}

	/**
	 * @dataProvider provideNewActorFromRow_exception
	 * @covers ::newActorFromRow
	 */
	public function testNewActorFromRow_exception( stdClass $row ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore()->newActorFromRow( $row );
	}

	public function provideNewActorFromRowFields() {
		yield 'full row' => [
			UserIdentity::LOCAL, // $wikiId
			42, // $actorId
			'TestUser', // $name
			24, // $userId
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'full row, strings' => [
			UserIdentity::LOCAL, // $wikiId
			'42', // $actorId
			'TestUser', // $name
			'24', // $userId
			new UserIdentityValue( 24, 'TestUser', 42 ), // $expected
		];
		yield 'null user' => [
			UserIdentity::LOCAL, // $wikiId
			42, // $actorId
			'TestUser', // $name
			null, // $userId
			new UserIdentityValue( 0, 'TestUser', 42 ), // $expected
		];
		yield 'zero user' => [
			UserIdentity::LOCAL, // $wikiId
			42, // $actorId
			'TestUser', // $name
			0, // $userId
			new UserIdentityValue( 0, 'TestUser', 42 ), // $expected
		];
		yield 'cross-wiki' => [
			'acmewiki', // $wikiId
			42, // $actorId
			'TestUser', // $name
			0, // $userId
			new UserIdentityValue( 0, 'TestUser', 42, 'acmewiki' ), // $expected
		];
	}

	/**
	 * @dataProvider provideNewActorFromRowFields
	 * @covers ::newActorFromRowFields
	 */
	public function testNewActorFromRowFields( $wikiId, $actorId, $name, $userId, UserIdentity $expected ) {
		$actor = $this->getStore( $wikiId )->newActorFromRowFields( $actorId, $name, $userId );
		$this->assertSameActors( $expected, $actor, $wikiId );
	}

	public function provideNewActorFromRowFields_exception() {
		yield 'empty name' => [
			42, // $actorId
			'', // $name
			24, // $userId
		];
		yield 'null name' => [
			42, // $actorId
			null, // $name
			24, // $userId
		];
		yield 'null actor' => [
			null, // $actorId
			'TestUser', // $name
			null, // $userId
		];
		yield 'zero actor' => [
			0, // $actorId
			'TestUser', // $name
			null, // $userId
		];
	}

	/**
	 * @dataProvider provideNewActorFromRowFields_exception
	 * @covers ::newActorFromRowFields
	 */
	public function testNewActorFromRowFields_exception( $actorId, $name, $userId ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore()->newActorFromRowFields( $actorId, $name, $userId );
	}

	public function provideFindActorId() {
		yield 'anon, local' => [
			function () {
				return new UserIdentityValue( 0, self::IP, 0 );
			}, // $actorCallback
			43,  // $expected
		];
		yield 'anon, non-canonical, local' => [
			function () {
				return new UserIdentityValue( 0, strtolower( self::IP ), 0 );
			}, // $actorCallback
			43,  // $expected
		];
		yield 'registered, local' => [
			function () {
				return new UserIdentityValue( 24, 'TestUser', 0 );
			}, // $actorCallback
			42, // $expected
		];
		yield 'anon, non-existent, local' => [
			function () {
				return new UserIdentityValue( 0, '127.1.2.3', 0 );
			},  // $actorCallback
			null, // $expected
		];
		yield 'registered, non-existent, local' => [
			function () {
				return new UserIdentityValue( 51, 'DoNotExist', 0 );
			}, // $actorCallback
			null, // $expected
		];
		yield 'anon User, local' => [
			function ( MediaWikiServices $serviceContainer ) {
				return $serviceContainer->getUserFactory()->newAnonymous( self::IP );
			}, // $actorCallback
			43, // $expected
		];
		yield 'anon User, non-canonical, local' => [
			function ( MediaWikiServices $serviceContainer ) {
				return $serviceContainer->getUserFactory()->newAnonymous( strtolower( self::IP ) );
			}, // $actorCallback
			43, // $expected
		];
		yield 'anon User, non-existent, local' => [
			function ( MediaWikiServices $serviceContainer ) {
				return $serviceContainer->getUserFactory()->newAnonymous( '127.1.2.3' );
			},  // $actorCallback
			null, // $expected
		];
		yield 'anon, foreign' => [
			function () {
				return new UserIdentityValue( 0, self::IP, 0, 'acmewiki' );
			}, // $actorCallback
			43, // $expected
			'acmewiki', // $wikiId
		];
		yield 'registered, foreign' => [
			function () {
				return new UserIdentityValue( 24, 'TestUser', 0, 'acmewiki' );
			}, // $actorCallback
			42, // $expected
			'acmewiki', // $wikiId
		];
	}

	/**
	 * @dataProvider provideFindActorId
	 * @covers ::findActorId
	 */
	public function testFindActorId( callable $actorCallback, $expected, $wikiId = WikiAwareEntity::LOCAL ) {
		$actor = $actorCallback( $this->getServiceContainer() );
		if ( $wikiId ) {
			$this->executeWithForeignStore(
				$wikiId,
				function ( ActorStore $store ) use ( $expected, $actor ) {
					$this->assertSame( $expected, $store->findActorId( $actor ) );
					$this->assertSame( $expected ?: 0, $actor->getActorId( $actor->getWikiId() ) );
				}
			);
		} else {
			$this->assertSame( $expected, $this->getStore()->findActorId( $actor ) );
			$this->assertSame( $expected ?: 0, $actor->getActorId( $actor->getWikiId() ) );
		}
	}

	/**
	 * @covers ::findActorId
	 */
	public function testFindActorId_wikiMismatch() {
		$this->markTestSkipped();
		$this->expectException( PreconditionException::class );
		$this->getStore()->findActorId(
			new UserIdentityValue( 0, self::IP, 0, 'acmewiki' )
		);
	}

	public function provideAcquireActorId() {
		yield 'anon' => [ function () {
			return new UserIdentityValue( 0, '127.3.2.1', 0 );
		} ];
		yield 'registered' => [ function () {
			return new UserIdentityValue( 15, 'MyUser', 0 );
		} ];
		yield 'User object' => [ function ( $serviceContainer ) {
			return $serviceContainer->getUserFactory()->newAnonymous( '127.4.3.2' );
		} ];
	}

	/**
	 * @dataProvider provideAcquireActorId
	 * @covers ::acquireActorId
	 */
	public function testAcquireActorId( callable $userCallback ) {
		$user = $userCallback( $this->getServiceContainer() );
		$actorId = $this->getStore()->acquireActorId( $user );
		$this->assertTrue( $actorId > 0 );
		$this->assertSame( $actorId, $user->getActorId() );
	}

	public function provideAcquireActorId_foreign() {
		yield 'anon' => [ function () {
			return new UserIdentityValue( 0, '127.3.2.1', 0, 'acmewiki' );
		} ];
		yield 'registered' => [ function () {
			return new UserIdentityValue( 15, 'MyUser', 0, 'acmewiki' );
		} ];
		// This is backwards-compatibility test case, this can be removed when we deprecate
		// and drop support for passing User object with foreign DB connections.
		yield 'User object' => [ function ( $serviceContainer ) {
			return $serviceContainer->getUserFactory()->newAnonymous( '127.4.3.2' );
		} ];
	}

	/**
	 * @dataProvider provideAcquireActorId_foreign
	 * @covers ::acquireActorId
	 */
	public function testAcquireActorId_foreign( callable $userCallback ) {
		$user = $userCallback( $this->getServiceContainer() );
		$this->executeWithForeignStore(
			'acmewiki',
			function ( ActorStore $store ) use ( $user ) {
				$actorId = $store->acquireActorId( $user );
				$this->assertTrue( $actorId > 0 );
				$this->assertSame( $actorId, $user->getActorId( $user->getWikiId() ) );
			}
		);
	}

	/**
	 * @dataProvider provideAcquireActorId_foreign
	 * @covers ::acquireActorId
	 */
	public function testAcquireActorId_foreign_withDBConnection( callable $userCallback ) {
		$user = $userCallback( $this->getServiceContainer() );
		$this->executeWithForeignStore(
			'acmewiki',
			function ( ActorStore $store, IDatabase $dbw ) use ( $user ) {
				$actorId = $store->acquireActorId( $user, $dbw );
				$this->assertTrue( $actorId > 0 );
				$this->assertSame( $actorId, $user->getActorId( $user->getWikiId() ) );
			}
		);
	}

	public function provideAcquireActorId_canNotCreate() {
		yield 'usable name' => [ new UserIdentityValue( 0, 'MyFancyUser', 0 ) ];
		yield 'empty name' => [ new UserIdentityValue( 15, '', 0 ) ];
	}

	/**
	 * @dataProvider provideAcquireActorId_canNotCreate
	 * @covers ::acquireActorId
	 */
	public function testAcquireActorId_canNotCreate( UserIdentityValue $actor ) {
		$this->expectException( CannotCreateActorException::class );
		$this->getStore()->acquireActorId( $actor );
	}

	public function provideAcquireActorId_existing() {
		yield 'anon' => [
			new UserIdentityValue( 0, self::IP, 43 ), // $actor
			43, // $expected
		];
		yield 'registered' => [
			new UserIdentityValue( 24, 'TestUser', 42 ), // $actor
			42, // $expected
		];
	}

	/**
	 * @dataProvider provideAcquireActorId_existing
	 * @covers ::acquireActorId
	 */
	public function testAcquireActorId_existing( UserIdentityValue $actor, int $expected ) {
		$this->assertSame( $expected, $this->getStore()->acquireActorId( $actor ) );
	}

	/**
	 * @covers ::acquireActorId
	 */
	public function testAcquireActorId_wikiMismatch() {
		$this->markTestSkipped();
		$this->expectException( PreconditionException::class );
		$this->getStore()->acquireActorId(
			new UserIdentityValue( 0, self::IP, 0, 'acmewiki' )
		);
	}
}
