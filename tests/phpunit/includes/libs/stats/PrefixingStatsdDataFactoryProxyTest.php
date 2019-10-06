<?php

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers PrefixingStatsdDataFactoryProxy
 */
class PrefixingStatsdDataFactoryProxyTest extends PHPUnit\Framework\TestCase {

	public function provideMethodNames() {
		return [
			[ 'timing' ],
			[ 'gauge' ],
			[ 'set' ],
			[ 'increment' ],
			[ 'decrement' ],
			[ 'updateCount' ],
			[ 'produceStatsdData' ],
		];
	}

	/**
	 * @dataProvider provideMethodNames
	 */
	public function testPrefixingAndPassthrough( $method ) {
		/** @var StatsdDataFactoryInterface|MockObject $innerFactory */
		$innerFactory = $this->createMock(
			\Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface::class
		);
		$innerFactory->expects( $this->once() )
			->method( $method )
			->with( 'testprefix.metricname' );

		$proxy = new PrefixingStatsdDataFactoryProxy( $innerFactory, 'testprefix' );
		// 1,2,3,4 simply makes sure we provide enough parameters, without caring what they are
		$proxy->$method( 'metricname', 1, 2, 3, 4 );
	}

	/**
	 * @dataProvider provideMethodNames
	 */
	public function testPrefixIsTrimmed( $method ) {
		/** @var StatsdDataFactoryInterface|MockObject $innerFactory */
		$innerFactory = $this->createMock(
			\Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface::class
		);
		$innerFactory->expects( $this->once() )
			->method( $method )
			->with( 'testprefix.metricname' );

		$proxy = new PrefixingStatsdDataFactoryProxy( $innerFactory, 'testprefix...' );
		// 1,2,3,4 simply makes sure we provide enough parameters, without caring what they are
		$proxy->$method( 'metricname', 1, 2, 3, 4 );
	}

}
