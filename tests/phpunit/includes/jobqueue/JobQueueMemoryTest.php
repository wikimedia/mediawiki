<?php

/**
 * @covers JobQueueMemory
 *
 * @group JobQueue
 *
 * @license GNU GPL v2+
 * @author Thiemo Kreuz
 */
class JobQueueMemoryTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @return JobQueueMemory
	 */
	private function newJobQueue() {
		return JobQueue::factory( [
			'class' => JobQueueMemory::class,
			'wiki' => wfWikiID(),
			'type' => 'null',
		] );
	}

	private function newJobSpecification() {
		return new JobSpecification(
			'null',
			[ 'customParameter' => null ],
			[],
			Title::newFromText( 'Custom title' )
		);
	}

	public function testGetAllQueuedJobs() {
		$queue = $this->newJobQueue();
		$this->assertCount( 0, $queue->getAllQueuedJobs() );

		$queue->push( $this->newJobSpecification() );
		$this->assertCount( 1, $queue->getAllQueuedJobs() );
	}

	public function testGetAllAcquiredJobs() {
		$queue = $this->newJobQueue();
		$this->assertCount( 0, $queue->getAllAcquiredJobs() );

		$queue->push( $this->newJobSpecification() );
		$this->assertCount( 0, $queue->getAllAcquiredJobs() );

		$queue->pop();
		$this->assertCount( 1, $queue->getAllAcquiredJobs() );
	}

	public function testJobFromSpecInternal() {
		$queue = $this->newJobQueue();
		$job = $queue->jobFromSpecInternal( $this->newJobSpecification() );
		$this->assertInstanceOf( Job::class, $job );
		$this->assertSame( 'null', $job->getType() );
		$this->assertArrayHasKey( 'customParameter', $job->getParams() );
		$this->assertSame( 'Custom title', $job->getTitle()->getText() );
	}

}
