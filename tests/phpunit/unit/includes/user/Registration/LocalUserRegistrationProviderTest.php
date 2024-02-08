<?php

namespace MediaWiki\Tests\User\Registration;

use Exception;
use MediaWiki\User\Registration\LocalUserRegistrationProvider;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\User\Registration\LocalUserRegistrationProvider
 */
class LocalUserRegistrationProviderTest extends MediaWikiUnitTestCase {

	private IConnectionProvider $connectionProvider;

	private LocalUserRegistrationProvider $provider;

	protected function setUp(): void {
		parent::setUp();

		$this->connectionProvider = $this->createMock( IConnectionProvider::class );
		$this->provider = new LocalUserRegistrationProvider( $this->connectionProvider );
	}

	public function testFetchRegistration() {
		$qbMock = $this->createMock( SelectQueryBuilder::class );
		$qbMock->method( $this->anythingBut(
			'fetchResultSet', 'fetchField', 'fetchFieldValues', 'fetchRow',
			'fetchRowCount', 'estimateRowCount'
		) )->willReturnSelf();
		$qbMock->expects( $this->once() )
			->method( 'fetchField' )
			->willReturn( '20200102000000' );
		$dbMock = $this->createMock( IDatabase::class );
		$dbMock->expects( $this->once() )
			->method( 'newSelectQueryBuilder' )
			->willReturn( $qbMock );

		$this->connectionProvider->expects( $this->once() )
			->method( 'getReplicaDatabase' )
			->willReturn( $dbMock );

		$userIdentity = new UserIdentityValue( 123, 'Admin' );
		$this->assertSame( '20200102000000', $this->provider->fetchRegistration( $userIdentity ) );
	}

	/**
	 * @dataProvider provideInvalidInput
	 */
	public function testFetchRegistrationBatchInvalidInput(
		array $users,
		Exception $expectedException
	): void {
		$this->expectExceptionObject( $expectedException );

		$this->provider->fetchRegistrationBatch( $users );
	}

	public static function provideInvalidInput(): iterable {
		yield 'foreign UserIdentity instance' => [
			[ new UserIdentityValue( 123, 'Admin', 'otherwiki' ) ],
			new PreconditionException(
				'Expected ' . UserIdentityValue::class . ' to belong to the local wiki, but it belongs to \'otherwiki\''
			)
		];
	}

	public function testFetchRegistrationBatchShouldBatchQueries(): void {
		$users = [];

		for ( $i = 1; $i <= 2_000; $i++ ) {
			$users[] = new UserIdentityValue( $i, 'TestUser' . $i );
		}

		$firstIdBatch = range( 1, 1_000 );
		$secondIdBatch = range( 1_001, 2_000 );

		$firstBatchSelectQueryBuilder = $this->createMock( SelectQueryBuilder::class );
		$firstBatchSelectQueryBuilder->method( $this->anythingBut( 'fetchResultSet' ) )
			->willReturnSelf();
		$firstBatchSelectQueryBuilder->method( 'fetchResultSet' )
			->willReturn( new FakeResultWrapper(
				array_map(
					static fn ( int $userId ) => (object)[ 'user_id' => $userId, 'user_registration' => '20250101000000' ],
					$firstIdBatch
				)
			) );

		$secondBatchSelectQueryBuilder = $this->createMock( SelectQueryBuilder::class );
		$secondBatchSelectQueryBuilder->method( $this->anythingBut( 'fetchResultSet' ) )
			->willReturnSelf();
		$secondBatchSelectQueryBuilder->method( 'fetchResultSet' )
			->willReturn( new FakeResultWrapper(
				array_map(
					static fn ( int $userId ) =>(object)[ 'user_id' => $userId, 'user_registration' => '20260101000000' ],
					$secondIdBatch
				)
			) );

		$selectQueryBuilder = $this->createMock( SelectQueryBuilder::class );
		$selectQueryBuilder->method( $this->anythingBut( 'where' ) )
			->willReturnSelf();
		$selectQueryBuilder->method( 'where' )
			->willReturnMap( [
				[ [ 'user_id' => $firstIdBatch ], $firstBatchSelectQueryBuilder ],
				[ [ 'user_id' => $secondIdBatch ], $secondBatchSelectQueryBuilder ]
			] );

		$dbr = $this->createMock( IDatabase::class );
		$dbr->method( 'newSelectQueryBuilder' )
			->willReturn( $selectQueryBuilder );

		$this->connectionProvider->method( 'getReplicaDatabase' )
			->willReturn( $dbr );

		$timestampsById = $this->provider->fetchRegistrationBatch( $users );

		$this->assertCount( 2_000, $timestampsById );
		$this->assertSame( '20250101000000', $timestampsById[1] );
		$this->assertSame( '20260101000000', $timestampsById[2_000] );
	}

	public function testFetchRegistrationBatchShouldHandleDuplicateUsersAndMissingTimestamps(): void {
		$user = new UserIdentityValue( 1, 'TestUser' );
		$otherUser = new UserIdentityValue( 2, 'OtherUser' );

		$users = [ $user, $otherUser, $user ];

		$selectQueryBuilder = $this->createMock( SelectQueryBuilder::class );
		$selectQueryBuilder->method( $this->anythingBut( 'where', 'fetchResultSet' ) )
			->willReturnSelf();
		$selectQueryBuilder->method( 'where' )
			->with( [ 'user_id' => [ $user->getId(), $otherUser->getId() ] ] )
			->willReturnSelf();
		$selectQueryBuilder->method( 'fetchResultSet' )
			->willReturn( new FakeResultWrapper( [
				(object)[ 'user_id' => $user->getId(), 'user_registration' => '20250101000000' ],
				(object)[ 'user_id' => $otherUser->getId(), 'user_registration' => null ],
			] ) );

		$dbr = $this->createMock( IDatabase::class );
		$dbr->method( 'newSelectQueryBuilder' )
			->willReturn( $selectQueryBuilder );

		$this->connectionProvider->method( 'getReplicaDatabase' )
			->willReturn( $dbr );

		$timestampsById = $this->provider->fetchRegistrationBatch( $users );

		$this->assertCount( 2, $timestampsById );
		$this->assertSame( '20250101000000', $timestampsById[$user->getId()] );
		$this->assertNull( $timestampsById[$otherUser->getId()] );
	}

	public function testFetchRegistrationBatchShouldHandleNoUsers(): void {
		$dbr = $this->createNoOpMock( IDatabase::class );

		$this->connectionProvider->method( 'getReplicaDatabase' )
			->willReturn( $dbr );

		$timestampsById = $this->provider->fetchRegistrationBatch( [] );

		$this->assertSame( [], $timestampsById );
	}
}
