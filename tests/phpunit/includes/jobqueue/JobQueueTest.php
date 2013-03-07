<?php

/**
 * @group JobQueue
 * @group medium
 * @group Database
 */
class JobQueueTest extends MediaWikiTestCase {
	protected $key;
	protected $queueRand, $queueRandTTL, $queueFifo, $queueFifoTTL, $queueDelay;
	protected $old = array();

	function __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'job';
	}

	protected function setUp() {
		global $wgMemc, $wgJobTypeConf;
		parent::setUp();
		$this->old['wgMemc'] = $wgMemc;
		$wgMemc = new HashBagOStuff();
		if ( $this->getCliArg( 'use-jobqueue=' ) ) {
			$name = $this->getCliArg( 'use-jobqueue=' );
			if ( !isset( $wgJobTypeConf[$name] ) ) {
				throw new MWException( "No \$wgJobTypeConf entry for '$name'." );
			}
			$baseConfig = $wgJobTypeConf[$name];
		} else {
			$baseConfig = array( 'class' => 'JobQueueDB' );
		}
		$baseConfig['type'] = 'null';
		$baseConfig['wiki'] = wfWikiID();
		$this->queueRand = JobQueue::factory(
			array( 'order' => 'random', 'claimTTL' => 0 ) + $baseConfig );
		$this->queueRandTTL = JobQueue::factory(
			array( 'order' => 'random', 'claimTTL' => 10 ) + $baseConfig );
		$this->queueFifo = JobQueue::factory(
			array( 'order' => 'fifo', 'claimTTL' => 0 ) + $baseConfig );
		$this->queueFifoTTL = JobQueue::factory(
			array( 'order' => 'fifo', 'claimTTL' => 10 ) + $baseConfig );
		$this->queueDelay = JobQueue::factory(
			array( 'order' => 'fifo', 'checkDelay' => true ) + $baseConfig );

		if ( $baseConfig['class'] !== 'JobQueueDB' ) { // DB namespace with prefix or temp tables
			foreach ( array( 'queueRand', 'queueRandTTL', 'queueFifo', 'queueFifoTTL', 'queueDelay' ) as $q ) {
				$this->$q->setTestingPrefix( 'unittests-' . wfRandomString( 32 ) );
			}
		}
	}

	protected function tearDown() {
		global $wgMemc;
		parent::tearDown();
		foreach ( array( 'queueRand', 'queueRandTTL', 'queueFifo', 'queueFifoTTL', 'queueDelay' ) as $q ) {
			do {
				$job = $this->$q->pop();
				if ( $job ) {
					$this->$q->ack( $job );
				}
			} while ( $job );
		}
		$this->queueRand = null;
		$this->queueRandTTL = null;
		$this->queueFifo = null;
		$this->queueFifoTTL = null;
		$this->queueDelay = null;
		$wgMemc = $this->old['wgMemc'];
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	function testProperties( $queue, $order, $recycles, $desc ) {
		$queue = $this->$queue;

		$this->assertEquals( wfWikiID(), $queue->getWiki(), "Proper wiki ID ($desc)" );
		$this->assertEquals( 'null', $queue->getType(), "Proper job type ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	function testBasicOperations( $queue, $order, $recycles, $desc ) {
		$queue = $this->$queue;
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$this->assertTrue( $queue->push( $this->newJob() ), "Push worked ($desc)" );
		$this->assertTrue( $queue->batchPush( array( $this->newJob() ) ), "Push worked ($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 2, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$job1 = $queue->pop();
		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 1, $queue->getSize(), "Queue size is correct ($desc)" );

		$queue->flushCaches();
		if ( $recycles ) {
			$this->assertEquals( 1, $queue->getAcquiredCount(), "Active job count ($desc)" );
		} else {
			$this->assertEquals( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$job2 = $queue->pop();
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		if ( $recycles ) {
			$this->assertEquals( 2, $queue->getAcquiredCount(), "Active job count ($desc)" );
		} else {
			$this->assertEquals( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$queue->ack( $job1 );

		$queue->flushCaches();
		if ( $recycles ) {
			$this->assertEquals( 1, $queue->getAcquiredCount(), "Active job count ($desc)" );
		} else {
			$this->assertEquals( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$queue->ack( $job2 );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$this->assertTrue( $queue->push( $this->newDelayedJob() ), "Push of delayed job worked regardless of support($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );
		$queue->flushCaches();
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	function testBasicDeduplication( $queue, $order, $recycles, $desc ) {
		$queue = $this->$queue;

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$this->assertTrue( $queue->batchPush(
				array( $this->newDedupedJob(), $this->newDedupedJob(), $this->newDedupedJob() ) ),
			"Push worked ($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 1, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$this->assertTrue( $queue->batchPush(
				array( $this->newDedupedJob(), $this->newDedupedJob(), $this->newDedupedJob() ) ),
			"Push worked ($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 1, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$job1 = $queue->pop();
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		if ( $recycles ) {
			$this->assertEquals( 1, $queue->getAcquiredCount(), "Active job count ($desc)" );
		} else {
			$this->assertEquals( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );
		}

		$queue->ack( $job1 );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Active job count ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	function testRootDeduplication( $queue, $order, $recycles, $desc ) {
		$queue = $this->$queue;

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$id = wfRandomString( 32 );
		$root1 = Job::newRootJobParams( "nulljobspam:$id" ); // task ID/timestamp
		for ( $i = 0; $i < 5; ++$i ) {
			$this->assertTrue( $queue->push( $this->newJob( 0, $root1 ) ), "Push worked ($desc)" );
		}
		$queue->deduplicateRootJob( $this->newJob( 0, $root1 ) );
		sleep( 1 ); // roo job timestamp will increase
		$root2 = Job::newRootJobParams( "nulljobspam:$id" ); // task ID/timestamp
		$this->assertNotEquals( $root1['rootJobTimestamp'], $root2['rootJobTimestamp'],
			"Root job signatures have different timestamps." );
		for ( $i = 0; $i < 5; ++$i ) {
			$this->assertTrue( $queue->push( $this->newJob( 0, $root2 ) ), "Push worked ($desc)" );
		}
		$queue->deduplicateRootJob( $this->newJob( 0, $root2 ) );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 10, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$dupcount = 0;
		$jobs = array();
		do {
			$job = $queue->pop();
			if ( $job ) {
				$jobs[] = $job;
				$queue->ack( $job );
			}
			if ( $job instanceof DuplicateJob ) {
				++$dupcount;
			}
		} while ( $job );

		$this->assertEquals( 10, count( $jobs ), "Correct number of jobs popped ($desc)" );
		$this->assertEquals( 5, $dupcount, "Correct number of duplicate jobs popped ($desc)" );
	}

	/**
	 * @dataProvider provider_fifoQueueLists
	 */
	function testJobOrder( $queue, $recycles, $desc ) {
		$queue = $this->$queue;

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		for ( $i = 0; $i < 10; ++$i ) {
			$this->assertTrue( $queue->push( $this->newJob( $i ) ), "Push worked ($desc)" );
		}

		for ( $i = 0; $i < 10; ++$i ) {
			$job = $queue->pop();
			$this->assertTrue( $job instanceof Job, "Jobs popped from queue ($desc)" );
			$params = $job->getParams();
			$this->assertEquals( $i, $params['i'], "Job popped from queue is FIFO ($desc)" );
			$queue->ack( $job );
		}

		$this->assertFalse( $queue->pop(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		//Test that delays have no effect on a non-delayed queue
		$this->assertTrue( $queue->push( $this->newDelayedJob( 1, array(), 1 ) ), "Push worked ($desc)" );
		$job = $queue->pop();
		$this->assertTrue( $job instanceof Job, "Immediate pop should succeed with delay request ignored ($desc)" );
		$queue->ack( $job );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 2, array(), 0 ) ), "Push worked ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 3, array(), 999 ) ), "Push worked ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 4, array(), 0 ) ), "Push worked ($desc)" );

		//  pop order should be FIFO - 2, 3, 4
		for ( $i = 2; $i < 5 ; ++$i ) {
			$job = $queue->pop();
			$this->assertTrue( $job instanceof Job, "Jobs popped from queue ($desc)" );
			$params = $job->getParams();
			$this->assertEquals( $i, $params['i'], "Job with ignored delay $i popped from queue in position $i ($desc)" );
			$queue->ack( $job );
		}
	}

	/**
	 * @dataProvider provider_delayedQueueLists
	 * Note these tests rely on magic numbers and timing so are susceptible to spurious failures
	 */
	function testDelayedJobOrder( $queue, $recycles, $desc ) {
		$queue = $this->$queue;

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		for ( $i = 0; $i < 10; ++$i ) {
			$this->assertTrue( $queue->push( $this->newDelayedJob( $i, array(), 0 ) ), "Push worked ($desc)" );
		}

		for ( $i = 0; $i < 10; ++$i ) {
			$job = $queue->pop();
			$this->assertTrue( $job instanceof Job, "Jobs popped from queue ($desc)" );
			$params = $job->getParams();
			$this->assertEquals( $i, $params['i'], "Job with zero delay popped from queue is FIFO ($desc)" );
			$queue->ack( $job );
		}

		$this->assertFalse( $queue->pop(), "Queue is empty ($desc)" );

		// test setting delay param
		$this->assertTrue( $queue->push( $this->newDelayedJob( 1, array(), 2 ) ), "Push worked ($desc)" );
		$this->assertFalse( $queue->pop(), "Immediate pop should fail with delay ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 2, array(), 0 ) ), "Push worked ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 3, array(), 999 ) ), "Push worked ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 4, array(), 0 ) ), "Push worked ($desc)" );

		// after waiting more than a second, pop order should be 1,2,4,false
		$correct = array( 1, 2, 4 );
		sleep(2);
		for ( $i = 0; $i < 3 ; ++$i ) {
			$job = $queue->pop();
			$this->assertTrue( $job instanceof Job, "Jobs popped from queue ($desc)" );
			$params = $job->getParams();
			$this->assertEquals( $correct[$i], $params['i'], "Job with delay {$correct[$i]} popped from queue in position $i ($desc)" );
			$queue->ack( $job );
		}
		$this->assertFalse( $queue->pop(), "Long delayed item should not be popable during this test ($desc)" );


		// test setting notBefore param
		$this->assertTrue( $queue->push( $this->newDelayedJob( 1, array(), 0, time() + 2 ) ), "Push worked ($desc)" );
		$this->assertFalse( $queue->pop(), "Immediate pop should fail with notBefore ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 2, array(), 0, time() + 0 ) ), "Push worked ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 3, array(), 0, time() + 999 ) ), "Push worked ($desc)" );
		$this->assertTrue( $queue->push( $this->newDelayedJob( 4, array(), 0, time() + 0 ) ), "Push worked ($desc)" );

		// after waiting more than a second, pop order should be 1,2,4,false
		$correct = array( 1, 2, 4 );
		sleep(2);
		for ( $i = 0; $i < 3 ; ++$i ) {
			$job = $queue->pop();
			$this->assertTrue( $job instanceof Job, "Jobs popped from queue ($desc)" );
			$params = $job->getParams();
			$this->assertEquals( $correct[$i], $params['i'], "Job with delay {$correct[$i]} popped from queue in position $i ($desc)" );
			$queue->ack( $job );
		}
		$this->assertFalse( $queue->pop(), "Long delayed item should not be popable during this test ($desc)" );
	}

	function provider_queueLists() {
		return array(
			array( 'queueRand', 'rand', false, 'Random queue without ack()' ),
			array( 'queueRandTTL', 'rand', true, 'Random queue with ack()' ),
			array( 'queueFifo', 'fifo', false, 'Ordered queue without ack()' ),
			array( 'queueFifoTTL', 'fifo', true, 'Ordered queue with ack()' ),
			array( 'queueDelay', 'fifo', false, 'Ordered queue with delayed start times' )
		);
	}

	function provider_fifoQueueLists() {
		return array(
			array( 'queueFifo', false, 'Ordered queue without ack()' ),
			array( 'queueFifoTTL', true, 'Ordered queue with ack()' )
		);
	}

	function provider_delayedQueueLists() {
		return array(
			array( 'queueDelay', 'fifo', false, 'Ordered queue with delayed start times' )
		);
	}

	function newJob( $i = 0, $rootJob = array() ) {
		return new NullJob( Title::newMainPage(),
			array( 'lives' => 0, 'usleep' => 0, 'removeDuplicates' => 0, 'i' => $i ) + $rootJob );
	}

	function newDedupedJob( $i = 0, $rootJob = array() ) {
		return new NullJob( Title::newMainPage(),
			array( 'lives' => 0, 'usleep' => 0, 'removeDuplicates' => 1, 'i' => $i ) + $rootJob );
	}

	function newDelayedJob( $i = 0, $rootJob = array(), $delay = 0, $notBefore = 0 ) {
		$params = array( 'lives' => 0, 'usleep' => 0, 'removeDuplicates' => 0, 'i' => $i ) + $rootJob;
		if( $delay !== 0 ) {
			$params['delay'] = $delay;
		}
		if( $notBefore !== 0 ) {
			$params['notBefore'] = $notBefore;
		}
		return new NullJob( Title::newMainPage(), $params );
	}

}
