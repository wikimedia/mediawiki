<?php

namespace MediaWiki\Tests\User;

use InvalidArgumentException;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use MediaWiki\User\User;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\User\UserNamePrefixSearch
 * @author DannyS712
 */
class UserNamePrefixSearchTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/**
	 * @dataProvider provideTestSearch
	 * @param int $audienceType 1='public', 2=user without `hideuser` rights, 3=user with `hideuser` rights
	 * @param string $prefix
	 * @param int $limit
	 * @param int $offset
	 * @param array $result
	 */
	public function testSearch( int $audienceType, $prefix, int $limit, int $offset, array $result ) {
		$userNameUtils = $this->getDummyUserNameUtils();

		if ( $audienceType === 1 ) {
			// 'public' audience
			$audience = UserNamePrefixSearch::AUDIENCE_PUBLIC;
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
			$audience->method( 'isAllowed' )
				->with( 'hideuser' )
				->willReturn( $hasHideuser );
		}

		$platform = new SQLPlatformTestHelper( new AddQuoterMock() );

		$database = $this->createMock( Database::class );
		$database->expects( $this->once() )
			->method( 'anyString' )
			->willReturn( 'anyStringGoesHere' );
		$args = [ 'user_name', IExpression::LIKE, new LikeValue( $prefix, 'anyStringGoesHere' ) ];
		$database->expects( $this->once() )
			->method( 'expr' )
			->with( ...$args )
			->willReturn( new Expression( ...$args ) );
		$database->expects( $this->any() )
			->method( 'selectSQLText' )
			->willReturnCallback(
				static function ( $table, $vars, $conds, $fname, $options, $join_conds )
				use ( $platform ) {
					return $platform->selectSQLText(
						$table, $vars, $conds, $fname, $options, $join_conds );
				}
			);

		// Query parameters
		$tables = [ 'user' ];
		$conds = [ new Expression( ...$args ) ];
		$joinConds = [];
		if ( $excludeHidden ) {
			$conds[] = '(SELECT  1  FROM block_target hu_block_target ' .
				'JOIN block hu_block ON ((hu_block.bl_target=hu_block_target.bt_id))   ' .
				'WHERE (hu_block_target.bt_user=user_id) AND hu_block.bl_deleted = 1  ' .
				'LIMIT 1  ) IS NULL';
		}
		$options = [
			'LIMIT' => $limit,
			'ORDER BY' => [ 'user_name' ],
			'OFFSET' => $offset
		];
		$database->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				$tables,
				'user_name',
				$conds,
				'MediaWiki\User\UserNamePrefixSearch::search',
				$options,
				$joinConds
			)
			->willReturn( $result );
		$database->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $database ) );

		$dbProvider = $this->createMock( IConnectionProvider::class );
		$dbProvider->expects( $this->once() )
			->method( 'getReplicaDatabase' )
			->willReturn( $database );

		$hideUserUtils = new HideUserUtils();

		$userNamePrefixSearch = new UserNamePrefixSearch(
			$dbProvider,
			$userNameUtils,
			$hideUserUtils
		);
		$res = $userNamePrefixSearch->search(
			$audience,
			$prefix,
			$limit,
			$offset
		);
		$this->assertSame( $result, $res );
	}

	public static function provideTestSearch() {
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
				'Prefix',
				10,
				5,
				[ 'public result goes here, since user cannot see anything hidden' ]
			],
			'user with hideuser rights' => [
				3,
				'AnotherPrefix',
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
		$userNamePrefixSearch = new UserNamePrefixSearch(
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( UserNameUtils::class ),
			new HideUserUtils()
		);

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$audience must be AUDIENCE_PUBLIC or an Authority object' );
		$userNamePrefixSearch->search(
			'ThisIsTheInvalidAudience',
			'',
			1,
			0
		);
	}

}
