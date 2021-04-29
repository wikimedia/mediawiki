<?php

use MediaWiki\User\UserIdentityValue;
use Wikimedia\Assert\PreconditionException;

/**
 * @coversDefaultClass \MediaWiki\User\UserIdentityValue
 */
class UserIdentityValueTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ::getActorId
	 */
	public function testGetActorIdLocalUIVForeignParam() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$this->expectDeprecation();
		$this->assertSame( 0, $user->getActorId( $foreignWikiId ) );
	}

	/**
	 * @covers ::getActorId
	 */
	public function testGetActorIdDeprecated() {
		$user = new UserIdentityValue( 0, 'TestUserName' );

		$this->expectDeprecation();
		$this->assertSame( 0, $user->getActorId() );
	}

	/**
	 * @covers ::getActorId
	 */
	public function testGetActorIdLocalUIVNoParam() {
		$this->filterDeprecated( '/UserIdentityValue::getActorId was deprecated/' );
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$this->assertSame( 0, $user->getActorId() );
	}

	/**
	 * @covers ::getActorId
	 */
	public function testGetActorIdLocalUIVLocalParam() {
		$this->filterDeprecated( '/UserIdentityValue::getActorId was deprecated/' );
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$this->assertSame( 0, $user->getActorId( UserIdentityValue::LOCAL ) );
	}

	/**
	 * @covers ::getActorId
	 */
	public function testGetActorIdForeignUIVForeignParam() {
		$this->filterDeprecated( '/UserIdentityValue::getActorId was deprecated/' );
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', $foreignWikiId );

		$this->assertSame( 0, $user->getActorId( $foreignWikiId ) );
	}

	/**
	 * @return Generator
	 */
	public function provideWikiIds() {
		yield [ UserIdentityValue::LOCAL ];
		yield [ 'Foreign Wiki' ];
	}

	/**
	 * @covers ::getWikiId
	 * @dataProvider provideWikiIds
	 * @param string|false $wikiId
	 */
	public function testGetWiki( $wikiId ) {
		$user = new UserIdentityValue( 0, 'TestUserName', $wikiId );
		$this->assertSame( $wikiId, $user->getWikiId() );
	}

	/**
	 * @covers ::assertWiki
	 */
	public function testAssertWikiLocalUIV() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', UserIdentityValue::LOCAL );

		$user->assertWiki( UserIdentityValue::LOCAL );
		$this->assertTrue( true, 'User is for same wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( $foreignWikiId );
	}

	/**
	 * @covers ::assertWiki
	 */
	public function testAssertWikiForeignUIV() {
		$foreignWikiId = 'Foreign Wiki';
		$user = new UserIdentityValue( 0, 'TestUserName', $foreignWikiId );

		$user->assertWiki( $foreignWikiId );
		$this->assertTrue( true, 'User is for same wiki' );

		$this->expectException( PreconditionException::class );
		$user->assertWiki( UserIdentityValue::LOCAL );
	}
}
