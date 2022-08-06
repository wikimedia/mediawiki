<?php
/**
 * Test class to run the query of most of all our special pages
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 */

use Wikimedia\Rdbms\ResultWrapper;

/**
 * @group Database
 * @covers QueryPage<extended>
 */
class QueryAllSpecialPagesTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var SpecialPage[]
	 */
	private $queryPages;

	/** @var string[] List query pages that can not be tested automatically */
	protected $manualTest = [
		SpecialLinkSearch::class
	];

	/**
	 * @var string[] Names of pages whose query use the same DB table more than once.
	 * This is used to skip testing those pages when run against a MySQL backend
	 * which does not support reopening a temporary table.
	 * For more info, see https://phabricator.wikimedia.org/T256006
	 */
	protected $reopensTempTable = [
		'BrokenRedirects',
	];

	/**
	 * Initialize all query page objects
	 */
	protected function setUp(): void {
		parent::setUp();

		foreach ( QueryPage::getPages() as [ $class, $name ] ) {
			if ( !in_array( $class, $this->manualTest ) ) {
				$this->queryPages[$class] =
					$this->getServiceContainer()->getSpecialPageFactory()->getPage( $name );
			}
		}
	}

	/**
	 * Test SQL for each of our QueryPages objects
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
			$this->assertInstanceOf( ResultWrapper::class, $result, $msg );
		}
	}
}
