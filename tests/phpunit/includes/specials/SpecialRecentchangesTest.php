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
			$expected,
			$queryConditions,
			$message
		);
	}

	/** return false if condition begin with 'rc_timestamp ' */
	private static function filterOutRcTimestampCondition( $var ) {
		return ( false === strpos( $var, 'rc_timestamp ' ) );
	}

	public function testRcNsFilter() {
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => "rc_type != '6'",
				1 => "rc_namespace = '0'",
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
				0 => "rc_type != '6'",
				1 => sprintf( "rc_namespace != '%s'", NS_MAIN ),
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
				0 => "rc_type != '6'",
				1 => sprintf( "(rc_namespace = '%s' OR rc_namespace = '%s')", $ns1, $ns2 ),
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
				0 => "rc_type != '6'",
				1 => sprintf( "(rc_namespace != '%s' AND rc_namespace != '%s')", $ns1, $ns2 ),
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
				0 => "rc_user != '{$user->getId()}'",
				1 => "rc_type != '6'",
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
				0 => "rc_user_text != '10.11.12.13'",
				1 => "rc_type != '6'",
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
				0 => "rc_user = '{$user->getId()}'",
				1 => "rc_type != '6'",
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
				0 => "rc_user_text = '10.11.12.13'",
				1 => "rc_type != '6'",
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
				0 => "rc_user != '{$user->getId()}'",
				1 => "rc_user = '{$user->getId()}'",
				2 => "rc_type != '6'",
			],
			[
				'hidemyself' => 1,
				'hidebyothers' => 1,
			],
			"rc conditions: hidemyself=1 hidebyothers=1 (logged in)",
			$user
		);
	}

	public function testRcHidepatrolledDisabledFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => "rc_type != '6'",
			],
			[
				'hidepatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1 (user not allowed)",
			$user
		);
	}

	public function testRcHidenonpatrolledDisabledFilter() {
		$user = $this->getTestUser()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => "rc_type != '6'",
			],
			[
				'hidenonpatrolled' => 1,
			],
			"rc conditions: hidenonpatrolled=1 (user not allowed)",
			$user
		);
	}
	public function testRcHidepatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => "rc_patrolled = 0",
				1 => "rc_type != '6'",
			],
			[
				'hidepatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1",
			$user
		);
	}

	public function testRcHidenonpatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => "rc_patrolled = 1",
				1 => "rc_type != '6'",
			],
			[
				'hidenonpatrolled' => 1,
			],
			"rc conditions: hidenonpatrolled=1",
			$user
		);
	}

	// This is probably going to change when we do auto-fix of
	// filters combinations that don't make sense but for now
	// it's the behavior therefore it's the test.
	public function testRcHidepatrolledHidenonpatrolledFilter() {
		$user = $this->getTestSysop()->getUser();
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => "rc_patrolled = 0",
				1 => "rc_patrolled = 1",
				2 => "rc_type != '6'",
			],
			[
				'hidepatrolled' => 1,
				'hidenonpatrolled' => 1,
			],
			"rc conditions: hidepatrolled=1 hidenonpatrolled=1",
			$user
		);
	}
}
