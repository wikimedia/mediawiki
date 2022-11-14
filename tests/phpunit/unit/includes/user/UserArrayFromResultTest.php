<?php

/**
 * @author Addshore
 * @covers UserArrayFromResult
 */
class UserArrayFromResultTest extends \MediaWikiUnitTestCase {

	private function getMockResultWrapper( $row = null, $numRows = 1 ) {
		$resultWrapper = $this->createMock( Wikimedia\Rdbms\IResultWrapper::class );
		$resultWrapper->expects( $this->atLeastOnce() )
			->method( 'current' )
			->willReturn( $row );
		$resultWrapper->method( 'numRows' )
			->willReturn( $numRows );
		$resultWrapper->method( 'fetchObject' )
			->willReturn( $row );

		return $resultWrapper;
	}

	private function getRowWithUsername( $username = 'fooUser' ) {
		return (object)[ 'user_name' => $username ];
	}

	/**
	 * @covers UserArrayFromResult::__construct
	 */
	public function testConstructionWithFalseRow() {
		$row = false;
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = new UserArrayFromResult( $resultWrapper );

		$this->assertEquals( $resultWrapper, $object->res );
		$this->assertSame( 0, $object->key );
		$this->assertEquals( $row, $object->current );
	}

	/**
	 * @covers UserArrayFromResult::__construct
	 */
	public function testConstructionWithRow() {
		$username = 'addshore';
		$row = $this->getRowWithUsername( $username );
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = new UserArrayFromResult( $resultWrapper );

		$this->assertEquals( $resultWrapper, $object->res );
		$this->assertSame( 0, $object->key );
		$this->assertInstanceOf( User::class, $object->current );
		$this->assertEquals( $username, $object->current->mName );
	}

	public static function provideNumberOfRows() {
		return [
			[ 0 ],
			[ 1 ],
			[ 122 ],
		];
	}

	/**
	 * @dataProvider provideNumberOfRows
	 * @covers UserArrayFromResult::count
	 */
	public function testCountWithVaryingValues( $numRows ) {
		$object = new UserArrayFromResult( $this->getMockResultWrapper(
			$this->getRowWithUsername(),
			$numRows
		) );
		$this->assertEquals( $numRows, $object->count() );
	}

	/**
	 * @covers UserArrayFromResult::current
	 */
	public function testCurrentAfterConstruction() {
		$username = 'addshore';
		$userRow = $this->getRowWithUsername( $username );
		$object = new UserArrayFromResult( $this->getMockResultWrapper( $userRow ) );
		$this->assertInstanceOf( User::class, $object->current() );
		$this->assertEquals( $username, $object->current()->mName );
	}

	public function provideTestValid() {
		return [
			[ $this->getRowWithUsername(), true ],
			[ false, false ],
		];
	}

	/**
	 * @dataProvider provideTestValid
	 * @covers UserArrayFromResult::valid
	 */
	public function testValid( $input, $expected ) {
		$object = new UserArrayFromResult( $this->getMockResultWrapper( $input ) );
		$this->assertEquals( $expected, $object->valid() );
	}

	public function provideTestKey() {
		return [
			[ $this->getRowWithUsername(), 0 ],
			[ $this->getRowWithUsername( 'xSavitar' ), 0 ],
			[ (object)[], 0 ],
			[ false, false ],
		];
	}

	/**
	 * @dataProvider provideTestKey
	 * @covers UserArrayFromResult::key
	 */
	public function testKey( $input, $expected ) {
		$object = new UserArrayFromResult( $this->getMockResultWrapper( $input ) );
		$this->assertEquals( $expected, $object->key() );
	}

	/**
	 * @covers UserArrayFromResult::next
	 */
	public function testNextOnce() {
		$object = new UserArrayFromResult(
			$this->getMockResultWrapper( $this->getRowWithUsername() )
		);
		$object->next();
		$this->assertSame( 1, $object->key() );
	}

	/**
	 * @covers UserArrayFromResult::next
	 * @covers UserArrayFromResult::key
	 */
	public function testNextTwice() {
		$object = new UserArrayFromResult(
			$this->getMockResultWrapper( $this->getRowWithUsername() )
		);
		$object->next(); // once
		$object->next(); // twice
		$this->assertSame( 2, $object->key() );
	}

	/**
	 * @covers UserArrayFromResult::rewind
	 * @covers UserArrayFromResult::next
	 * @covers UserArrayFromResult::key
	 */
	public function testRewind() {
		$object = new UserArrayFromResult(
			$this->getMockResultWrapper( $this->getRowWithUsername() )
		);

		$object->next();
		$this->assertSame( 1, $object->key() );

		$object->next();
		$this->assertSame( 2, $object->key() );

		$object->rewind();
		$this->assertSame( 0, $object->key() );

		$object->rewind();
		$this->assertSame( 0, $object->key() );
	}
}
