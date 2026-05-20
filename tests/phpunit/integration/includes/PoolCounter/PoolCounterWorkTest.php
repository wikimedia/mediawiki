<?php
/**
 * Provides of semaphore semantics for restricting the number
 * of workers that may be concurrently performing the same task.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\PoolCounter;

use MediaWiki\PoolCounter\PoolCounter;
use MediaWiki\PoolCounter\PoolCounterWork;
use MediaWiki\Status\Status;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Concurrency
 * @covers \MediaWiki\PoolCounter\PoolCounterWork
 */
class PoolCounterWorkTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param MockObject $obj
	 * @param array<string,mixed> $configMethods Method names mapped to return values
	 * @return MockObject
	 */
	private function configureMock( $obj, $configMethods ) {
		$obj->expects( $this->never() )
			->method( $this->anythingBut( ...array_keys( $configMethods ) ) );
		foreach ( $configMethods as $method => $value ) {
			$obj->expects( $this->once() )
				->method( $method )
				->willReturn( $value );
		}
		return $obj;
	}

	private function configureFixture(
		array $workerMethods,
		array $poolCounterMethods,
		array $loggerMethods
	) {
		$logger = $this->createMock( LoggerInterface::class );
		$this->configureMock( $logger, $loggerMethods );

		$poolCounter = $this->getMockBuilder( PoolCounter::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$poolCounter->setLogger( $logger );

		$this->configureMock( $poolCounter, $poolCounterMethods );

		$worker = $this->getMockBuilder( PoolCounterWork::class )
			->setConstructorArgs( [ 'PoolType', 'PoolKey', $poolCounter ] )
			->onlyMethods( array_keys( $workerMethods ) )
			->getMockForAbstractClass();

		$this->configureMock( $worker, $workerMethods );

		return TestingAccessWrapper::newFromObject( $worker );
	}

	public static function provideExecuteFlow() {
		yield "Not Cacheable and not skipCache should call acquireForMe" => [
			false, false,
			[
				'acquireForMe' => Status::newGood( 'StupidAction' )
			], [], [
				'info' => null
			],
			false
		];

		yield "Not Cacheable and skipCache should call acquireForMe" => [
			false, true,
			[
				'acquireForMe' => Status::newGood( 'StupidAction' )
			], [], [
				'info' => null
			],
			false
		];

		yield "Cacheable and not skipCache should call acquireForAnyone" => [
			true, false,
			[
				'acquireForAnyone' => Status::newGood( 'StupidAction' )
			], [], [
				'info' => null
			],
			false
		];

		yield "Cacheable and skipCache should call acquireForMe" => [
			true, true,
			[
				'acquireForMe' => Status::newGood( 'StupidAction' )
			], [], [
				'info' => null
			],
			false
		];

		yield 'If Fatal error call doWork and log error' => [
			false, false,
			[
				'acquireForMe' => Status::newFatal( 'apierror' )
			], [
				'doWork' => "SomeResults"
			], [
				'info' => null
			],
			"SomeResults"
		];

		foreach ( [
			'LOCK_HELD' => PoolCounter::LOCK_HELD,
			'LOCKED' => PoolCounter::LOCKED
		] as $name => $acquireResult ) {
			yield 'If ' . $name . ' call doWork and return' => [
				false, false,
				[
					'acquireForMe' => Status::newGood( $acquireResult ),
					'release' => null
				], [
					'doWork' => "SomeResults"
				], [],
				"SomeResults"
			];
		}

		yield 'If DONE and not chacheable call getCachedWork and return if OK' => [
			false, false,
			[
				'acquireForMe' => Status::newGood( PoolCounter::DONE ),
			], [
				'getCachedWork' => "SomeResults"
			], [],
			"SomeResults"
		];

		yield 'If DONE and chacheable call getCachedWork and return if OK' => [
			true, false,
			[
				'acquireForAnyone' => Status::newGood( PoolCounter::DONE ),
			], [
				'getCachedWork' => "SomeResults"
			], [],
			"SomeResults"
		];

		yield 'If DONE and chacheable call getCachedWork and repeat if not OK' => [
			true, false,
			[
				'acquireForAnyone' => Status::newGood( PoolCounter::DONE ),
				'acquireForMe' => Status::newGood( PoolCounter::LOCKED ),
				'release' => ''
			], [
				'getCachedWork' => false,
				'doWork' => 'SomeResults'
			], [],
			"SomeResults"
		];

		foreach ( [
			'QUEUE_FULL' => PoolCounter::QUEUE_FULL,
			'TIMEOUT' => PoolCounter::TIMEOUT
		] as $name => $acquireResult ) {
			yield 'If ' . $name . ' and not chacheable call fallback and return if OK' => [
				false, false,
				[
					'acquireForMe' => Status::newGood( $acquireResult ),
				], [
					'fallback' => "SomeResults"
				], [],
				"SomeResults"
			];

			yield 'If ' . $name . ' and not chacheable call fallback and log if not OK' => [
				false, false,
				[
					'acquireForMe' => Status::newGood( $acquireResult ),
				], [
				], [
					'info' => null
				],
				false
			];
		}
	}

	/**
	 * @dataProvider provideExecuteFlow
	 */
	public function testExecuteFlow(
		bool $cacheable,
		bool $skipCache,
		array $poolCounterMethods,
		array $workerMethods,
		array $loggerMethods,
		$expectedResult
	) {
		$worker = $this->configureFixture(
			$workerMethods,
			$poolCounterMethods,
			$loggerMethods
		);

		$worker->cacheable = $cacheable;
		$this->assertSame( $expectedResult, $worker->execute( $skipCache ) );
	}

	public function testDoWorkRaiseException() {
		$expectedException = new RuntimeException( __METHOD__ );
		$worker = $this->configureFixture(
			[ 'doWork' => $this->throwException( $expectedException ) ],
			[
				'acquireForMe' => Status::newGood( PoolCounter::LOCK_HELD ),
				'release' => ''
			],
			[]
		);

		$this->expectExceptionObject( $expectedException );
		$worker->execute();
	}

	public function testDefaults() {
		$worker = $this->configureFixture( [], [], [] );
		$this->assertFalse( $worker->cacheable );
		$this->assertFalse( $worker->getCachedWork() );
		$this->assertFalse( $worker->error( Status::newFatal( 'apierror' ) ) );
		$this->assertFalse( $worker->fallback( false ) );
	}
}
