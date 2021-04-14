<?php

use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\User\UserEditTracker
 *
 * @author DannyS712
 */
class UserEditTrackerTest extends MediaWikiUnitTestCase {
	private $reqId;

	/** @before */
	public function reqIdSetUp() {
		$this->reqId = WebRequest::getRequestId();
		WebRequest::overrideRequestId( '000' );
	}

	/** @after */
	public function reqIdTearDown() {
		WebRequest::overrideRequestId( $this->reqId );
	}

	public function testGetUserEditCount() {
		// getUserEditCount returns a value found in user_editcount
		$userId = 345623;
		$methodName = 'MediaWiki\User\UserEditTracker::getUserEditCount';
		$editCount = 5;

		$actorMigration = $this->createMock( ActorMigration::class );

		$database = $this->createMock( Database::class );
		$database->expects( $this->once() )
			->method( 'selectField' )
			->with(
				$this->equalTo( 'user' ),
				$this->equalTo( 'user_editcount' ),
				$this->equalTo( [ 'user_id' => $userId ] ),
				$this->equalTo( $methodName )
			)
			->willReturn( $editCount );
		$loadBalancer = $this->createMock( LoadBalancer::class );
		$loadBalancer->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA )
			->willReturn( $database );

		$user = new UserIdentityValue( $userId, 'TestUser' );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );
		$this->assertSame(
			$editCount,
			$tracker->getUserEditCount( $user )
		);

		// Now fetch from cache
		$this->assertSame(
			$editCount,
			$tracker->getUserEditCount( $user )
		);
	}

	public function testGetUserEditCount_exception() {
		// getUserEditCount throws if the user id is falsy
		$userId = 0;

		$actorMigration = $this->createMock( ActorMigration::class );
		$loadBalancer = $this->createMock( LoadBalancer::class );

		$user = $this->createMock( UserIdentity::class );
		$user = new UserIdentityValue( $userId, 'TestUser' );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'requires a user ID' );
		$tracker->getUserEditCount( $user );
	}

	public function testGetUserEditCount_null() {
		// getUserEditCount doesn't find a value in user_editcount and calls
		// initializeUserEditCount
		$userId = 343;
		$actorId = 9842;
		$methodName1 = 'MediaWiki\User\UserEditTracker::getUserEditCount';
		$methodName2 = 'MediaWiki\User\UserEditTracker::initializeUserEditCount';
		$editCount = 17;

		$user = new UserIdentityValue( $userId, 'TestUser' );

		$database1 = $this->createMock( Database::class );
		$database1->expects( $this->once() )
			->method( 'selectField' )
			->with(
				$this->equalTo( 'user' ),
				$this->equalTo( 'user_editcount' ),
				$this->equalTo( [ 'user_id' => $userId ] ),
				$this->equalTo( $methodName1 )
			)
			->willReturn( null );

		$database2 = $this->createMock( Database::class );
		$database2->expects( $this->once() )
			->method( 'selectField' )
			->with(
				$this->equalTo( [
					'revision',
					'temp_rev_user' => 'revision_actor_temp'
				] ),
				$this->equalTo( 'COUNT(*)' ),
				$this->equalTo( [ [ 'temp_rev_actor.revactor_actor' => $actorId ] ] ),
				$this->equalTo( $methodName2 ),
				$this->equalTo( [] ),
				$this->equalTo( [ 'temp_rev_actor' =>
					[ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ]
				] )
			)
			->willReturn( $editCount );

		$loadBalancer = $this->createMock( LoadBalancer::class );
		$loadBalancer->expects( $this->exactly( 2 ) )
			->method( 'getConnectionRef' )
			->withConsecutive(
				[ DB_REPLICA ],
				[ DB_REPLICA ]
			)
			->will(
				$this->onConsecutiveCalls(
					$database1,
					$database2
				)
			);
		$actorMigration = $this->createMock( ActorMigration::class );
		$actorMigration->expects( $this->once() )
			->method( 'getWhere' )
			->with(
				$this->equalTo( $database2 ),
				$this->equalTo( 'rev_user' ),
				$this->equalTo( $user )
			)
			->willReturn( [
				'tables' => [ 'temp_rev_user' => 'revision_actor_temp' ],
				'conds' => [ 'temp_rev_actor.revactor_actor' => $actorId ],
				'joins' => [ 'temp_rev_actor' =>
					[ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ]
				]
			] );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );
		$jobQueueGroup->expects( $this->once() )
			->method( 'push' )
			->willReturnCallback( function ( IJobSpecification $job ) use ( $user, $editCount ): void {
				$this->assertEquals( 'userEditCountInit', $job->getType() );
				$this->assertEquals( [
					'userId' => $user->getId(),
					'editCount' => $editCount,
					'requestId' => '000',
				], $job->getParams() );
			} );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );
		$this->assertSame(
			$editCount,
			$tracker->getUserEditCount( $user )
		);
	}

	public function testInitializeUserEditCount() {
		// initializeUserEditCount counts the revisions in the database
		$userId = 7281;
		$actorId = 3982;
		$methodName = 'MediaWiki\User\UserEditTracker::initializeUserEditCount';
		$editCount = 341;

		$user = new UserIdentityValue( $userId, 'TestUser' );

		$database1 = $this->createMock( Database::class );
		$database1->expects( $this->once() )
			->method( 'selectField' )
			->with(
				$this->equalTo( [
					'revision',
					'temp_rev_user' => 'revision_actor_temp'
				] ),
				$this->equalTo( 'COUNT(*)' ),
				$this->equalTo( [ [ 'temp_rev_actor.revactor_actor' => $actorId ] ] ),
				$this->equalTo( $methodName ),
				$this->equalTo( [] ),
				$this->equalTo( [ 'temp_rev_actor' =>
					[ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ]
				] )
			)
			->willReturn( $editCount );

		$loadBalancer = $this->createMock( LoadBalancer::class );
		$loadBalancer->expects( $this->any() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA )
			->willReturn( $database1 );
		$actorMigration = $this->createMock( ActorMigration::class );
		$actorMigration->expects( $this->once() )
			->method( 'getWhere' )
			->with(
				$this->equalTo( $database1 ),
				$this->equalTo( 'rev_user' ),
				$this->equalTo( $user )
			)
			->willReturn( [
				'tables' => [ 'temp_rev_user' => 'revision_actor_temp' ],
				'conds' => [ 'temp_rev_actor.revactor_actor' => $actorId ],
				'joins' => [ 'temp_rev_actor' =>
					[ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ]
				]
			] );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );
		$jobQueueGroup->expects( $this->once() )
			->method( 'push' )
			->willReturnCallback( function ( IJobSpecification $job ) use ( $user, $editCount ): void {
				$this->assertEquals( 'userEditCountInit', $job->getType() );
				$this->assertEquals( [
					'userId' => $user->getId(),
					'editCount' => $editCount,
					'requestId' => '000',
				], $job->getParams() );
			} );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );
		$this->assertSame(
			$editCount,
			$tracker->initializeUserEditCount( $user )
		);
	}

	/**
	 * @dataProvider provideTestGetEditTimestamp
	 * @param string $type either 'first' or 'latest'
	 * @param string $time either 'null' for returning null, or a timestamp
	 */
	public function testGetEditTimestamp( $type, $time ) {
		$methodName = 'MediaWiki\User\UserEditTracker::getUserEditTimestamp';
		$actorId = 982110;

		$user = new UserIdentityValue( 1, 'TestUser' );

		$expectedSort = ( $type === 'first' ) ? 'ASC' : 'DESC';
		$dbTime = ( $time === 'null' ) ? null : $time;

		$database = $this->createMock( Database::class );
		$database->expects( $this->once() )
			->method( 'selectField' )
			->with(
				$this->equalTo( [
					'revision',
					'temp_rev_user' => 'revision_actor_temp'
				] ),
				$this->equalTo( 'revactor_timestamp' ),
				$this->equalTo( [ [ 'temp_rev_actor.revactor_actor' => $actorId ] ] ),
				$this->equalTo( $methodName ),
				$this->equalTo( [
					'ORDER BY' => 'revactor_timestamp ' . $expectedSort
				] ),
				$this->equalTo( [ 'temp_rev_actor' =>
					[ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ]
				] )
			)
			->willReturn( $dbTime );
		$loadBalancer = $this->createMock( LoadBalancer::class );
		$loadBalancer->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA )
			->willReturn( $database );

		$actorMigration = $this->createMock( ActorMigration::class );
		$actorMigration->expects( $this->once() )
			->method( 'getWhere' )
			->with(
				$this->equalTo( $database ),
				$this->equalTo( 'rev_user' ),
				$this->equalTo( $user )
			)
			->willReturn( [
				'tables' => [ 'temp_rev_user' => 'revision_actor_temp' ],
				'conds' => [ 'temp_rev_actor.revactor_actor' => $actorId ],
				'joins' => [ 'temp_rev_actor' =>
					[ 'JOIN', 'temp_rev_user.revactor_rev = rev_id' ]
				]
			] );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );
		if ( $type === 'first' ) {
			$ret = $tracker->getFirstEditTimestamp( $user );
		} else {
			$ret = $tracker->getLatestEditTimestamp( $user );
		}

		if ( $dbTime === null ) {
			$this->assertFalse( $ret );
		} else {
			$this->assertSame( wfTimestamp( TS_MW, $dbTime ), $ret );
		}
	}

	public function provideTestGetEditTimestamp() {
		return [
			'first with no edits' => [ 'first', 'null' ],
			'first with edits' => [ 'first', '20200421194632' ],
			'latest with no edits' => [ 'latest', 'null' ],
			'latest with edits' => [ 'latest', '20200421194632' ],
		];
	}

	public function testGetEditTimestamp_anon() {
		$actorMigration = $this->createMock( ActorMigration::class );
		$loadBalancer = $this->createMock( LoadBalancer::class );

		$user = new UserIdentityValue( 0, 'TestUser' );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );
		$this->assertFalse( $tracker->getFirstEditTimestamp( $user ) );
		$this->assertFalse( $tracker->getLatestEditTimestamp( $user ) );
	}

	public function testClearUserEditCache() {
		$actorMigration = $this->createMock( ActorMigration::class );
		$loadBalancer = $this->createMock( LoadBalancer::class );
		$jobQueueGroup = $this->createMock( JobQueueGroup::class );

		$tracker = new UserEditTracker( $actorMigration, $loadBalancer, $jobQueueGroup );

		$anon = new UserIdentityValue( 0, 'TestUser' );

		$user = new UserIdentityValue( 123, 'TestUser' );

		$accessible = TestingAccessWrapper::newFromObject( $tracker );
		$accessible->userEditCountCache = [ 'u123' => 5 ];

		$tracker->clearUserEditCache( $anon ); // getId called once, early return
		$tracker->clearUserEditCache( $user ); // actually cleared

		$this->assertNull( $accessible->userEditCountCache[ 'u123' ] );
	}

}
