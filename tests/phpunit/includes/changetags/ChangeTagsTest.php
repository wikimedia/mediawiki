<?php

/**
 * @covers ChangeTags
 */
class ChangeTagsTest extends MediaWikiTestCase {

	// TODO only modifyDisplayQuery and getSoftwareTags are tested, nothing else is

	/** @dataProvider provideModifyDisplayQuery */
	public function testModifyDisplayQuery( $origQuery, $filter_tag, $useTags, $modifiedQuery ) {
		$this->setMwGlobals( 'wgUseTagFilter', $useTags );
		// HACK resolve deferred group concats (see comment in provideModifyDisplayQuery)
		if ( isset( $modifiedQuery['fields']['ts_tags'] ) ) {
			$modifiedQuery['fields']['ts_tags'] = call_user_func_array(
				[ wfGetDB( DB_REPLICA ), 'buildGroupConcatField' ],
				$modifiedQuery['fields']['ts_tags']
			);
		}
		if ( isset( $modifiedQuery['exception'] ) ) {
			$this->setExpectedException( $modifiedQuery['exception'] );
		}
		ChangeTags::modifyDisplayQuery(
			$origQuery['tables'],
			$origQuery['fields'],
			$origQuery['conds'],
			$origQuery['join_conds'],
			$origQuery['options'],
			$filter_tag
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
		$groupConcats = [
			'recentchanges' => [ ',', 'change_tag', 'ct_tag', 'ct_rc_id=rc_id' ],
			'logging' => [ ',', 'change_tag', 'ct_tag', 'ct_log_id=log_id' ],
			'revision' => [ ',', 'change_tag', 'ct_tag', 'ct_rev_id=rev_id' ],
			'archive' => [ ',', 'change_tag', 'ct_tag', 'ct_rev_id=ar_rev_id' ],
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
				[
					'tables' => [ 'recentchanges' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'" ],
					'join_conds' => [],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				]
			],
			'simple query with strings' => [
				[
					'tables' => 'recentchanges',
					'fields' => 'rc_id',
					'conds' => "rc_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => 'ORDER BY rc_timestamp DESC',
				],
				'', // no tag filter
				true, // tag filtering enabled
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
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag' => 'foo' ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_rc_id=rc_id' ] ],
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
				[
					'tables' => [ 'logging', 'change_tag' ],
					'fields' => [ 'log_id', 'ts_tags' => $groupConcats['logging'] ],
					'conds' => [ "log_timestamp > '20170714183203'", 'ct_tag' => 'foo' ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_log_id=log_id' ] ],
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
				[
					'tables' => [ 'revision', 'change_tag' ],
					'fields' => [ 'rev_id', 'rev_timestamp', 'ts_tags' => $groupConcats['revision'] ],
					'conds' => [ "rev_timestamp > '20170714183203'", 'ct_tag' => 'foo' ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_rev_id=rev_id' ] ],
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
				[
					'tables' => [ 'archive', 'change_tag' ],
					'fields' => [ 'ar_id', 'ar_timestamp', 'ts_tags' => $groupConcats['archive'] ],
					'conds' => [ "ar_timestamp > '20170714183203'", 'ct_tag' => 'foo' ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_rev_id=ar_rev_id' ] ],
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
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag' => [ 'foo', 'bar' ] ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC', 'DISTINCT' ],
				]
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
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag' => [ 'foo', 'bar' ] ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_rc_id=rc_id' ] ],
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
				[
					'tables' => [ 'recentchanges', 'change_tag' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'ct_tag' => [ 'foo', 'bar' ] ],
					'join_conds' => [ 'change_tag' => [ 'INNER JOIN', 'ct_rc_id=rc_id' ] ],
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
					'mw-replace' => true
				],
				[
					'mw-rollback',
					'mw-replace',
					'mw-blank'
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
		$this->setMwGlobals( 'wgSoftwareTags', $softwareTags );

		$actual = ChangeTags::getSoftwareTags();
		// Order of tags in arrays is not important
		sort( $expected );
		sort( $actual );
		$this->assertEquals( $expected, $actual );
	}
}
