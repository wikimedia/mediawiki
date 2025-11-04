<?php
namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\User\TempUser\TempUserDetailsLookup
 */
class TempUserDetailsLookupTest extends MediaWikiUnitTestCase {
	private TempUserConfig $tempUserConfig;
	private UserRegistrationLookup $userRegistrationLookup;

	private TempUserDetailsLookup $tempUserDetailsLookup;

	protected function setUp(): void {
		parent::setUp();

		$this->tempUserConfig = $this->createMock( TempUserConfig::class );
		$this->userRegistrationLookup = $this->createMock( UserRegistrationLookup::class );

		$this->tempUserDetailsLookup = new TempUserDetailsLookup( $this->tempUserConfig, $this->userRegistrationLookup );
	}

	/**
	 * @dataProvider provideIsExpired
	 *
	 * @param UserIdentity $user The user to check
	 * @param string|null $registration The stored registration timestamp for this user in MediaWiki format,
	 * or `null` for none
	 * @param bool $expired Whether the user is expected to be expired
	 */
	public function testIsExpired(
		UserIdentity $user,
		?string $registration,
		bool $expired
	): void {
		ConvertibleTimestamp::setFakeTime( '20250101000000' );

		$isTemp = str_starts_with( $user->getName(), '~' );

		$this->tempUserConfig->method( 'isTempName' )
			->with( $user->getName() )
			->willReturn( $isTemp );

		if ( $isTemp ) {
			$this->userRegistrationLookup->method( 'getFirstRegistration' )
				->with( $user )
				->willReturn( $registration );
		} else {
			$this->userRegistrationLookup->expects( $this->never() )
				->method( $this->anything() );
		}

		$this->tempUserConfig->method( 'getExpireAfterDays' )
			->willReturn( 30 );

		$actual = $this->tempUserDetailsLookup->isExpired( $user );
		$cached = $this->tempUserDetailsLookup->isExpired( $user );

		$this->assertSame( $expired, $actual );
		$this->assertSame( $expired, $cached );
	}

	public static function provideIsExpired(): iterable {
		yield 'legacy IP user' => [
			new UserIdentityValue( 0, '1.1.1.1' ),
			null,
			false
		];

		yield 'named user' => [
			new UserIdentityValue( 1, 'Foo' ),
			'20231015000000',
			false
		];

		yield 'non-expired temporary account' => [
			new UserIdentityValue( 2, '~2024-1' ),
			'20241215000000',
			false
		];

		yield 'temporary account with missing user record' => [
			new UserIdentityValue( 3, '~2024-2' ),
			null,
			false
		];

		yield 'expired temporary account' => [
			new UserIdentityValue( 4, '~2024-3' ),
			'20241115000000',
			true
		];
	}

	public function testPreloadExpirationStatus(): void {
		ConvertibleTimestamp::setFakeTime( '20250101000000' );

		$users = [
			new UserIdentityValue( 1, '~2024-1' ),
			new UserIdentityValue( 2, '~2024-2' ),
			new UserIdentityValue( 3, '~2024-3' ),
			new UserIdentityValue( 4, 'Test' ),
			new UserIdentityValue( 0, '127.0.0.1' )
		];

		$this->tempUserConfig->method( 'getExpireAfterDays' )
			->willReturn( 30 );

		$this->tempUserConfig->method( 'isTempName' )
			->willReturnMap( [
				[ '~2024-1', true ],
				[ '~2024-2', true ],
				[ '~2024-3', true ],
				[ 'Test', false ],
			] );

		$this->userRegistrationLookup->method( 'getFirstRegistrationBatch' )
			->willReturnCallback( function ( iterable $usersIt ) use ( $users ): array {
				$this->assertSame(
					[ $users[0], $users[1], $users[2] ],
					iterator_to_array( $usersIt )
				);
				return [
					// expired
					1 => '20241115000000',
					// missing
					2 => null,
					// non-expired
					3 => '20241215000000',
				];
			} );

		$this->tempUserDetailsLookup->preloadExpirationStatus( $users );

		$this->assertTrue( $this->tempUserDetailsLookup->isExpired( $users[0] ) );
		$this->assertFalse( $this->tempUserDetailsLookup->isExpired( $users[1] ) );
		$this->assertFalse( $this->tempUserDetailsLookup->isExpired( $users[2] ) );
	}

	public function testPreloadExpirationStatusWithoutUsers(): void {
		$user = new UserIdentityValue( 1, 'Test' );

		$this->tempUserConfig->method( 'isTempName' )
			->with( $user->getName() )
			->willReturn( false );

		$this->userRegistrationLookup->method( 'getFirstRegistrationBatch' )
			->willReturnCallback( function ( iterable $users ): array {
				$this->assertSame( [], iterator_to_array( $users ) );
				return [];
			} );

		$this->tempUserDetailsLookup->preloadExpirationStatus( [ $user ] );
	}
}
