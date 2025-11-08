<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\User\MultiFormatUserIdentityLookup
 */
class MultiFormatUserIdentityLookupTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	public function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::LocalDatabases, [ WikiMap::getCurrentWikiId(), 'wiki' ] );
		$this->overrideConfigValue( MainConfigNames::UserrightsInterwikiDelimiter, '@' );
	}

	/** @dataProvider provideParseUserDesignator */
	public function testParseUserDesignator( string $designator, string $expectedName, string|false $expectedWikiId ) {
		$service = $this->getServiceContainer()->getMultiFormatUserIdentityLookup();
		$wrappedService = TestingAccessWrapper::newFromObject( $service );
		[ $name, $wikiId ] = $wrappedService->parseUserDesignator( $designator );

		$this->assertSame( $expectedName, $name );
		$this->assertSame( $expectedWikiId, $wikiId );
	}

	public static function provideParseUserDesignator(): array {
		return [
			'username only' => [ 'Example', 'Example', UserIdentity::LOCAL ],
			'username with interwiki' => [ 'Example@wiki', 'Example', 'wiki' ],
			'user ID only' => [ '#123', '#123', UserIdentity::LOCAL ],
			'user ID with interwiki' => [ '#123@wiki', '#123', 'wiki' ],
			'username with delimiter at end' => [ 'Example@', 'Example', '' ],
			'user ID with delimiter at end' => [ '#123@', '#123', '' ],
			'multiple delimiters in username' => [ 'Exa@mple@wiki', 'Exa', 'mple' ],
			'username with spaces around' => [ '  Example  ', 'Example', UserIdentity::LOCAL ],
			'username with interwiki and spaces around' => [ ' Example @ wiki ', 'Example', 'wiki' ],
			'explicit local wiki' => [ 'Example@' . WikiMap::getCurrentWikiId(), 'Example', UserIdentity::LOCAL ],
		];
	}

	public function testLookupFailsOnNonLocalDatabase() {
		$service = $this->getServiceContainer()->getMultiFormatUserIdentityLookup();
		$status = $service->getUserIdentity( 'Example@nonlocal' )->getStatusValue();
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'userrights-nodatabase' ) );
	}

	/** @dataProvider provideLookupFailsForEmptyUsername */
	public function testLookupFailsForEmptyUsername( string $designator, string $message ) {
		$service = $this->getServiceContainer()->getMultiFormatUserIdentityLookup();
		$status = $service->getUserIdentity( $designator )->getStatusValue();
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( $message ) );
	}

	public static function provideLookupFailsForEmptyUsername(): array {
		return [
			'empty string' => [ '', 'nouserspecified' ],
			'only spaces' => [ '   ', 'nouserspecified' ],
			'only delimiter' => [ '@', 'userrights-nodatabase' ],
			'only spaces and delimiter' => [ '   @   ', 'userrights-nodatabase' ],
			'only wiki is set' => [ '@wiki', 'nouserspecified' ],
			'spaces and wiki' => [ ' @wiki', 'nouserspecified' ],
		];
	}

	public function testLookupForIP() {
		$service = $this->getServiceContainer()->getMultiFormatUserIdentityLookup();
		$status = $service->getUserIdentity( '127.0.0.1' )->getStatusValue();
		$this->assertTrue( $status->isOK() );
		$user = $status->value;
		$this->assertSame( '127.0.0.1', $user->getName() );
		$this->assertSame( UserIdentity::LOCAL, $user->getWikiId() );
		$this->assertFalse( $user->isRegistered() );
	}

	/** @dataProvider provideFetchUser */
	public function testFetchUser(
		string $designator,
		int $expectedId,
		string $expectedName,
		string|false $expectedWiki,
		bool $shouldUseNameLookup
	) {
		$lookupMock = $this->createMock( UserIdentityLookup::class );
		$lookupMock->method( 'getUserIdentityByName' )
			->willReturnCallback(
				function ( $name ) use ( $expectedId, $expectedName, $expectedWiki, $shouldUseNameLookup ) {
					$this->assertTrue( $shouldUseNameLookup, 'Expected name lookup to be used' );
					$this->assertSame( $expectedName, $name );
					return new UserIdentityValue( $expectedId, $expectedName, $expectedWiki );
				} );
		$lookupMock->method( 'getUserIdentityByUserId' )
			->willReturnCallback(
				function ( $id ) use ( $expectedId, $expectedName, $expectedWiki, $shouldUseNameLookup ) {
					$this->assertFalse( $shouldUseNameLookup, 'Expected ID lookup to be used' );
					$this->assertSame( $expectedId, $id );
					return new UserIdentityValue( $expectedId, $expectedName, $expectedWiki );
				} );

		$lookupFactoryMock = $this->createMock( ActorStoreFactory::class );
		$lookupFactoryMock->method( 'getUserIdentityLookup' )
			->willReturnCallback( function ( $wikiId ) use ( $expectedWiki, $lookupMock ) {
				$this->assertSame( $expectedWiki, $wikiId );
				return $lookupMock;
			} );

		$this->setService( 'ActorStoreFactory', $lookupFactoryMock );
		$service = $this->getServiceContainer()->getMultiFormatUserIdentityLookup();
		$status = $service->getUserIdentity( $designator )->getStatusValue();
		$this->assertTrue( $status->isOK() );
		$user = $status->value;
		$this->assertInstanceOf( UserIdentity::class, $user );
		$this->assertSame( $expectedId, $user->getId( $expectedWiki ) );
		$this->assertSame( $expectedName, $user->getName() );
		$this->assertSame( $expectedWiki, $user->getWikiId() );
	}

	public static function provideFetchUser(): array {
		return [
			'local user by name' => [
				'designator' => 'Example',
				'expectedId' => 1,
				'expectedName' => 'Example',
				'expectedWiki' => UserIdentity::LOCAL,
				'shouldUseNameLookup' => true,
			],
			'local user by name, explicit wiki' => [
				'designator' => 'Example@' . WikiMap::getCurrentWikiId(),
				'expectedId' => 1,
				'expectedName' => 'Example',
				'expectedWiki' => UserIdentity::LOCAL,
				'shouldUseNameLookup' => true,
			],
			'remote user by name' => [
				'designator' => 'Example@wiki',
				'expectedId' => 1,
				'expectedName' => 'Example',
				'expectedWiki' => 'wiki',
				'shouldUseNameLookup' => true,
			],
			'local user by id' => [
				'designator' => '#1',
				'expectedId' => 1,
				'expectedName' => 'Example',
				'expectedWiki' => UserIdentity::LOCAL,
				'shouldUseNameLookup' => false,
			],
			'local user by id, explicit wiki' => [
				'designator' => '#2@' . WikiMap::getCurrentWikiId(),
				'expectedId' => 2,
				'expectedName' => 'Example',
				'expectedWiki' => UserIdentity::LOCAL,
				'shouldUseNameLookup' => false,
			],
			'remote user by id' => [
				'designator' => '#3@wiki',
				'expectedId' => 3,
				'expectedName' => 'Example',
				'expectedWiki' => 'wiki',
				'shouldUseNameLookup' => false,
			],
		];
	}

	/** @dataProvider provideCanSeeHiddenUser */
	public function testCanSeeHiddenUser(
		string|false $targetWiki,
		bool $isHidden,
		// If $canSeeHidden is null, don't pass an authority to getUserIdentity()
		?bool $canSeeHidden,
		bool $expected
	) {
		$lookupMock = $this->createMock( UserIdentityLookup::class );
		$lookupMock->method( 'getUserIdentityByName' )
			->willReturn( new UserIdentityValue( 1, 'TargetUser', $targetWiki ) );

		$lookupFactoryMock = $this->createMock( ActorStoreFactory::class );
		$lookupFactoryMock->method( 'getUserIdentityLookup' )
			->willReturn( $lookupMock );

		$mockUser = $this->createMock( User::class );
		$mockUser->method( 'isHidden' )
			->willReturn( $isHidden );

		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->method( 'newFromUserIdentity' )
			->willReturn( $mockUser );

		$this->setService( 'ActorStoreFactory', $lookupFactoryMock );
		$this->setService( 'UserFactory', $userFactoryMock );
		$service = $this->getServiceContainer()->getMultiFormatUserIdentityLookup();

		$viewer = null;
		if ( $canSeeHidden !== null ) {
			$viewer = $this->mockAnonAuthorityWithPermissions( $canSeeHidden ? [ 'hideuser' ] : [] );
		}
		// The designator here doesn't matter, as we're mocking the lookup
		$status = $service->getUserIdentity( 'HiddenUser', $viewer )->getStatusValue();

		$this->assertSame( $expected, $status->isOK() );
		if ( $expected ) {
			$user = $status->value;
			$this->assertInstanceOf( UserIdentity::class, $user );
			$this->assertSame( 'TargetUser', $user->getName() );
			$this->assertSame( $targetWiki, $user->getWikiId() );
		} else {
			$this->assertTrue( $status->hasMessage( 'nosuchusershort' ) );
		}
	}

	public static function provideCanSeeHiddenUser(): array {
		return [
			'local hidden user, viewer can see hidden' => [
				'targetWiki' => UserIdentity::LOCAL,
				'isHidden' => true,
				'canSeeHidden' => true,
				'expected' => true,
			],
			'local hidden user, viewer cannot see hidden' => [
				'targetWiki' => UserIdentity::LOCAL,
				'isHidden' => true,
				'canSeeHidden' => false,
				'expected' => false,
			],
			'local visible user, viewer cannot see hidden' => [
				'targetWiki' => UserIdentity::LOCAL,
				'isHidden' => false,
				'canSeeHidden' => false,
				'expected' => true,
			],
			'local hidden user, no authority' => [
				'targetWiki' => UserIdentity::LOCAL,
				'isHidden' => true,
				'canSeeHidden' => null,
				'expected' => true,
			],
			'remote hidden user, viewer cannot see hidden (permission ignored for remote)' => [
				'targetWiki' => 'wiki',
				'isHidden' => true,
				'canSeeHidden' => false,
				'expected' => true,
			],
			'remote visible user, no authority' => [
				'targetWiki' => 'wiki',
				'isHidden' => false,
				'canSeeHidden' => null,
				'expected' => true,
			],
		];
	}
}
