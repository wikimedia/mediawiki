<?php

use Wikimedia\TestingAccessWrapper;

/**
 * Test class for ChangesListSpecialPage class
 *
 * Copyright © 2011-, Antoine Musso, Stephane Bisson, Matthew Flaschen
 *
 * @author Antoine Musso
 * @author Stephane Bisson
 * @author Matthew Flaschen
 * @group Database
 *
 * @covers ChangesListSpecialPage
 */
class ChangesListSpecialPageTest extends AbstractChangesListSpecialPageTestCase {
	protected function setUp() {
		parent::setUp();

		# setup the rc object
		$this->changesListSpecialPage = $this->getPage();
	}

	protected function getPage() {
		return TestingAccessWrapper::newFromObject(
			$this->getMockForAbstractClass(
				'ChangesListSpecialPage',
				[
					'ChangesListSpecialPage',
					''
				]
			)
		);
	}

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

		$this->changesListSpecialPage->setContext( $context );
		$formOptions = $this->changesListSpecialPage->setup( null );

		#  Filter out rc_timestamp conditions which depends on the test runtime
		# This condition is not needed as of march 2, 2011 -- hashar
		# @todo FIXME: Find a way to generate the correct rc_timestamp

		$tables = [];
		$fields = [];
		$queryConditions = [];
		$query_options = [];
		$join_conds = [];

		call_user_func_array(
			[ $this->changesListSpecialPage, 'buildQuery' ],
			[
				&$tables,
				&$fields,
				&$queryConditions,
				&$query_options,
				&$join_conds,
				$formOptions
			]
		);

		$queryConditions = array_filter(
			$queryConditions,
			'ChangesListSpecialPageTest::filterOutRcTimestampCondition'
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
	 * T4429
	 * @dataProvider provideNamespacesAssociations
	 */
	public function testRcNsFilterAssociation( $ns1, $ns2 ) {
		$this->assertConditions(
			[ # expected
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
	 * T4429
	 * @dataProvider provideNamespacesAssociations
	 */
	public function testRcNsFilterAssociationWithInversion( $ns1, $ns2 ) {
		$this->assertConditions(
			[ # expected
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
				"rc_user_text != '{$user->getName()}'",
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
				"rc_user_text != '10.11.12.13'",
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
				"rc_user_text = '{$user->getName()}'",
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
				"rc_user_text = '10.11.12.13'",
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
				"rc_user_text != '{$user->getName()}'",
				"rc_user_text = '{$user->getName()}'",
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
				"rc_patrolled = 0",
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
				"rc_patrolled = 1",
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
				"rc_minor = 0",
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
				"rc_minor = 1",
			],
			[
				'hidemajor' => 1,
			],
			"rc conditions: hidemajor=1"
		);
	}

	public function testRcHidepatrolledHideunpatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				"rc_patrolled = 0",
				"rc_patrolled = 1",
			],
			[
				'hidepatrolled' => 1,
				'hideunpatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1 hideunpatrolled=1",
			$user
		);
	}

	public function testHideCategorization() {
		$this->assertConditions(
			[
				# expected
				"rc_type != '6'"
			],
			[
				'hidecategorization' => 1
			],
			"rc conditions: hidecategorization=1"
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
			$this->fetchUsers( [ 'newcomer' ] )
		);

		// newcomers and learner
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
			],
			$this->fetchUsers( [ 'newcomer', 'learner' ] )
		);

		// newcomers and more learner
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Experienced1',
			],
			$this->fetchUsers( [ 'newcomer', 'experienced' ] )
		);

		// learner only
		$this->assertArrayEquals(
			[ 'Learner1', 'Learner2', 'Learner3', 'Learner4' ],
			$this->fetchUsers( [ 'learner' ] )
		);

		// more experienced only
		$this->assertArrayEquals(
			[ 'Experienced1' ],
			$this->fetchUsers( [ 'experienced' ] )
		);

		// learner and more experienced
		$this->assertArrayEquals(
			[
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
				'Experienced1',
			],
			$this->fetchUsers( [ 'learner', 'experienced' ] ),
			'Learner and more experienced'
		);

		// newcomers, learner, and more experienced
		// TOOD: Fix test.  This needs to test that anons are excluded,
		// and right now the join fails.
		/* $this->assertArrayEquals( */
		/* 	[ */
		/* 		'Newcomer1', 'Newcomer2', 'Newcomer3', */
		/* 		'Learner1', 'Learner2', 'Learner3', 'Learner4', */
		/* 		'Experienced1', */
		/* 	], */
		/* 	$this->fetchUsers( [ 'newcomer', 'learner', 'experienced' ] ) */
		/* ); */
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
		$tables = [];
		$conds = [];
		$fields = [];
		$query_options = [];
		$join_conds = [];

		sort( $filters );

		call_user_func_array(
			[ $this->changesListSpecialPage, 'filterOnUserExperienceLevel' ],
			[
				get_class( $this->changesListSpecialPage ),
				$this->changesListSpecialPage->getContext(),
				$this->changesListSpecialPage->getDB(),
				&$tables,
				&$fields,
				&$conds,
				&$query_options,
				&$join_conds,
				$filters
			]
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

	public function testGetFilterGroupDefinitionFromLegacyCustomFilters() {
		$customFilters = [
			'hidefoo' => [
				'msg' => 'showhidefoo',
				'default' => true,
			],

			'hidebar' => [
				'msg' => 'showhidebar',
				'default' => false,
			],
		];

		$this->assertEquals(
			[
				'name' => 'unstructured',
				'class' => ChangesListBooleanFilterGroup::class,
				'priority' => -1,
				'filters' => [
					[
						'name' => 'hidefoo',
						'showHide' => 'showhidefoo',
						'default' => true,
					],
					[
						'name' => 'hidebar',
						'showHide' => 'showhidebar',
						'default' => false,
					]
				],
			],
			$this->changesListSpecialPage->getFilterGroupDefinitionFromLegacyCustomFilters(
				$customFilters
			)
		);
	}

	public function testGetStructuredFilterJsData() {
		$definition = [
			[
				'name' => 'gub-group',
				'title' => 'gub-group-title',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hidefoo',
						'label' => 'foo-label',
						'description' => 'foo-description',
						'default' => true,
						'showHide' => 'showhidefoo',
						'priority' => 2,
					],
					[
						'name' => 'hidebar',
						'label' => 'bar-label',
						'description' => 'bar-description',
						'default' => false,
						'priority' => 4,
					]
				],
			],

			[
				'name' => 'des-group',
				'title' => 'des-group-title',
				'class' => ChangesListStringOptionsFilterGroup::class,
				'isFullCoverage' => true,
				'filters' => [
					[
						'name' => 'grault',
						'label' => 'grault-label',
						'description' => 'grault-description',
					],
					[
						'name' => 'garply',
						'label' => 'garply-label',
						'description' => 'garply-description',
					],
				],
				'queryCallable' => function () {
				},
				'default' => ChangesListStringOptionsFilterGroup::NONE,
			],

			[
				'name' => 'unstructured',
				'class' => ChangesListBooleanFilterGroup::class,
				'filters' => [
					[
						'name' => 'hidethud',
						'showHide' => 'showhidethud',
						'default' => true,
					],

					[
						'name' => 'hidemos',
						'showHide' => 'showhidemos',
						'default' => false,
					],
				],
			],

		];

		$this->changesListSpecialPage->registerFiltersFromDefinitions( $definition );

		$this->assertArrayEquals(
			[
				// Filters that only display in the unstructured UI are
				// are not included, and neither are groups that would
				// be empty due to the above.
				'groups' => [
					[
						'name' => 'gub-group',
						'title' => 'gub-group-title',
						'type' => ChangesListBooleanFilterGroup::TYPE,
						'priority' => -1,
						'filters' => [
							[
								'name' => 'hidebar',
								'label' => 'bar-label',
								'description' => 'bar-description',
								'default' => false,
								'priority' => 4,
								'cssClass' => null,
								'conflicts' => [],
								'subset' => [],
							],
							[
								'name' => 'hidefoo',
								'label' => 'foo-label',
								'description' => 'foo-description',
								'default' => true,
								'priority' => 2,
								'cssClass' => null,
								'conflicts' => [],
								'subset' => [],
							],
						],
						'fullCoverage' => true,
						'conflicts' => [],
					],

					[
						'name' => 'des-group',
						'title' => 'des-group-title',
						'type' => ChangesListStringOptionsFilterGroup::TYPE,
						'priority' => -2,
						'fullCoverage' => true,
						'filters' => [
							[
								'name' => 'grault',
								'label' => 'grault-label',
								'description' => 'grault-description',
								'cssClass' => null,
								'priority' => -2,
								'conflicts' => [],
								'subset' => [],
							],
							[
								'name' => 'garply',
								'label' => 'garply-label',
								'description' => 'garply-description',
								'cssClass' => null,
								'priority' => -3,
								'conflicts' => [],
								'subset' => [],
							],
						],
						'conflicts' => [],
						'separator' => ';',
						'default' => ChangesListStringOptionsFilterGroup::NONE,
					],
				],
				'messageKeys' => [
					'gub-group-title',
					'bar-label',
					'bar-description',
					'foo-label',
					'foo-description',
					'des-group-title',
					'grault-label',
					'grault-description',
					'garply-label',
					'garply-description',
				],
			],
			$this->changesListSpecialPage->getStructuredFilterJsData(),
			/** ordered= */ false,
			/** named= */ true
		);
	}

	public function provideParseParameters() {
		return [
			[ 'hidebots', [ 'hidebots' => true ] ],

			[ 'bots', [ 'hidebots' => false ] ],

			[ 'hideminor', [ 'hideminor' => true ] ],

			[ 'minor', [ 'hideminor' => false ] ],

			[ 'hidemajor', [ 'hidemajor' => true ] ],

			[ 'hideliu', [ 'hideliu' => true ] ],

			[ 'hidepatrolled', [ 'hidepatrolled' => true ] ],

			[ 'hideunpatrolled', [ 'hideunpatrolled' => true ] ],

			[ 'hideanons', [ 'hideanons' => true ] ],

			[ 'hidemyself', [ 'hidemyself' => true ] ],

			[ 'hidebyothers', [ 'hidebyothers' => true ] ],

			[ 'hidehumans', [ 'hidehumans' => true ] ],

			[ 'hidepageedits', [ 'hidepageedits' => true ] ],

			[ 'pagedits', [ 'hidepageedits' => false ] ],

			[ 'hidenewpages', [ 'hidenewpages' => true ] ],

			[ 'hidecategorization', [ 'hidecategorization' => true ] ],

			[ 'hidelog', [ 'hidelog' => true ] ],

			[
				'userExpLevel=learner;experienced',
				[
					'userExpLevel' => 'learner;experienced'
				],
			],

			// A few random combos
			[
				'bots,hideliu,hidemyself',
				[
					'hidebots' => false,
					'hideliu' => true,
					'hidemyself' => true,
				],
			],

			[
				'minor,hideanons,categorization',
				[
					'hideminor' => false,
					'hideanons' => true,
					'hidecategorization' => false,
				]
			],

			[
				'hidehumans,bots,hidecategorization',
				[
					'hidehumans' => true,
					'hidebots' => false,
					'hidecategorization' => true,
				],
			],

			[
				'hidemyself,userExpLevel=newcomer;learner,hideminor',
				[
					'hidemyself' => true,
					'hideminor' => true,
					'userExpLevel' => 'newcomer;learner',
				],
			],
		];
	}

	public function provideGetFilterConflicts() {
		return [
			[
				"parameters" => [],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hideliu" => true,
					"userExpLevel" => "newcomer",
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hideanons" => true,
					"userExpLevel" => "learner",
				],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hidemajor" => true,
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hideWikidata" => true,
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hidemajor" => true,
					"hidenewpages" => false,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => false,
					"hideWikidata" => true,
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hidemajor" => true,
					"hidenewpages" => false,
					"hidepageedits" => false,
					"hidecategorization" => true,
					"hidelog" => true,
					"hideWikidata" => true,
				],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hideminor" => true,
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hideWikidata" => true,
				],
				"expectedConflicts" => false,
			],
		];
	}

	/**
	 * @dataProvider provideGetFilterConflicts
	 */
	public function testGetFilterConflicts( $parameters, $expectedConflicts ) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $parameters ) );
		$this->changesListSpecialPage->setContext( $context );

		$this->assertEquals(
			$expectedConflicts,
			$this->changesListSpecialPage->areFiltersInConflict()
		);
	}
}
