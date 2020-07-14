<?php

use MediaWiki\MediaWikiServices;
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
		$userNameUtils = MediaWikiServices::getInstance()->getUserNameUtils();
		return new UserFactory( $userNameUtils );
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

		$name = '192.0.2.0';
		$anonIpSpecified = $factory->newAnonymous( $name );
		$this->assertSame( $name, $anonIpSpecified->getName() );
	}

	public function testNewFromId() {
		$id = 98257;

		$factory = $this->getUserFactory();
		$user = $factory->newFromId( $id );

		$this->assertInstanceOf( User::class, $user );
		$this->assertSame( $id, $user->getId() );
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
			'Sanity check: valid actor id for a user'
		);

		$user2 = $factory->newFromActorId( $actorId );
		$this->assertInstanceOf( User::class, $user2 );
		$this->assertSame( $actorId, $user2->getActorId() );
	}

	public function testNewFromUserIdentity() {
		$id = 23560;
		$name = 'UserFactoryTest3';
		$actorId = 34562;

		$userIdentity = new UserIdentityValue( $id, $name, $actorId );
		$factory = $this->getUserFactory();

		$user1 = $factory->newFromUserIdentity( $userIdentity );
		$this->assertInstanceOf( User::class, $user1 );
		$this->assertSame( $id, $user1->getId() );
		$this->assertSame( $name, $user1->getName() );
		$this->assertSame( $actorId, $user1->getActorId() );

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
			'Sanity check: valid user'
		);
		$actorId = $user1->getActorId();
		$this->assertGreaterThan(
			0,
			$actorId,
			'Sanity check: valid actor id for a user'
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

}
