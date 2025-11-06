<?php
namespace MediaWiki\Tests\JobQueue\Jobs;

use MediaWiki\Category\Category;
use MediaWiki\JobQueue\Jobs\CategoryCountUpdateJob;
use MediaWiki\Page\WikiPage;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\JobQueue\Jobs\CategoryCountUpdateJob
 *
 * @group JobQueue
 * @group Database
 *
 * @license GPL-2.0-or-later
 */
class CategoryCountUpdateJobTest extends MediaWikiIntegrationTestCase {

	private const TITLE_STRING = 'UTCatChangeJobPage';

	protected function setUp(): void {
		parent::setUp();
		$this->setContentLang( 'qqx' );
	}

	public function testUpdateCategoryCounts() {
		$page = new WikiPage( Title::newFromText( self::TITLE_STRING ) );

		// Add an initial category
		$jobSpec = CategoryCountUpdateJob::newSpec( $page, [ 'A' ], [], 100 );
		$job = new CategoryCountUpdateJob(
			$page,
			$jobSpec->getParams(),
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getNamespaceInfo()
		);
		$job->run();

		$this->assertSame( 1, Category::newFromName( 'A' )->getMemberCount() );
		$this->assertSame( 0, Category::newFromName( 'B' )->getMemberCount() );
		$this->assertSame( 0, Category::newFromName( 'C' )->getMemberCount() );

		// Add a new category
		$jobSpec = CategoryCountUpdateJob::newSpec( $page, [ 'B' ], [], 100 );
		$job = new CategoryCountUpdateJob(
			$page,
			$jobSpec->getParams(),
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getNamespaceInfo()
		);
		$job->run();

		$this->assertSame( 1, Category::newFromName( 'A' )->getMemberCount() );
		$this->assertSame( 1, Category::newFromName( 'B' )->getMemberCount() );
		$this->assertSame( 0, Category::newFromName( 'C' )->getMemberCount() );

		// Add and remove a category
		$jobSpec = CategoryCountUpdateJob::newSpec( $page, [ 'C' ], [ 'A' ], 100 );
		$job = new CategoryCountUpdateJob(
			$page,
			$jobSpec->getParams(),
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getNamespaceInfo()
		);
		$job->run();

		$this->assertSame( 0, Category::newFromName( 'A' )->getMemberCount() );
		$this->assertSame( 1, Category::newFromName( 'B' )->getMemberCount() );
		$this->assertSame( 1, Category::newFromName( 'C' )->getMemberCount() );
	}
}
