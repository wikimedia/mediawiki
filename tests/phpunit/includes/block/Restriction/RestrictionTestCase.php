<?php

namespace MediaWiki\Tests\Block\Restriction;

/**
 * @group Blocking
 */
abstract class RestrictionTestCase extends \MediaWikiTestCase {
	public function testConstruct() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );

		$this->assertSame( $restriction->getBlockId(), 1 );
		$this->assertSame( $restriction->getValue(), 2 );
	}

	public function testSetBlockId() {
		$class = $this->getClass();
		$restriction = new $class( 1, 2 );

		$restriction->setBlockId( 10 );
		$this->assertSame( $restriction->getBlockId(), 10 );
	}

	public function testEquals() {
		$class = $this->getClass();

		// Test two restrictions with the same data.
		$restriction = new $class( 1, 2 );
		$second = new $class( 1, 2 );
		$this->assertTrue( $restriction->equals( $second ) );

		// Test two restrictions that implement different classes.
		$second = $this->createMock( $this->getClass() );
		$this->assertFalse( $restriction->equals( $second ) );

		// Not the same block id.
		$second = new $class( 2, 2 );
		$this->assertTrue( $restriction->equals( $second ) );

		// Not the same value.
		$second = new $class( 1, 3 );
		$this->assertFalse( $restriction->equals( $second ) );
	}

	public function testNewFromRow() {
		$class = $this->getClass();

		$restriction = $class::newFromRow( (object)[
			'ir_ipb_id' => 1,
			'ir_value' => 2,
		] );

		$this->assertSame( 1, $restriction->getBlockId() );
		$this->assertSame( 2, $restriction->getValue() );
	}

	public function testToRow() {
		$class = $this->getClass();

		$restriction = new $class( 1, 2 );
		$row = $restriction->toRow();

		$this->assertSame( 1, $row['ir_ipb_id'] );
		$this->assertSame( 2, $row['ir_value'] );
	}

	/**
	 * Get the class name of the class that is being tested.
	 *
	 * @return string
	 */
	abstract protected function getClass();
}
