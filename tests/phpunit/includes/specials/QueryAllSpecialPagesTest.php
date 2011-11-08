<?php
/**
 * Test class to run the query of most of all our special pages
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 * @group Database
 */
global $IP;
require_once("$IP/includes/QueryPage.php");
class QueryAllSpecialPagesTest extends MediaWikiTestCase {

	/** List query pages that can not be tested automatically */
	protected $manualTest = array(
		'LinkSearchPage'
	);

	/**
	 * Initialize all query page objects
	 */
	function __construct() {
		parent::__construct();

		global $wgQueryPages;
		foreach( $wgQueryPages as $page ) {
			$class = $page[0];
			if( ! in_array( $class, $this->manualTest ) ) {
				$this->queryPages[$class] = new $class;
			}
		}
	}

	/**
	 * Test SQL for each of our QueryPages objects
	 * @group Database
	 */
	function testQuerypageSqlQuery() {
		foreach( $this->queryPages as $page ) {

			$msg = "SQL query for page {$page->getName()} should give a result wrapper object" ;

			$result = $page->reallyDoQuery( 50 );
			if( $result instanceof ResultWrapper ) {
				$this->assertTrue( true, $msg );
			} else {
				$this->assertFalse( false, $msg );
			}
		}
	}
}
