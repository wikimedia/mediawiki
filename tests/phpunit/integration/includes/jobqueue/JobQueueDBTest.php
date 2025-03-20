<?php

namespace MediaWiki\Tests\Integration\JobQueue;

use MediaWiki\JobQueue\JobQueue;
use MediaWiki\JobQueue\JobQueueDB;
use MediaWiki\JobQueue\JobSpecification;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use Profiler;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\JobQueue\JobQueueDB
 * @group JobQueue
 * @group Database
 */
class JobQueueDBTest extends MediaWikiIntegrationTestCase {

	private function newJobQueue(): JobQueueDB {
		$jobQueue = JobQueue::factory( [
			'class' => JobQueueDB::class,
			'domain' => WikiMap::getCurrentWikiDbDomain()->getId(),
			'type' => 'null',
			'idGenerator' => $this->getServiceContainer()->getGlobalIdGenerator(),
			'claimTTL' => 3600,
		] );
		$this->assertInstanceOf( JobQueueDB::class, $jobQueue );
		return $jobQueue;
	}

	/**
	 * @return JobSpecification A job which has a parameter named 'id' which should be a unique enough ID to
	 *   assert that two given jobs are either the same or not the same.
	 */
	private function newJobSpecification() {
		return new JobSpecification(
			'null',
			[ 'customParameter' => null, 'id' => mt_rand( 1, 0xFFFFFF ) ],
			[],
			Title::makeTitle( NS_MAIN, 'Custom title' )
		);
	}

	private function addQueuedJobs( JobQueueDB $jobQueue, int $numOfQueuedJobs ) {
		for ( $i = 0; $i < $numOfQueuedJobs; $i++ ) {
			$jobQueue->push( $this->newJobSpecification() );
		}
	}

	private function addAcquiredJobs( JobQueueDB $jobQueue, int $numOfAcquiredJobs ) {
		for ( $i = 0; $i < $numOfAcquiredJobs; $i++ ) {
			$jobQueue->push( $this->newJobSpecification() );
			$jobQueue->pop();
		}
	}

	/** @dataProvider provideIsEmpty */
	public function testIsEmpty( $numOfQueuedJobs, $numOfAcquiredJobs, $expectedReturnValue ) {
		$jobQueue = $this->newJobQueue();
		$this->addQueuedJobs( $jobQueue, $numOfQueuedJobs );
		$this->addAcquiredJobs( $jobQueue, $numOfAcquiredJobs );
		$this->assertSame( $expectedReturnValue, $jobQueue->isEmpty() );
	}

	public static function provideIsEmpty() {
		return [
			'Job queue is empty' => [ 0, 0, true ],
			'Job queue has all acquired jobs' => [ 0, 2, true ],
			'Job queue has all queued jobs' => [ 2, 0, false ],
			'Job queue has a mix of acquired and queued jobs' => [ 2, 1, false ],
		];
	}

	/** @dataProvider provideGetSize */
	public function testGetSize( $numOfQueuedJobs, $numOfAcquiredJobs, $expectedReturnValue ) {
		$jobQueue = $this->newJobQueue();
		$this->addQueuedJobs( $jobQueue, $numOfQueuedJobs );
		$this->addAcquiredJobs( $jobQueue, $numOfAcquiredJobs );
		$this->assertSame( $expectedReturnValue, $jobQueue->getSize() );
	}

	public static function provideGetSize() {
		return [
			'Job queue is empty' => [ 0, 0, 0 ],
			'Job queue has all acquired jobs' => [ 0, 2, 0 ],
			'Job queue has all queued jobs' => [ 2, 0, 2 ],
			'Job queue has a mix of acquired and queued jobs' => [ 2, 1, 2 ],
		];
	}

	/** @dataProvider provideGetAcquiredCount */
	public function testGetAcquiredCount( $numOfQueuedJobs, $numOfAcquiredJobs, $expectedReturnValue ) {
		$jobQueue = $this->newJobQueue();
		$this->addQueuedJobs( $jobQueue, $numOfQueuedJobs );
		$this->addAcquiredJobs( $jobQueue, $numOfAcquiredJobs );
		$this->assertSame( $expectedReturnValue, $jobQueue->getAcquiredCount() );
	}

	public static function provideGetAcquiredCount() {
		return [
			'Job queue is empty' => [ 0, 0, 0 ],
			'Job queue has all acquired jobs' => [ 0, 2, 2 ],
			'Job queue has all queued jobs' => [ 2, 0, 0 ],
			'Job queue has a mix of acquired and queued jobs' => [ 2, 1, 1 ],
		];
	}

	public function testGetAllQueuedJobsWhenJobQueueEmpty() {
		$this->assertCount( 0, $this->newJobQueue()->getAllQueuedJobs() );
	}

	public function testGetAllQueuedJobs() {
		// Queue a job into the job queue
		$jobQueue = $this->newJobQueue();
		$firstJob = $this->newJobSpecification();
		$jobQueue->push( $firstJob );
		// Check that ::getAllQueuedJobs returns the job we just queued into the queue.
		$allQueuedJobs = iterator_to_array( $jobQueue->getAllQueuedJobs() );
		$this->assertCount( 1, $allQueuedJobs );
		$this->assertSame( $firstJob->getParams()['id'], $allQueuedJobs[0]->getParams()['id'] );
	}

	public function testGetAllAcquiredJobsWhenJobQueueEmpty() {
		$this->assertCount( 0, $this->newJobQueue()->getAllAcquiredJobs() );
	}

	public function testGetAllAcquiredJobs() {
		// Add a job to the queue
		$queue = $this->newJobQueue();
		$queuedJob = $this->newJobSpecification();
		$queue->push( $queuedJob );
		$this->assertCount( 0, $queue->getAllAcquiredJobs() );

		// Pop the job off the queue and check that it is the correct job
		$job = $queue->pop();
		$this->assertNotFalse( $job );
		$this->assertSame( $queuedJob->getParams()['id'], $job->getParams()['id'] );

		// Check that ::getAllAcquiredJobs returns the job we just popped off the queue
		$acquiredJobs = iterator_to_array( $queue->getAllAcquiredJobs() );
		$this->assertCount( 1, $acquiredJobs );
		$this->assertSame( $job->getParams()['id'], $acquiredJobs[0]->getParams()['id'] );
	}

	public function testPushWhenTransactionProfilerExpectsNoWrites() {
		// Narrow the expectations to be no writes and no master connections.
		$transactionProfiler = Profiler::instance()->getTransactionProfiler();
		$transactionProfiler->setExpectations(
			[ 'writes' => 0, 'masterConns' => 0 ],
			__METHOD__
		);
		// Expect no calls to the LoggerInterface used by the profiler. We can't use ::createNoOpMock because we need
		// to reset the transaction profiler to clean up after the test.
		$logCreated = false;
		$mockLogger = $this->createMock( LoggerInterface::class );
		$mockLogger->method( $this->anythingBut( [ '__debugInfo', '__destruct' ] ) )
			->willReturnCallback( static function () use ( &$logCreated ) {
				$logCreated = true;
			} );
		$transactionProfiler->setLogger( $mockLogger );

		// Push a job into the queue, which should cause a write to occur without trying to log a violated exception.
		$queue = $this->newJobQueue();
		$queue->push( $this->newJobSpecification() );
		$this->assertFalse( $logCreated );

		// Reset the logger and expectations to clean up after ourselves.
		$transactionProfiler->setLogger( new NullLogger() );
		$transactionProfiler->resetExpectations();
	}
}
