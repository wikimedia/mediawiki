<?php

use MediaWiki\User\UserIdentityValue;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\User\UserIdentityValue
 */
class UserIdentityValueTest extends MediaWikiUnitTestCase {

	public function testGetActorIdLocalUIVForeignParam() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$this->expectDeprecationAndContinue(
			'/Use of MediaWiki\\\\User\\\\UserIdentityValue::getActorId was deprecated in MediaWiki 1\.36/'
		);
		$this->assertSame( 0, $user->getActorId( $foreignWikiId ) );
	}

	public function testGetActorIdDeprecated() {
		$user = new UserIdentityValue( 0, 'TestUserName' );

		$this->expectDeprecationAndContinue(
			'/Use of MediaWiki\\\\User\\\\UserIdentityValue::getActorId was deprecated in MediaWiki 1\.36/'
		);
		$this->assertSame( 0, $user->getActorId() );
	}

	public function testGetActorIdLocalUIVNoParam() {
		$this->filterDeprecated( '/UserIdentityValue::getActorId was deprecated/' );
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$this->assertSame( 0, $user->getActorId() );
	}

	public function testGetActorIdLocalUIVLocalParam() {
		$this->filterDeprecated( '/UserIdentityValue::getActorId was deprecated/' );
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$this->assertSame( 0, $user->getActorId( UserIdentityValue::LOCAL ) );
	}

	public function testGetActorIdForeignUIVForeignParam() {
		$this->filterDeprecated( '/UserIdentityValue::getActorId was deprecated/' );
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', $foreignWikiId );

		$this->assertSame( 0, $user->getActorId( $foreignWikiId ) );
	}

	public static function provideWikiIds(): Generator {
		yield [ UserIdentityValue::LOCAL ];
		yield [ 'Foreign Wiki' ];
	}

	/**
	 * @dataProvider provideWikiIds
	 * @param string|false $wikiId
	 */
	public function testGetWiki( $wikiId ) {
		$user = new UserIdentityValue( 0, 'TestUserName', $wikiId );
		$this->assertSame( $wikiId, $user->getWikiId() );
	}

	public function testAssertWikiLocalUIV() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$user->assertWiki( UserIdentityValue::LOCAL );
		$this->assertTrue( true, 'User is for same wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( $foreignWikiId );
	}

	public function testAssertWikiForeignUIV() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', $foreignWikiId );

		$user->assertWiki( $foreignWikiId );
		$this->assertTrue( true, 'User is for same wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( UserIdentityValue::LOCAL );
	}

	public function testNewAnonymous() {
		$user = UserIdentityValue::newAnonymous( 'TEST', 'acmewiki' );
		$this->assertFalse( $user->isRegistered() );
		$this->assertSame( 'TEST', $user->getName() );
		$this->assertSame( 0, $user->getId( 'acmewiki' ) );
		$this->assertSame( 'acmewiki', $user->getWikiId() );
	}

	public function testNewRegistered() {
		$user = UserIdentityValue::newRegistered( 1, 'TEST', 'acmewiki' );
		$this->assertTrue( $user->isRegistered() );
		$this->assertSame( 'TEST', $user->getName() );
		$this->assertSame( 1, $user->getId( 'acmewiki' ) );
		$this->assertSame( 'acmewiki', $user->getWikiId() );
	}

	public function testNewRegistered_invalid() {
		$this->expectException( InvalidArgumentException::class );
		UserIdentityValue::newRegistered( 0, 'TEST', 'acmewiki' );
	}

	public function testNewExternal() {
		$user = UserIdentityValue::newExternal( 'imported', 'TEST', 'acmewiki' );
		$this->assertFalse( $user->isRegistered() );
		$this->assertSame( 'imported>TEST', $user->getName() );
		$this->assertSame( 0, $user->getId( 'acmewiki' ) );
		$this->assertSame( 'acmewiki', $user->getWikiId() );
	}
}
