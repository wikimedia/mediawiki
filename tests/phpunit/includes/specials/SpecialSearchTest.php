<?php
use MediaWiki\MediaWikiServices;

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
			[ /** Expected: */
				'ProfileName' => $expectedProfile,
				'Namespaces' => $expectedNS,
			],
			[ /** Actual: */
				'ProfileName' => $search->getProfile(),
				'Namespaces' => $search->getNamespaces(),
			],
			$message
		);
	}

	public static function provideSearchOptionsTests() {
		$defaultNS = MediaWikiServices::getInstance()->getSearchEngineConfig()->defaultNamespaces();
		$EMPTY_REQUEST = [];
		$NO_USER_PREF = null;

		return [
			/**
			 * Parameters:
			 *     <Web Request>, <User options>
			 * Followed by expected values:
			 *     <ProfileName>, <NSList>
			 * Then an optional message.
			 */
			[
				$EMPTY_REQUEST, $NO_USER_PREF,
				'default', $defaultNS,
				'Bug 33270: No request nor user preferences should give default profile'
			],
			[
				[ 'ns5' => 1 ], $NO_USER_PREF,
				'advanced', [ 5 ],
				'Web request with specific NS should override user preference'
			],
			[
				$EMPTY_REQUEST, [
				'searchNs2' => 1,
				'searchNs14' => 1,
			] + array_fill_keys( array_map( function ( $ns ) {
				return "searchNs$ns";
			}, $defaultNS ), 0 ),
				'advanced', [ 2, 14 ],
				'Bug 33583: search with no option should honor User search preferences'
					. ' and have all other namespace disabled'
			],
		];
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
		$this->setMwGlobals( [
			'wgSearchType' => null,
		] );

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
			'/' . preg_quote( $term, '/' ) . '/',
			$pageTitle,
			"Search term '{$term}' should not be expanded in Special:Search <title>"
		);
	}

	public function provideRewriteQueryWithSuggestion() {
		return [
			[
				'With suggestion and no rewritten query shows did you mean',
				'/Did you mean: <a[^>]+>first suggestion/',
				new SpecialSearchTestMockResultSet( 'first suggestion', null, [
					SearchResult::newFromTitle( Title::newMainPage() ),
				] ),
			],

			[
				'With rewritten query informs user of change',
				'/Showing results for <a[^>]+>first suggestion/',
				new SpecialSearchTestMockResultSet( 'asdf', 'first suggestion', [
					SearchResult::newFromTitle( Title::newMainPage() ),
				] ),
			],

			[
				'When both queries have no results user gets no results',
				'/There were no results matching the query/',
				new SpecialSearchTestMockResultSet( 'first suggestion', 'first suggestion', [] ),
			],
		];
	}

	/**
	 * @dataProvider provideRewriteQueryWithSuggestion
	 */
	public function testRewriteQueryWithSuggestion( $message, $expectRegex, $results ) {
		$mockSearchEngine = $this->mockSearchEngine( $results );
		$search = $this->getMockBuilder( 'SpecialSearch' )
			->setMethods( [ 'getSearchEngine' ] )
			->getMock();
		$search->expects( $this->any() )
			->method( 'getSearchEngine' )
			->will( $this->returnValue( $mockSearchEngine ) );

		$search->getContext()->setTitle( Title::makeTitle( NS_SPECIAL, 'Search' ) );
		$search->getContext()->setLanguage( Language::factory( 'en' ) );
		$search->load();
		$search->showResults( 'this is a fake search' );

		$html = $search->getContext()->getOutput()->getHTML();
		foreach ( (array)$expectRegex as $regex ) {
			$this->assertRegExp( $regex, $html, $message );
		}
	}

	protected function mockSearchEngine( $results ) {
		$mock = $this->getMockBuilder( 'SearchEngine' )
			->setMethods( [ 'searchText', 'searchTitle' ] )
			->getMock();

		$mock->expects( $this->any() )
			->method( 'searchText' )
			->will( $this->returnValue( $results ) );

		return $mock;
	}
}

class SpecialSearchTestMockResultSet extends SearchResultSet {
	protected $results;
	protected $suggestion;

	public function __construct(
		$suggestion = null,
		$rewrittenQuery = null,
		array $results = [],
		$containedSyntax = false
	) {
		$this->suggestion = $suggestion;
		$this->rewrittenQuery = $rewrittenQuery;
		$this->results = $results;
		$this->containedSyntax = $containedSyntax;
	}

	public function numRows() {
		return count( $this->results );
	}

	public function getTotalHits() {
		return $this->numRows();
	}

	public function hasSuggestion() {
		return $this->suggestion !== null;
	}

	public function getSuggestionQuery() {
		return $this->suggestion;
	}

	public function getSuggestionSnippet() {
		return $this->suggestion;
	}

	public function hasRewrittenQuery() {
		return $this->rewrittenQuery !== null;
	}

	public function getQueryAfterRewrite() {
		return $this->rewrittenQuery;
	}

	public function getQueryAfterRewriteSnippet() {
		return htmlspecialchars( $this->rewrittenQuery );
	}
}
