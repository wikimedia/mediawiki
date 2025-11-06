<?php
namespace MediaWiki\Tests\JobQueue\Jobs;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob;
use MediaWiki\MediaWikiServices;
use MediaWiki\OutputTransform\Stages\RenderDebugInfo;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\ParserOptions;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group JobQueue
 * @group Database
 *
 * @license GPL-2.0-or-later
 */
class ParsoidCachePrewarmJobTest extends MediaWikiIntegrationTestCase {

	private const NON_JOB_QUEUE_EDIT = 'parsoid edit not executed by job queue';
	private const JOB_QUEUE_EDIT = 'parsoid edit executed by job queue';

	private function getPageIdentity( PageRecord $page ): PageIdentityValue {
		return PageIdentityValue::localIdentity(
			$page->getId(),
			$page->getNamespace(),
			$page->getDBkey()
		);
	}

	private function getPageRecord( PageIdentity $page ): PageRecord {
		return $this->getServiceContainer()->getPageStore()
			->getPageByReference( $page );
	}

	/**
	 * @covers \MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob::doParsoidCacheUpdate
	 * @covers \MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob::newSpec
	 * @covers \MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob::run
	 */
	public function testRun() {
		$page = $this->getExistingTestPage( 'ParsoidPrewarmJob' )->toPageRecord();
		$rev1 = $this->editPage( $page, self::NON_JOB_QUEUE_EDIT )->getNewRevision();

		$parsoidPrewarmJob = new ParsoidCachePrewarmJob(
			[ 'revId' => $rev1->getId(), 'pageId' => $page->getId() ],
			$this->getServiceContainer()->getParserOutputAccess(),
			$this->getServiceContainer()->getPageStore(),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getParsoidSiteConfig()
		);

		// NOTE: calling ->run() will not run the job scheduled in the queue but will
		//       instead call doParsoidCacheUpdate() directly. Will run the job and assert
		//       below.
		$execStatus = $parsoidPrewarmJob->run();
		$this->assertTrue( $execStatus );

		$popts = ParserOptions::newFromAnon();
		$popts->setUseParsoid();
		$parsoidOutput = $this->getServiceContainer()->getParserOutputAccess()->getCachedParserOutput(
			$this->getPageRecord( $this->getPageIdentity( $page ) ),
			$popts,
			$rev1
		);

		// Ensure we have the parsoid output in parser cache as an HTML document
		$this->assertStringContainsString( '<html', $parsoidOutput->getRawText() );
		$this->assertStringContainsString( self::NON_JOB_QUEUE_EDIT, $parsoidOutput->getRawText() );

		$rev2 = $this->editPage( $page, self::JOB_QUEUE_EDIT )->getNewRevision();
		// Post-edit, reset all services!
		// ParserOutputAccess has a localCache which can incorrectly return stale
		// content for the previous revision! Resetting ensures that ParsoidCachePrewarmJob
		// gets a fresh copy of ParserOutputAccess.
		$this->resetServices();

		$parsoidPrewarmJob = new ParsoidCachePrewarmJob(
			[ 'revId' => $rev2->getId(), 'pageId' => $page->getId(), 'causeAction' => 'just for testing' ],
			$this->getServiceContainer()->getParserOutputAccess(),
			$this->getServiceContainer()->getPageStore(),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getParsoidSiteConfig()
		);

		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$jobQueueGroup->get( $parsoidPrewarmJob->getType() )->delete();
		$jobQueueGroup->push( $parsoidPrewarmJob );

		// At this point, we have 1 job scheduled for this job type.
		$this->assertSame( 1, $jobQueueGroup->getQueueSizes()['parsoidCachePrewarm'] );

		// doParsoidCacheUpdate() now with a job queue instead of calling directly.
		$this->runJobs( [ 'maxJobs' => 1 ], [ 'type' => 'parsoidCachePrewarm' ] );

		// At this point, we have 0 jobs scheduled for this job type.
		$this->assertSame( 0, $jobQueueGroup->getQueueSizes()['parsoidCachePrewarm'] );

		$parsoidOutput = $this->getServiceContainer()->getParserOutputAccess()->getCachedParserOutput(
			$this->getPageRecord( $this->getPageIdentity( $page ) ),
			$popts,
			$rev2
		);

		// Ensure we have the parsoid output in parser cache as an HTML document
		$this->assertStringContainsString( '<html', $parsoidOutput->getRawText() );
		$this->assertStringContainsString( self::JOB_QUEUE_EDIT, $parsoidOutput->getRawText() );

		$services = MediaWikiServices::getInstance();
		$servicesOptions = new ServiceOptions(
			RenderDebugInfo::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
		);
		$rdi = TestingAccessWrapper::newFromObject(
			new RenderDebugInfo( $servicesOptions, new NullLogger(), $services->getHookContainer() )
		);
		// Check that the causeAction was looped through as the render reason
		$this->assertStringContainsString(
			'triggered because: just for testing',
			$rdi->debugInfo( $parsoidOutput )
		);
	}

	/**
	 * @covers \MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob::newSpec
	 */
	public function testEnqueueSpec() {
		$page = $this->getExistingTestPage( 'ParsoidPrewarmJob' )->toPageRecord();
		$rev1 = $this->editPage( $page, self::NON_JOB_QUEUE_EDIT )->getNewRevision();

		$parsoidPrewarmSpec = ParsoidCachePrewarmJob::newSpec(
			$rev1->getId(), $page,
		);

		$this->assertSame( 'parsoidCachePrewarm', $parsoidPrewarmSpec->getType(), 'getType' );

		$dedupeInfo = $parsoidPrewarmSpec->getDeduplicationInfo();
		$this->assertTrue( $dedupeInfo['params']['rootJobIsSelf'] );
		$this->assertSame( $page->getTouched(), $dedupeInfo['params']['page_touched'] );
		$this->assertSame( $rev1->getId(), $dedupeInfo['params']['revId'] );
		$this->assertSame( $page->getId(), $dedupeInfo['params']['pageId'] );

		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$jobQueueGroup->get( $parsoidPrewarmSpec->getType() )->delete();

		$jobQueueGroup->push( $parsoidPrewarmSpec );

		// At this point, we have 1 job scheduled for this job type.
		$this->assertSame( 1, $jobQueueGroup->getQueueSizes()['parsoidCachePrewarm'] );

		// Push again times, deduplication should apply!
		$jobQueueGroup->push( $parsoidPrewarmSpec );
		$jobQueueGroup->push( $parsoidPrewarmSpec );

		// We should still have just 1 job scheduled for this job type.
		$this->assertSame( 1, $jobQueueGroup->getQueueSizes()['parsoidCachePrewarm'] );
	}

}
