<?php
/**
 * Test class for SpecialSearch class
 * Copyright © 2012, Antoine Musso
 *
 * @author Antoine Musso
 * @group Database
 */

class SpecialSearchTest extends MediaWikiTestCase {

	/**
	 * @covers SpecialSearch::load
	 * @dataProvider provideSearchOptionsTests
	 * @param array $requested Request parameters. For example:
	 *   array( 'ns5' => true, 'ns6' => true). Null to use default options.
	 * @param array $userOptions User options to test with. For example:
	 *   array('searchNs5' => 1 );. Null to use default options.
	 * @param string $expectedProfile An expected search profile name
	 * @param array $expectedNS Expected namespaces
	 * @param string $message
	 */
	public function testProfileAndNamespaceLoading( $requested, $userOptions,
		$expectedProfile, $expectedNS, $message = 'Profile name and namespaces mismatches!'
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
		$context->setRequest( new FauxRequest( $requested ) );
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
				'Namespaces' => $expectedNS,
			),
			array( /** Actual: */
				'ProfileName' => $search->getProfile(),
				'Namespaces' => $search->getNamespaces(),
			),
			$message
		);
	}

	public static function provideSearchOptionsTests() {
		$defaultNS = SearchEngine::defaultNamespaces();
		$EMPTY_REQUEST = array();
		$NO_USER_PREF = null;

		return array(
			/**
			 * Parameters:
			 *     <Web Request>, <User options>
			 * Followed by expected values:
			 *     <ProfileName>, <NSList>
			 * Then an optional message.
			 */
			array(
				$EMPTY_REQUEST, $NO_USER_PREF,
				'default', $defaultNS,
				'Bug 33270: No request nor user preferences should give default profile'
			),
			array(
				array( 'ns5' => 1 ), $NO_USER_PREF,
				'advanced', array( 5 ),
				'Web request with specific NS should override user preference'
			),
			array(
				$EMPTY_REQUEST, array(
				'searchNs2' => 1,
				'searchNs14' => 1,
			) + array_fill_keys( array_map( function ( $ns ) {
				return "searchNs$ns";
			}, $defaultNS ), 0 ),
				'advanced', array( 2, 14 ),
				'Bug 33583: search with no option should honor User search preferences'
					. ' and have all other namespace disabled'
			),
		);
	}

	/**
	 * Helper to create a new User object with given options
	 * User remains anonymous though
	 * @param array|null $opt
	 */
	function newUserWithSearchNS( $opt = null ) {
		$u = User::newFromId( 0 );
		if ( $opt === null ) {
			return $u;
		}
		foreach ( $opt as $name => $value ) {
			$u->setOption( $name, $value );
		}

		return $u;
	}

	/**
	 * Verify we do not expand search term in <title> on search result page
	 * https://gerrit.wikimedia.org/r/4841
	 */
	public function testSearchTermIsNotExpanded() {
		$this->setMwGlobals( array(
			'wgSearchType' => null,
		) );

		# Initialize [[Special::Search]]
		$search = new SpecialSearch();
		$search->getContext()->setTitle( Title::newFromText( 'Special:Search' ) );
		$search->load();

		# Simulate a user searching for a given term
		$term = '{{SITENAME}}';
		$search->showResults( $term );

		# Lookup the HTML page title set for that page
		$pageTitle = $search
			->getContext()
			->getOutput()
			->getHTMLTitle();

		# Compare :-]
		$this->assertRegExp(
			'/' . preg_quote( $term ) . '/',
			$pageTitle,
			"Search term '{$term}' should not be expanded in Special:Search <title>"
		);
	}
}
