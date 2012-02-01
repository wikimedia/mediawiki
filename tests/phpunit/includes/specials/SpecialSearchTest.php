<?php
/**
 * Test class for SpecialSearch class
 * Copyright © 2012, Antoine Musso
 *
 * @author Antoine Musso
 * @group Database
 */

class SpecialSearchTest extends MediaWikiTestCase {
	private $search;

	function setUp() { }
	function tearDown() { }

	/**
	 * @covers SpecialSearch::load
	 * @dataProvider provideSearchOptionsTests
	 * @param $requested Array Request parameters. For example array( 'ns5' => true, 'ns6' => true). NULL to use default options.
	 * @param $userOptions Array User options to test with. For example array('searchNs5' => 1 );. NULL to use default options.
	 * @param $expectedProfile An expected search profile name
	 * @param $expectedNs Array Expected namespaces
	 */
	function testProfileAndNamespaceLoading(
		$requested, $userOptions, $expectedProfile, $expectedNS,
		$message = 'Profile name and namespaces mismatches!'
	) {
		$context = new RequestContext;
		$context->setUser(
			$this->newUserWithSearchNS( $userOptions )
		);
		/*
		$context->setRequest( new FauxRequest( array(
			'ns5'=>true,
			'ns6'=>true,
		) ));
		 */
		$context->setRequest( new FauxRequest( $requested ));
		$search = new SpecialSearch();
		$search->setContext( $context );
		$search->load();

		/**
		 * Verify profile name and namespace in the same assertion to make
		 * sure we will be able to fully compare the above code. PHPUnit stop
		 * after an assertion fail.
		 */
		$this->assertEquals(
			array( /** Expected: */
				'ProfileName' => $expectedProfile,
				'Namespaces'  => $expectedNS,
			)
			, array( /** Actual: */
				'ProfileName' => $search->getProfile(),
				'Namespaces'  => $search->getNamespaces(),
			)
			, $message
		);

	}

	function provideSearchOptionsTests() {
		$defaultNS = SearchEngine::defaultNamespaces();
		$EMPTY_REQUEST = array();
		$NO_USER_PREF  = null;

		return array(
			/**
			 * Parameters:
			 * 	<Web Request>, <User options>
			 * Followed by expected values:
			 * 	<ProfileName>, <NSList>
			 * Then an optional message.
			 */
			array(
				$EMPTY_REQUEST, $NO_USER_PREF,
				'default', $defaultNS,
				'Bug 33270: No request nor user preferences should give default profile'
			),
			array(
				array( 'ns5' => 1 ), $NO_USER_PREF,
				'advanced', array(  5),
				'Web request with specific NS should override user preference'
			),
			array(
				$EMPTY_REQUEST, array( 'searchNs2' => 1, 'searchNs14' => 1 ),
				'advanced', array( 2, 14 ),
				'Bug 33583: search with no option should honor User search preferences'
			),
		);
	}

	/**
	 * Helper to create a new User object with given options
	 * User remains anonymous though
	 */
	function newUserWithSearchNS( $opt = null ) {
		$u = User::newFromId(0);
		if( $opt === null ) {
			return $u;
		}
		foreach($opt as $name => $value) {
			$u->setOption( $name, $value );
		}
		return $u;
	}
}

