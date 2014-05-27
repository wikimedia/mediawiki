<?php

// We will use this class with getMockForAbstractClass to create a concrete mock class. That call will die if the
// contructor is not public, unless we use disableOriginalConstructor(), in which case we could not test the constructor.
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
			// don't mock anything - the proper syntax would be setMethods(null), but due to a PHPUnit bug that
			// does not work with getMockForAbstractClass()
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

		$keys = array();
		foreach ( range( 0, 10 ) as $i ) {
			$poolCounter = $this->getMockBuilder( 'PoolCounterAbstractMock' )
				->setConstructorArgs( array( $poolCounterConfig, 'testCounter', 'key' . $i ) )
				->setMethods( array( 'idontexist' ) ) // don't mock anything
				->getMockForAbstractClass();
			$this->assertInstanceOf( 'PoolCounter', $poolCounter );
			$keys[] = $poolCounter->getKey();
		}

		// when the slot option is used, the keys should be hashed into [0..slots-1]
		$this->assertArrayEquals( array( 0, 1 ), array_unique( $keys ) );
	}
}
