<?php

/**
 * @group Search
 * @group Database
 */
class PrefixSearchTest extends MediaWikiTestCase {
	protected function searchProvision( Array $results ) {
		$this->setMwGlobals( 'wgHooks', array(
			'PrefixSearchBackend' => array(
				function ( $namespaces, $search, $limit, &$srchres ) use ( $results ) {
					$srchres = $results;
					return false;
				}
			),
		) );
	}

	public function addDBData() {
		$this->insertPage( 'Test' );
		$this->insertPage( 'Example' );
		$this->insertPage( 'Foo' );
	}

	/**
	 * @covers PrefixSearch::searchBackend
	 */
	public function testBug70958() {
		$this->searchProvision( array(
			'Test',
			'Testing',
			'Tests',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Test', 3 );
		$this->assertEquals(
			array( 'Test', 'Testing', 'Tests' ),
			$results,
			'Simple case'
		);

		$this->searchProvision( array(
			'Example.net',
			'Example',
			'Example.com',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Example', 3 );
		$this->assertEquals(
			array( 'Example', 'Example.net', 'Example.com' ),
			$results,
			'Exact match not on top'
		);

		$this->searchProvision( array(
			'Football',
			'Foot',
			'Fool',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Foo', 3 );
		$this->assertEquals(
			// Last result is dropped since a limit of 3 was requested
			array( 'Foo', 'Football', 'Foot' ),
			$results,
			'Exact match missing'
		);

		$this->searchProvision( array(
			'Barcelona',
			'Barbara',
			'Bart',
		) );
		$searcher = new StringPrefixSearch;
		$results = $searcher->search( 'Bar', 3 );
		$this->assertEquals(
			array( 'Barcelona', 'Barbara', 'Bart' ),
			$results,
			'Exact match missing and not existing'
		);
	}
}
