<?php
/**
 * Test class to run the query of most of all our special pages
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 */

/**
 * @group Database
 * @covers QueryPage<extended>
 */
class QueryAllSpecialPagesTest extends MediaWikiTestCase {

	/**
	 * @var SpecialPage[]
	 */
	private $queryPages;

	/** List query pages that can not be tested automatically */
	protected $manualTest = [
		LinkSearchPage::class
	];

	/**
	 * Pages whose query use the same DB table more than once.
	 * This is used to skip testing those pages when run against a MySQL backend
	 * which does not support reopening a temporary table. See upstream bug:
	 * https://bugs.mysql.com/bug.php?id=10327
	 */
	protected $reopensTempTable = [
		BrokenRedirects::class,
	];

	/**
	 * Initialize all query page objects
	 */
	function __construct() {
		parent::__construct();

		foreach ( QueryPage::getPages() as $page ) {
			$class = $page[0];
			$name = $page[1];
			if ( !in_array( $class, $this->manualTest ) ) {
				$this->queryPages[$class] = SpecialPageFactory::getPage( $name );
			}
		}
	}

	/**
	 * Test SQL for each of our QueryPages objects
	 * @group Database
	 */
	public function testQuerypageSqlQuery() {
		global $wgDBtype;

		foreach ( $this->queryPages as $page ) {
			// With MySQL, skips special pages reopening a temporary table
			// See https://bugs.mysql.com/bug.php?id=10327
			if (
				$wgDBtype === 'mysql'
				&& in_array( $page->getName(), $this->reopensTempTable )
			) {
				$this->markTestSkipped( "SQL query for page {$page->getName()} "
					. "can not be tested on MySQL backend (it reopens a temporary table)" );
				continue;
			}

			$msg = "SQL query for page {$page->getName()} should give a result wrapper object";

			$result = $page->reallyDoQuery( 50 );
			if ( $result instanceof ResultWrapper ) {
				$this->assertTrue( true, $msg );
			} else {
				$this->assertFalse( false, $msg );
			}
		}
	}
}
