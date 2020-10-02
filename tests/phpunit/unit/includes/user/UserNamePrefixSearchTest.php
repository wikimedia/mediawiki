<?php

namespace MediaWiki\Tests\User;

use InvalidArgumentException;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWikiUnitTestCase;
use User;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers MediaWiki\User\UserNamePrefixSearch
 * @author DannyS712
 */
class UserNamePrefixSearchTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestSearch
	 * @param int $audienceType 1='public', 2=user without `hideuser` rights, 3=user with `hideuser` rights
	 * @param string $prefix if '', UserFactory::newFromName returns null, otherwise its the user name
	 * @param int $limit
	 * @param int $offset
	 * @param array $result
	 */
	public function testSearch( int $audienceType, $prefix, int $limit, int $offset, array $result ) {
		if ( $prefix === '' ) {
			$user = null;
		} else {
			$user = $this->createMock( User::class );
			$user->expects( $this->once() )
				->method( 'getName' )
				->willReturn( $prefix );
		}

		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->expects( $this->once() )
			->method( 'newFromName' )
			->with(
				$this->equalTo( $prefix )
			)
			->willReturn( $user );

		$permissionManager = $this->createMock( PermissionManager::class );
		if ( $audienceType === 1 ) {
			// 'public' audience
			$audience = UserNamePrefixSearch::AUDIENCE_PUBLIC;
			$permissionManager->expects( $this->never() )
				->method( 'userHasRight' );
			$excludeHidden = true;
		} else {
			if ( $audienceType === 2 ) {
				// no hideuser rights
				$hasHideuser = false;
				$excludeHidden = true;
			} else {
				// 3 - has hideuser rights
				$hasHideuser = true;
				$excludeHidden = false;
			}
			// specific to a user identity
			$audience = $this->createMock( User::class );
			$permissionManager->expects( $this->once() )
				->method( 'userHasRight' )
				->with(
					$this->equalTo( $audience ),
					$this->equalTo( 'hideuser' )
				)
				->willReturn( $hasHideuser );
		}

		$database = $this->createMock( Database::class );
		$database->expects( $this->once() )
			->method( 'anyString' )
			->willReturn( 'anyStringGoesHere' );
		$database->expects( $this->once() )
			->method( 'buildLike' )
			->with(
				$this->equalTo( $prefix ),
				$this->equalTo( 'anyStringGoesHere' )
			)
			->willReturn( 'LIKE ' . $prefix . 'anyStringGoesHere' );

		// Query parameters
		$tables = [ 'user' ];
		$conds = [ 'user_name LIKE ' . $prefix . 'anyStringGoesHere' ];
		$joinConds = [];
		if ( $excludeHidden ) {
			$tables[] = 'ipblocks';
			$conds['ipb_deleted'] = [ 0, null ];
			$joinConds['ipblocks'] = [ 'LEFT JOIN', 'user_id=ipb_user' ];
		}
		$options = [
			'LIMIT' => $limit,
			'ORDER BY' => 'user_name',
			'OFFSET' => $offset
		];
		$database->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				$this->equalTo( $tables ),
				$this->equalTo( 'user_name' ),
				$this->equalTo( $conds ),
				$this->equalTo( 'MediaWiki\User\UserNamePrefixSearch::search' ),
				$this->equalTo( $options ),
				$this->equalTo( $joinConds )
			)
			->willReturn( $result );

		$loadBalancer = $this->createMock( LoadBalancer::class );
		$loadBalancer->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA )
			->willReturn( $database );

		$userNamePrefixSearch = new UserNamePrefixSearch(
			$loadBalancer,
			$permissionManager,
			$userFactory
		);
		$res = $userNamePrefixSearch->search(
			$audience,
			$prefix,
			$limit,
			$offset
		);
		$this->assertSame( $result, $res );
	}

	public function provideTestSearch() {
		// [ $audienceType, $prefix, $limit, $offset, $result ]
		return [
			'public' => [
				1,
				'',
				10,
				0,
				[ 'public result goes here' ]
			],
			'user without hideuser rights' => [
				2,
				'prefix',
				10,
				5,
				[ 'public result goes here, since user cannot see anything hidden' ]
			],
			'user with hideuser rights' => [
				3,
				'anotherPrefix',
				15,
				2,
				[
					'result that is public',
					'also a result that is private'
				]
			],
		];
	}

	public function testSearchInvalidAudience() {
		$userFactory = $this->createMock( UserFactory::class );
		$permissionManager = $this->createMock( PermissionManager::class );
		$loadBalancer = $this->createMock( LoadBalancer::class );

		$userNamePrefixSearch = new UserNamePrefixSearch(
			$loadBalancer,
			$permissionManager,
			$userFactory
		);

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$audience must be AUDIENCE_PUBLIC or a UserIdentity' );
		$userNamePrefixSearch->search(
			'ThisIsTheInvalidAudience',
			'',
			1,
			0
		);
	}

}
