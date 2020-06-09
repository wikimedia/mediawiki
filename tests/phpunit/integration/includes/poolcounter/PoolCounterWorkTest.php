<?php
/**
 * Provides of semaphore semantics for restricting the number
 * of workers that may be concurrently performing the same task.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Concurrency
 * @coversDefaultClass PoolCounterWork
 */
class PoolCounterWorkTest extends MediaWikiIntegrationTestCase {

	private function configureMock( $obj, $configMethods ) {
		$obj->expects( $this->never() )
			->method( $this->anythingBut( ...array_keys( $configMethods ) ) );
		foreach ( $configMethods as $method => $value ) {
			$obj->expects( $this->once() )
				->method( $method )
				->will( $value );
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

		$this->setLogger( 'poolcounter', $logger );

		$poolCounter = $this->getMockBuilder( PoolCounter::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->configureMock( $poolCounter, $poolCounterMethods );

		$worker = $this->getMockBuilder( PoolCounterWork::class )
			->setConstructorArgs( [ 'PoolType', 'PoolKey', $poolCounter ] )
			->setMethods( array_keys( $workerMethods ) )
			->getMockForAbstractClass();

		$this->configureMock( $worker, $workerMethods );

		return TestingAccessWrapper::newFromObject( $worker );
	}

	public function provideExecuteFlow() {
		yield "Not Cacheable and not skipCache should call acquireForMe" => [
			false, false,
			[
				'acquireForMe' => $this->returnValue( Status::newGood( 'StupidAction' ) )
			], [], [
				'info' => $this->returnValue( null )
			],
			false
		];

		yield "Not Cacheable and skipCache should call acquireForMe" => [
			false, true,
			[
				'acquireForMe' => $this->returnValue( Status::newGood( 'StupidAction' ) )
			], [], [
				'info' => $this->returnValue( null )
			],
			false
		];

		yield "Cacheable and not skipCache should call acquireForAnyone" => [
			true, false,
			[
				'acquireForAnyone' => $this->returnValue( Status::newGood( 'StupidAction' ) )
			], [], [
				'info' => $this->returnValue( null )
			],
			false
		];

		yield "Cacheable and skipCache should call acquireForMe" => [
			true, true,
			[
				'acquireForMe' => $this->returnValue( Status::newGood( 'StupidAction' ) )
			], [], [
				'info' => $this->returnValue( null )
			],
			false
		];

		yield 'If Fatal error call doWork and log error' => [
			false, false,
			[
				'acquireForMe' => $this->returnValue( Status::newFatal( 'apierror' ) )
			], [
				'doWork' => $this->returnValue( "SomeResults" )
			], [
				'info' => $this->returnValue( null )
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
					'acquireForMe' => $this->returnValue( Status::newGood( $acquireResult ) ),
					'release' => $this->returnValue( null )
				], [
					'doWork' => $this->returnValue( "SomeResults" )
				], [],
				"SomeResults"
			];
		}

		yield 'If DONE and not chacheable call getCachedWork and return if OK' => [
			false, false,
			[
				'acquireForMe' => $this->returnValue( Status::newGood( PoolCounter::DONE ) ),
			], [
				'getCachedWork' => $this->returnValue( "SomeResults" )
			], [],
			"SomeResults"
		];

		yield 'If DONE and chacheable call getCachedWork and return if OK' => [
			true, false,
			[
				'acquireForAnyone' => $this->returnValue( Status::newGood( PoolCounter::DONE ) ),
			], [
				'getCachedWork' => $this->returnValue( "SomeResults" )
			], [],
			"SomeResults"
		];

		yield 'If DONE and chacheable call getCachedWork and repeat if not OK' => [
			true, false,
			[
				'acquireForAnyone' => $this->returnValue( Status::newGood( PoolCounter::DONE ) ),
				'acquireForMe' => $this->returnValue( Status::newGood( PoolCounter::LOCKED ) ),
				'release' => $this->returnValue( '' )
			], [
				'getCachedWork' => $this->returnValue( false ),
				'doWork' => $this->returnValue( 'SomeResults' )
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
					'acquireForMe' => $this->returnValue( Status::newGood( $acquireResult ) ),
				], [
					'fallback' => $this->returnValue( "SomeResults" )
				], [],
				"SomeResults"
			];

			yield 'If ' . $name . ' and not chacheable call fallback and log if not OK' => [
				false, false,
				[
					'acquireForMe' => $this->returnValue( Status::newGood( $acquireResult ) ),
				], [
				], [
					'info' => $this->returnValue( null )
				],
				false
			];
		}
	}

	/**
	 * @dataProvider provideExecuteFlow
	 * @covers ::execute
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

	/**
	 * @covers ::execute
	 */
	public function testDoWorkRaiseException() {
		$workerResults = 'SomeStringForResult';

		$worker = $this->configureFixture(
			[ 'doWork' => $this->throwException( new MWException() ) ],
			[
				'acquireForMe' => $this->returnValue( Status::newGood( PoolCounter::LOCK_HELD ) ),
				'release' => $this->returnValue( '' )
			],
			[]
		);

		$this->expectException( MWException::class );
		$result = $worker->execute();
	}

	/**
	 * @covers ::getCachedWork
	 * @covers ::error
	 * @covers ::fallback
	 * @covers ::__construct
	 */
	public function testDefaults() {
		$worker = $this->configureFixture( [], [], [] );
		$this->assertFalse( $worker->cacheable );
		$this->assertFalse( $worker->getCachedWork() );
		$this->assertFalse( $worker->error( Status::newFatal( 'apierror' ) ) );
		$this->assertFalse( $worker->fallback( false ) );
	}
}
