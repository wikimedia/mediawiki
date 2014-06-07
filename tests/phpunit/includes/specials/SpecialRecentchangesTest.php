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

		# Filter out rc_timestamp conditions which depends on the test runtime
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
			array( # expected
				'rc_bot' => 0,
				0 => "rc_namespace = '0'",
			),
			array(
				'namespace' => NS_MAIN,
			),
			"rc conditions with no options (aka default setting)"
		);
	}

	public function testRcNsFilterInversion() {
		$this->assertConditions(
			array( # expected
				'rc_bot' => 0,
				0 => sprintf( "rc_namespace != '%s'", NS_MAIN ),
			),
			array(
				'namespace' => NS_MAIN,
				'invert' => 1,
			),
			"rc conditions with namespace inverted"
		);
	}

	/**
	 * @bug 2429
	 * @dataProvider provideNamespacesAssociations
	 */
	public function testRcNsFilterAssociation( $ns1, $ns2 ) {
		$this->assertConditions(
			array( # expected
				'rc_bot' => 0,
				0 => sprintf( "(rc_namespace = '%s' OR rc_namespace = '%s')", $ns1, $ns2 ),
			),
			array(
				'namespace' => $ns1,
				'associated' => 1,
			),
			"rc conditions with namespace inverted"
		);
	}

	/**
	 * @bug 2429
	 * @dataProvider provideNamespacesAssociations
	 */
	public function testRcNsFilterAssociationWithInversion( $ns1, $ns2 ) {
		$this->assertConditions(
			array( # expected
				'rc_bot' => 0,
				0 => sprintf( "(rc_namespace != '%s' AND rc_namespace != '%s')", $ns1, $ns2 ),
			),
			array(
				'namespace' => $ns1,
				'associated' => 1,
				'invert' => 1,
			),
			"rc conditions with namespace inverted"
		);
	}

	/**
	 * Provides associated namespaces to test recent changes
	 * namespaces association filtering.
	 */
	public static function provideNamespacesAssociations() {
		return array( # (NS => Associated_NS)
			array( NS_MAIN, NS_TALK ),
			array( NS_TALK, NS_MAIN ),
		);
	}
}
