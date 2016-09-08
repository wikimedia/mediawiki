<?php

class WaitConditionLoopFakeTime extends WaitConditionLoop {
	protected $wallClock = 1;

	function __construct( callable $condition, $timeout, array $busyCallbacks ) {
		parent::__construct( $condition, $timeout, $busyCallbacks );
	}

	function usleep( $microseconds ) {
		$this->wallClock += $microseconds / 1e6;
	}

	function getCpuTime() {
		return 0.0;
	}

	function getWallTime() {
		return $this->wallClock;
	}

	public function setWallClock( &$timestamp ) {
		$this->wallClock =& $timestamp;
	}
}

class WaitConditionLoopTest extends PHPUnit_Framework_TestCase {
	public function testCallbackReached() {
		$wallClock = microtime( true );

		$count = 0;
		$status = new StatusValue();
		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$count, $status ) {
				++$count;
				$status->value = 'cookie';

				return WaitConditionLoop::CONDITION_REACHED;
			},
			10.0,
			$this->newBusyWork( $x, $y, $z )
		);
		$this->assertEquals( $loop::CONDITION_REACHED, $loop->invoke() );
		$this->assertEquals( 1, $count );
		$this->assertEquals( 'cookie', $status->value );
		$this->assertEquals( [ 0, 0, 0 ], [ $x, $y, $z ], "No busy work done" );

		$count = 0;
		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$count, &$wallClock ) {
				$wallClock += 1;
				++$count;

				return $count >= 2 ? WaitConditionLoop::CONDITION_REACHED : false;
			},
			7.0,
			$this->newBusyWork( $x, $y, $z, $wallClock )
		);
		$this->assertEquals( $loop::CONDITION_REACHED, $loop->invoke(),
			"Busy work did not cause timeout" );
		$this->assertEquals( [ 1, 0, 0 ], [ $x, $y, $z ] );

		$count = 0;
		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$count, &$wallClock ) {
				$wallClock += .1;
				++$count;

				return $count > 80 ? true : false;
			},
			50.0,
			$this->newBusyWork( $x, $y, $z, $wallClock, $dontCallMe, $badCalls )
		);
		$this->assertEquals( 0, $badCalls, "Callback exception not yet called" );
		$this->assertEquals( $loop::CONDITION_REACHED, $loop->invoke() );
		$this->assertEquals( [ 1, 1, 1 ], [ $x, $y, $z ], "Busy work done" );
		$this->assertEquals( 1, $badCalls, "Bad callback ran and was exception caught" );

		try {
			$e = null;
			$dontCallMe();
		} catch ( Exception $e ) {
		}

		$this->assertInstanceOf( 'RunTimeException', $e );
		$this->assertEquals( 1, $badCalls, "Callback exception cached" );
	}

	public function testCallbackTimeout() {
		$count = 0;
		$wallClock = microtime( true );
		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$count, &$wallClock ) {
				$wallClock += 3;
				++$count;

				return $count > 300 ? true : false;
			},
			50.0,
			$this->newBusyWork( $x, $y, $z, $wallClock )
		);
		$loop->setWallClock( $wallClock );
		$this->assertEquals( $loop::CONDITION_TIMED_OUT, $loop->invoke() );
		$this->assertEquals( [ 1, 1, 1 ], [ $x, $y, $z ], "Busy work done" );

		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$count, &$wallClock ) {
				$wallClock += 3;
				++$count;

				return true;
			},
			0.0,
			$this->newBusyWork( $x, $y, $z, $wallClock )
		);
		$this->assertEquals( $loop::CONDITION_REACHED, $loop->invoke() );

		$count = 0;
		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$count, &$wallClock ) {
				$wallClock += 3;
				++$count;

				return $count > 10 ? true : false;
			},
			0,
			$this->newBusyWork( $x, $y, $z, $wallClock )
		);
		$this->assertEquals( $loop::CONDITION_FAILED, $loop->invoke() );
	}

	public function testCallbackAborted() {
		$x = 0;
		$wallClock = microtime( true );
		$loop = new WaitConditionLoopFakeTime(
			function () use ( &$x, &$wallClock ) {
				$wallClock += 2;
				++$x;

				return $x > 2 ? WaitConditionLoop::CONDITION_ABORTED : false;
			},
			10.0,
			$this->newBusyWork( $x, $y, $z, $wallClock )
		);
		$loop->setWallClock( $wallClock );
		$this->assertEquals( $loop::CONDITION_ABORTED, $loop->invoke() );
	}

	private function newBusyWork(
		&$x, &$y, &$z, &$wallClock = 1, &$dontCallMe = null, &$badCalls = 0
	) {
		$x = $y = $z = 0;
		$badCalls = 0;

		$list = [];
		$list[] = function () use ( &$x, &$wallClock ) {
			$wallClock += 1;

			return ++$x;
		};
		$dontCallMe = function () use ( &$badCalls ) {
			++$badCalls;
			throw new RuntimeException( "TrollyMcTrollFace" );
		};
		$list[] =& $dontCallMe;
		$list[] = function () use ( &$y, &$wallClock ) {
			$wallClock += 15;

			return ++$y;
		};
		$list[] = function () use ( &$z, &$wallClock ) {
			$wallClock += 0.1;

			return ++$z;
		};

		return $list;
	}
}
