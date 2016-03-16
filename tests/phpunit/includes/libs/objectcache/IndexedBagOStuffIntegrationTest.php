<?php

/**
 * @covers IndexedBagOStuff
 *
 * @author Addshore
 */
class IndexedBagOStuffIntegrationTest extends PHPUnit_Framework_TestCase {

	private function getBag() {
		return new IndexedBagOStuff( new HashBagOStuff() );
	}

	public function testFoo() {
		$bag = $this->getBag();
		$ballOne = 'ToStore1';
		$ballTwo = 'ToStore2';

		$ballOneKey = $bag->makeKey( 'ball', 1 );
		$ballTwoKey = $bag->makeKey( 'ball', 2 );

		$bag->add( $ballOneKey, $ballOne );
		$bag->add( $ballTwoKey, $ballTwo );

		$this->assertEquals( [ $ballOneKey ], $bag->getKeysForArgs( [ 'ball', 1 ] ) );



	}

}