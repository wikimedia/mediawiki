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
		$currentUser = null
	) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $requestOptions ) );

		if ( $currentUser ) {
			$context->setUser( $currentUser );
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

	public function testEditAuthorshipFilter_myEdits_loggedIn() {
		$user = $this->getTestUser()->getUser();

		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_user = '%s'", $user->getId() ),
				1 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'me',
				'hidemyself' => 0,
			],
			'rc conditions for MY edits: editauthorship=me, hidemyself=0',
			$user
		);

		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_user = '%s'", $user->getId() ),
				1 => sprintf( "rc_user != '%s'", $user->getId() ),
				2 => "rc_type != '6'",
			],
			[ # what to do when the old and new filters contradict each other?
				'editauthorship' => 'me',
				'hidemyself' => 1,
			],
			'rc conditions for MY edits: editauthorship=me, hidemyself=1',
			$user
		);
	}

	public function testEditAuthorshipFilter_myEdits_anon() {
		$ip = '10.11.12.13';
		$anon = User::newFromName( $ip, false );
		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_user_text = '%s'", $ip ),
				1 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'me',
			],
			'rc conditions for MY edits: editauthorship=me',
			$anon
		);
	}

	public function testEditAuthorshipFilter_editsFromOthers_loggedIn() {
		$user = $this->getTestUser()->getUser();

		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_user != '%s'", $user->getId() ),
				1 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'others',
				'hidemyself' => 0,
			],
			'rc conditions for edits by other people: editauthorship=others, hidemyself=0',
			$user
		);

		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_user != '%s'", $user->getId() ),
				1 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'others',
				'hidemyself' => 1,
			],
			'rc conditions for edits by other people: editauthorship=others, hidemyself=1',
			$user
		);

		$this->assertConditions(
			[ # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_user != '%s'", $user->getId() ),
				1 => "rc_type != '6'",
			],
			[
				'hidemyself' => 1,
			],
			'rc conditions for edits by other people: hidemyself=1',
			$user
		);
	}

	public function testEditAuthorshipFilter_editsFromMeAndOthers() {
		$this->assertConditions(
			[ # expected (no filtering on users)
				'rc_bot' => 0,
				0 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'all',
			],
			'rc conditions for edits by other people: editauthorship=all'
		);

		$this->assertConditions(
			[ # expected (no filtering on users)
				'rc_bot' => 0,
				0 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'me,others',
			],
			'rc conditions for edits by other people: editauthorship=me,others'
		);

		$this->assertConditions(
			[ # expected (no filtering on users)
				'rc_bot' => 0,
				0 => "rc_type != '6'",
			],
			[
				'editauthorship' => 'whatever',
			],
			'rc conditions for edits by other people: editauthorship=whatever'
		);

		$this->assertConditions(
			[ # expected (no filtering on users)
				'rc_bot' => 0,
				0 => "rc_type != '6'",
			],
			[],
			'rc conditions for edits by other people: no editauthorship or hidemyself'
		);
	}
}
