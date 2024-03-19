<?php

namespace MediaWiki\Tests\Api\Query;

use ApiQueryBase;
use ApiQueryBlockInfoTrait;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\MockDatabase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \ApiQueryBlockInfoTrait
 */
class ApiQueryBlockInfoTraitTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	public function testUsesApiBlockInfoTrait() {
		$this->assertTrue( method_exists( ApiQueryBlockInfoTrait::class, 'getBlockDetails' ),
			'ApiQueryBlockInfoTrait::getBlockDetails exists' );
	}

	/**
	 * @dataProvider provideAddDeletedUserFilter
	 */
	public function testAddDeletedUserFilter( $schema, $isAllowed, $expect ) {
		$this->overrideConfigValue( MainConfigNames::BlockTargetMigrationStage, $schema );

		// Fake timestamp to show up in the queries
		ConvertibleTimestamp::setFakeTime( '20190101000000' );

		$authority = $this->mockRegisteredAuthorityWithPermissions(
			$isAllowed ? [ 'hideuser' ] : [] );
		$db = new MockDatabase;
		$queryBuilder = $db->newSelectQueryBuilder()
			->from( 'table' );

		$mock = $this->getMockBuilder( ApiQueryBase::class )
			->disableOriginalConstructor()
			->onlyMethods( [
				'getQueryBuilder',
				'getDB',
				'getAuthority'
			] )
			->getMockForAbstractClass();

		$mock->method( 'getQueryBuilder' )->willReturn( $queryBuilder );
		$mock->method( 'getDB' )->willReturn( new MockDatabase );
		$mock->method( 'getAuthority' )->willReturn( $authority );

		TestingAccessWrapper::newFromObject( $mock )->addDeletedUserFilter();
		$data = $queryBuilder->getQueryInfo();
		$this->assertSame( $expect, $data );
	}

	public static function provideAddDeletedUserFilter() {
		return [
			'old unauthorized' => [
				SCHEMA_COMPAT_OLD,
				false,
				[
					'tables' => [ 'table' ],
					'fields' => [ 'hu_deleted' => '1=0' ],
					'conds' => [
						'NOT EXISTS (SELECT  1  FROM "ipblocks" "hu_ipblocks"    ' .
						'WHERE (hu_ipblocks.ipb_user=user_id) AND hu_ipblocks.ipb_deleted = 1  )' ],
					'options' => [],
					'join_conds' => [],
				],
			],
			'old authorized' => [
				SCHEMA_COMPAT_OLD,
				true,
				[
					'tables' => [ 'table' ],
					'fields' => [ 'hu_deleted' => 'EXISTS (SELECT  1  FROM "ipblocks" "hu_ipblocks"    ' .
						'WHERE (hu_ipblocks.ipb_user=user_id) AND hu_ipblocks.ipb_deleted = 1  )' ],
					'conds' => [],
					'options' => [],
					'join_conds' => []
				],
			],
			'new unauthorized' => [
				SCHEMA_COMPAT_NEW,
				false,
				[
					'tables' => [ 'table' ],
					'fields' => [ 'hu_deleted' => '1=0' ],
					'conds' => [ '(SELECT  1  FROM "block_target" "hu_block_target" ' .
						'JOIN "block" "hu_block" ON ((hu_block.bl_target=hu_block_target.bt_id))   ' .
						'WHERE (hu_block_target.bt_user=user_id) AND hu_block.bl_deleted = 1  ) IS NULL' ],
					'options' => [],
					'join_conds' => [],
				],
			],
			'new authorized' => [
				SCHEMA_COMPAT_NEW,
				true,
				[
					'tables' => [ 'table' ],
					'fields' => [ 'hu_deleted' => '(SELECT  1  FROM "block_target" "hu_block_target" ' .
						'JOIN "block" "hu_block" ON ((hu_block.bl_target=hu_block_target.bt_id))   ' .
						'WHERE (hu_block_target.bt_user=user_id) AND hu_block.bl_deleted = 1  ) IS NOT NULL' ],
					'conds' => [],
					'options' => [],
					'join_conds' => []
				],
			],
		];
	}

}
