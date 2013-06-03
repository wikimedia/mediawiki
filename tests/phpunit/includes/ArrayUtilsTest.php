<?php

class ArrayUtilsTest extends MediaWikiTestCase {

	/**
	 * Test ArrayUtils::setAdd and ArrayUtils::setRemove
	 */
	function testSetAddRemove() {
		$set = array( 'a', 'b', 'c' );

		ArrayUtils::setRemove( $set, 'b' );
		$this->assertEquals( array( 'a', 'c' ), $set, "item removal" );

		ArrayUtils::setRemove( $set, 'd' );
		$this->assertEquals( array( 'a', 'c' ), $set, "removal of a non-existent item" );

		ArrayUtils::setAdd( $set, 'e' );
		$this->assertEquals( array( 'a', 'c', 'e' ), $set, "adding an item" );

		ArrayUtils::setAdd( $set, 'e' );
		$this->assertEquals( array( 'a', 'c', 'e' ), $set, "adding an item that is already present" );
	}

}
