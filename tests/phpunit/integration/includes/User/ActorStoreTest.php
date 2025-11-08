<?php

namespace MediaWiki\Tests\User;

use InvalidArgumentException;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Exception\CannotCreateActorException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\ActorStore;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserSelectQueryBuilder;
use stdClass;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\User\ActorStore
 * @group Database
 */
class ActorStoreTest extends ActorStoreTestBase {
	use TempUserTestTrait;

	public static function provideGetActorById() {
		yield 'getActorById, registered' => [
			42, // $argument
			new UserIdentityValue( 24, 'TestUser' ), // $expected
		];
		yield 'getActorById, anon' => [
			43, // $argument
			new UserIdentityValue( 0, self::IP ), // $expected
		];
		yield 'getActorById, non-existent' => [
			4321231, // $argument
			null, // $expected
		];
		yield 'getActorById, zero' => [
			0, // $argument
			null, // $expected
		];
	}

	/**
	 * @dataProvider provideGetActorById
	 */
	public function testGetActorById( $argument, ?UserIdentity $expected ) {
		$actor = $this->getStore()->getActorById( $argument, $this->getDb() );
		if ( $expected ) {
			$this->assertNotNull( $actor );
			$this->assertSameActors( $expected, $actor );

			// test caching
			$cachedActor = $this->getStore()->getActorById( $argument, $this->getDb() );
			$this->assertSame( $actor, $cachedActor );
		} else {
			$this->assertNull( $actor );
		}
	}

	public static function provideGetActorByMethods() {
		yield 'getUserIdentityByName, registered' => [
			'getUserIdentityByName', // $method
			'TestUser', // $argument
			new UserIdentityValue( 24, 'TestUser' ), // $expected
		];
		yield 'getUserIdentityByName, non-existent' => [
			'getUserIdentityByName', // $method
			'TestUser_I_Do_Not_Exist', // $argument
			null, // $expected
		];
		yield 'getUserIdentityByName, anon' => [
			'getUserIdentityByName', // $method
			self::IP, // $argument
			new UserIdentityValue( 0, self::IP ), // $expected
		];
		yield 'getUserIdentityByName, anon, non-canonicalized IP' => [
			'getUserIdentityByName', // $method
			strtolower( self::IP ), // $argument
			new UserIdentityValue( 0, self::IP ), // $expected
		];
		yield 'getUserIdentityByName, user name 0' => [
			'getUserIdentityByName', // $method
			'0', // $argument
			new UserIdentityValue( 26, '0' ), // $expected
		];
		yield 'getUserIdentityByName, empty' => [
			'getUserIdentityByName', // $method
			'', // $argument
			null, // $expected
		];
		yield 'getUserIdentityByName, ip range' => [
			'getUserIdentityByName', // $method
			'1.1.1.1/1', // $argument
			null, // $expected
		];
		yield 'getUserIdentityByUserId, registered' => [
			'getUserIdentityByUserId', // $method
			24, // $argument
			new UserIdentityValue( 24, 'TestUser' ), // $expected
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

	public static function provideUserIdentityValues() {
		yield [ new UserIdentityValue( 24, 'TestUser' ) ];
		yield [ new UserIdentityValue( 0, self::IP ) ];
	}

	/**
	 * @dataProvider provideUserIdentityValues
	 * @param UserIdentity $expected
	 */
	public function testSequentialCacheRetrieval( UserIdentity $expected ) {
		if ( $expected->getId() === 0 ) {
			$this->disableAutoCreateTempUser();
		}
		// ensure UserIdentity is cached
		$actorId = $this->getStore()->findActorId( $expected, $this->getDb() );

		$cachedActorId = $this->getStore()->findActorId( $expected, $this->getDb() );
		$this->assertSame( $actorId, $cachedActorId );

		$cachedActorId = $this->getStore()->acquireActorId( $expected, $this->getDb() );
		$this->assertSame( $actorId, $cachedActorId );

		$cached = $this->getStore()->getActorById( $actorId, $this->getDb() );
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

	public function testGetActorByIdRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getActorById( $user->getActorId(), $this->getDb() );
		$this->assertSameActors( $user, $actor );
	}

	public function testgetUserIdentityByNameRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getUserIdentityByName( $user->getName() );
		$this->assertSameActors( $user, $actor );
	}

	public function testGetUserIdentityByUserIdRealUser() {
		$user = $this->getTestUser()->getUser();
		$actor = $this->getStore()->getUserIdentityByUserId( $user->getId() );
		$this->assertSameActors( $user, $actor );
	}

	public static function provideNewActorFromRow() {
		yield 'full row' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => 42, 'actor_name' => 'TestUser', 'actor_user' => 24 ], // $row
			new UserIdentityValue( 24, 'TestUser' ), // $expected
		];
		yield 'full row, strings' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '42', 'actor_name' => 'TestUser', 'actor_user' => '24' ], // $row
			new UserIdentityValue( 24, 'TestUser' ), // $expected
		];
		yield 'null user' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '42', 'actor_name' => 'TestUser', 'actor_user' => null ], // $row
			new UserIdentityValue( 0, 'TestUser' ), // $expected
		];
		yield 'zero user' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '42', 'actor_name' => 'TestUser', 'actor_user' => 0 ], // $row
			new UserIdentityValue( 0, 'TestUser' ), // $expected
		];
		yield 'cross-wiki' => [
			'acmewiki', // $wikiId
			(object)[ 'actor_id' => 42, 'actor_name' => 'TestUser', 'actor_user' => 24 ], // $row
			new UserIdentityValue( 24, 'TestUser', 'acmewiki' ), // $expected
		];
		yield 'user name 0' => [
			UserIdentity::LOCAL, // $wikiId
			(object)[ 'actor_id' => '46', 'actor_name' => '0', 'actor_user' => 26 ], // $row
			new UserIdentityValue( 26, '0' ), // $expected
		];
	}

	/**
	 * @dataProvider provideNewActorFromRow
	 */
	public function testNewActorFromRow( $wikiId, stdClass $row, UserIdentity $expected ) {
		$actor = $this->getStore( $wikiId )->newActorFromRow( $row );
		$this->assertSameActors( $expected, $actor, $wikiId );
	}

	public static function provideNewActorFromRow_exception() {
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
	 */
	public function testNewActorFromRow_exception( stdClass $row ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore()->newActorFromRow( $row );
	}

	public static function provideNewActorFromRowFields() {
		yield 'full row' => [
			UserIdentity::LOCAL, // $wikiId
			42, // $actorId
			'TestUser', // $name
			24, // $userId
			new UserIdentityValue( 24, 'TestUser' ), // $expected
		];
		yield 'full row, strings' => [
			UserIdentity::LOCAL, // $wikiId
			'42', // $actorId
			'TestUser', // $name
			'24', // $userId
			new UserIdentityValue( 24, 'TestUser' ), // $expected
		];
		yield 'null user' => [
			UserIdentity::LOCAL, // $wikiId
			42, // $actorId
			'TestUser', // $name
			null, // $userId
			new UserIdentityValue( 0, 'TestUser' ), // $expected
		];
		yield 'zero user' => [
			UserIdentity::LOCAL, // $wikiId
			42, // $actorId
			'TestUser', // $name
			0, // $userId
			new UserIdentityValue( 0, 'TestUser' ), // $expected
		];
		yield 'user name 0' => [
			UserIdentity::LOCAL, // $wikiId
			46, // $actorId
			'0', // $name
			26, // $userId
			new UserIdentityValue( 26, '0' ), // $expected
		];
		yield 'cross-wiki' => [
			'acmewiki', // $wikiId
			42, // $actorId
			'TestUser', // $name
			0, // $userId
			new UserIdentityValue( 0, 'TestUser', 'acmewiki' ), // $expected
		];
	}

	/**
	 * @dataProvider provideNewActorFromRowFields
	 */
	public function testNewActorFromRowFields( $wikiId, $actorId, $name, $userId, UserIdentity $expected ) {
		$actor = $this->getStore( $wikiId )->newActorFromRowFields( $userId, $name, $actorId );
		$this->assertSameActors( $expected, $actor, $wikiId );
	}

	public static function provideNewActorFromRowFields_exception() {
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
	 */
	public function testNewActorFromRowFields_exception( $actorId, $name, $userId ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore()->newActorFromRowFields( $userId, $name, $actorId );
	}

	public static function provideFindActorId() {
		yield 'anon, local' => [
			static function () {
				return new UserIdentityValue( 0, self::IP );
			}, // $actorCallback
			43, // $expected
		];
		yield 'anon, non-canonical, local' => [
			static function () {
				return new UserIdentityValue( 0, strtolower( self::IP ) );
			}, // $actorCallback
			43, // $expected
		];
		yield 'registered, local' => [
			static function () {
				return new UserIdentityValue( 24, 'TestUser' );
			}, // $actorCallback
			42, // $expected
		];
		yield 'registered, zero user name' => [
			static function () {
				return new UserIdentityValue( 26, '0' );
			}, // $actorCallback
			46, // $expected
		];
		yield 'anon, non-existent, local' => [
			static function () {
				return new UserIdentityValue( 0, '127.1.2.3' );
			}, // $actorCallback
			null, // $expected
		];
		yield 'registered, non-existent, local' => [
			static function () {
				return new UserIdentityValue( 51, 'DoNotExist' );
			}, // $actorCallback
			null, // $expected
		];
		yield 'external, local' => [
			static function () {
				return new UserIdentityValue( 0, 'acme>TestUser' );
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
			}, // $actorCallback
			null, // $expected
		];
		yield 'anon, foreign' => [
			static function () {
				return new UserIdentityValue( 0, self::IP, 'acmewiki' );
			}, // $actorCallback
			43, // $expected
			'acmewiki', // $wikiId
		];
		yield 'registered, foreign' => [
			static function () {
				return new UserIdentityValue( 24, 'TestUser', 'acmewiki' );
			}, // $actorCallback
			42, // $expected
			'acmewiki', // $wikiId
		];
	}

	/**
	 * @dataProvider provideFindActorId
	 */
	public function testFindActorId( callable $actorCallback, $expected, $wikiId = WikiAwareEntity::LOCAL ) {
		$actor = $actorCallback( $this->getServiceContainer() );
		if ( $wikiId ) {
			$this->executeWithForeignStore(
				$wikiId,
				function ( ActorStore $store ) use ( $expected, $actor ) {
					$this->assertSame( $expected, $store->findActorId( $actor, $this->getDb() ) );

					if ( $actor instanceof User ) {
						$this->assertSame( $expected ?: 0, $actor->getActorId() );
					}
				}
			);
		} else {
			$this->assertSame( $expected, $this->getStore()->findActorId( $actor, $this->getDb() ) );

			if ( $actor instanceof User ) {
				$this->assertSame( $expected ?: 0, $actor->getActorId() );
			}
		}
	}

	public function testFindActorId_wikiMismatch() {
		$this->markTestSkipped();
		$this->expectException( PreconditionException::class );
		$this->getStore()->findActorId(
			new UserIdentityValue( 0, self::IP, 'acmewiki' ),
			$this->getDb()
		);
	}

	public static function provideFindActorIdByName() {
		yield 'anon' => [
			self::IP, // $actorCallback
			43, // $expected
		];
		yield 'anon, non-canonical' => [
			strtolower( self::IP ), // $actorCallback
			43, // $expected
		];
		yield 'registered' => [
			'TestUser', // $actorCallback
			42, // $expected
		];
		yield 'registered, unnormalized' => [
			'testUser', // $actorCallback
			42, // $expected
		];
		yield 'registered, 0 user name' => [
			'0', // $actorCallback
			46, // $expected
		];
		yield 'external, local' => [
			'acme>TestUser',
			45, // $expected
		];
		yield 'anon, non-existent' => [
			'127.1.2.3', // $actorCallback
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
	 */
	public function testFindActorIdByName( $name, $expected ) {
		$this->assertSame( $expected, $this->getStore()->findActorIdByName( $name, $this->getDb() ) );
	}

	public static function provideAcquireActorId() {
		yield 'anon' => [ static function () {
			return new UserIdentityValue( 0, '127.3.2.1' );
		} ];
		yield 'registered' => [ static function () {
			return new UserIdentityValue( 15, 'MyUser' );
		} ];
		yield 'User object' => [ static function ( $serviceContainer ) {
			return $serviceContainer->getUserFactory()->newAnonymous( '127.4.3.2' );
		} ];
	}

	/**
	 * @dataProvider provideAcquireActorId
	 */
	public function testAcquireActorId( callable $userCallback ) {
		/** @var UserIdentity $user */
		$user = $userCallback( $this->getServiceContainer() );
		if ( $user->getId() === 0 ) {
			$this->disableAutoCreateTempUser();
		}
		$actorId = $this->getStore()->acquireActorId( $user, $this->getDb() );
		$this->assertTrue( $actorId > 0 );

		if ( $user instanceof User ) {
			$this->assertSame( $actorId, $user->getActorId() );
		}
	}

	public static function provideAcquireActorId_foreign() {
		yield 'anon' => [
			'userCallback' => static function () {
				return new UserIdentityValue( 0, '127.3.2.1', 'acmewiki' );
			},
			'method' => 'acquireActorId'
		];
		yield 'registered' => [
			'userCallback' => static function () {
				return new UserIdentityValue( 15, 'MyUser', 'acmewiki' );
			},
			'method' => 'acquireActorId'
		];
		yield 'anon, new' => [
			'userCallback' => static function () {
				return new UserIdentityValue( 0, '128.9.8.7', 'acmewiki' );
			},
			'method' => 'createNewActor'
		];
		yield 'registered, new' => [
			'userCallback' => static function () {
				return new UserIdentityValue( 15, 'New MyUser', 'acmewiki' );
			},
			'method' => 'createNewActor'
		];
	}

	/**
	 * @dataProvider provideAcquireActorId_foreign
	 */
	public function testAcquireActorId_foreign( callable $userCallback, string $method ) {
		/** @var UserIdentity $user */
		$user = $userCallback( $this->getServiceContainer() );
		if ( $user->getId( 'acmewiki' ) === 0 ) {
			$this->disableAutoCreateTempUser();
		}
		$this->executeWithForeignStore(
			'acmewiki',
			function ( ActorStore $store ) use ( $user, $method ) {
				$actorId = $store->$method( $user, $this->getDb() );
				$this->assertTrue( $actorId > 0 );
				if ( $user instanceof User ) {
					$this->assertSame( $actorId, $user->getActorId() );
				}
			}
		);
	}

	/**
	 * @dataProvider provideAcquireActorId_foreign
	 */
	public function testAcquireActorId_foreign_withDBConnection( callable $userCallback, string $method ) {
		/** @var UserIdentity $user */
		$user = $userCallback( $this->getServiceContainer() );
		if ( $user->getId( 'acmewiki' ) === 0 ) {
			$this->disableAutoCreateTempUser();
		}
		$this->executeWithForeignStore(
			'acmewiki',
			function ( ActorStore $store, IDatabase $dbw ) use ( $user, $method ) {
				$actorId = $store->$method( $user, $dbw );
				$this->assertTrue( $actorId > 0 );
				if ( $user instanceof User ) {
					$this->assertSame( $actorId, $user->getActorId() );
				}
			}
		);
	}

	public static function provideAcquireActorId_canNotCreate() {
		yield 'usable name' => [
			'actor' => new UserIdentityValue( 0, 'MyFancyUser' ),
			'method' => 'acquireActorId'
		];
		yield 'empty name' => [
			'actor' => new UserIdentityValue( 15, '' ),
			'method' => 'acquireActorId'
		];
		yield 'usable name, new' => [
			'actor' => new UserIdentityValue( 0, 'MyFancyUser' ),
			'method' => 'createNewActor'
		];
		yield 'empty name, new' => [
			'actor' => new UserIdentityValue( 15, '' ),
			'method' => 'createNewActor'
		];
		yield 'usable name, replace' => [
			'actor' => new UserIdentityValue( 0, 'MyFancyUser' ),
			'method' => 'acquireSystemActorId'
		];
		yield 'empty name, replace' => [
			'actor' => new UserIdentityValue( 15, '' ),
			'method' => 'acquireSystemActorId'
		];
		yield 'existing non-anon, replace' => [
			'actor' => new UserIdentityValue( 25, 'TestUser' ),
			'method' => 'acquireSystemActorId'
		];
	}

	/**
	 * @dataProvider provideAcquireActorId_canNotCreate
	 */
	public function testAcquireActorId_canNotCreate( UserIdentityValue $actor, string $method ) {
		// We rely on DB to protect against duplicates when inserting new actor
		$this->setNullLogger( 'rdbms' );
		$this->expectException( CannotCreateActorException::class );
		$this->getStore()->$method( $actor, $this->getDb() );
	}

	public function testAcquireNowActorId_existing() {
		// We rely on DB to protect against duplicates when inserting new actor
		$this->setNullLogger( 'rdbms' );
		$this->expectException( CannotCreateActorException::class );
		$this->getStore()->createNewActor( new UserIdentityValue( 24, 'TestUser' ), $this->getDb() );
	}

	public function testAcquireActorId_autocreateTempIP() {
		$this->enableAutoCreateTempUser();
		$this->expectException( CannotCreateActorException::class );
		$this->getStore()->createNewActor( new UserIdentityValue( 0, '127.3.2.1' ), $this->getDb() );
	}

	public function testAcquireActorId_autocreateTempIPallowed() {
		$this->enableAutoCreateTempUser();
		$actorId = $this->getStoreForImport()->createNewActor( new UserIdentityValue( 0, '127.3.2.1' ), $this->getDb() );
		$this->assertTrue( $actorId > 0 );
	}

	public static function provideAcquireActorId_existing() {
		yield 'anon' => [
			new UserIdentityValue( 0, self::IP ), // $actor
			43, // $expected
		];
		yield 'registered' => [
			new UserIdentityValue( 24, 'TestUser' ), // $actor
			42, // $expected
		];
		yield 'registered, 0 user name' => [
			new UserIdentityValue( 26, '0' ), // $actor
			46, // $expected
		];
	}

	/**
	 * @dataProvider provideAcquireActorId_existing
	 */
	public function testAcquireActorId_existing( UserIdentityValue $actor, int $expected ) {
		if ( $actor->getId() === 0 ) {
			$this->disableAutoCreateTempUser();
		}
		$this->assertSame( $expected, $this->getStore()->acquireActorId( $actor, $this->getDb() ) );
	}

	public function testAcquireActorId_domain_mismatch() {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore( 'fancywiki' )->acquireActorId(
			new UserIdentityValue( 15, 'Test', 'fancywiki' ),
			$this->getDb()
		);
	}

	public function testcreateNewActor_domain_mismatch() {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore( 'fancywiki' )->createNewActor(
			new UserIdentityValue( 15, 'Test', 'fancywiki' ),
			$this->getDb()
		);
	}

	public function testAcquireSystemActorId_domain_mismatch() {
		$this->expectException( InvalidArgumentException::class );
		$this->getStore( 'fancywiki' )->acquireSystemActorId(
			new UserIdentityValue( 15, 'Test', 'fancywiki' ),
			$this->getDb()
		);
	}

	public function testAcquireActorId_wikiMismatch() {
		$this->markTestSkipped();
		$this->expectException( PreconditionException::class );
		$this->getStore()->acquireActorId(
			new UserIdentityValue( 0, self::IP, 'acmewiki' ),
			$this->getDb()
		);
	}

	public function testAcquireActorId_clearCacheOnRollback() {
		$this->disableAutoCreateTempUser();
		$rolledBackActor = new UserIdentityValue( 0, '127.0.0.10' );
		$store = $this->getStore();
		$this->getDb()->startAtomic( __METHOD__ );
		$rolledBackActorId = $store->acquireActorId( $rolledBackActor, $this->getDb() );
		$this->assertTrue( $rolledBackActorId > 0 );
		$foundActorId = $store->findActorId( $rolledBackActor, $this->getDb() );
		$this->assertSame( $rolledBackActorId, $foundActorId );
		$this->getDb()->rollback( __METHOD__ );

		// Insert some other user identity using another store
		// so that we take over the rolled back actor ID.
		$anotherActor = new UserIdentityValue( 0, '127.0.0.11' );
		$anotherActorId = $this->getStore()->acquireActorId( $anotherActor, $this->getDb() );

		// Make sure no actor ID associated with rolled back actor.
		$foundActorIdAfterRollback = $store->findActorId( $rolledBackActor, $this->getDb() );
		$this->assertNull( $foundActorIdAfterRollback );

		// Make sure we can acquire new actor ID for the rolled back actor
		$newActorId = $store->acquireActorId( $rolledBackActor, $this->getDb() );
		$this->assertTrue( $newActorId > 0 );
		$this->assertNotSame( $newActorId, $rolledBackActorId );

		// Make sure we find correct actor by rolled back actor ID
		$this->assertSameActors( $anotherActor, $store->getActorById( $anotherActorId, $this->getDb() ) );
	}

	public function testUserRenameUpdatesActor() {
		$user = $this->getMutableTestUser()->getUser();
		$oldName = $user->getName();

		$store = $this->getStore();
		$actorId = $store->findActorId( $user, $this->getDb() );
		$this->assertTrue( $actorId > 0 );

		$user->setName( 'NewName' );
		$user->saveSettings();

		$this->assertSameActors( $user, $store->getActorById( $actorId, $this->getDb() ) );
		$this->assertSameActors( $user, $store->getUserIdentityByName( 'NewName' ) );
		$this->assertSameActors( $user, $store->getUserIdentityByUserId( $user->getId() ) );
		$this->assertNull( $store->getUserIdentityByName( $oldName ) );
	}

	public function testAcquireSystemActorId() {
		$this->disableAutoCreateTempUser();
		$store = $this->getStore();
		$originalActor = new UserIdentityValue( 0, '129.0.0.1' );
		$actorId = $store->createNewActor( $originalActor, $this->getDb() );
		$this->assertTrue( $actorId > 0, 'Acquired new actor ID' );

		$updatedActor = new UserIdentityValue( 56789, '129.0.0.1' );
		$this->assertSame( $actorId, $store->acquireSystemActorId( $updatedActor, $this->getDb() ) );
		$this->assertSame( 56789, $store->getActorById( $actorId, $this->getDb() )->getId() );
		// Try with another store to verify not just cache was updated
		$this->assertSame( 56789, $this->getStore()->getActorById( $actorId, $this->getDb() )->getId() );
	}

	public function testAcquireSystemActorId_replaceReserved() {
		$this->overrideConfigValue(
			MainConfigNames::ReservedUsernames,
			[ 'RESERVED' ]
		);
		$store = $this->getStore();
		$originalActor = new UserIdentityValue( 0, 'RESERVED' );
		$actorId = $store->createNewActor( $originalActor, $this->getDb() );
		$this->assertTrue( $actorId > 0, 'Acquired new actor ID' );

		$updatedActor = new UserIdentityValue( 80, 'RESERVED' );
		$this->assertSame( $actorId, $store->acquireSystemActorId( $updatedActor, $this->getDb() ) );
		$this->assertSame( 80, $store->getActorById( $actorId, $this->getDb() )->getId() );
		// Try with another store to verify not just cache was updated
		$this->assertSame( 80, $this->getStore()->getActorById( $actorId, $this->getDb() )->getId() );
	}

	public function testAcquireSystemActorId_assignsNew() {
		$store = $this->getStore();

		$newActor = new UserIdentityValue( 456789, 'Foo' );
		$newActorId = $store->acquireSystemActorId( $newActor, $this->getDb() );
		$this->assertTrue( $newActorId > 0 );
		$this->assertSame( 456789, $store->getActorById( $newActorId, $this->getDb() )->getId() );
		// Try with another store to verify not just cache was updated
		$this->assertSame( 456789, $this->getStore()->getActorById( $newActorId, $this->getDb() )->getId() );
	}

	public function testDelete() {
		$store = $this->getStore();
		$actor = new UserIdentityValue( 9999, 'DeleteTest' );
		$actorId = $store->acquireActorId( $actor, $this->getDb() );
		$this->assertTrue( $store->deleteActor( $actor, $this->getDb() ) );

		$this->assertNull( $store->findActorId( $actor, $this->getDb() ) );
		$this->assertNull( $store->getUserIdentityByUserId( $actor->getId() ) );
		$this->assertNull( $store->getUserIdentityByName( $actor->getName() ) );
		$this->assertNull( $store->getActorById( $actorId, $this->getDb() ) );
	}

	public function testDeleteDoesNotExist() {
		$this->assertFalse(
			$this->getStore()->deleteActor( new UserIdentityValue( 9998, 'DoesNotExist' ), $this->getDb() )
		);
	}

	public function testGetUnknownActor() {
		$store = $this->getStore();
		$unknownActor = $store->getUnknownActor();
		$this->assertInstanceOf( UserIdentity::class, $unknownActor );
		$this->assertSame( ActorStore::UNKNOWN_USER_NAME, $unknownActor->getName() );
		$this->assertSameActors( $unknownActor, $store->getUnknownActor() );
	}

	public static function provideNormalizeUserName() {
		yield [ strtolower( self::IP ), self::IP ];
		yield [ 'acme>test', 'acme>test' ];
		yield [ 'test_this', 'Test this' ];
		yield [ 'foo#bar', null ];
		yield [ 'foo|bar', null ];
		yield [ '_', null ];
		yield [ 'test', 'Test' ];
		yield [ '', null ];
		yield [ '0', '0' ];
	}

	/**
	 * @dataProvider provideNormalizeUserName
	 */
	public function testNormalizeUserName( $name, $expected ) {
		$store = $this->getStore();
		$this->assertSame( $expected, $store->normalizeUserName( $name ) );
	}

	public function testNewSelectQueryBuilderWithoutDB() {
		$store = $this->getStore();
		$queryBuilder = $store->newSelectQueryBuilder();
		$this->assertInstanceOf( UserSelectQueryBuilder::class, $queryBuilder );
	}

	public function testNewSelectQueryBuilderWithDB() {
		$store = $this->getStore();
		$queryBuilder = $store->newSelectQueryBuilder( $this->getDb() );
		$this->assertInstanceOf( UserSelectQueryBuilder::class, $queryBuilder );
	}

	public function testNewSelectQueryBuilderWithQueryFlags() {
		$store = $this->getStore();
		$queryBuilder = $store->newSelectQueryBuilder( IDBAccessObject::READ_NORMAL );
		$this->assertInstanceOf( UserSelectQueryBuilder::class, $queryBuilder );
	}
}
