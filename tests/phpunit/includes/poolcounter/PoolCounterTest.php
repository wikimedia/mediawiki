<?php

// We will use this class with getMockForAbstractClass to create a concrete mock class.
// That call will die if the contructor is not public, unless we use disableOriginalConstructor(),
// in which case we could not test the constructor.
abstract class PoolCounterAbstractMock extends PoolCounter {
	public function __construct() {
		call_user_func_array( 'parent::__construct', func_get_args() );
	}
}

class PoolCounterTest extends MediaWikiTestCase {
	public function testConstruct() {
		$poolCounterConfig = array(
			'class' => 'PoolCounterMock',
			'timeout' => 10,
			'workers' => 10,
			'maxqueue' => 100,
		);

		$poolCounter = $this->getMockBuilder( 'PoolCounterAbstractMock' )
			->setConstructorArgs( array( $poolCounterConfig, 'testCounter', 'someKey' ) )
			// don't mock anything - the proper syntax would be setMethods(null), but due
			// to a PHPUnit bug that does not work with getMockForAbstractClass()
			->setMethods( array( 'idontexist' ) )
			->getMockForAbstractClass();
		$this->assertInstanceOf( 'PoolCounter', $poolCounter );
	}

	public function testConstructWithSlots() {
		$poolCounterConfig = array(
			'class' => 'PoolCounterMock',
			'timeout' => 10,
			'workers' => 10,
			'slots' => 2,
			'maxqueue' => 100,
		);

		$poolCounter = $this->getMockBuilder( 'PoolCounterAbstractMock' )
			->setConstructorArgs( array( $poolCounterConfig, 'testCounter', 'key' ) )
			->setMethods( array( 'idontexist' ) ) // don't mock anything
			->getMockForAbstractClass();
		$this->assertInstanceOf( 'PoolCounter', $poolCounter );
	}

	public function testHashKeyIntoSlots() {
		$poolCounter = $this->getMockBuilder( 'PoolCounterAbstractMock' )
			// don't mock anything - the proper syntax would be setMethods(null), but due
			// to a PHPUnit bug that does not work with getMockForAbstractClass()
			->setMethods( array( 'idontexist' ) )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$hashKeyIntoSlots = new ReflectionMethod( $poolCounter, 'hashKeyIntoSlots' );
		$hashKeyIntoSlots->setAccessible( true );

		$keysWithTwoSlots = $keysWithFiveSlots = array();
		foreach ( range( 1, 100 ) as $i ) {
			$keysWithTwoSlots[] = $hashKeyIntoSlots->invoke( $poolCounter, 'key ' . $i, 2 );
			$keysWithFiveSlots[] = $hashKeyIntoSlots->invoke( $poolCounter, 'key ' . $i, 5 );
		}

		$this->assertArrayEquals( range( 0, 1 ), array_unique( $keysWithTwoSlots ) );
		$this->assertArrayEquals( range( 0, 4 ), array_unique( $keysWithFiveSlots ) );

		// make sure it is deterministic
		$this->assertEquals(
			$hashKeyIntoSlots->invoke( $poolCounter, 'asdfgh', 1000 ),
			$hashKeyIntoSlots->invoke( $poolCounter, 'asdfgh', 1000 )
		);
	}
}
