<?php

namespace MediaWiki\Tests\ChangeTags;

use InvalidArgumentException;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsStore
 * @group Database
 */
class ChangeTagsStoreTest extends MediaWikiIntegrationTestCase {

	use RestrictedTagTestTrait;

	private ChangeTagsStore $changeTags;

	protected function setUp(): void {
		parent::setUp();

		$this->changeTags = $this->getServiceContainer()->getChangeTagsStore();
	}

	/** @dataProvider provideModifyDisplayQueryBuilder */
	public function testModifyDisplayQueryBuilder(
		string $table,
		string $idField,
		string $joinCond,
		$filterTag,
		bool $useTags,
		bool $exclude,
		array $expected
	): void {
		$this->hideDeprecated( ChangeTagsStore::class . '::modifyDisplayQueryBuilder' );
		$this->overrideConfigValue( MainConfigNames::UseTagFilter, $useTags );
		// Reset the ChangeTagsStore after the config change
		$this->changeTags = $this->getServiceContainer()->getChangeTagsStore();

		$rcId = 123;
		$this->changeTags->updateTags( [ 'foo', 'bar', '0' ], [], $rcId );

		$queryBuilder = $this->getDb()->newSelectQueryBuilder()
			->select( $idField )
			->from( $table )
			->where( "$idField > 5" )
			->orderBy( $idField, 'DESC' );

		$this->changeTags->modifyDisplayQueryBuilder( $queryBuilder, $table, $filterTag, $exclude );

		$info = $queryBuilder->getQueryInfo();

		// ts_tags is always selected, restricted tags are always hidden from it
		$dbr = $this->getDb();
		$expectedTsTags = $dbr->newSelectQueryBuilder()
			->table( 'change_tag' )
			->join( 'change_tag_def', null, 'ct_tag_id=ctd_id' )
			->field( 'ctd_name' )
			->where( $joinCond )
			->andWhere( $dbr->expr(
				'ctd_name',
				IExpression::NOT_LIKE,
				new LikeValue( ChangeTagsStore::PRIVATE_TAG_PREFIX, $dbr->anyString() )
			) )
			->buildGroupConcatField( ',' );
		$this->assertSame( $expectedTsTags, $info['fields']['ts_tags'] );

		$this->assertSame( $expected['tables'], $info['tables'] );
		$this->assertSame( $expected['conds'], $info['conds'] );
		$this->assertSame( $expected['join_conds'], $info['join_conds'] );
		$this->assertSame(
			$expected['distinct'],
			in_array( 'DISTINCT', $info['options'], true ),
			'DISTINCT is added only when selecting on more than one tag'
		);
	}

	public static function provideModifyDisplayQueryBuilder(): iterable {
		$alias = 'changetagdisplay';
		$tables = static fn ( string $table ) => [ $table, $alias => 'change_tag' ];
		$join = static fn ( string $cond ) => [ $alias => [ 'JOIN', "$alias.$cond" ] ];

		yield 'recentchanges, no tag filter' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', '', true, false,
			[ 'tables' => [ 'recentchanges' ], 'conds' => [ 'rc_id > 5' ], 'join_conds' => [], 'distinct' => false ],
		];
		yield 'recentchanges, tagfilter=false' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', false, true, false,
			[ 'tables' => [ 'recentchanges' ], 'conds' => [ 'rc_id > 5' ], 'join_conds' => [], 'distinct' => false ],
		];
		yield 'recentchanges, tagfilter=null' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', null, true, false,
			[ 'tables' => [ 'recentchanges' ], 'conds' => [ 'rc_id > 5' ], 'join_conds' => [], 'distinct' => false ],
		];
		yield 'recentchanges, single tag filter' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', 'foo', true, false,
			[
				'tables' => $tables( 'recentchanges' ),
				'conds' => [ 'rc_id > 5', "$alias.ct_tag_id" => [ 1 ] ],
				'join_conds' => $join( 'ct_rc_id=rc_id' ),
				'distinct' => false,
			],
		];
		yield 'recentchanges, "0" tag filter' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', '0', true, false,
			[
				'tables' => $tables( 'recentchanges' ),
				'conds' => [ 'rc_id > 5', "$alias.ct_tag_id" => [ 3 ] ],
				'join_conds' => $join( 'ct_rc_id=rc_id' ),
				'distinct' => false,
			],
		];
		yield 'logging, single tag filter' => [
			'logging', 'log_id', 'ct_log_id=log_id', 'foo', true, false,
			[
				'tables' => $tables( 'logging' ),
				'conds' => [ 'log_id > 5', "$alias.ct_tag_id" => [ 1 ] ],
				'join_conds' => $join( 'ct_log_id=log_id' ),
				'distinct' => false,
			],
		];
		yield 'revision, single tag filter' => [
			'revision', 'rev_id', 'ct_rev_id=rev_id', 'foo', true, false,
			[
				'tables' => $tables( 'revision' ),
				'conds' => [ 'rev_id > 5', "$alias.ct_tag_id" => [ 1 ] ],
				'join_conds' => $join( 'ct_rev_id=rev_id' ),
				'distinct' => false,
			],
		];
		yield 'archive, single tag filter' => [
			'archive', 'ar_rev_id', 'ct_rev_id=ar_rev_id', 'foo', true, false,
			[
				'tables' => $tables( 'archive' ),
				'conds' => [ 'ar_rev_id > 5', "$alias.ct_tag_id" => [ 1 ] ],
				'join_conds' => $join( 'ct_rev_id=ar_rev_id' ),
				'distinct' => false,
			],
		];
		yield 'tag filter ignored when tag filtering is disabled' => [
			'archive', 'ar_rev_id', 'ct_rev_id=ar_rev_id', 'foo', false, false,
			[ 'tables' => [ 'archive' ], 'conds' => [ 'ar_rev_id > 5' ], 'join_conds' => [], 'distinct' => false ],
		];
		yield 'recentchanges, multiple tag filter adds DISTINCT' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', [ 'foo', 'bar' ], true, false,
			[
				'tables' => $tables( 'recentchanges' ),
				'conds' => [ 'rc_id > 5', "$alias.ct_tag_id" => [ 1, 2 ] ],
				'join_conds' => $join( 'ct_rc_id=rc_id' ),
				'distinct' => true,
			],
		];
		yield 'recentchanges, exclusive multiple tag filter' => [
			'recentchanges', 'rc_id', 'ct_rc_id=rc_id', [ 'foo', 'bar' ], true, true,
			[
				'tables' => $tables( 'recentchanges' ),
				'conds' => [ 'rc_id > 5', "$alias.ct_tag_id" => null ],
				'join_conds' => [ $alias => [
					'LEFT JOIN',
					[ "$alias.ct_rc_id=rc_id", "$alias.ct_tag_id" => [ 1, 2 ] ]
				] ],
				'distinct' => false,
			],
		];
	}

	public function testModifyDisplayQueryBuilderRejectsUnsupportedTable(): void {
		$this->hideDeprecated( ChangeTagsStore::class . '::modifyDisplayQueryBuilder' );
		$queryBuilder = $this->getDb()->newSelectQueryBuilder()
			->select( 'fb_id' )
			->from( 'foobar' );

		// Even without a tag filter, the table must be one tags can be joined to
		$this->expectException( InvalidArgumentException::class );
		$this->changeTags->modifyDisplayQueryBuilder( $queryBuilder, 'foobar' );
	}

	public function testModifyDisplayQueryBuilderFilterByRestrictedTagMatchesNothing(): void {
		$this->hideDeprecated( ChangeTagsStore::class . '::modifyDisplayQueryBuilder' );
		$this->overrideConfigValue( MainConfigNames::UseTagFilter, true );
		$this->setRestrictedTags( [ 'mw-private-secret' => [ 'suppress' ] ] );
		$store = $this->getServiceContainer()->getChangeTagsStore();

		$queryBuilder = $this->getDb()->newSelectQueryBuilder()
			->select( 'rc_id' )
			->from( 'recentchanges' );
		$store->modifyDisplayQueryBuilder( $queryBuilder, 'recentchanges', [ 'mw-private-secret' ] );

		$info = $queryBuilder->getQueryInfo();
		// The legacy method has no performer, so the restricted tag is never viewable
		$this->assertContains( '0=1', $info['conds'] );
		$this->assertNotContains( 'DISTINCT', $info['options'] );
	}

	public function testModifyDisplayQueryBuilderHidesRestrictedTags(): void {
		$this->hideDeprecated( ChangeTagsStore::class . '::modifyDisplayQueryBuilder' );
		$this->overrideConfigValue( MainConfigNames::UseTagFilter, true );
		$this->setRestrictedTags( [ 'mw-private-secret' => [ 'suppress' ] ] );
		$store = $this->getServiceContainer()->getChangeTagsStore();
		$revId = $this->makeTaggedRevision();

		$queryBuilder = $this->getDb()->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( [ 'rev_id' => $revId ] )
			->caller( __METHOD__ );
		$store->modifyDisplayQueryBuilder( $queryBuilder, 'revision' );

		$row = $queryBuilder->fetchRow();

		$this->assertNotFalse( $row );
		$this->assertArrayEquals( [ 'foo' ], explode( ',', $row->ts_tags ) );
	}

	private function makeTaggedRevision( array $tags = [ 'foo', 'mw-private-secret' ] ): int {
		$revId = $this->getExistingTestPage()->getRevisionRecord()->getId();
		$this->changeTags->addTags( $tags, null, $revId );

		return $revId;
	}

}
