<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\IPUtils;

/**
 * @covers \MediaWiki\User\UserFactory
 * @group Database
 *
 * @author DannyS712
 */
class UserFactoryTest extends MediaWikiIntegrationTestCase {

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

		$user2 = $factory->newFromConfirmationCode( $fakeCode, UserFactory::READ_LATEST );
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
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => true,
				'actions' => [ 'edit' ],
				'genPattern' => '*Unregistered $1',
				'matchPattern' => '*$1',
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
			]
		);
		$user = $this->getUserFactory()->newTempPlaceholder();
		$this->assertTrue( $user->isTemp() );
		$this->assertFalse( $user->isRegistered() );
		$this->assertFalse( $user->isNamed() );
		$this->assertSame( 0, $user->getId() );
	}

	public function testNewUnsavedTempUser() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser,
			[
				'enabled' => true,
				'actions' => [ 'edit' ],
				'genPattern' => '*Unregistered $1',
				'matchPattern' => '*$1',
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
			]
		);
		$user = $this->getUserFactory()->newUnsavedTempUser( '*Unregistered 1234' );
		$this->assertTrue( $user->isTemp() );
		$this->assertFalse( $user->isNamed() );
	}

}
