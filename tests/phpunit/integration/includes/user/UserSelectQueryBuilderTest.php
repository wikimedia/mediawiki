<?php

namespace MediaWiki\Tests\User;

use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserSelectQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @group Database
 * @covers \MediaWiki\User\UserSelectQueryBuilder
 * @coversDefaultClass \MediaWiki\User\UserSelectQueryBuilder
 * @package MediaWiki\Tests\User
 */
class UserSelectQueryBuilderTest extends ActorStoreTestBase {
	public static function provideFetchUserIdentitiesByNamePrefix() {
		yield 'nothing found' => [
			'z_z_Z_Z_z_Z_z_z', // $prefix
			[ 'limit' => 100 ], // $options
			[], // $expected
		];
		yield 'default parameters' => [
			'Test', // $prefix
			[ 'limit' => 100 ], // $options
			[
				new UserIdentityValue( 24, 'TestUser' ),
				new UserIdentityValue( 25, 'TestUser1' ),
			], // $expected
		];
		yield 'limited' => [
			'Test', // $prefix
			[ 'limit' => 1 ], // $options
			[
				new UserIdentityValue( 24, 'TestUser' ),
			], // $expected
		];
		yield 'sorted' => [
			'Test', // $prefix
			[
				'sort' => UserSelectQueryBuilder::SORT_DESC,
				'limit' => 100,
			], // $options
			[
				new UserIdentityValue( 25, 'TestUser1' ),
				new UserIdentityValue( 24, 'TestUser' ),
			], // $expected
		];
	}

	/**
	 * @dataProvider provideFetchUserIdentitiesByNamePrefix
	 */
	public function testFetchUserIdentitiesByNamePrefix( string $prefix, array $options, array $expected ) {
		$queryBuilder = $this->getStore()
			->newSelectQueryBuilder()
			->limit( $options['limit'] )
			->whereUserNamePrefix( $prefix )
			->caller( __METHOD__ )
			->orderByName( $options['sort'] ?? SelectQueryBuilder::SORT_ASC );
		$actors = iterator_to_array( $queryBuilder->fetchUserIdentities() );
		$this->assertSameSize( $expected, $actors );
		foreach ( $expected as $idx => $expectedActor ) {
			$this->assertSameActors( $expectedActor, $actors[$idx] );
		}
	}

	public static function provideFetchUserIdentitiesByUserIds() {
		yield 'default parameters' => [
			[ 24, 25 ], // ids
			[], // $options
			[
				new UserIdentityValue( 24, 'TestUser' ),
				new UserIdentityValue( 25, 'TestUser1' ),
			], // $expected
		];
		yield 'sorted' => [
			[ 24, 25 ], // ids
			[ 'sort' => UserSelectQueryBuilder::SORT_DESC ], // $options
			[
				new UserIdentityValue( 25, 'TestUser1' ),
				new UserIdentityValue( 24, 'TestUser' ),
			], // $expected
		];
	}

	/**
	 * @dataProvider provideFetchUserIdentitiesByUserIds
	 */
	public function testFetchUserIdentitiesByUserIds( array $ids, array $options, array $expected ) {
		$actors = iterator_to_array(
			$this->getStore()
				->newSelectQueryBuilder()
				->whereUserIds( $ids )
				->caller( __METHOD__ )
				->orderByUserId( $options['sort'] ?? SelectQueryBuilder::SORT_ASC )
				->fetchUserIdentities()
		);
		$this->assertSameSize( $expected, $actors );
		foreach ( $expected as $idx => $expectedActor ) {
			$this->assertSameActors( $expectedActor, $actors[$idx] );
		}
	}

	public static function provideFetchUserIdentitiesByNames() {
		yield 'default parameters' => [
			[ 'TestUser', 'TestUser1' ], // $names
			[], // $options
			[
				new UserIdentityValue( 24, 'TestUser' ),
				new UserIdentityValue( 25, 'TestUser1' ),
			], // $expected
		];
		yield 'sorted' => [
			[ 'TestUser', 'TestUser1' ], // $names
			[ 'sort' => UserSelectQueryBuilder::SORT_DESC ], // $options
			[
				new UserIdentityValue( 25, 'TestUser1' ),
				new UserIdentityValue( 24, 'TestUser' ),
			], // $expected
		];
		yield 'with IPs' => [
			[ self::IP ], // $names
			[], // $options
			[
				new UserIdentityValue( 0, self::IP ),
			], // $expected
		];
		yield 'with IPs, normalization' => [
			[ strtolower( self::IP ), self::IP ], // $names
			[], // $options
			[
				new UserIdentityValue( 0, self::IP ),
			], // $expected
		];
	}

	/**
	 * @dataProvider provideFetchUserIdentitiesByNames
	 */
	public function testFetchUserIdentitiesByNames( array $names, array $options, array $expected ) {
		$actors = iterator_to_array(
			$this->getStore()
				->newSelectQueryBuilder()
				->whereUserNames( $names )
				->caller( __METHOD__ )
				->orderByUserId( $options['sort'] ?? SelectQueryBuilder::SORT_ASC )
				->fetchUserIdentities()
		);
		$this->assertSameSize( $expected, $actors );
		foreach ( $expected as $idx => $expectedActor ) {
			$this->assertSameActors( $expectedActor, $actors[$idx] );
		}
	}

	/**
	 * @covers ::fetchUserIdentity
	 */
	public function testFetchUserIdentity() {
		$this->assertSameActors(
			new UserIdentityValue( 24, 'TestUser' ),
			$this->getStore()
				->newSelectQueryBuilder()
				->whereUserIds( 24 )
				->fetchUserIdentity()
		);
	}

	/**
	 * @covers ::fetchUserNames
	 */
	public function testFetchUserNames() {
		$this->assertArrayEquals(
			[ 'TestUser', 'TestUser1' ],
			$this->getStore()
				->newSelectQueryBuilder()
				->conds( [ 'actor_id' => [ 42, 44 ] ] )
				->fetchUserNames()
		);
	}

	/**
	 * @covers ::registered
	 */
	public function testRegistered() {
		$actors = iterator_to_array(
			$this->getStore()
				->newSelectQueryBuilder()
				->conds( [ 'actor_id' => [ 42, 43 ] ] )
				->registered()
				->fetchUserIdentities()
		);
		$this->assertCount( 1, $actors );
		$this->assertSameActors(
			new UserIdentityValue( 24, 'TestUser' ),
			$actors[0]
		);
	}

	/**
	 * @covers ::anon
	 */
	public function testAnon() {
		$actors = iterator_to_array(
			$this->getStore()
				->newSelectQueryBuilder()
				->limit( 100 )
				->whereUserNamePrefix( '' )
				->anon()
				->fetchUserIdentities()
		);
		$this->assertCount( 2, $actors );
		$this->assertSameActors(
			new UserIdentityValue( 0, self::IP ),
			$actors[0]
		);
	}

	/**
	 * @covers ::hidden
	 */
	public function testHidden() {
		$hiddenUser = $this->getMutableTestUser()->getUserIdentity();
		$normalUser = $this->getMutableTestUser()->getUserIdentity();
		$this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$hiddenUser,
			$this->getTestSysop()->getUser(),
			'infinity',
			'Test',
			[
				'isHideUser' => true
			]
		)->placeBlockUnsafe( true );

		// hidden set to true
		$actors = iterator_to_array(
			$this->getStore()
			->newSelectQueryBuilder()
			->limit( 100 )
			->whereUserIds( [
				$hiddenUser->getId(),
				$normalUser->getId()
			] )
			->hidden( true )
			->fetchUserIdentities()
		);
		$this->assertCount( 1, $actors );
		$this->assertSameActors(
			$hiddenUser,
			$actors[0]
		);

		// hidden set to false
		$actors = iterator_to_array(
			$this->getStore()
				->newSelectQueryBuilder()
				->limit( 100 )
				->whereUserIds( [
					$hiddenUser->getId(),
					$normalUser->getId()
				] )
				->hidden( false )
				->fetchUserIdentities()
		);
		$this->assertCount( 1, $actors );
		$this->assertSameActors(
			$normalUser,
			$actors[0]
		);

		// hidden not set
		$usernames = $this->getStore()
			->newSelectQueryBuilder()
			->limit( 100 )
			->whereUserIds( [
				$hiddenUser->getId(),
				$normalUser->getId()
			] )
			->fetchUserNames();
		$this->assertCount( 2, $usernames );
		$this->assertArrayEquals(
			[ $normalUser->getName(), $hiddenUser->getName() ],
			$usernames
		);
	}
}
