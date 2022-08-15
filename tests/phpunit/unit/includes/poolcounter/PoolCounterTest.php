<?php

/**
 * @covers PoolCounter
 */
class PoolCounterTest extends MediaWikiUnitTestCase {
	public function testConstruct() {
		$poolCounterConfig = [
			'class' => 'PoolCounterMock',
			'timeout' => 10,
			'workers' => 10,
			'maxqueue' => 100,
		];

		$poolCounter = $this->getMockBuilder( PoolCounterAbstractMock::class )
			->setConstructorArgs( [ $poolCounterConfig, 'testCounter', 'someKey' ] )
			->onlyMethods( [] )
			->getMockForAbstractClass();
		$this->assertInstanceOf( PoolCounter::class, $poolCounter );
	}

	public function testConstructWithSlots() {
		$poolCounterConfig = [
			'class' => 'PoolCounterMock',
			'timeout' => 10,
			'workers' => 10,
			'slots' => 2,
			'maxqueue' => 100,
		];

		$poolCounter = $this->getMockBuilder( PoolCounterAbstractMock::class )
			->setConstructorArgs( [ $poolCounterConfig, 'testCounter', 'key' ] )
			->onlyMethods( [] ) // don't mock anything
			->getMockForAbstractClass();
		$this->assertInstanceOf( PoolCounter::class, $poolCounter );
	}

	public function testHashKeyIntoSlots() {
		$poolCounter = $this->createMock( PoolCounterAbstractMock::class );

		$hashKeyIntoSlots = new ReflectionMethod( $poolCounter, 'hashKeyIntoSlots' );
		$hashKeyIntoSlots->setAccessible( true );

		$keysWithTwoSlots = $keysWithFiveSlots = [];
		foreach ( range( 1, 100 ) as $i ) {
			$keysWithTwoSlots[] = $hashKeyIntoSlots->invoke( $poolCounter, 'test', 'key ' . $i, 2 );
			$keysWithFiveSlots[] = $hashKeyIntoSlots->invoke( $poolCounter, 'test', 'key ' . $i, 5 );
		}

		$twoSlotKeys = [];
		for ( $i = 0; $i <= 1; $i++ ) {
			$twoSlotKeys[] = "test:$i";
		}
		$fiveSlotKeys = [];
		for ( $i = 0; $i <= 4; $i++ ) {
			$fiveSlotKeys[] = "test:$i";
		}

		$this->assertArrayEquals( $twoSlotKeys, array_unique( $keysWithTwoSlots ) );
		$this->assertArrayEquals( $fiveSlotKeys, array_unique( $keysWithFiveSlots ) );

		// make sure it is deterministic
		$this->assertEquals(
			$hashKeyIntoSlots->invoke( $poolCounter, 'test', 'asdfgh', 1000 ),
			$hashKeyIntoSlots->invoke( $poolCounter, 'test', 'asdfgh', 1000 )
		);
	}
}

// We will use this class with getMockForAbstractClass to create a concrete mock class.
// That call will die if the contructor is not public, unless we use disableOriginalConstructor(),
// in which case we could not test the constructor.
abstract class PoolCounterAbstractMock extends PoolCounter {
	public function __construct( ...$args ) {
		parent::__construct( ...$args );
	}
}
