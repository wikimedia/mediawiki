<?php

/**
 * @author Addshore
 * @covers UserArrayFromResult
 */
class UserArrayFromResultTest extends \MediaWikiUnitTestCase {

	private function getMockResultWrapper( $row = null, $numRows = 1 ) {
		$resultWrapper = $this->getMockBuilder( Wikimedia\Rdbms\IResultWrapper::class )
			->disableOriginalConstructor();

		$resultWrapper = $resultWrapper->getMock();
		$resultWrapper->expects( $this->atLeastOnce() )
			->method( 'current' )
			->will( $this->returnValue( $row ) );
		$resultWrapper->expects( $this->any() )
			->method( 'numRows' )
			->will( $this->returnValue( $numRows ) );

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

	// @todo unit test for key()
	// @todo unit test for next()
	// @todo unit test for rewind()
}
