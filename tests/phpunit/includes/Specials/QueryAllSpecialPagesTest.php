<?php
/**
 * Test class to run the query of most of all our special pages
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 */
namespace MediaWiki\Tests\Specials;

use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialLinkSearch;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialAncientPages
 * @covers \MediaWiki\Specials\SpecialBrokenRedirects
 * @covers \MediaWiki\Specials\SpecialDeadendPages
 * @covers \MediaWiki\Specials\SpecialDoubleRedirects
 * @covers \MediaWiki\Specials\SpecialListDuplicatedFiles
 * @covers \MediaWiki\Specials\SpecialLinkSearch
 * @covers \MediaWiki\Specials\SpecialListRedirects
 * @covers \MediaWiki\Specials\SpecialLonelyPages
 * @covers \MediaWiki\Specials\SpecialLongPages
 * @covers \MediaWiki\Specials\SpecialMediaStatistics
 * @covers \MediaWiki\Specials\SpecialMIMESearch
 * @covers \MediaWiki\Specials\SpecialMostCategories
 * @covers \MediaWiki\Specials\SpecialMostImages
 * @covers \MediaWiki\Specials\SpecialMostInterwikis
 * @covers \MediaWiki\Specials\SpecialMostLinkedCategories
 * @covers \MediaWiki\Specials\SpecialMostLinkedTemplates
 * @covers \MediaWiki\Specials\SpecialMostLinked
 * @covers \MediaWiki\Specials\SpecialMostRevisions
 * @covers \MediaWiki\Specials\SpecialFewestRevisions
 * @covers \MediaWiki\Specials\SpecialShortPages
 * @covers \MediaWiki\Specials\SpecialUncategorizedCategories
 * @covers \MediaWiki\Specials\SpecialUncategorizedPages
 * @covers \MediaWiki\Specials\SpecialUncategorizedImages
 * @covers \MediaWiki\Specials\SpecialUncategorizedTemplates
 * @covers \MediaWiki\Specials\SpecialUnusedCategories
 * @covers \MediaWiki\Specials\SpecialUnusedImages
 * @covers \MediaWiki\Specials\SpecialWantedCategories
 * @covers \MediaWiki\Specials\SpecialWantedFiles
 * @covers \MediaWiki\Specials\SpecialWantedPages
 * @covers \MediaWiki\Specials\SpecialWantedTemplates
 * @covers \MediaWiki\Specials\SpecialUnwatchedPages
 * @covers \MediaWiki\Specials\SpecialUnusedTemplates
 * @covers \MediaWiki\Specials\SpecialWithoutInterwiki
 */
class QueryAllSpecialPagesTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var SpecialPage[]
	 */
	private $queryPages;

	/** @var string[] List query pages that cannot be tested automatically */
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
		foreach ( $this->queryPages as $page ) {
			// With MySQL, skips special pages reopening a temporary table
			// See https://bugs.mysql.com/bug.php?id=10327
			if (
				$this->getDb()->getType() === 'mysql' &&
				str_contains( $this->getDb()->getSoftwareLink(), 'MySQL' ) &&
				in_array( $page->getName(), $this->reopensTempTable )
			) {
				$this->markTestSkipped( "SQL query for page {$page->getName()} "
					. "cannot be tested on MySQL backend (it reopens a temporary table)" );
				continue;
			}

			$msg = "SQL query for page {$page->getName()} should give a result wrapper object";

			$result = $page->reallyDoQuery( 50 );
			$this->assertInstanceOf( ResultWrapper::class, $result, $msg );
		}
	}
}
