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
use MediaWiki\User\UserNameUtils;
use stdClass;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\User\ActorStore
 * @coversDefaultClass \MediaWiki\User\ActorStore
 * @group Database
 */
class ActorStoreTest extends ActorStoreTestBase {

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
		yield 'getActorById, zero' => [
			'getActorById', // $method
			0, // $argument
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
		yield 'getUserIdentityByUserId, zero' => [
			'getUserIdentityByUserId', // $method
			0, // $argument
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

			// test caching
			$cachedActor = $this->getStore()->$method( $argument );
			$this->assertSame( $actor, $cachedActor );
		} else {
			$this->assertNull( $actor );
		}
	}

	public function provideUserIdentityValues() {
		yield [ new UserIdentityValue( 24, 'TestUser', 42 ) ];
		yield [ new UserIdentityValue( 0, self::IP, 43 ) ];
	}

	/**
	 * should check that select is only called once
	 * @dataProvider provideUserIdentityValues
	 * @covers ::findActorId
	 * @covers ::getActorById
	 * @covers ::getUserIdentityByName
	 * @covers ::getUserIdentityByUserId
	 * @param UserIdentity $expected
	 */
	public function testSequentialCacheRetrieval( UserIdentity $expected ) {
		// ensure UserIdentity is cached
		$actorId = $this->getStore()->findActorId( $expected );
		$this->assertSame( $expected->getActorId(), $actorId );

		$cachedActorId = $this->getStore()->findActorId( $expected );
		$this->assertSame( $actorId, $cachedActorId );

		$cachedActorId = $this->getStore()->acquireActorId( $expected );
		$this->assertSame( $actorId, $cachedActorId );

		$cached = $this->getStore()->getActorById( $actorId );
		$this->assertNotNull( $cached );
		$this->assertSameActors( $expected, $cached );

		$cached = $this->getStore()->getUserIdentityByName( $expected->getName() );
		$this->assertNotNull( $cached );
		$this->assertSameActors( $expected, $cached );

		$userId = $expected->getId();
		if ( $userId ) {
			$cached = $this->getStore()->getUserIdentityByUserId( $userId );
			$this->assertNotNull( $cached );
			$this->assertSameActors( $expected, $cached );
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
	public function testGetUserIdentityByUserIdRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getUserIdentityByUserId( $user->getId() );
		$this->assertSameActors( $user, $actor );
	}

	public function provideGetUserIdentityByName_exception() {
		yield 'empty' => [
			'', // $name
		];
	}

	/**
	 * @dataProvider provideGetUserIdentityByName_exception
	 * @covers ::getUserIdentityByName
	 */
	public function testGetUserIdentityByName_exception( string $name ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore()->getUserIdentityByName( $name );
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
		yield 'empty name' => [
			(object)[ 'actor_id' => '10', 'actor_name' => '', 'actor_user' => 15 ], // $row
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
		$actor = $this->getStore( $wikiId )->newActorFromRowFields( $userId, $name, $actorId );
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
		$this->getStore()->newActorFromRowFields( $userId, $name, $actorId );
	}

	public function provideFindActorId() {
		yield 'anon, local' => [
			static function () {
				return new UserIdentityValue( 0, self::IP, 0 );
			}, // $actorCallback
			43,  // $expected
		];
		yield 'anon, non-canonical, local' => [
			static function () {
				return new UserIdentityValue( 0, strtolower( self::IP ), 0 );
			}, // $actorCallback
			43,  // $expected
		];
		yield 'registered, local' => [
			static function () {
				return new UserIdentityValue( 24, 'TestUser', 0 );
			}, // $actorCallback
			42, // $expected
		];
		yield 'anon, non-existent, local' => [
			static function () {
				return new UserIdentityValue( 0, '127.1.2.3', 0 );
			},  // $actorCallback
			null, // $expected
		];
		yield 'registered, non-existent, local' => [
			static function () {
				return new UserIdentityValue( 51, 'DoNotExist', 0 );
			}, // $actorCallback
			null, // $expected
		];
		yield 'external, local' => [
			static function () {
				return new UserIdentityValue( 0, 'acme>TestUser', 45 );
			}, // $actorCallback
			45, // $expected
		];
		yield 'anon User, local' => [
			static function ( MediaWikiServices $serviceContainer ) {
				return $serviceContainer->getUserFactory()->newAnonymous( self::IP );
			}, // $actorCallback
			43, // $expected
		];
		yield 'anon User, non-canonical, local' => [
			static function ( MediaWikiServices $serviceContainer ) {
				return $serviceContainer->getUserFactory()->newAnonymous( strtolower( self::IP ) );
			}, // $actorCallback
			43, // $expected
		];
		yield 'anon User, non-existent, local' => [
			static function ( MediaWikiServices $serviceContainer ) {
				return $serviceContainer->getUserFactory()->newAnonymous( '127.1.2.3' );
			},  // $actorCallback
			null, // $expected
		];
		yield 'anon, foreign' => [
			static function () {
				return new UserIdentityValue( 0, self::IP, 0, 'acmewiki' );
			}, // $actorCallback
			43, // $expected
			'acmewiki', // $wikiId
		];
		yield 'registered, foreign' => [
			static function () {
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

	public function provideFindActorIdByName() {
		yield 'anon' => [
			self::IP, // $actorCallback
			43,  // $expected
		];
		yield 'anon, non-canonical' => [
			strtolower( self::IP ), // $actorCallback
			43,  // $expected
		];
		yield 'registered' => [
			'TestUser', // $actorCallback
			42, // $expected
		];
		yield 'registered, unnormalized' => [
			'testUser', // $actorCallback
			42, // $expected
		];
		yield 'external, local' => [
			'acme>TestUser',
			45, // $expected
		];
		yield 'anon, non-existent' => [
			'127.1.2.3',  // $actorCallback
			null, // $expected
		];
		yield 'registered, non-existent' => [
			'DoNotExist', // $actorCallback
			null, // $expected
		];
		yield 'invalid' => [
			'#', // $actorCallback
			null, // $expected
		];
	}

	/**
	 * @dataProvider provideFindActorIdByName
	 * @covers ::findActorId
	 */
	public function testFindActorIdByName( $name, $expected ) {
		$this->assertSame( $expected, $this->getStore()->findActorIdByName( $name ) );
	}

	public function provideAcquireActorId() {
		yield 'anon' => [ static function () {
			return new UserIdentityValue( 0, '127.3.2.1', 0 );
		} ];
		yield 'registered' => [ static function () {
			return new UserIdentityValue( 15, 'MyUser', 0 );
		} ];
		yield 'User object' => [ static function ( $serviceContainer ) {
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
		yield 'anon' => [ static function () {
			return new UserIdentityValue( 0, '127.3.2.1', 0, 'acmewiki' );
		} ];
		yield 'registered' => [ static function () {
			return new UserIdentityValue( 15, 'MyUser', 0, 'acmewiki' );
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

	public function testAcquireActorId_domain_mismatch() {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore( 'fancywiki' )->acquireActorId(
			new UserIdentityValue( 15, 'Test', 0, 'fancywiki' ),
			$this->db
		);
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

	/**
	 * @covers ::getUnknownActor
	 */
	public function testGetUnknownActor() {
		$store = $this->getStore();
		$unknownActor = $store->getUnknownActor();
		$this->assertInstanceOf( UserIdentity::class, $unknownActor );
		$this->assertTrue( $unknownActor->getActorId( UserIdentity::LOCAL ) > 0 );
		$this->assertSame( ActorStore::UNKNOWN_USER_NAME, $unknownActor->getName() );
		$this->assertSameActors( $unknownActor, $store->getUnknownActor() );
	}

	public function provideNormalizeUserName() {
		yield [ strtolower( self::IP ), UserNameUtils::RIGOR_NONE, self::IP ];
		yield [ 'acme>test', UserNameUtils::RIGOR_VALID, 'acme>test' ];
		yield [ 'test_this', UserNameUtils::RIGOR_VALID, 'Test this' ];
		yield [ 'foo#bar', UserNameUtils::RIGOR_VALID, null ];
		yield [ 'foo|bar', UserNameUtils::RIGOR_VALID, null ];
		yield [ '_', UserNameUtils::RIGOR_NONE, '_' ];
		yield [ 'test', UserNameUtils::RIGOR_NONE, 'test' ];
	}

	/**
	 * @dataProvider provideNormalizeUserName
	 */
	public function testNormalizeUserName( $name, $rigor, $expected ) {
		$store = $this->getStore();
		$this->assertSame( $expected, $store->normalizeUserName( $name, $rigor ) );
	}

}
