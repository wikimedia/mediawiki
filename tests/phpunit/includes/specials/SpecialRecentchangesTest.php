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
	private function assertConditions( $expected, $requestOptions = null, $message = '' ) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $requestOptions ) );

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
		return array_map(
			function ( $k, $v ) {
				return is_numeric( $k ) ? $v : "$k = $v";
			},
			array_keys( $conds ),
			$conds
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
}
