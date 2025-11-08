<?php

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\UpdateQueryBuilder;

/**
 * @covers \MediaWiki\User\UserFactory
 * @group Database
 *
 * @author DannyS712
 */
class UserFactoryTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

	private function getUserFactory() {
		return $this->getServiceContainer()->getUserFactory();
	}

	public function testNewFromName() {
		$name = 'UserFactoryTest1';

		$factory = $this->getUserFactory();
		$user = $factory->newFromName( $name );

		$this->assertInstanceOf( User::class, $user );
		$this->assertSame( $name, $user->getName() );
	}

	public function testNewAnonymous() {
		$factory = $this->getUserFactory();
		$anon = $factory->newAnonymous();
		$this->assertInstanceOf( User::class, $anon );

		$currentIp = IPUtils::sanitizeIP( $anon->getRequest()->getIP() );

		// Unspecified name defaults to current user's IP address
		$this->assertSame( $currentIp, $anon->getName() );

		// FIXME: should be a query count performance assertion instead of this hack
		$this->getServiceContainer()->disableService( 'DBLoadBalancer' );

		$name = '192.0.2.0';
		$anonIpSpecified = $factory->newAnonymous( $name );
		$this->assertSame( $name, $anonIpSpecified->getName() );
		$anonIpSpecified->load(); // no queries expected
	}

	public function testNewFromId() {
		$id = 98257;

		$factory = $this->getUserFactory();
		$user = $factory->newFromId( $id );

		$this->assertInstanceOf( User::class, $user );
		$this->assertSame( $id, $user->getId() );
	}

	public function testNewFromIdentity() {
		$identity = new UserIdentityValue( 98257, __METHOD__ );

		$factory = $this->getUserFactory();
		$user = $factory->newFromUserIdentity( $identity );

		$this->assertInstanceOf( User::class, $user );
		$this->assertSame( $identity->getId(), $user->getId() );
		$this->assertSame( $identity->getName(), $user->getName() );

		// make sure instance caching happens
		$this->assertSame( $user, $factory->newFromUserIdentity( $identity ) );

		// make sure instance caching distingushes between IDs
		$otherIdentity = new UserIdentityValue( 33245, __METHOD__ );
		$this->assertNotSame( $user, $factory->newFromUserIdentity( $otherIdentity ) );
	}

	public function testNewFromActorId() {
		$name = 'UserFactoryTest2';

		$factory = $this->getUserFactory();
		$user1 = $factory->newFromName( $name );
		$user1->addToDatabase();

		$actorId = $user1->getActorId();
		$this->assertGreaterThan(
			0,
			$actorId,
			'Valid actor id for a user'
		);

		$user2 = $factory->newFromActorId( $actorId );
		$this->assertInstanceOf( User::class, $user2 );
		$this->assertSame( $actorId, $user2->getActorId() );
	}

	public function testNewFromUserIdentity() {
		$id = 23560;
		$name = 'UserFactoryTest3';

		$userIdentity = new UserIdentityValue( $id, $name );
		$factory = $this->getUserFactory();

		$user1 = $factory->newFromUserIdentity( $userIdentity );
		$this->assertInstanceOf( User::class, $user1 );
		$this->assertSame( $id, $user1->getId() );
		$this->assertSame( $name, $user1->getName() );

		$user2 = $factory->newFromUserIdentity( $user1 );
		$this->assertInstanceOf( User::class, $user1 );
		$this->assertSame( $user1, $user2 );
	}

	public function testNewFromAnyId() {
		$name = 'UserFactoryTest4';

		$factory = $this->getUserFactory();
		$user1 = $factory->newFromName( $name );
		$user1->addToDatabase();

		$id = $user1->getId();
		$this->assertGreaterThan(
			0,
			$id,
			'Valid user'
		);
		$actorId = $user1->getActorId();
		$this->assertGreaterThan(
			0,
			$actorId,
			'Valid actor id for a user'
		);

		$user2 = $factory->newFromAnyId( $id, null, null );
		$this->assertInstanceOf( User::class, $user2 );
		$this->assertSame( $id, $user2->getId() );

		$user3 = $factory->newFromAnyId( null, $name, null );
		$this->assertInstanceOf( User::class, $user3 );
		$this->assertSame( $name, $user3->getName() );

		$user4 = $factory->newFromAnyId( null, null, $actorId );
		$this->assertInstanceOf( User::class, $user4 );
		$this->assertSame( $actorId, $user4->getActorId() );
	}

	public function testNewFromConfirmationCode() {
		$fakeCode = 'NotARealConfirmationCode';

		$factory = $this->getUserFactory();

		$user1 = $factory->newFromConfirmationCode( $fakeCode );
		$this->assertNull(
			$user1,
			'Invalid confirmation codes result in null users when reading from replicas'
		);

		$user2 = $factory->newFromConfirmationCode( $fakeCode, IDBAccessObject::READ_LATEST );
		$this->assertNull(
			$user2,
			'Invalid confirmation codes result in null users when reading from master'
		);
	}

	// Copied from UserTest
	public function testNewFromRow() {
		// TODO: Create real tests here for loadFromRow
		$row = (object)[];
		$user = $this->getUserFactory()->newFromRow( $row );
		$this->assertInstanceOf( User::class, $user, 'newFromRow returns a user object' );
	}

	public function testNewFromRow_bad() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$row must be an object' );
		$this->getUserFactory()->newFromRow( [] );
	}

	/**
	 * @covers \MediaWiki\User\UserFactory::newFromAuthority
	 */
	public function testNewFromAuthority() {
		$authority = new UltimateAuthority( new UserIdentityValue( 42, 'Test' ) );
		$user = $this->getUserFactory()->newFromAuthority( $authority );
		$this->assertSame( 42, $user->getId() );
		$this->assertSame( 'Test', $user->getName() );
	}

	public function testNewTempPlaceholder() {
		$this->enableAutoCreateTempUser();
		$user = $this->getUserFactory()->newTempPlaceholder();
		$this->assertTrue( $user->isTemp() );
		$this->assertFalse( $user->isRegistered() );
		$this->assertFalse( $user->isNamed() );
		$this->assertSame( 0, $user->getId() );
	}

	public function testNewUnsavedTempUser() {
		$this->enableAutoCreateTempUser();
		$user = $this->getUserFactory()->newUnsavedTempUser( '~1234' );
		$this->assertTrue( $user->isTemp() );
		$this->assertFalse( $user->isNamed() );
	}

	public function testInvalidateCacheLocal() {
		$userMock = $this->createMock( User::class );
		$userMock->method( 'isRegistered' )->willReturn( true );
		$userMock->method( 'getWikiId' )->willReturn( User::LOCAL );
		$userMock->expects( $this->once() )->method( 'invalidateCache' );

		$this->getUserFactory()->invalidateCache( $userMock );
	}

	public function testInvalidateCacheCrossWiki() {
		$dbMock = $this->createMock( IDatabase::class );
		$dbMock->method( 'timestamp' )->willReturn( 'timestamp' );
		$dbMock->expects( $this->once() )
			->method( 'newUpdateQueryBuilder' )
			->willReturn( new UpdateQueryBuilder( $dbMock ) );
		$dbMock->expects( $this->once() )
			->method( 'update' )
			->with(
				'user',
				[ 'user_touched' => 'timestamp' ],
				[ 'user_id' => 123 ]
			);

		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getConnection' )->willReturn( $dbMock );

		$lbFactoryMock = $this->createMock( LBFactory::class );
		$lbFactoryMock->method( 'getMainLB' )->willReturn( $lbMock );
		$this->setService( 'DBLoadBalancerFactory', $lbFactoryMock );

		$user = new UserIdentityValue( 123, 'UserIdentityCacheUpdaterTest', 'meta' );
		$this->getUserFactory()->invalidateCache( $user );
	}

}
