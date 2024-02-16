<?php

namespace Wikimedia\Tests\WRStats;

use PHPUnit\Framework\TestCase;
use Wikimedia\WRStats\ArrayStatsStore;
use Wikimedia\WRStats\LimitCondition;
use Wikimedia\WRStats\LimitOperation;
use Wikimedia\WRStats\LocalEntityKey;
use Wikimedia\WRStats\WRStatsRateLimiter;

/**
 * @covers \Wikimedia\WRStats\WRStatsRateLimiter
 * @covers \Wikimedia\WRStats\LimitOperationResult
 * @covers \Wikimedia\WRStats\LimitBatch
 * @covers \Wikimedia\WRStats\LimitBatchResult
 * @covers \Wikimedia\WRStats\LimitCondition
 * @covers \Wikimedia\WRStats\LimitOperation
 */
class WRStatsRateLimiterTest extends TestCase {
	public function testTryIncrBatch() {
		$store = new ArrayStatsStore;
		$conds = [
			'cond1' => new LimitCondition( 3, 60 ),
		];
		$rateLimiter = new WRStatsRateLimiter( $store, $conds );
		$rateLimiter->setCurrentTime( 1000 );
		$actions = [ new LimitOperation( 'cond1' ) ];

		$batchResult = $rateLimiter->tryIncrBatch( $actions );
		$this->assertTrue( $batchResult->isAllowed() );
		$this->assertSame( "LimitActionResult{1/3}",
			$batchResult->getAllResults()[0]->dump() );

		$this->assertTrue( $rateLimiter->tryIncrBatch( $actions )->isAllowed() );
		$this->assertTrue( $rateLimiter->tryIncrBatch( $actions )->isAllowed() );

		$batchResult = $rateLimiter->tryIncrBatch( $actions );
		$this->assertFalse( $batchResult->isAllowed() );
		$this->assertCount( 0, $batchResult->getPassedResults() );
		$failed = $batchResult->getFailedResults();
		$this->assertCount( 1, $failed );
		$this->assertSame( "LimitActionResult{4/3}", $failed[0]->dump() );
		$this->assertSame( 3, $failed[0]->prevTotal );
		$this->assertSame( 4, $failed[0]->newTotal );
	}

	public function testMultiEntity() {
		$store = new ArrayStatsStore;
		$conds = [
			'cond1' => new LimitCondition( 1, 60 ),
		];
		$entity1 = new LocalEntityKey( [ 'user_id', 1 ] );
		$entity2 = new LocalEntityKey( [ 'user_id', 2 ] );
		$rateLimiter = new WRStatsRateLimiter( $store, $conds );
		$rateLimiter->setCurrentTime( 1000 );
		$this->assertTrue(
			$rateLimiter->tryIncrBatch( [
				new LimitOperation( 'cond1', $entity1 )
			] )->isAllowed()
		);
		$this->assertTrue(
			$rateLimiter->tryIncrBatch( [
				new LimitOperation( 'cond1', $entity2 )
			] )->isAllowed()
		);
		$rateLimiter->incr( 'cond1', $entity1, 10 );
		$rateLimiter->incr( 'cond1', $entity2, 20 );
		$res = $rateLimiter->tryIncr( 'cond1', $entity1 );
		$this->assertSame( 'LimitActionResult{12/1}', $res->dump() );
		$res = $rateLimiter->tryIncr( 'cond1', $entity2 );
		$this->assertSame( 'LimitActionResult{22/1}', $res->dump() );
	}

	public function testMultiEntityBatch() {
		$store = new ArrayStatsStore;
		$conds = [
			'id' => new LimitCondition( 2, 60 ),
			'ip' => new LimitCondition( 3, 60 )
		];
		$rateLimiter = new WRStatsRateLimiter( $store, $conds );
		$rateLimiter->setCurrentTime( 1000 );

		// Increment both metrics to 1
		$res = $rateLimiter->createBatch()
			->localOp( 'id', 1 )
			->globalOp( 'ip', '127.0.0.1' )
			->tryIncr();
		$this->assertTrue( $res->isAllowed() );
		$this->assertSame( 'LimitActionResult{1/2}',
			$res->getAllResults()['id']->dump() );
		$this->assertSame( 'LimitActionResult{1/3}',
			$res->getAllResults()['ip']->dump() );

		// Increment to 2, testing peek/incr
		$batch = $rateLimiter->createBatch()
			->localOp( 'id', 1 )
			->globalOp( 'ip', '127.0.0.1' );
		$this->assertTrue( $batch->peek()->isAllowed() );
		$batch->incr();

		// Increment to 3, in which the id metric is expected to fail
		$res = $rateLimiter->createBatch()
			->localOp( 'id', 1 )
			->globalOp( 'ip', '127.0.0.1' )
			->tryIncr();
		$this->assertFalse( $res->isAllowed() );
		$this->assertCount( 1, $res->getPassedResults() );
		$failed = $res->getFailedResults();
		$this->assertCount( 1, $failed );
		$all = $res->getAllResults();
		$this->assertSame( 'LimitActionResult{3/2}', $all['id']->dump() );
		$this->assertSame( 'LimitActionResult{3/3}', $all['ip']->dump() );
	}

	public function testTimeDependent() {
		$store = new ArrayStatsStore;
		$conds = [
			'cond1' => new LimitCondition( 3, 100 ),
		];
		$rateLimiter = new WRStatsRateLimiter( $store, $conds, 'WRLimit',
			[ 'bucketCount' => 10 ] );

		$rateLimiter->setCurrentTime( 1000 );
		$this->assertTrue( $rateLimiter->tryIncr( 'cond1' )->isAllowed() );
		$rateLimiter->setCurrentTime( 1010 );
		$this->assertTrue( $rateLimiter->tryIncr( 'cond1' )->isAllowed() );
		$rateLimiter->setCurrentTime( 1020 );
		$this->assertTrue( $rateLimiter->tryIncr( 'cond1' )->isAllowed() );
		$rateLimiter->setCurrentTime( 1090 );
		$this->assertSame( 'LimitActionResult{4/3}',
			$rateLimiter->peek( 'cond1' )->dump() );

		$rateLimiter->setCurrentTime( 1100 );
		// The range goes back to 1000 so the count is still 3
		$this->assertSame( 3,
			$rateLimiter->peek( 'cond1' )->prevTotal );

		$rateLimiter->setCurrentTime( 1103 );
		// The range goes back to 1000 so the count should be 2.7, rounded up to 3
		$this->assertSame( 3,
			$rateLimiter->peek( 'cond1' )->prevTotal );

		$rateLimiter->setCurrentTime( 1109 );
		// The range goes back to 1009 so the first bucket is interpolated and
		// rounded down to zero, so the count is 2 and there is room for
		// another action
		$this->assertTrue( $rateLimiter->peek( 'cond1' )->isAllowed() );
		// But not room for 2 actions
		$this->assertSame( 'LimitActionResult{4/3}',
			$rateLimiter->tryIncr( 'cond1', null, 2 )->dump() );
	}
}
