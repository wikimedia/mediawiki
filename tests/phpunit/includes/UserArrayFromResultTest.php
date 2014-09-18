<?php

/**
 * @author Adam Shorland
 * @covers UserArrayFromResult
 */
class UserArrayFromResultTest extends MediaWikiTestCase {

	private function getMockResultWrapper( $row = null, $numRows = 1 ) {
		$resultWrapper = $this->getMockBuilder( 'ResultWrapper' )
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
		$row = new stdClass();
		$row->user_name = $username;
		return $row;
	}

	private function getUserArrayFromResult( $resultWrapper ) {
		return new UserArrayFromResult( $resultWrapper );
	}

	/**
	 * @covers UserArrayFromResult::__construct
	 */
	public function testConstructionWithFalseRow() {
		$row = false;
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = $this->getUserArrayFromResult( $resultWrapper );

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

		$object = $this->getUserArrayFromResult( $resultWrapper );

		$this->assertEquals( $resultWrapper, $object->res );
		$this->assertSame( 0, $object->key );
		$this->assertInstanceOf( 'User', $object->current );
		$this->assertEquals( $username, $object->current->mName );
	}

	public static function provideNumberOfRows() {
		return array(
			array( 0 ),
			array( 1 ),
			array( 122 ),
		);
	}

	/**
	 * @dataProvider provideNumberOfRows
	 * @covers UserArrayFromResult::count
	 */
	public function testCountWithVaryingValues( $numRows ) {
		$object = $this->getUserArrayFromResult( $this->getMockResultWrapper(
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
		$object = $this->getUserArrayFromResult( $this->getMockResultWrapper( $userRow ) );
		$this->assertInstanceOf( 'User', $object->current() );
		$this->assertEquals( $username, $object->current()->mName );
	}

	public function provideTestValid() {
		return array(
			array( $this->getRowWithUsername(), true ),
			array( false, false ),
		);
	}

	/**
	 * @dataProvider provideTestValid
	 * @covers UserArrayFromResult::valid
	 */
	public function testValid( $input, $expected ) {
		$object = $this->getUserArrayFromResult( $this->getMockResultWrapper( $input ) );
		$this->assertEquals( $expected, $object->valid() );
	}

	//@todo unit test for key()
	//@todo unit test for next()
	//@todo unit test for rewind()
}
