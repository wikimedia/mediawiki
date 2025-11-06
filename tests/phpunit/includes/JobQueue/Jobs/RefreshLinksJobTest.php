<?php
namespace MediaWiki\Tests\JobQueue\Jobs;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\WikitextContent;
use MediaWiki\JobQueue\Jobs\RefreshLinksJob;
use MediaWiki\Page\PageAssertionException;
use MediaWiki\Page\WikiPage;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \MediaWiki\JobQueue\Jobs\RefreshLinksJob
 *
 * @group JobQueue
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author Addshore
 */
class RefreshLinksJobTest extends MediaWikiIntegrationTestCase {
	/** @var StatsFactory */
	private $statsFactory;

	protected function setUp(): void {
		parent::setUp();
		$this->statsFactory = StatsFactory::newNull();
		$this->setService( 'StatsFactory', $this->statsFactory );
	}

	/**
	 * @param string $name
	 * @param Content[] $content
	 *
	 * @return WikiPage
	 */
	private function createPage( $name, array $content ) {
		$title = Title::makeTitle( $this->getDefaultWikitextNS(), $name );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$updater = $page->newPageUpdater( $this->getTestUser()->getUser() );

		foreach ( $content as $slot => $cnt ) {
			$updater->setContent( $slot, $cnt );
		}

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( 'Test' ) );

		return $page;
	}

	// TODO: test multi-page
	// TODO: test recursive
	// TODO: test partition

	public function testBadTitle() {
		$specialBlankPage = Title::makeTitle( NS_SPECIAL, 'Blankpage' );

		$this->expectException( PageAssertionException::class );
		new RefreshLinksJob( $specialBlankPage, [] );
	}

	public function testRunForNonexistentPage() {
		$nonexistentPage = $this->getNonexistingTestPage();
		$job = new RefreshLinksJob( $nonexistentPage, [] );
		$totalFailuresCounter = $this->statsFactory->getCounter( 'refreshlinks_failures_total' );

		$result = $job->run();

		$this->assertFalse( $result );
		$this->assertSame( 1, $totalFailuresCounter->getSampleCount() );
	}

	public function testUpdateSuperseded() {
		$page = $this->getExistingTestPage();
		$job = new RefreshLinksJob( $page->getTitle(), [ 'rootJobTimestamp' => '20240101000000' ] );
		$supersededUpdatesCounter = $this->statsFactory->getCounter( 'refreshlinks_superseded_updates_total' );

		$result = $job->run();

		$this->assertTrue( $result );
		$this->assertSame( 1, $supersededUpdatesCounter->getSampleCount() );
	}

	public function testStaleRevision() {
		$page = $this->getExistingTestPage();
		$prevRev = $page->getRevisionRecord();
		$this->editPage( $page, 'New content' );

		$job = new RefreshLinksJob( $page->getTitle(), [ 'triggeringRevisionId' => $prevRev->getId() ] );
		$totalFailuresCounter = $this->statsFactory->getCounter( 'refreshlinks_failures_total' );

		$result = $job->run();

		// We don't want to retry the job so it is returned with true.
		$this->assertTrue( $result );
		$this->assertSame( 1, $totalFailuresCounter->getSampleCount() );
		$this->assertSame( "Revision {$prevRev->getId()} is not current", $job->getLastError() );
	}

	public function testRunForSinglePage() {
		$this->getServiceContainer()->getSlotRoleRegistry()->defineRoleWithModel(
			'aux',
			CONTENT_MODEL_WIKITEXT
		);

		$cacheOpsCounter = $this->statsFactory->getCounter( 'refreshlinks_parsercache_operations_total' );

		$mainContent = new WikitextContent( 'MAIN [[Kittens]]' );
		$auxContent = new WikitextContent( 'AUX [[Category:Goats]]' );
		$page = $this->createPage( __METHOD__, [ 'main' => $mainContent, 'aux' => $auxContent ] );

		// clear state
		$parserCache = $this->getServiceContainer()->getParserCache();
		$parserCache->deleteOptionsKey( $page );

		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'pagelinks' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'categorylinks' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();

		// run job
		$job = new RefreshLinksJob( $page->getTitle(), [ 'parseThreshold' => 0 ] );
		$result = $job->run();

		$this->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $page->getId() ] )
			->assertFieldValue( 'Kittens' );
		$this->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'categorylinks' )
			->join( 'linktarget', null, 'cl_target_id=lt_id' )
			->where( [ 'cl_from' => $page->getId() ] )
			->assertFieldValue( 'Goats' );

		$this->assertTrue( $result );
		$this->assertSame( 1, $cacheOpsCounter->getSampleCount() );
	}

	public function testRunForMultiPage() {
		$this->getServiceContainer()->getSlotRoleRegistry()->defineRoleWithModel(
			'aux',
			CONTENT_MODEL_WIKITEXT
		);

		$fname = __METHOD__;

		$mainContent = new WikitextContent( 'MAIN [[Kittens]]' );
		$auxContent = new WikitextContent( 'AUX [[Category:Goats]]' );
		$page1 = $this->createPage( "$fname-1", [ 'main' => $mainContent, 'aux' => $auxContent ] );

		$mainContent = new WikitextContent( 'MAIN [[Dogs]]' );
		$auxContent = new WikitextContent( 'AUX [[Category:Hamsters]]' );
		$page2 = $this->createPage( "$fname-2", [ 'main' => $mainContent, 'aux' => $auxContent ] );

		// clear state
		$parserCache = $this->getServiceContainer()->getParserCache();
		$parserCache->deleteOptionsKey( $page1 );
		$parserCache->deleteOptionsKey( $page2 );

		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'pagelinks' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'categorylinks' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();

		// run job
		$job = new RefreshLinksJob(
			Title::makeTitle( NS_SPECIAL, 'Blankpage' ),
			[ 'pages' => [ [ 0, "$fname-1" ], [ 0, "$fname-2" ] ] ]
		);
		$job->run();

		$this->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $page1->getId() ] )
			->assertFieldValue( 'Kittens' );
		$this->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'categorylinks' )
			->join( 'linktarget', null, 'cl_target_id=lt_id' )
			->where( [ 'cl_from' => $page1->getId() ] )
			->assertFieldValue( 'Goats' );
		$this->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $page2->getId() ] )
			->assertFieldValue( 'Dogs' );
		$this->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'categorylinks' )
			->join( 'linktarget', null, 'cl_target_id=lt_id' )
			->where( [ 'cl_from' => $page2->getId() ] )
			->assertFieldValue( 'Hamsters' );
	}
}
