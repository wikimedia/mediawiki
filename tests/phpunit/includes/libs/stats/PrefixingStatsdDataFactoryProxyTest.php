<?php

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;

/**
 * @covers PrefixingStatsdDataFactoryProxy
 */
class PrefixingStatsdDataFactoryProxyTest extends PHPUnit\Framework\TestCase {

	use PHPUnit4And6Compat;

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
		/** @var StatsdDataFactoryInterface|PHPUnit_Framework_MockObject_MockObject $innerFactory */
		$innerFactory = $this->getMock(
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
		/** @var StatsdDataFactoryInterface|PHPUnit_Framework_MockObject_MockObject $innerFactory */
		$innerFactory = $this->getMock(
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
