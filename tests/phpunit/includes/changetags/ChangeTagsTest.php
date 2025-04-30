<?php

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsStore
 * @covers \MediaWiki\ChangeTags\ChangeTags
 * @group Database
 */
class ChangeTagsTest extends MediaWikiIntegrationTestCase {

	private ChangeTagsStore $changeTags;

	protected function setUp(): void {
		parent::setUp();

		$this->changeTags = $this->getServiceContainer()->getChangeTagsStore();
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	private function emptyChangeTagsTables() {
		$dbw = $this->getDb();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'change_tag' )
			->where( ISQLPlatform::ALL_ROWS )
			->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'change_tag_def' )
			->where( ISQLPlatform::ALL_ROWS )
			->execute();
	}

	// TODO most methods are not tested

	public function testBuildTagFilterSelector_allTags() {
		// Set `activeOnly` to false
		// Expect that at least all the software defined tags are returned
		$allTags = MediaWikiServices::getInstance()->getChangeTagsStore()->listDefinedTags();
		$allTagsList = ChangeTags::getChangeTagListSummary(
			RequestContext::getMain(),
			RequestContext::getMain()->getLanguage(),
			ChangeTags::TAG_SET_ALL
		);
		$this->assertTrue(
			count( $allTagsList ) >= count( $allTags ),
			'`activeOnly` is false, expect all software tags'
		);
	}

	public function testBuildTagFilterSelector_allSoftwareTags() {
		// Set both `activeOnly` and `useAllTags` to false
		// Expect that only software defined tags are returned
		$allSoftwareTags = MediaWikiServices::getInstance()->getChangeTagsStore()->getSoftwareTags( true );
		$allSoftwareTagsList = ChangeTags::getChangeTagListSummary(
			RequestContext::getMain(),
			RequestContext::getMain()->getLanguage(),
			ChangeTags::TAG_SET_ALL,
			ChangeTags::USE_SOFTWARE_TAGS_ONLY
		);
		$this->assertTrue(
			count( $allSoftwareTagsList ) == count( $allSoftwareTags ),
			'`activeOnly` and `useAllTags` are false, expect only software tags'
		);
	}

	public function testBuildTagFilterSelector_activeOnlyNoHits() {
		// Enable and test `activeOnly` and expect no tags returned,
		// as there are currently no tagged edits in the test database
		$emptyTagListSummary = ChangeTags::getChangeTagListSummary(
			RequestContext::getMain(),
			RequestContext::getMain()->getLanguage(),
			ChangeTags::TAG_SET_ACTIVE_ONLY
		);
		$this->assertCount( 0, $emptyTagListSummary, '`activeOnly` is true and no hits, expect no tags' );

		// Assert that by default, an empty select is returned, as no tags have been used yet
		$this->assertEquals(
			[
				'<label for="tagfilter"><a href="/wiki/Special:Tags" title="Special:Tags">Tag</a> filter:</label>',
				'<input class="mw-tagfilter-input mw-ui-input mw-ui-input-inline" size="20" id="tagfilter" list="tagfilter-datalist" name="tagfilter"><datalist id="tagfilter-datalist"></datalist>'
			],
			ChangeTags::buildTagFilterSelector(
				'', false, RequestContext::getMain()
			)
		);
	}

	public function testBuildTagFilterSelector_activeOnly() {
		// Disable patrolling so reverts will happen without approval
		$this->overrideConfigValues( [ MainConfigNames::UseRCPatrol => false ] );

		// Make an edit and replace the content, adding the `mw-replace` tag to the revision
		$page = $this->getExistingTestPage();
		$this->editPage( $page, '1' );
		$this->editPage(
			$page, '0', '', NS_MAIN, $this->getTestUser()->getUser()
		);

		// Ensure all deferred updates are run
		DeferredUpdates::doUpdates();

		// Assert that only the `mw-replace` tag is returned
		$replaceOnlyTagList = ChangeTags::getChangeTagListSummary(
			RequestContext::getMain(),
			RequestContext::getMain()->getLanguage()
		);
		$this->assertCount( 1, $replaceOnlyTagList, '`activeOnly` is true with 1 hit, return 1 tag' );
		$this->assertEquals(
			'mw-replace', $replaceOnlyTagList[0]['name'],
			'`activeOnly` is true with 1 hit, return expected tag'
		);

		// Assert that the tag is reflected in the default markup returned
		$this->assertEquals(
			[
				'<label for="tagfilter"><a href="/wiki/Special:Tags" title="Special:Tags">Tag</a> filter:</label>',
				'<input class="mw-tagfilter-input mw-ui-input mw-ui-input-inline" size="20" id="tagfilter" list="tagfilter-datalist" name="tagfilter"><datalist id="tagfilter-datalist"><option value="mw-replace">Replaced</option></datalist>'
			],
			ChangeTags::buildTagFilterSelector(
				'', false, RequestContext::getMain()
			),
			'`activeOnly` is true with 1 hit, return expected tag markup'
		);
	}

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
		// Reset the ChangeTagsStore after the config change
		$this->changeTags = $this->getServiceContainer()->getChangeTagsStore();

		if (
			$avoidReopeningTables &&
			$this->getDb()->getType() === 'mysql' &&
			str_contains( $this->getDb()->getSoftwareLink(), 'MySQL' )
		) {
			$this->markTestSkipped( 'See T256006' );
		}

		$rcId = 123;
		$this->changeTags->updateTags( [ 'foo', 'bar', '0' ], [], $rcId );
		// HACK resolve deferred group concats (see comment in provideModifyDisplayQuery)
		if ( isset( $modifiedQuery['fields']['ts_tags'] ) ) {
			$modifiedQuery['fields']['ts_tags'] = $this->getDb()
				->buildGroupConcatField( ...$modifiedQuery['fields']['ts_tags'] );
		}
		if ( isset( $modifiedQuery['exception'] ) ) {
			$this->expectException( $modifiedQuery['exception'] );
		}
		$this->changeTags->modifyDisplayQuery(
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

	public static function provideModifyDisplayQuery() {
		// HACK if we call $dbr->buildGroupConcatField() now, it will return the wrong table names
		// We have to have the test runner call it instead
		$baseConcats = [ ',', [ 'change_tag', 'change_tag_def' ], 'ctd_name' ];
		$joinConds = [ 'change_tag_def' => [ 'JOIN', 'ct_tag_id=ctd_id' ] ];
		$groupConcats = [
			'recentchanges' => array_merge( $baseConcats, [ [ 'ct_rc_id=rc_id' ], $joinConds ] ),
			'logging' => array_merge( $baseConcats, [ [ 'ct_log_id=log_id' ], $joinConds ] ),
			'revision' => array_merge( $baseConcats, [ [ 'ct_rev_id=rev_id' ], $joinConds ] ),
			'archive' => array_merge( $baseConcats, [ [ 'ct_rev_id=ar_rev_id' ], $joinConds ] ),
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rc_id=rc_id' ] ],
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 3 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY' => 'rc_timestamp DESC' ],
				]
			],
			'logging query with single tag filter and strings' => [
				[
					'tables' => 'logging',
					'fields' => 'log_id',
					'conds' => "log_timestamp > '20170714183203'",
					'join_conds' => [],
					'options' => [ 'ORDER BY' => [ 'log_timestamp DESC', 'log_id DESC' ] ],
				],
				'foo',
				true, // tag filtering enabled
				false, // not avoiding reopening tables
				[
					'tables' => [ 'logging', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'log_id', 'ts_tags' => $groupConcats['logging'] ],
					'conds' => [ "log_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_log_id=log_id' ] ],
					'options' => [ 'ORDER BY' => [ 'log_timestamp DESC', 'log_id DESC' ] ],
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
					'tables' => [ 'revision', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rev_id', 'rev_timestamp', 'ts_tags' => $groupConcats['revision'] ],
					'conds' => [ "rev_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rev_id=rev_id' ] ],
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
					'tables' => [ 'archive', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'ar_id', 'ar_timestamp', 'ts_tags' => $groupConcats['archive'] ],
					'conds' => [ "ar_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rev_id=ar_rev_id' ] ],
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
					'tables' => [ 'archive', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'ar_id', 'ar_timestamp', 'ts_tags' => $groupConcats['archive'] ],
					'conds' => [ "ar_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rev_id=ar_rev_id' ] ],
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
				[ 'exception' => InvalidArgumentException::class ]
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rc_id=rc_id' ] ],
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => null ],
					'join_conds' => [ 'changetagdisplay' => [ 'LEFT JOIN', [ 'changetagdisplay.ct_rc_id=rc_id', 'changetagdisplay.ct_tag_id' => [ 1, 2 ] ] ] ],
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'rc_timestamp', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rc_id=rc_id' ] ],
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rc_id=rc_id' ] ],
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
					'tables' => [ 'recentchanges', 'changetagdisplay' => 'change_tag' ],
					'fields' => [ 'rc_id', 'ts_tags' => $groupConcats['recentchanges'] ],
					'conds' => [ "rc_timestamp > '20170714183203'", 'changetagdisplay.ct_tag_id' => [ 1, 2 ] ],
					'join_conds' => [ 'changetagdisplay' => [ 'JOIN', 'changetagdisplay.ct_rc_id=rc_id' ] ],
					'options' => [ 'ORDER BY rc_timestamp DESC', 'DISTINCT' ],
				]
			],
		];
	}

	public static function provideGetSoftwareTags() {
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
	 * @dataProvider provideGetSoftwareTags
	 * @covers \MediaWiki\ChangeTags\ChangeTagsStore::getSoftwareTags
	 */
	public function testGetSoftwareTags( $softwareTags, $expected ) {
		$this->overrideConfigValue( MainConfigNames::SoftwareTags, $softwareTags );
		// Reset the ChangeTagsStore after the config change
		$this->changeTags = $this->getServiceContainer()->getChangeTagsStore();

		$actual = $this->changeTags->getSoftwareTags();
		// Order of tags in arrays is not important
		sort( $expected );
		sort( $actual );
		$this->assertEquals( $expected, $actual );
	}

	public function testUpdateTags() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		$revId = 341;
		$this->changeTags->updateTags( [ 'tag1', 'tag2' ], [], $rcId, $revId );

		$this->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_id', 'ctd_count' ] )
			->from( 'change_tag_def' )
			->assertResultSet( [
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
				[ 'tag2', 2, 1 ],
			] );
		$this->newSelectQueryBuilder()
			->from( 'change_tag' )
			->select( [ 'ct_tag_id', 'ct_rc_id', 'ct_rev_id' ] )
			->assertResultSet( [
				// values of fields 'ct_tag_id', 'ct_rc_id', 'ct_rev_id'
				[ 1, 123, 341 ],
				[ 2, 123, 341 ],
			] );

		$rcId = 124;
		$revId = 342;
		$this->changeTags->updateTags( [ 'tag1' ], [], $rcId, $revId );
		$this->changeTags->updateTags( [ 'tag3' ], [], $rcId, $revId );

		$this->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_id', 'ctd_count' ] )
			->from( 'change_tag_def' )
			->assertResultSet( [
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 2 ],
				[ 'tag2', 2, 1 ],
				[ 'tag3', 3, 1 ],
			] );
		$this->newSelectQueryBuilder()
			->select( [ 'ct_tag_id', 'ct_rc_id', 'ct_rev_id' ] )
			->from( 'change_tag' )
			->assertResultSet( [
				// values of fields 'ct_tag_id', 'ct_rc_id', 'ct_rev_id'
				[ 1, 123, 341 ],
				[ 1, 124, 342 ],
				[ 2, 123, 341 ],
				[ 3, 124, 342 ],
			] );
	}

	public function testUpdateTagsSkipDuplicates() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		$this->changeTags->updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		$this->changeTags->updateTags( [ 'tag2', 'tag3' ], [], $rcId );

		$this->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_id', 'ctd_count' ] )
			->from( 'change_tag_def' )
			->assertResultSet( [
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
				[ 'tag2', 2, 1 ],
				[ 'tag3', 3, 1 ],
			] );
		$this->newSelectQueryBuilder()
			->select( [ 'ct_tag_id', 'ct_rc_id' ] )
			->from( 'change_tag' )
			->assertResultSet( [
				// values of fields 'ct_tag_id', 'ct_rc_id',
				[ 1, 123 ],
				[ 2, 123 ],
				[ 3, 123 ],
			] );
	}

	public function testUpdateTagsDoNothingOnRepeatedCall() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		$this->changeTags->updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		$res = $this->changeTags->updateTags( [ 'tag2', 'tag1' ], [], $rcId );
		$this->assertEquals( [ [], [], [ 'tag1', 'tag2' ] ], $res );

		$this->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_id', 'ctd_count' ] )
			->from( 'change_tag_def' )
			->assertResultSet( [
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
				[ 'tag2', 2, 1 ],
			] );
		$this->newSelectQueryBuilder()
			->select( [ 'ct_tag_id', 'ct_rc_id' ] )
			->from( 'change_tag' )
			->assertResultSet( [
				// values of fields 'ct_tag_id', 'ct_rc_id',
				[ 1, 123 ],
				[ 2, 123 ],
			] );
	}

	public function testDeleteTags() {
		$this->emptyChangeTagsTables();
		$this->getServiceContainer()->resetServiceForTesting( 'NameTableStoreFactory' );

		$rcId = 123;
		$this->changeTags->updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		$this->changeTags->updateTags( [], [ 'tag2' ], $rcId );

		$this->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_id', 'ctd_count' ] )
			->from( 'change_tag_def' )
			->assertResultSet( [
				// values of fields 'ctd_name', 'ctd_id', 'ctd_count'
				[ 'tag1', 1, 1 ],
			] );
		$this->newSelectQueryBuilder()
			->select( [ 'ct_tag_id', 'ct_rc_id' ] )
			->from( 'change_tag' )
			->assertResultSet( [
				// values of fields 'ct_tag_id', 'ct_rc_id',
				[ 1, 123 ],
			] );
	}

	public static function provideTags() {
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
		$this->changeTags->addTags( $tags, $rcId, $revId, $logId );

		$actualTags = $this->changeTags->getTags( $this->getDb(), $rcId, $revId, $logId );

		$this->assertSame( $tags, $actualTags );
	}

	public function testGetTags_multiple_arguments() {
		$rcId = 123;
		$revId = 456;
		$logId = 789;

		$this->changeTags->addTags( [ 'tag 1' ], $rcId );
		$this->changeTags->addTags( [ 'tag 2' ], $rcId, $revId );
		$this->changeTags->addTags( [ 'tag 3' ], $rcId, $revId, $logId );

		$tags3 = [ 'tag 3' ];
		$tags2 = array_merge( $tags3, [ 'tag 2' ] );
		$tags1 = array_merge( $tags2, [ 'tag 1' ] );
		$this->assertArrayEquals( $tags3, $this->changeTags->getTags( $this->getDb(), $rcId, $revId, $logId ) );
		$this->assertArrayEquals( $tags2, $this->changeTags->getTags( $this->getDb(), $rcId, $revId ) );
		$this->assertArrayEquals( $tags1, $this->changeTags->getTags( $this->getDb(), $rcId ) );
	}

	public function testGetTagsWithData() {
		$rcId1 = 123;
		$rcId2 = 456;
		$rcId3 = 789;
		$this->changeTags->addTags( [ 'tag 1' ], $rcId1, null, null, 'data1' );
		$this->changeTags->addTags( [ 'tag 3_1' ], $rcId3, null, null );
		$this->changeTags->addTags( [ 'tag 3_2' ], $rcId3, null, null, 'data3_2' );

		$data = $this->changeTags->getTagsWithData( $this->getDb(), $rcId1 );
		$this->assertSame( [ 'tag 1' => 'data1' ], $data );

		$data = $this->changeTags->getTagsWithData( $this->getDb(), $rcId2 );
		$this->assertSame( [], $data );

		$data = $this->changeTags->getTagsWithData( $this->getDb(), $rcId3 );
		$this->assertArrayEquals( [ 'tag 3_1' => null, 'tag 3_2' => 'data3_2' ], $data, false, true );
	}

	public function testTagUsageStatistics() {
		$this->emptyChangeTagsTables();
		$this->getServiceContainer()->resetServiceForTesting( 'NameTableStoreFactory' );

		$rcId = 123;
		$this->changeTags->updateTags( [ 'tag1', 'tag2' ], [], $rcId );

		$rcId = 124;
		$this->changeTags->updateTags( [ 'tag1' ], [], $rcId );

		$this->assertEquals( [ 'tag1' => 2, 'tag2' => 1 ], $this->changeTags->tagUsageStatistics() );
	}

	public function testListExplicitlyDefinedTags() {
		$this->emptyChangeTagsTables();

		$rcId = 123;
		$this->changeTags->updateTags( [ 'tag1', 'tag2' ], [], $rcId );
		$this->changeTags->defineTag( 'tag2' );

		$this->assertEquals( [ 'tag2' ], $this->changeTags->listExplicitlyDefinedTags() );

		$this->newSelectQueryBuilder()
			->select( [ 'ctd_name', 'ctd_user_defined' ] )
			->from( 'change_tag_def' )
			->assertResultSet( [
				// values of fields 'ctd_name', 'ctd_user_defined'
				[ 'tag1', 0 ],
				[ 'tag2', 1 ],
			] );
	}

	public static function provideFormatSummaryRow() {
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
