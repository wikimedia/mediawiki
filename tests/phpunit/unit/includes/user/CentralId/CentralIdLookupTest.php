<?php

namespace MediaWiki\Tests\User\CentralId;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\User\CentralId\CentralIdLookup
 */
class CentralIdLookupTest extends MediaWikiUnitTestCase {
	use MockAuthorityTrait;

	public function testGetProviderId() {
		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->init( 'this is a test', $this->createNoOpMock( UserIdentityLookup::class ),
			$this->createNoOpMock( UserFactory::class ) );
		$this->assertSame( 'this is a test', $mock->getProviderId() );
	}

	public function testRepeatingInitThrows() {
		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->init( 'foo', $this->createNoOpMock( UserIdentityLookup::class ),
			$this->createNoOpMock( UserFactory::class ) );
		$this->expectException( LogicException::class );
		$mock->init( 'bar', $this->createNoOpMock( UserIdentityLookup::class ),
			$this->createNoOpMock( UserFactory::class ) );
	}

	public function testCheckAudience() {
		$mock = TestingAccessWrapper::newFromObject(
			$this->getMockForAbstractClass( CentralIdLookup::class )
		);
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newAnonymous' )->willReturnCallback( function () {
			$user = $this->createNoOpMock( User::class );
			$user->mFrom = 'defaults';
			return $user;
		} );
		$mock->init( 'test', $this->createNoOpMock( UserIdentityLookup::class ), $userFactory );

		$authority = $this->mockAnonUltimateAuthority();
		$this->assertSame( $authority, $mock->checkAudience( $authority ) );

		$authority = $mock->checkAudience( CentralIdLookup::AUDIENCE_PUBLIC );
		$this->assertInstanceOf( Authority::class, $authority );
		// Should be an empty User object, don't trigger loading that requires integration
		// if a user is created from "defaults" then its an anonymous default user
		$this->assertInstanceOf( User::class, $authority );
		$this->assertSame( 'defaults', $authority->mFrom );

		$this->assertNull( $mock->checkAudience( CentralIdLookup::AUDIENCE_RAW ) );

		try {
			$mock->checkAudience( 100 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Invalid audience', $ex->getMessage() );
		}
	}

	public function testNameFromCentralId() {
		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->expects( $this->once() )->method( 'lookupCentralIds' )
			->with(
				[ 15 => null ],
				CentralIdLookup::AUDIENCE_RAW,
				IDBAccessObject::READ_LATEST
			)
			->willReturn( [ 15 => 'FooBar' ] );

		$this->assertSame(
			'FooBar',
			$mock->nameFromCentralId( 15, CentralIdLookup::AUDIENCE_RAW, IDBAccessObject::READ_LATEST )
		);
	}

	/**
	 * @dataProvider provideLocalUserFromCentralId
	 * @param string $name
	 * @param bool $succeeds
	 */
	public function testLocalUserFromCentralId( $name, $succeeds ) {
		// UserIdentityLookup expects to be asked for a UserIdentity with the
		// name $name, and will return one only if its for UTSysop, no other
		// users are registered
		$lookupResult = ( $name === 'UTSysop' ? ( new UserIdentityValue( 5, $name ) ) : null );
		$userIdentityLookup = $this->createMock( UserIdentityLookup::class );
		$userIdentityLookup->method( 'getUserIdentityByName' )
			->with( $name )
			->willReturn( $lookupResult );
		$userFactory = $this->createMock( UserFactory::class );

		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->method( 'isAttached' )
			->willReturn( true );
		$mock->expects( $this->once() )->method( 'lookupCentralIds' )
			->with(
				[ 42 => null ],
				CentralIdLookup::AUDIENCE_RAW,
				IDBAccessObject::READ_LATEST
			)
			->willReturn( [ 42 => $name ] );

		$mock->init( 'test', $userIdentityLookup, $userFactory );
		$user = $mock->localUserFromCentralId(
			42, CentralIdLookup::AUDIENCE_RAW, IDBAccessObject::READ_LATEST
		);
		if ( $succeeds ) {
			$this->assertInstanceOf( UserIdentity::class, $user );
			$this->assertSame( $name, $user->getName() );
		} else {
			$this->assertNull( $user );
		}

		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->method( 'isAttached' )
			->willReturn( false );
		$mock->expects( $this->once() )->method( 'lookupCentralIds' )
			->with(
				[ 42 => null ],
				CentralIdLookup::AUDIENCE_RAW,
				IDBAccessObject::READ_LATEST
			)
			->willReturn( [ 42 => $name ] );
		$mock->init( 'test', $userIdentityLookup, $userFactory );

		$this->assertNull(
			$mock->localUserFromCentralId( 42, CentralIdLookup::AUDIENCE_RAW, IDBAccessObject::READ_LATEST )
		);
	}

	public static function provideLocalUserFromCentralId() {
		return [
			[ 'UTSysop', true ],
			[ 'UTDoesNotExist', false ],
			[ null, false ],
			[ '', false ],
			[ '<X>', false ],
		];
	}

	public function testCentralIdFromName() {
		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->expects( $this->once() )->method( 'lookupUserNamesWithFilter' )
			->with(
				[ 'FooBar' => 0 ],
				CentralIdLookup::FILTER_NONE,
				CentralIdLookup::AUDIENCE_RAW,
				IDBAccessObject::READ_LATEST
			)
			->willReturn( [ 'FooBar' => 23 ] );

		$this->assertSame(
			23,
			$mock->centralIdFromName( 'FooBar', CentralIdLookup::AUDIENCE_RAW, IDBAccessObject::READ_LATEST )
		);
	}

	public function testCentralIdFromLocalUser() {
		$mock = $this->getMockForAbstractClass( CentralIdLookup::class );
		$mock->expects( $this->once() )->method( 'lookupUserNamesWithFilter' )
			->with(
				[ 'FooBar' => 0 ],
				CentralIdLookup::FILTER_ATTACHED,
				CentralIdLookup::AUDIENCE_RAW,
				IDBAccessObject::READ_LATEST
			)
			->willReturn( [ 'FooBar' => 23 ] );

		$this->assertSame(
			23,
			$mock->centralIdFromLocalUser(
				new UserIdentityValue( 10, 'FooBar' ),
				CentralIdLookup::AUDIENCE_RAW,
				IDBAccessObject::READ_LATEST
			)
		);
	}

}
