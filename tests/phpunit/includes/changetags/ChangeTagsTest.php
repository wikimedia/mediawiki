<?php

use MediaWiki\MainConfigNames;

/**
 * @covers ChangeTags
 * @group Database
 */
class ChangeTagsTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed[] = 'change_tag';
		$this->tablesUsed[] = 'change_tag_def';

		// Truncate these to avoid the supposed-to-be-unused IDs in tests here turning
		// out to be used, leading ChangeTags::updateTags() to pick up bogus rc_id,
		// log_id, or rev_id values and run into unique constraint violations.
		$this->tablesUsed[] = 'recentchanges';
		$this->tablesUsed[] = 'logging';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'archive';
	}

	protected function tearDown(): void {
		ChangeTags::$avoidReopeningTablesForTesting = false;
		parent::tearDown();
	}

	private function emptyChangeTagsTables() {
		$dbw = wfGetDB( DB_PRIMARY );
		$dbw->delete( 'change_tag', '*' );
		$dbw->delete( 'change_tag_def', '*' );
	}

	// TODO most methods are not tested

	/** @dataProvider provideModifyDisplayQuery */
	public function testModifyDisplayQuery(
		$origQuery,
		$filter_tag,
		$useTags,
		$avoidReopeningTables,
		$modifiedQuery,
		$exclude = false
	) {
		$this->overrideConfigValue( MainConfigNames::UseTagFilter, $useTags );

		if ( $avoidReopeningTables && $this->db->getType() !== 'mysql' ) {
			$this->markTestSkipped( 'MySQL only' );
		}

		ChangeTags::$avoidReopeningTablesForTesting = $avoidReopeningTables;

		$rcId = 123;
		ChangeTags::updateTags( [ 'foo', 'bar', '0' ], [], $rcId );
		// HACK resolve deferred group concats (see comment in provideModifyDisplayQuery)
		if ( isset( $modifiedQuery['fields']['ts_tags'] ) ) {
			$modifiedQuery['fields']['ts_tags'] = wfGetDB( DB_REPLICA )
				->buildGroupConcatField( ...$modifiedQuery['fields']['ts_tags'] );
		}
		if ( isset( $modifiedQuery['exception'] ) ) {
			$this->expectException( $modifiedQuery['exception'] );
		}
		ChangeTags::modifyDisplayQuery(
			$origQuery['tables'],
			$origQuery['fields'],
			$origQuery['conds'],
			$origQuery['join_conds'],
			$origQuery['options'],
			$filter_tag,
			$exclude
		);
		if ( !isset( $modifiedQuery['exception'] ) ) {
			$this->assertArrayEquals(
				$modifiedQuery,
				$origQuery,
				/* ordered = */ false,
				/* named = */ true
			);
		}
	}

	public function provideModifyDisplayQuery() {
		// HACK if we call $dbr->buildGroupConcatField() now, it will return the wrong table names
		// We have to have the test runner call it instead
		$baseConcats = [ ',', [ 'change_tag', 'change_tag_def' ], 'ctd_name' ];
		$joinConds = [ 'change_tag_def' => [ 'JOIN', 'ct_tag_id=ctd_id' ] ];
		$groupConcats = [
			'recentchanges' => array_merge( $baseConcats, [ 'ct_rc_id=rc_id', $joinConds ] ),
			'logging' => array_merge( $baseConcats, [ 'ct_log_id=log_id', $joinConds ] ),
			'revision' => array_merge( $baseConcats, [ 'ct_rev_id=rev_id', $joinConds ] ),
			'archive' => array_merge( $baseConcats, [ 'ct_rev_id=ar_rev_id', $joinConds ] ),
		];

		return [
			'simple recentchanges query' => [
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp' ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				'', // no tag filter
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				]
			],
			"simple query with strings, tagfilter=''" => [
				[
					'tables' => 'recentchanges',
					'fields' => 'rc_id',
					'conds' => "rc_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY rc_timestamp DESC',
				],
				'', // no tag filter
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY rc_timestamp DESC' ],
				]
			],
			'simple query with strings, tagfilter=false' => [
				[
					'tables' => 'recentchanges',
					'fields' => 'rc_id',
					'conds' => "rc_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY rc_timestamp DESC',
				],
				false, // no tag filter
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY rc_timestamp DESC' ],
				]
			],
			'simple query with strings, tagfilter=null' => [
				[
					'tables' => 'recentchanges',
					'fields' => 'rc_id',
					'conds' => "rc_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY rc_timestamp DESC',
				],
				null, // no tag filter
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY rc_timestamp DESC' ],
				]
			],
			'recentchanges query with single tag filter' => [
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp' ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				'foo',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				]
			],
			'recentchanges query with "0" tag filter' => [
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp' ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				'0',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag_id' => [ 3 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				]
			],
			'logging query with single tag filter and strings' => [
				[
					'tables' => 'logging',
					'fields' => 'log_id',
					'conds' => "log_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY log_timestamp DESC',
				],
				'foo',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'logging', 'change_tag' ],
					'fields' => [ 'log_id', 'ts_tags' => $groupConcats['logging'] ],
					'conds' => [ "log_timestamp > '20170714183203'", 'ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_log_id=log_id' ] ],
					'options' => [ 'ORDER BY log_timestamp DESC' ],
				]
			],
			'revision query with single tag filter' => [
				[
					'tables' => [ 'revision' ],
					'fields' => [ 'rev_id', 'rev_timestamp' ],
					'conds' => [ "rev_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rev_timestamp DESC' ],
				],
				'foo',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'revision', 'change_tag' ],
					'fields' => [ 'rev_id', 'rev_timestamp', 'ts_tags' => $groupConcats['revision'] ],
					'conds' => [ "rev_timestamp > '20170714183203'", 'ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rev_id=rev_id' ] ],
					'options' => [ 'ORDER BY' => 'rev_timestamp DESC' ],
				]
			],
			'archive query with single tag filter' => [
				[
					'tables' => [ 'archive' ],
					'fields' => [ 'ar_id', 'ar_timestamp' ],
					'conds' => [ "ar_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'ar_timestamp DESC' ],
				],
				'foo',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'archive', 'change_tag' ],
					'fields' => [ 'ar_id', 'ar_timestamp', 'ts_tags' => $groupConcats['archive'] ],
					'conds' => [ "ar_timestamp > '20170714183203'", 'ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rev_id=ar_rev_id' ] ],
					'options' => [ 'ORDER BY' => 'ar_timestamp DESC' ],
				]
			],
			'archive query with single tag filter, avoiding reopening tables' => [
				[
					'tables' => [ 'archive' ],
					'fields' => [ 'ar_id', 'ar_timestamp' ],
					'conds' => [ "ar_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'ar_timestamp DESC' ],
				],
				'foo',
				true, // tag filtering enabled
				true, // avoid reopening tables
				[
					'tables' => [ 'archive', 'change_tag_for_display_query' ],
					'fields' => [ 'ar_id', 'ar_timestamp', 'ts_tags' => $groupConcats['archive'] ],
					'conds' => [ "ar_timestamp > '20170714183203'", 'ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'change_tag_for_display_query' => [ 'JOIN', 'ct_rev_id=ar_rev_id' ] ],
					'options' => [ 'ORDER BY' => 'ar_timestamp DESC' ],
				]
			],
			'unsupported table name throws exception (even without tag filter)' => [
				[
					'tables' => [ 'foobar' ],
					'fields' => [ 'fb_id', 'fb_timestamp' ],
					'conds' => [ "fb_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'fb_timestamp DESC' ],
				],
				'',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[ 'exception' => MWException::class ]
			],
			'tag filter ignored when tag filtering is disabled' => [
				[
					'tables' => [ 'archive' ],
					'fields' => [ 'ar_id', 'ar_timestamp' ],
					'conds' => [ "ar_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'ar_timestamp DESC' ],
				],
				'foo',
				false, // tag filtering disabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'archive' ],
					'fields' => [ 'ar_id', 'ar_timestamp', 'ts_tags' => $groupConcats['archive'] ],
					'conds' => [ "ar_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'ar_timestamp DESC' ],
				]
			],
			'recentchanges query with multiple tag filter' => [
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp' ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				[ 'foo', 'bar' ],
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC', 'DISTINCT' ],
				]
			],
			'recentchanges query with exclusive multiple tag filter' => [
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp' ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				[ 'foo', 'bar' ],
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'change_tag.ct_tag_id IS NULL' ],
					'join_conds' => [ 'change_tag' => [ 'LEFT JOIN', [ 'ct_rc_id=rc_id', 'ct_tag_id' => [ 1, 2 ] ] ] ],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				true // exclude
			],
			'recentchanges query with multiple tag filter that already has DISTINCT' => [
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp' ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'DISTINCT', 'ORDER BY' => 'rc_timestamp DESC' ],
				],
				[ 'foo', 'bar' ],
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'DISTINCT', 'ORDER BY' => 'rc_timestamp DESC' ],
				]
			],
			'recentchanges query with multiple tag filter with strings' => [
				[
					'tables' => 'recentchanges',
					'fields' => 'rc_id',
					'conds' => "rc_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY rc_timestamp DESC',
				],
				[ 'foo', 'bar' ],
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'change_tag' => [ 'JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY rc_timestamp DESC', 'DISTINCT' ],
				]
			],
			'recentchanges query with multiple tag filter with strings, avoiding reopening tables' => [
				[
					'tables' => 'recentchanges',
					'fields' => 'rc_id',
					'conds' => "rc_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY rc_timestamp DESC',
				],
				[ 'foo', 'bar' ],
				true, // tag filtering enabled
				true, // avoid reopening tables
				[
					'tables' => [ 'recentchanges', 'change_tag_for_display_query' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'change_tag_for_display_query' => [ 'JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY rc_timestamp DESC', 'DISTINCT' ],
				]
			],
		];
	}

	public static function dataGetSoftwareTags() {
		return [
			[
				[
					'mw-contentModelChange' => true,
					'mw-redirect' => true,
					'mw-rollback' => true,
					'mw-blank' => true,
					'mw-replace' => true,
				],
				[
					'mw-rollback',
					'mw-replace',
					'mw-blank',
				]
			],

			[
				[
					'mw-contentmodelchanged' => true,
					'mw-replace' => true,
					'mw-new-redirects' => true,
					'mw-changed-redirect-target' => true,
					'mw-rolback' => true,
					'mw-blanking' => false
				],
				[
					'mw-replace',
					'mw-changed-redirect-target'
				]
			],

			[
				[
					null,
					false,
					'Lorem ipsum',
					'mw-translation'
				],
				[]
			],

			[
				[],
				[]
			]
		];
	}

	/**
	 * @dataProvider dataGetSoftwareTags
	 * @covers ChangeTags::getSoftwareTags
	 */
	public function testGetSoftwareTags( $softwareTags, $expected ) {
		$this->overrideConfigValue( MainConfigNames::SoftwareTags, $softwareTags );

		$actual = ChangeTags::getSoftwareTags();
		// Order of tags in arrays is not important
		sort( $expected );
		sort( $actual );
		$this->assertEquals( $expected, $actual );
	}

	public function testUpdateTags() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		$revId = 341;
		ChangeTags::updateTags( [ 'tag1', 'tag2' ], [], $rcId, $revId );

		$this->assertSelect(
			'change_tag_def',
			[ 'ctd_name', 'ctd_id', 'ctd_count' ],
			'',
			[
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
				[ 'tag2', 2, 1 ],
			]
		);
		$this->assertSelect(
			'change_tag',
			[ 'ct_tag_id', 'ct_rc_id', 'ct_rev_id' ],
			'',
			[
				// values of fields 'ct_tag_id', 'ct_rc_id', 'ct_rev_id'
				[ 1, 123, 341 ],
				[ 2, 123, 341 ],
			]
		);

		$rcId = 124;
		$revId = 342;
		ChangeTags::updateTags( [ 'tag1' ], [], $rcId, $revId );
		ChangeTags::updateTags( [ 'tag3' ], [], $rcId, $revId );

		$this->assertSelect(
			'change_tag_def',
			[ 'ctd_name', 'ctd_id', 'ctd_count' ],
			'',
			[
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 2 ],
				[ 'tag2', 2, 1 ],
				[ 'tag3', 3, 1 ],
			]
		);
		$this->assertSelect(
			'change_tag',
			[ 'ct_tag_id', 'ct_rc_id', 'ct_rev_id' ],
			'',
			[
				// values of fields 'ct_tag_id', 'ct_rc_id', 'ct_rev_id'
				[ 1, 123, 341 ],
				[ 1, 124, 342 ],
				[ 2, 123, 341 ],
				[ 3, 124, 342 ],
			]
		);
	}

	public function testUpdateTagsSkipDuplicates() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		ChangeTags::updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		ChangeTags::updateTags( [ 'tag2', 'tag3' ], [], $rcId );

		$this->assertSelect(
			'change_tag_def',
			[ 'ctd_name', 'ctd_id', 'ctd_count' ],
			'',
			[
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
				[ 'tag2', 2, 1 ],
				[ 'tag3', 3, 1 ],
			]
		);
		$this->assertSelect(
			'change_tag',
			[ 'ct_tag_id', 'ct_rc_id' ],
			'',
			[
				// values of fields 'ct_tag_id', 'ct_rc_id',
				[ 1, 123 ],
				[ 2, 123 ],
				[ 3, 123 ],
			]
		);
	}

	public function testUpdateTagsDoNothingOnRepeatedCall() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		ChangeTags::updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		$res = ChangeTags::updateTags( [ 'tag2', 'tag1' ], [], $rcId );
		$this->assertEquals( [ [], [], [ 'tag1', 'tag2' ] ], $res );

		$this->assertSelect(
			'change_tag_def',
			[ 'ctd_name', 'ctd_id', 'ctd_count' ],
			'',
			[
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
				[ 'tag2', 2, 1 ],
			]
		);
		$this->assertSelect(
			'change_tag',
			[ 'ct_tag_id', 'ct_rc_id' ],
			'',
			[
				// values of fields 'ct_tag_id', 'ct_rc_id',
				[ 1, 123 ],
				[ 2, 123 ],
			]
		);
	}

	public function testDeleteTags() {
		$this->emptyChangeTagsTables();
		$this->getServiceContainer()->resetServiceForTesting( 'NameTableStoreFactory' );

		$rcId = 123;
		ChangeTags::updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		ChangeTags::updateTags( [], [ 'tag2' ], $rcId );

		$this->assertSelect(
			'change_tag_def',
			[ 'ctd_name', 'ctd_id', 'ctd_count' ],
			'',
			[
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
			]
		);
		$this->assertSelect(
			'change_tag',
			[ 'ct_tag_id', 'ct_rc_id' ],
			'',
			[
				// values of fields 'ct_tag_id', 'ct_rc_id',
				[ 1, 123 ],
			]
		);
	}

	public function provideTags() {
		$tags = [ 'tag 1', 'tag 2', 'tag 3' ];
		$rcId = 123;
		$revId = 456;
		$logId = 789;

		yield [ $tags, $rcId, null, null ];
		yield [ $tags, null, $revId, null ];
		yield [ $tags, null, null, $logId ];
		yield [ $tags, $rcId, $revId, null ];
		yield [ $tags, $rcId, null, $logId ];
		yield [ $tags, $rcId, $revId, $logId ];
	}

	/**
	 * @dataProvider provideTags
	 */
	public function testGetTags( array $tags, $rcId, $revId, $logId ) {
		ChangeTags::addTags( $tags, $rcId, $revId, $logId );

		$actualTags = ChangeTags::getTags( $this->db, $rcId, $revId, $logId );

		$this->assertSame( $tags, $actualTags );
	}

	public function testGetTags_multiple_arguments() {
		$rcId = 123;
		$revId = 456;
		$logId = 789;

		ChangeTags::addTags( [ 'tag 1' ], $rcId );
		ChangeTags::addTags( [ 'tag 2' ], $rcId, $revId );
		ChangeTags::addTags( [ 'tag 3' ], $rcId, $revId, $logId );

		$tags3 = [ 'tag 3' ];
		$tags2 = array_merge( $tags3, [ 'tag 2' ] );
		$tags1 = array_merge( $tags2, [ 'tag 1' ] );
		$this->assertArrayEquals( $tags3, ChangeTags::getTags( $this->db, $rcId, $revId, $logId ) );
		$this->assertArrayEquals( $tags2, ChangeTags::getTags( $this->db, $rcId, $revId ) );
		$this->assertArrayEquals( $tags1, ChangeTags::getTags( $this->db, $rcId ) );
	}

	public function testGetTagsWithData() {
		$rcId1 = 123;
		$rcId2 = 456;
		$rcId3 = 789;
		ChangeTags::addTags( [ 'tag 1' ], $rcId1, null, null, 'data1' );
		ChangeTags::addTags( [ 'tag 3_1' ], $rcId3, null, null );
		ChangeTags::addTags( [ 'tag 3_2' ], $rcId3, null, null, 'data3_2' );

		$data = ChangeTags::getTagsWithData( $this->db, $rcId1 );
		$this->assertSame( [ 'tag 1' => 'data1' ], $data );

		$data = ChangeTags::getTagsWithData( $this->db, $rcId2 );
		$this->assertSame( [], $data );

		$data = ChangeTags::getTagsWithData( $this->db, $rcId3 );
		$this->assertArrayEquals( [ 'tag 3_1' => null, 'tag 3_2' => 'data3_2' ], $data, false, true );
	}

	public function testTagUsageStatistics() {
		$this->emptyChangeTagsTables();
		$this->getServiceContainer()->resetServiceForTesting( 'NameTableStoreFactory' );

		$rcId = 123;
		ChangeTags::updateTags( [ 'tag1', 'tag2' ], [], $rcId );

		$rcId = 124;
		ChangeTags::updateTags( [ 'tag1' ], [], $rcId );

		$this->assertEquals( [ 'tag1' => 2, 'tag2' => 1 ], ChangeTags::tagUsageStatistics() );
	}

	public function testListExplicitlyDefinedTags() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		ChangeTags::updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		ChangeTags::defineTag( 'tag2' );

		$this->assertEquals( [ 'tag2' ], ChangeTags::listExplicitlyDefinedTags() );

		$this->assertSelect(
			'change_tag_def',
			[ 'ctd_name', 'ctd_user_defined' ],
			'',
			[
				// values of fields 'ctd_name', 'ctd_user_defined'
				[ 'tag1', 0 ],
				[ 'tag2', 1 ],
			],
			[ 'ORDER BY' => 'ctd_name' ]
		);
	}

	public function provideFormatSummaryRow() {
		yield 'nothing' => [ '', [ '', [] ] ];
		yield 'valid tag' => [
			'tag1',
			[
				'<span class="mw-tag-markers">(tag-list-wrapper: 1, '
				. '<span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
				. ')</span>',
				[ 'mw-tag-tag1' ]
			]
		];
		yield '0 tag' => [
			'0',
			[
				'<span class="mw-tag-markers">(tag-list-wrapper: 1, '
				. '<span class="mw-tag-marker mw-tag-marker-0">(tag-0)</span>'
				. ')</span>',
				[ 'mw-tag-0' ]
			]
		];
		yield 'hidden tag' => [
			'hidden-tag',
			[
				'',
				[ 'mw-tag-hidden-tag' ]
			]
		];
		yield 'mutliple tags' => [
			'tag1,0,,hidden-tag',
			[
				'<span class="mw-tag-markers">(tag-list-wrapper: 2, '
				. '<span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
				. ' <span class="mw-tag-marker mw-tag-marker-0">(tag-0)</span>'
				. ')</span>',
				[ 'mw-tag-tag1', 'mw-tag-0', 'mw-tag-hidden-tag' ]
			]
		];
	}

	/**
	 * @dataProvider provideFormatSummaryRow
	 */
	public function testFormatSummaryRow( $tags, $expected ) {
		$qqx = new MockMessageLocalizer();
		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )
			->willReturnCallback( static function ( $key, ...$params ) use ( $qqx ) {
				if ( $key === 'tag-hidden-tag' ) {
					return new RawMessage( '-' );
				}
				return $qqx->msg( $key, ...$params );
			} );

		$out = ChangeTags::formatSummaryRow( $tags, 'dummy', $localizer );
		$this->assertSame( $expected, $out );
	}

}
