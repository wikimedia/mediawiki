<?php

/**
 * @covers PoolCounter
 */
class PoolCounterTest extends MediaWikiTestCase {
	public function testConstruct() {
		$poolCounterConfig = [
			'class' => 'PoolCounterMock',
			'timeout' => 10,
			'workers' => 10,
			'maxqueue' => 100,
		];

		$poolCounter = $this->getMockBuilder( PoolCounterAbstractMock::class )
			->setConstructorArgs( [ $poolCounterConfig, 'testCounter', 'someKey' ] )
			// don't mock anything - the proper syntax would be setMethods(null), but due
			// to a PHPUnit bug that does not work with getMockForAbstractClass()
			->setMethods( [ 'idontexist' ] )
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
			->setMethods( [ 'idontexist' ] ) // don't mock anything
			->getMockForAbstractClass();
		$this->assertInstanceOf( PoolCounter::class, $poolCounter );
	}

	public function testHashKeyIntoSlots() {
		$poolCounter = $this->getMockBuilder( PoolCounterAbstractMock::class )
			// don't mock anything - the proper syntax would be setMethods(null), but due
			// to a PHPUnit bug that does not work with getMockForAbstractClass()
			->setMethods( [ 'idontexist' ] )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

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
	public function __construct() {
		call_user_func_array( 'parent::__construct', func_get_args() );
	}
}
