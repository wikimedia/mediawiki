<?php
/**
 * Test class for SpecialRecentchanges class
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 * @group Database
 *
 * @covers SpecialRecentChanges
 */
class SpecialRecentchangesTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgRCWatchCategoryMembership', true );
	}

	/**
	 * @var SpecialRecentChanges
	 */
	protected $rc;

	/** helper to test SpecialRecentchanges::buildMainQueryConds() */
	private function assertConditions(
		$expected,
		$requestOptions = null,
		$message = '',
		$user = null
	) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $requestOptions ) );
		if ( $user ) {
			$context->setUser( $user );
		}

		# setup the rc object
		$this->rc = new SpecialRecentChanges();
		$this->rc->setContext( $context );
		$formOptions = $this->rc->setup( null );

		#  Filter out rc_timestamp conditions which depends on the test runtime
		# This condition is not needed as of march 2, 2011 -- hashar
		# @todo FIXME: Find a way to generate the correct rc_timestamp
		$queryConditions = array_filter(
			$this->rc->buildMainQueryConds( $formOptions ),
			'SpecialRecentchangesTest::filterOutRcTimestampCondition'
		);

		$this->assertEquals(
			self::normalizeCondition( $expected ),
			self::normalizeCondition( $queryConditions ),
			$message
		);
	}

	private static function normalizeCondition( $conds ) {
		$normalized = array_map(
			function ( $k, $v ) {
				return is_numeric( $k ) ? $v : "$k = $v";
			},
			array_keys( $conds ),
			$conds
		);
		sort( $normalized );
		return $normalized;
	}

	/** return false if condition begin with 'rc_timestamp ' */
	private static function filterOutRcTimestampCondition( $var ) {
		return ( false === strpos( $var, 'rc_timestamp ' ) );
	}

	public function testRcNsFilter() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"rc_namespace = '0'",
			],
			[
				'namespace' => NS_MAIN,
			],
			"rc conditions with no options (aka default setting)"
		);
	}

	public function testRcNsFilterInversion() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"rc_namespace != '0'",
			],
			[
				'namespace' => NS_MAIN,
				'invert' => 1,
			],
			"rc conditions with namespace inverted"
		);
	}

	/**
	 * @bug 2429
	 * @dataProvider provideNamespacesAssociations
	 */
	public function testRcNsFilterAssociation( $ns1, $ns2 ) {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"(rc_namespace = '$ns1' OR rc_namespace = '$ns2')",
			],
			[
				'namespace' => $ns1,
				'associated' => 1,
			],
			"rc conditions with namespace inverted"
		);
	}

	/**
	 * @bug 2429
	 * @dataProvider provideNamespacesAssociations
	 */
	public function testRcNsFilterAssociationWithInversion( $ns1, $ns2 ) {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"(rc_namespace != '$ns1' AND rc_namespace != '$ns2')",
			],
			[
				'namespace' => $ns1,
				'associated' => 1,
				'invert' => 1,
			],
			"rc conditions with namespace inverted"
		);
	}

	/**
	 * Provides associated namespaces to test recent changes
	 * namespaces association filtering.
	 */
	public static function provideNamespacesAssociations() {
		return [ # (NS => Associated_NS)
			[ NS_MAIN, NS_TALK ],
			[ NS_TALK, NS_MAIN ],
		];
	}

	public function testRcHidemyselfFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_user != '{$user->getId()}'",
				"rc_type != '6'",
			],
			[
				'hidemyself' => 1,
			],
			"rc conditions: hidemyself=1 (logged in)",
			$user
		);

		$user = User::newFromName( '10.11.12.13', false );
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_user_text != '10.11.12.13'",
				"rc_type != '6'",
			],
			[
				'hidemyself' => 1,
			],
			"rc conditions: hidemyself=1 (anon)",
			$user
		);
	}

	public function testRcHidebyothersFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_user = '{$user->getId()}'",
				"rc_type != '6'",
			],
			[
				'hidebyothers' => 1,
			],
			"rc conditions: hidebyothers=1 (logged in)",
			$user
		);

		$user = User::newFromName( '10.11.12.13', false );
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_user_text = '10.11.12.13'",
				"rc_type != '6'",
			],
			[
				'hidebyothers' => 1,
			],
			"rc conditions: hidebyothers=1 (anon)",
			$user
		);
	}

	public function testRcHidemyselfHidebyothersFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_user != '{$user->getId()}'",
				"rc_user = '{$user->getId()}'",
				"rc_type != '6'",
			],
			[
				'hidemyself' => 1,
				'hidebyothers' => 1,
			],
			"rc conditions: hidemyself=1 hidebyothers=1 (logged in)",
			$user
		);
	}

	public function testRcHidepageedits() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"rc_type != '0'",
			],
			[
				'hidepageedits' => 1,
			],
			"rc conditions: hidepageedits=1"
		);
	}

	public function testRcHidenewpages() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"rc_type != '1'",
			],
			[
				'hidenewpages' => 1,
			],
			"rc conditions: hidenewpages=1"
		);
	}

	public function testRcHidelog() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
				"rc_type != '3'",
			],
			[
				'hidelog' => 1,
			],
			"rc conditions: hidelog=1"
		);
	}

	public function testRcHidehumans() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 1,
				"rc_type != '6'",
			],
			[
				'hidebots' => 0,
				'hidehumans' => 1,
			],
			"rc conditions: hidebots=0 hidehumans=1"
		);
	}

	public function testRcHidepatrolledDisabledFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
			],
			[
				'hidepatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1 (user not allowed)",
			$user
		);
	}

	public function testRcHideunpatrolledDisabledFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_type != '6'",
			],
			[
				'hideunpatrolled' => 1,
			],
			"rc conditions: hideunpatrolled=1 (user not allowed)",
			$user
		);
	}
	public function testRcHidepatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_patrolled = 0",
				"rc_type != '6'",
			],
			[
				'hidepatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1",
			$user
		);
	}

	public function testRcHideunpatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_patrolled = 1",
				"rc_type != '6'",
			],
			[
				'hideunpatrolled' => 1,
			],
			"rc conditions: hideunpatrolled=1",
			$user
		);
	}

	public function testRcHideminorFilter() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_minor = 0",
				"rc_type != '6'",
			],
			[
				'hideminor' => 1,
			],
			"rc conditions: hideminor=1"
		);
	}

	public function testRcHidemajorFilter() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_minor = 1",
				"rc_type != '6'",
			],
			[
				'hidemajor' => 1,
			],
			"rc conditions: hidemajor=1"
		);
	}

	// This is probably going to change when we do auto-fix of
	// filters combinations that don't make sense but for now
	// it's the behavior therefore it's the test.
	public function testRcHidepatrolledHideunpatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				"rc_patrolled = 0",
				"rc_patrolled = 1",
				"rc_type != '6'",
			],
			[
				'hidepatrolled' => 1,
				'hideunpatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1 hideunpatrolled=1",
			$user
		);
	}

	public function testFilterUserExpLevel() {
		$this->setMwGlobals( [
			'wgLearnerEdits' => 10,
			'wgLearnerMemberSince' => 4,
			'wgExperiencedUserEdits' => 500,
			'wgExperiencedUserMemberSince' => 30,
		] );

		$this->createUsers( [
			'Newcomer1' => [ 'edits' => 2, 'days' => 2 ],
			'Newcomer2' => [ 'edits' => 12, 'days' => 3 ],
			'Newcomer3' => [ 'edits' => 8, 'days' => 5 ],
			'Learner1' => [ 'edits' => 15, 'days' => 10 ],
			'Learner2' => [ 'edits' => 450, 'days' => 20 ],
			'Learner3' => [ 'edits' => 460, 'days' => 33 ],
			'Learner4' => [ 'edits' => 525, 'days' => 28 ],
			'Experienced1' => [ 'edits' => 538, 'days' => 33 ],
		] );

		// newcomers only
		$this->assertArrayEquals(
			[ 'Newcomer1', 'Newcomer2', 'Newcomer3' ],
			$this->fetchUsers( [ 'userExpLevel' => 'newcomer' ] )
		);

		// newcomers and learner
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
			],
			$this->fetchUsers( [ 'userExpLevel' => 'newcomer,learner' ] )
		);

		// newcomers and more learner
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Experienced1',
			],
			$this->fetchUsers( [ 'userExpLevel' => 'newcomer,experienced' ] )
		);

		// learner only
		$this->assertArrayEquals(
			[ 'Learner1', 'Learner2', 'Learner3', 'Learner4' ],
			$this->fetchUsers( [ 'userExpLevel' => 'learner' ] )
		);

		// more experienced only
		$this->assertArrayEquals(
			[ 'Experienced1' ],
			$this->fetchUsers( [ 'userExpLevel' => 'experienced' ] )
		);

		// learner and more experienced
		$this->assertArrayEquals(
			[
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
				'Experienced1',
			],
			$this->fetchUsers( [ 'userExpLevel' => 'learner,experienced' ] )
		);

		// newcomers, learner, and more experienced
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
				'Experienced1',
			],
			$this->fetchUsers( [ 'userExpLevel' => 'newcomer,learner,experienced' ] )
		);

		// 'all'
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
				'Experienced1',
			],
			$this->fetchUsers( [ 'userExpLevel' => 'all' ] )
		);
	}

	private function createUsers( $specs ) {
		$dbw = wfGetDB( DB_MASTER );
		foreach ( $specs as $name => $spec ) {
			User::createNew(
				$name,
				[
					'editcount' => $spec['edits'],
					'registration' => $dbw->timestamp( $this->daysAgo( $spec['days'] ) ),
					'email' => 'ut',
				]
			);
		}
	}

	private function fetchUsers( $filters ) {
		$specialRC = new SpecialRecentChanges();

		$tables = [];
		$conds = [];
		$join_conds = [];

		$specialRC->filterOnUserExperienceLevel(
			$tables,
			$conds,
			$join_conds,
			$filters
		);

		$result = wfGetDB( DB_MASTER )->select(
			'user',
			'user_name',
			array_filter( $conds ) + [ 'user_email' => 'ut' ]
		);

		$usernames = [];
		foreach ( $result as $row ) {
			$usernames[] = $row->user_name;
		}

		return $usernames;
	}

	private function daysAgo( $days ) {
		$secondsPerDay = 86400;
		return time() - $days * $secondsPerDay;
	}
}
