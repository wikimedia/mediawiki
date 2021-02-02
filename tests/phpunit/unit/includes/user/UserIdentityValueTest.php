<?php

use MediaWiki\User\UserIdentityValue;
use Wikimedia\Assert\PreconditionException;

class UserIdentityValueTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getActorId
	 */
	public function testGetActorIdLocalUIVNoParam() {
		$id = 88888888;
		$user = new UserIdentityValue( 0, 'TestUserName', $id, UserIdentityValue::LOCAL );

		$this->assertEquals( $id, $user->getActorId() );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getActorId
	 */
	public function testGetActorIdLocalUIVLocalParam() {
		$id = 88888888;
		$user = new UserIdentityValue( 0, 'TestUserName', $id, UserIdentityValue::LOCAL );

		$this->assertEquals( $id, $user->getActorId( UserIdentityValue::LOCAL ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getActorId
	 */
	public function testGetActorIdLocalUIVForeignParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( 0, 'TestUserName', $id, UserIdentityValue::LOCAL );

		$this->expectDeprecation();
		$this->assertEquals( $id, $user->getActorId( $foreignWikiId ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getActorId
	 */
	public function testGetActorIdForeignUIVNoParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( 0, 'TestUserName', $id, $foreignWikiId );

		$this->expectDeprecation();
		$this->assertEquals( $id, $user->getActorId() );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getActorId
	 */
	public function testGetActorIdForeignUIVLocalParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( 0, 'TestUserName', $id, $foreignWikiId );

		$this->expectDeprecation();
		$this->assertEquals( $id, $user->getActorId( UserIdentityValue::LOCAL ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getActorId
	 */
	public function testGetActorIdForeignUIVForeignParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( 0, 'TestUserName', $id, $foreignWikiId );

		$this->assertEquals( $id, $user->getActorId( $foreignWikiId ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getUserId
	 */
	public function testGetUserIdLocalUIVNoParam() {
		$id = 88888888;
		$user = new UserIdentityValue( $id, 'TestUserName', 0, UserIdentityValue::LOCAL );

		$this->assertEquals( $id, $user->getUserId() );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getUserId
	 */
	public function testGetUserIdLocalUIVLocalParam() {
		$id = 88888888;
		$user = new UserIdentityValue( $id, 'TestUserName', 0, UserIdentityValue::LOCAL );

		$this->assertEquals( $id, $user->getUserId( UserIdentityValue::LOCAL ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getUserId
	 */
	public function testGetUserIdLocalUIVForeignParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( $id, 'TestUserName', 0, UserIdentityValue::LOCAL );

		$this->expectException( PreconditionException::class );
		$this->assertEquals( $id, $user->getUserId( $foreignWikiId ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getUserId
	 */
	public function testGetUserIdForeignUIVNoParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( $id, 'TestLocalUserIdentity', 0, $foreignWikiId );

		$this->expectException( PreconditionException::class );
		$this->assertEquals( $id, $user->getUserId() );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getUserId
	 */
	public function testGetUserIdForeignUIVLocalParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( $id, 'TestLocalUserIdentity', 0, $foreignWikiId );

		$this->expectException( PreconditionException::class );
		$this->assertEquals( $id, $user->getUserId( UserIdentityValue::LOCAL ) );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getUserId
	 */
	public function testGetUserIdForeignUIVForeignParam() {
		$foreignWikiId = 'Foreign Wiki';
		$id = 88888888;
		$user = new UserIdentityValue( $id, 'TestLocalUserIdentity', 0, $foreignWikiId );

		$this->assertEquals( $id, $user->getUserId( $foreignWikiId ) );
	}

	/**
	 * @return Generator
	 */
	public function provideWikiIds() {
		yield [ UserIdentityValue::LOCAL ];
		yield [ 'Foreign Wiki' ];
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::getWikiId
	 * @dataProvider provideWikiIds
	 * @param string|false $wikiId
	 */
	public function testGetWiki( $wikiId ) {
		$user = new UserIdentityValue( 0, 'TestUserName', 0, $wikiId );
		$this->assertSame( $wikiId, $user->getWikiId() );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::assertWiki
	 */
	public function testAssertWikiLocalUIV() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', 0, UserIdentityValue::LOCAL );

		$user->assertWiki( UserIdentityValue::LOCAL );
		$this->assertTrue( true, 'User is for same wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( $foreignWikiId );
	}

	/**
	 * @covers \MediaWiki\User\UserIdentityValue::assertWiki
	 */
	public function testAssertWikiForeignUIV() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', 0, $foreignWikiId );

		$user->assertWiki( $foreignWikiId );
		$this->assertTrue( true, 'User is for same wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( UserIdentityValue::LOCAL );
	}
}
