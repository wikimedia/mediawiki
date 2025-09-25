<?php

namespace MediaWiki\Tests\RecentChanges\ChangesListQuery;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\WikitextContent;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ChangesListHighlight
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\BooleanFieldCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\EnumFieldCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ExperienceCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\FieldEqualityCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\NamedCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\NamedConditionHelper
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\RevisionTypeCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\SeenCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\SubpageOfCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\UserCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\WatchedCondition
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\WatchlistJoin
 * @group Database
 */
class ChangesListQueryTest extends \MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

	/** @var string[] Internal names given to recentchanges rows */
	private static $rcRowNames = [
		'NULL',
		'alice',
		'anon',
		'bob',
		'newuser',
		'minor',
		'bot',
		'unseen',
		'deleted',
	];

	private const ALICE_ID = 1;

	/** @var array<string,int> Lazy initialized flip of $rcRowNames */
	private static $rcIds;

	/** @var int The next content to write to a page */
	private static $content = 0;

	protected function setup(): void {
		$this->enableAutoCreateTempUser();
		$this->overrideConfigValues( [
			MainConfigNames::NewUserLog => true,
			MainConfigNames::LearnerEdits => 10,
			MainConfigNames::LearnerMemberSince => 4,
		] );
		ExtensionRegistry::getInstance()->setAttributeForTest(
			'RecentChangeSources',
			[]
		);
	}

	private static function getRcIds() {
		if ( !self::$rcIds ) {
			self::$rcIds = array_flip( self::$rcRowNames );
			unset( self::$rcIds['NULL'] );
		}
		return self::$rcIds;
	}

	public function addDBDataOnce() {
		// The data provider runs before addDBDataOnce so we can't set the IDs
		// here and use them in the provider. But we can assert that the IDs
		// are correct.
		$index = 1;
		$assertName = function ( $expectedIndex, $name ) use ( &$index ) {
			$this->assertSame( $expectedIndex, $index );
			$this->assertSame( $name, self::$rcRowNames[$index++] );
		};

		$now = strtotime( '2025-01-01T00:00:00' );
		ConvertibleTimestamp::setFakeTime( static function () use ( &$now ) {
			return $now;
		} );

		$assertName( 1, 'alice' );
		$alice = User::createNew( 'Alice' );
		$this->assertSame( self::ALICE_ID, $alice->getId() );
		$normalPage = $this->edit( 'Normal edit', $alice );
		$wis = $this->getServiceContainer()->getWatchedItemStore();
		$wis->addWatch( $alice, $normalPage );

		$now = strtotime( '2025-01-02T00:00:00' );

		$assertName( 2, 'anon' );
		$this->disableAutoCreateTempUser();
		$anon = new User;
		$this->edit( 'Talk:Edit by anon/subpage', $anon );

		$assertName( 3, 'bob' );
		$this->enableAutoCreateTempUser();
		$bob = User::createNew( 'Bob' );
		$this->edit( 'Normal edit', $bob );

		$now++;
		$wis->setNotificationTimestampsForUser(
			$alice,
			ConvertibleTimestamp::now(),
			[ $normalPage ]
		);

		$assertName( 4, 'newuser' );
		$this->createNewUserLogEntry( $bob );

		$assertName( 5, 'minor' );
		$carol = User::createNew( 'Carol' );
		$this->edit( 'Minor edit', $carol, EDIT_MINOR );

		$assertName( 6, 'bot' );
		$this->edit( 'Bot edit', $carol, EDIT_FORCE_BOT );

		$now = strtotime( '2025-01-03T00:00:00' );
		$assertName( 7, 'unseen' );
		$this->edit( 'Normal edit', $bob );

		$assertName( 8, 'deleted' );
		$log = new ManualLogEntry( 'delete', 'delete' );
		$log->setPerformer( $carol );
		$log->setTarget( new PageReferenceValue( 0, 'Deleted', WikiAwareEntity::LOCAL ) );
		$log->insert( $this->getDb() );
		$rc = $log->getRecentChange();
		$rc->setAttribute( 'rc_deleted', LogPage::DELETED_ACTION );
		$this->getServiceContainer()->getRecentChangeFactory()->insertRecentChange( $rc );
	}

	private function edit( $titleText, $user, $flags = 0 ) {
		$services = $this->getServiceContainer();
		$title = $services->getTitleFactory()->newFromTextThrow( $titleText );
		$services->getPageUpdaterFactory()
			->newPageUpdater( $title, $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( (string)( self::$content++ ) ) )
			->saveRevision( '', $flags );
		return $title;
	}

	private function createNewUserLogEntry( UserIdentity $user ) {
		$logEntry = new ManualLogEntry(
			'newusers',
			'create2'
		);
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $user->getUserPage() );
		$logEntry->setParameters( [
			'4::userid' => $user->getId(),
		] );
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );
	}

	private function getQuery( array $options = [] ) {
		$services = $this->getServiceContainer();
		$extraConfig = [];
		if ( !empty( $options['watchlist-expiry'] ) ) {
			$extraConfig[MainConfigNames::WatchlistExpiry] = true;
		}
		$query = new ChangesListQuery(
			new ServiceOptions(
				ChangesListQuery::CONSTRUCTOR_OPTIONS,
				$extraConfig,
				$services->getMainConfig(),
			),
			$services->getRecentChangeLookup(),
			$services->getWatchedItemStore(),
			$services->getTempUserConfig(),
			$services->getUserFactory(),
			$this->getDb(),
		);
		$query
			->caller( __CLASS__ )
			->fields( [ 'rc_id' ] );
		if ( empty( $options['anon-watchlist'] ) ) {
			$query->watchlistUser( new UserIdentityValue( self::ALICE_ID, 'Alice' ) );
		}
		return $query;
	}

	private function normalizeConds( $conds ) {
		return $this->getDb()->makeList( $conds, IDatabase::LIST_AND );
	}

	private function doQuery( ChangesListQuery $query, $expectedInfo, $expectedIds ) {
		$queryInfo = null;
		$query->sqbMutator( static function ( SelectQueryBuilder $sqb ) use ( &$queryInfo ) {
			$queryInfo = $sqb->getQueryInfo();
		} );
		$res = $query->fetchResult();
		if ( $expectedInfo === null ) {
			$this->assertNull( $queryInfo );
		} else {
			$this->assertNotNull( $queryInfo, 'query was not done' );
			$queryInfo['conds'] = $this->normalizeConds( $queryInfo['conds'] );
			$this->assertArrayEquals( $expectedInfo, $queryInfo,
				false, true, 'queryInfo' );
		}
		$ids = [];
		foreach ( $res->getRows() as $row ) {
			$ids[] = (int)$row->rc_id;
		}
		$this->assertArrayEquals( $expectedIds, $ids, false, false, 'rc_id values' );
	}

	private static function getDefaultInfo() {
		return [
			'tables' => [ 'recentchanges' ],
			'fields' => [ 'rc_id' ],
			'conds' => '',
			'options' => [
				'ORDER BY' => [ 'rc_timestamp DESC' ],
			],
			'caller' => __CLASS__,
			'join_conds' => [],
		];
	}

	public static function provideActions() {
		$defaultInfo = self::getDefaultInfo();
		$joinActor = [
			'tables' => [
				'recentchanges',
				'recentchanges_actor' => 'actor'
			],
			'join_conds' => [
				'recentchanges_actor' => [ 'JOIN', [ 'actor_id=rc_actor' ] ]
			],
		];

		$straightJoinActor = $joinActor;
		$straightJoinActor['join_conds']['recentchanges_actor'][0] = 'STRAIGHT_JOIN';

		$leftJoinUser = $straightJoinActor;
		$leftJoinUser['tables']['user'] = 'user';
		$leftJoinUser['join_conds']['user'] = [ 'LEFT JOIN', [ 'user_id=actor_user' ] ];

		$joinWatchlist = [
			'tables' => [
				'recentchanges',
				'watchlist' => 'watchlist',
			],
			'join_conds' => [
				'watchlist' => [ 'JOIN', [
					'wl_user' => self::ALICE_ID,
					'wl_namespace=rc_namespace',
					'wl_title=rc_title',
				] ],
			],
		];
		$leftJoinWatchlist = $joinWatchlist;
		$leftJoinWatchlist['join_conds']['watchlist'][0] = 'LEFT JOIN';

		$joinWatchlistExpiry = $joinWatchlist;
		$joinWatchlistExpiry['tables']['watchlist_expiry'] = 'watchlist_expiry';
		$joinWatchlistExpiry['join_conds']['watchlist_expiry'] = [ 'LEFT JOIN', [ 'we_item=wl_id' ] ];

		$rcIds = self::getRcIds();
		$allIds = array_values( $rcIds );
		$alice = new UserIdentityValue( 1, 'Alice' );
		$newIds = [ $rcIds['alice'], $rcIds['anon'], $rcIds['minor'], $rcIds['bot'] ];

		return [
			'No actions' => [
				[],
				$defaultInfo,
				$allIds,
			],
			'experience unregistered' => [
				[ [ 'require', 'experience', 'unregistered' ] ],
				array_merge( $defaultInfo, $straightJoinActor, [
					'conds' => "(((actor_user IS NULL OR actor_name LIKE '~%' ESCAPE '`')))",
				] ),
				[ $rcIds['anon'] ],
			],
			'experience registered' => [
				[
					[ 'require', 'experience', 'newcomer' ],
					[ 'require', 'experience', 'learner' ],
					[ 'require', 'experience', 'experienced' ],
				],
				array_merge( $defaultInfo, $straightJoinActor, [
					'conds' => "(((actor_user IS NOT NULL AND actor_name NOT LIKE '~%' ESCAPE '`')))",
				] ),
				array_diff( $allIds, [ $rcIds['anon'] ] ),
			],
			'require experience newcomer' => [
				[ [ 'require', 'experience', 'newcomer' ] ],
				array_merge( $defaultInfo, $leftJoinUser, [
					'conds' => "((((actor_user IS NOT NULL AND actor_name NOT LIKE '~%' ESCAPE '`') " .
						"AND (user_editcount < 10 OR user_registration > '20250101000000'))))",
				] ),
				array_diff( $allIds, [ $rcIds['anon'] ] ),
			],
			'exclude named' => [
				[ [ 'exclude', 'named' ] ],
				array_merge( $defaultInfo, $joinActor, [
					'conds' => "((actor_user IS NULL OR actor_name LIKE '~%' ESCAPE '`'))",
				] ),
				[ $rcIds['anon'] ],
			],
			'require named' => [
				[ [ 'require', 'named' ] ],
				array_merge( $defaultInfo, $joinActor, [
					'conds' => "((actor_user IS NOT NULL AND actor_name NOT LIKE '~%' ESCAPE '`'))",
				] ),
				array_diff( $allIds, [ $rcIds['anon'] ] ),
			],
			'require user alice' => [
				[ [ 'require', 'user', $alice ] ],
				array_merge( $defaultInfo, $joinActor, [
					'conds' => "(actor_user = 1)",
				] ),
				[ $rcIds['alice'] ],
			],
			'exclude user alice' => [
				[ [ 'exclude', 'user', $alice ] ],
				array_merge( $defaultInfo, $straightJoinActor, [
					'conds' => "(actor_name != 'Alice')",
				] ),
				array_diff( $allIds, [ $rcIds['alice'] ] ),
			],
			'require new user log' => [
				[ [ 'require', 'logType', 'newusers' ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_log_type = 'newusers')",
				] ),
				[ $rcIds['newuser'] ],
			],
			'exclude new user log' => [
				[ [ 'exclude', 'logType', 'newusers' ] ],
				array_merge( $defaultInfo, [
					'conds' => "((rc_log_type != 'newusers' OR rc_log_type IS NULL))",
				] ),
				array_diff( $allIds, [ $rcIds['newuser'] ] ),
			],
			'require minor' => [
				[ [ 'require', 'minor' ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_minor = 1)",
				] ),
				[ $rcIds['minor'] ],
			],
			'exclude minor' => [
				[ [ 'exclude', 'minor' ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_minor = 0)",
				] ),
				array_diff( $allIds, [ $rcIds['minor'] ] ),
			],
			'require minor+major' => [
				[
					[ 'require', 'minor', true ],
					[ 'require', 'minor', false ],
				],
				$defaultInfo,
				$allIds,
			],
			'exclude minor+major' => [
				[
					[ 'exclude', 'minor', true ],
					[ 'exclude', 'minor', false ],
				],
				$defaultInfo,
				$allIds,
			],
			'require minor, exclude major' => [
				[
					[ 'require', 'minor', true ],
					[ 'exclude', 'minor', false ],
				],
				array_merge( $defaultInfo, [
					'conds' => "(rc_minor = 1)",
				] ),
				[ $rcIds['minor'] ],
			],
			'require bot' => [
				[ [ 'require', 'bot' ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_bot = 1)",
				] ),
				[ $rcIds['bot'] ],
			],
			'require revisionType latest+none' => [
				[
					[ 'require', 'revisionType', 'latest' ],
					[ 'require', 'revisionType', 'none' ],
				],
				array_merge( $defaultInfo, [
					'conds' => "((rc_this_oldid = page_latest OR rc_this_oldid = 0))",
					'tables' => [
						'recentchanges',
						'page' => 'page'
					],
					'join_conds' => [
						'page' => [ 'LEFT JOIN', [ 'page_id=rc_cur_id' ] ],
					],
				] ),
				array_diff( $allIds, [ $rcIds['alice'], $rcIds['bob'] ] ),
			],
			'exclude revisionType latest' => [
				[ [ 'exclude', 'revisionType', 'latest' ] ],
				array_merge( $defaultInfo, [
					'conds' => "((rc_this_oldid != page_latest OR rc_this_oldid = 0))",
					'tables' => [
						'recentchanges',
						'page' => 'page'
					],
					'join_conds' => [
						'page' => [ 'LEFT JOIN', [ 'page_id=rc_cur_id' ] ],
					],
				] ),
				[ $rcIds['alice'], $rcIds['bob'], $rcIds['newuser'], $rcIds['deleted'] ],
			],
			'require/exclude conflict revisionType' => [
				[
					[ 'exclude', 'revisionType', 'latest' ],
					[ 'require', 'revisionType', 'latest' ]
				],
				null,
				[]
			],
			'require source new' => [
				[ [ 'require', 'source', RecentChange::SRC_NEW ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_source = 'mw.new')",
				] ),
				$newIds,
			],
			'exclude source new' => [
				[ [ 'exclude', 'source', RecentChange::SRC_NEW ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_source IN ('mw.edit','mw.log','mw.categorize'))",
				] ),
				array_diff( $allIds, $newIds ),
			],
			'require/exclude conflict source' => [
				[
					[ 'require', 'source', RecentChange::SRC_NEW ],
					[ 'exclude', 'source', RecentChange::SRC_NEW ]
				],
				null,
				[],
			],
			'require autopatrolled' => [
				[ [ 'require', 'patrolled', RecentChange::PRC_AUTOPATROLLED ] ],
				array_merge( $defaultInfo, [
					'conds' => "(rc_patrolled = 2)",
				] ),
				[ $rcIds['newuser'], $rcIds['deleted'] ],
			],
			'require watched' => [
				[ [ 'require', 'watched', 'watched' ] ],
				array_merge( $defaultInfo, $joinWatchlist ),
				[ $rcIds['alice'], $rcIds['bob'], $rcIds['unseen'] ],
			],
			'require watchednew' => [
				[ [ 'require', 'watched', 'watchednew' ] ],
				array_merge( $defaultInfo, $joinWatchlist, [
					'conds' => '(rc_timestamp >= wl_notificationtimestamp)',
				] ),
				[ $rcIds['unseen'] ],
			],
			'require watched+watchednew' => [
				[
					[ 'require', 'watched', 'watchednew' ],
					[ 'require', 'watched', 'watched' ]
				],
				array_merge( $defaultInfo, $joinWatchlist ),
				[ $rcIds['alice'], $rcIds['bob'], $rcIds['unseen'] ],
			],
			'exclude watched' => [
				[ [ 'exclude', 'watched', 'watched' ] ],
				array_merge( $defaultInfo, $leftJoinWatchlist, [
					'conds' => '(wl_user IS NULL)',
				] ),
				array_diff( $allIds, [ $rcIds['alice'], $rcIds['bob'], $rcIds['unseen'] ] ),
			],
			'exclude watched, require watchednew (b/c union)' => [
				[
					[ 'require', 'watched', 'watchednew' ],
					[ 'exclude', 'watched', 'watched' ]
				],
				array_merge( $defaultInfo, $leftJoinWatchlist, [
					'conds' => "((wl_user IS NULL OR " .
						"(wl_user IS NOT NULL AND rc_timestamp >= wl_notificationtimestamp)))"
				] ),
				array_diff( $allIds, [ $rcIds['alice'], $rcIds['bob'] ] ),
			],
			'require anon watched' => [
				[ [ 'require', 'watched', 'watched' ] ],
				null,
				[],
				[ 'anon-watchlist' => true ],
			],
			'require watched with expiry' => [
				[ [ 'require', 'watched', 'watched' ] ],
				array_merge( $defaultInfo, $joinWatchlistExpiry, [
					'conds' => "((we_expiry IS NULL OR we_expiry > '20250105000000'))",
				] ),
				[ $rcIds['alice'], $rcIds['bob'], $rcIds['unseen'] ],
				[ 'watchlist-expiry' => true ],
			],
			'require seen' => [
				[ [ 'require', 'seen' ] ],
				array_merge( $defaultInfo, $leftJoinWatchlist, [
					'conds' => '((wl_notificationtimestamp IS NULL ' .
						'OR rc_timestamp < wl_notificationtimestamp))',
				] ),
				array_diff( $allIds, [ $rcIds['unseen'] ] ),
			],
			'exclude seen' => [
				[ [ 'exclude', 'seen' ] ],
				array_merge( $defaultInfo, $leftJoinWatchlist, [
					'conds' => '((wl_notificationtimestamp IS NOT NULL ' .
						'AND rc_timestamp >= wl_notificationtimestamp))',
				] ),
				[ $rcIds['unseen'] ],
			],
			'require watched+seen' => [
				[
					[ 'require', 'watched', 'watched' ],
					[ 'require', 'seen' ]
				],
				array_merge( $defaultInfo, $joinWatchlist, [
					'conds' => '((wl_notificationtimestamp IS NULL ' .
						'OR rc_timestamp < wl_notificationtimestamp))',
				] ),
				[ $rcIds['alice'], $rcIds['bob'] ],
			],
			'require namespace' => [
				[ [ 'require', 'namespace', NS_TALK ] ],
				array_merge( $defaultInfo, [
					'conds' => '(rc_namespace = 1)',
				] ),
				[ $rcIds['anon'] ],
			],
			'require multiple namespaces' => [
				[
					[ 'require', 'namespace', NS_TALK ],
					[ 'require', 'namespace', NS_USER ],
				],
				array_merge( $defaultInfo, [
					'conds' => '(rc_namespace IN (1,2))',
				] ),
				[ $rcIds['anon'], $rcIds['newuser'] ],
			],
			'exclude namespace' => [
				[ [ 'exclude', 'namespace', NS_TALK ] ],
				array_merge( $defaultInfo, [
					'conds' => '(rc_namespace != 1)',
				] ),
				array_diff( $allIds, [ $rcIds['anon'] ] ),
			],
			'require subpageof' => [
				[ [
					'require',
					'subpageof',
					new PageReferenceValue( 1, 'Edit by anon', WikiAwareEntity::LOCAL )
				] ],
				array_merge( $defaultInfo, [
					'conds' => "(((rc_namespace = 1 AND rc_title LIKE 'Edit`_by`_anon/%' ESCAPE '`')))",
				] ),
				[ $rcIds['anon'] ],
			],
		];
	}

	/**
	 * @dataProvider provideActions
	 * @param array $actions
	 * @param array $expectedInfo
	 * @param int[] $expectedIds
	 * @param array $options
	 */
	public function testActions( $actions, $expectedInfo, $expectedIds, $options = [] ) {
		ConvertibleTimestamp::setFakeTime( '20250105000000' );
		$query = $this->getQuery( $options );
		foreach ( $actions as $action ) {
			$query->applyAction( ...$action );
		}
		$this->doQuery( $query, $expectedInfo, $expectedIds );
	}

	public static function provideHighlight() {
		// Use the same cases as for applyAction(), but ignore some cases that
		// don't give the right list of IDs
		foreach ( self::provideActions() as $case ) {
			[ $actions, $expectedInfo, $expectedIds ] = $case;
			$options = $case[3] ?? [];
			if ( count( $actions ) !== 1 ) {
				// Ignore unions and no-op conditions
				continue;
			}
			if ( $actions[0][0] !== 'require' ) {
				// Ignore exclusions
				continue;
			}
			yield [ $actions[0], $expectedIds, $options ];
		}
	}

	/**
	 * @dataProvider provideHighlight
	 * @param array $action
	 * @param int[] $expectedIds
	 * @param array $options
	 */
	public function testHighlight( $action, array $expectedIds, array $options = [] ) {
		ConvertibleTimestamp::setFakeTime( '20250105000000' );
		$query = $this->getQuery( $options );
		$query->highlight( '', ...$action );
		$res = $query->fetchResult();
		$hlIds = [];
		foreach ( $res->getRows() as $row ) {
			$highlights = $res->getHighlightsFromRow( $row );
			if ( $highlights ) {
				$this->assertSame( [ '' => true ], $highlights );
				$hlIds[] = (int)$row->rc_id;
			}
		}
		$this->assertArrayEquals( $expectedIds, $hlIds );
	}

	public function testRequireNamespaces() {
		$query = $this->getQuery();
		[ $actions, $expectedInfo, $expectedIds ] = self::provideActions()['require namespace'];
		$query->requireNamespaces( [ NS_TALK ] );
		$this->doQuery( $query, $expectedInfo, $expectedIds );
	}

	public function testExcludeNamespaces() {
		$query = $this->getQuery();
		[ $actions, $expectedInfo, $expectedIds ] = self::provideActions()['exclude namespace'];
		$query->excludeNamespaces( [ NS_TALK ] );
		$this->doQuery( $query, $expectedInfo, $expectedIds );
	}

	public function testRequireSubpageOf() {
		$query = $this->getQuery();
		[ $actions, $expectedInfo, $expectedIds ] = self::provideActions()['require subpageof'];
		$query->requireSubpageOf( $actions[0][2] );
		$this->doQuery( $query, $expectedInfo, $expectedIds );
	}

	public function requireWatched() {
		$query = $this->getQuery();
		[ $actions, $expectedInfo, $expectedIds ] = self::provideActions()['require watched'];
		$query->requireWatched();
		$this->doQuery( $query, $expectedInfo, $expectedIds );
	}

	public static function provideAudience() {
		$rcIds = self::getRcIds();
		$allRcIds = array_values( self::getRcIds() );
		return [
			'normal' => [
				[],
				array_merge( self::getDefaultInfo(), [
					'conds' => "((rc_source != 'mw.log' OR (rc_deleted & 1) != 1))",
				] ),
				array_diff( $allRcIds, [ $rcIds['deleted'] ] ),
			],
			'deletedhistory' => [
				[ 'deletedhistory' ],
				array_merge( self::getDefaultInfo(), [
					'conds' => "((rc_source != 'mw.log' OR (rc_deleted & 9) != 9))",
				] ),
				$allRcIds,
			],
			'suppressrevision' => [
				[ 'deletedhistory', 'suppressrevision' ],
				self::getDefaultInfo(),
				$allRcIds,
			],
		];
	}

	/**
	 * @dataProvider provideAudience
	 *
	 * @param string[] $rights
	 * @param array $expectedInfo
	 * @param int[] $expectedIds
	 */
	public function testAudience( $rights, $expectedInfo, $expectedIds ) {
		$query = $this->getQuery();
		$authority = new SimpleAuthority(
			new UserIdentityValue( 1, 'Alice' ),
			$rights
		);
		$query->audience( $authority );
		$this->doQuery( $query, $expectedInfo, $expectedIds );
	}

	public function testMinTimestamp() {
		$query = $this->getQuery()
			->minTimestamp( '20250102000000' );
		$rcIds = self::getRcIds();
		unset( $rcIds['alice'] );
		$this->doQuery(
			$query,
			array_merge( self::getDefaultInfo(), [
				'conds' => "(rc_timestamp >= '20250102000000')"
			] ),
			$rcIds,
		);
	}

	public function testLimit() {
		$query = $this->getQuery()
			->limit( 1 );
		$rcIds = self::getRcIds();
		$info = self::getDefaultInfo();
		$this->doQuery(
			$query,
			array_merge( $info, [
				'options' => [
					'LIMIT' => 1,
					'ORDER BY' => $info['options']['ORDER BY'],
				],
			] ),
			[ end( $rcIds ) ]
		);
	}

	public function testForceEmptySet() {
		$query = $this->getQuery();
		$this->assertFalse( $query->isEmptySet() );
		$query->forceEmptySet();
		$this->assertTrue( $query->isEmptySet() );
		$this->doQuery(
			$query,
			null,
			[]
		);
	}
}
