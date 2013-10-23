<?php

/**
 * @group JobQueue
 * @group medium
 * @group Database
 */
class JobQueueTest extends MediaWikiTestCase {
	protected $key;
	protected $queueRand, $queueRandTTL, $queueFifo, $queueFifoTTL;

	function __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'job';
	}

	protected function setUp() {
		global $wgJobTypeConf;
		parent::setUp();

		$this->setMwGlobals( 'wgMemc', new HashBagOStuff() );

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
		$variants = array(
			'queueRand' => array( 'order' => 'random', 'claimTTL' => 0 ),
			'queueRandTTL' => array( 'order' => 'random', 'claimTTL' => 10 ),
			'queueTimestamp' => array( 'order' => 'timestamp', 'claimTTL' => 0 ),
			'queueTimestampTTL' => array( 'order' => 'timestamp', 'claimTTL' => 10 ),
			'queueFifo' => array( 'order' => 'fifo', 'claimTTL' => 0 ),
			'queueFifoTTL' => array( 'order' => 'fifo', 'claimTTL' => 10 ),
		);
		foreach ( $variants as $q => $settings ) {
			try {
				$this->$q = JobQueue::factory( $settings + $baseConfig );
				if ( !( $this->$q instanceof JobQueueDB ) ) {
					$this->$q->setTestingPrefix( 'unittests-' . wfRandomString( 32 ) );
				}
			} catch ( MWException $e ) {
				// unsupported?
				// @todo What if it was another error?
			};
		}
	}

	protected function tearDown() {
		parent::tearDown();
		foreach (
			array(
				'queueRand', 'queueRandTTL', 'queueTimestamp', 'queueTimestampTTL',
				'queueFifo', 'queueFifoTTL'
			) as $q
		) {
			if ( $this->$q ) {
				$this->$q->delete();
			}
			$this->$q = null;
		}
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testProperties( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$this->assertEquals( wfWikiID(), $queue->getWiki(), "Proper wiki ID ($desc)" );
		$this->assertEquals( 'null', $queue->getType(), "Proper job type ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testBasicOperations( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

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
		$jobs = iterator_to_array( $queue->getAllQueuedJobs() );
		$this->assertEquals( 2, count( $jobs ), "Queue iterator size is correct ($desc)" );

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

		$this->assertTrue( $queue->batchPush( array( $this->newJob(), $this->newJob() ) ),
			"Push worked ($desc)" );
		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->delete();
		$queue->flushCaches();
		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
	}

	/**
	 * @dataProvider provider_queueLists
	 */
	public function testBasicDeduplication( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

		$this->assertTrue( $queue->isEmpty(), "Queue is empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 0, $queue->getSize(), "Queue is empty ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "Queue is empty ($desc)" );

		$this->assertTrue(
			$queue->batchPush(
				array( $this->newDedupedJob(), $this->newDedupedJob(), $this->newDedupedJob() )
			),
			"Push worked ($desc)" );

		$this->assertFalse( $queue->isEmpty(), "Queue is not empty ($desc)" );

		$queue->flushCaches();
		$this->assertEquals( 1, $queue->getSize(), "Queue size is correct ($desc)" );
		$this->assertEquals( 0, $queue->getAcquiredCount(), "No jobs active ($desc)" );

		$this->assertTrue(
			$queue->batchPush(
				array( $this->newDedupedJob(), $this->newDedupedJob(), $this->newDedupedJob() )
			),
			"Push worked ($desc)"
		);

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
	public function testRootDeduplication( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

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
	public function testJobOrder( $queue, $recycles, $desc ) {
		$queue = $this->$queue;
		if ( !$queue ) {
			$this->markTestSkipped( $desc );
		}

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
	}

	public static function provider_queueLists() {
		return array(
			array( 'queueRand', false, 'Random queue without ack()' ),
			array( 'queueRandTTL', true, 'Random queue with ack()' ),
			array( 'queueTimestamp', false, 'Time ordered queue without ack()' ),
			array( 'queueTimestampTTL', true, 'Time ordered queue with ack()' ),
			array( 'queueFifo', false, 'FIFO ordered queue without ack()' ),
			array( 'queueFifoTTL', true, 'FIFO ordered queue with ack()' )
		);
	}

	public static function provider_fifoQueueLists() {
		return array(
			array( 'queueFifo', false, 'Ordered queue without ack()' ),
			array( 'queueFifoTTL', true, 'Ordered queue with ack()' )
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
}
