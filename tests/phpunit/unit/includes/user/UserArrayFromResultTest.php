<?php

use MediaWiki\User\User;
use MediaWiki\User\UserArrayFromResult;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @author Addshore
 * @covers \MediaWiki\User\UserArrayFromResult
 */
class UserArrayFromResultTest extends \MediaWikiUnitTestCase {

	private function getMockResultWrapper( $row, int $numRows = 1 ): IResultWrapper {
		$resultWrapper = $this->createMock( IResultWrapper::class );
		$resultWrapper->expects( $this->atLeastOnce() )
			->method( 'current' )
			->willReturn( $row );
		$resultWrapper->method( 'numRows' )
			->willReturn( $numRows );
		$resultWrapper->method( 'fetchObject' )
			->willReturn( $row );

		return $resultWrapper;
	}

	private static function getRowWithUsername( string $username = 'fooUser' ): stdClass {
		return (object)[ 'user_name' => $username ];
	}

	public function testConstructionWithFalseRow() {
		$row = false;
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = new UserArrayFromResult( $resultWrapper );

		$this->assertFalse( $object->valid() );
		$this->assertSame( 0, $object->key() );
	}

	public function testConstructionWithRow() {
		$username = 'addshore';
		$row = self::getRowWithUsername( $username );
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = new UserArrayFromResult( $resultWrapper );

		$this->assertTrue( $object->valid() );
		$this->assertSame( 0, $object->key() );
		$this->assertInstanceOf( User::class, $object->current() );
		$this->assertEquals( $username, $object->current()->mName );
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
	 */
	public function testCountWithVaryingValues( int $numRows ) {
		$object = new UserArrayFromResult( $this->getMockResultWrapper(
			self::getRowWithUsername(),
			$numRows
		) );
		$this->assertEquals( $numRows, $object->count() );
	}

	public function testCurrentAfterConstruction() {
		$username = 'addshore';
		$userRow = self::getRowWithUsername( $username );
		$object = new UserArrayFromResult( $this->getMockResultWrapper( $userRow ) );
		$this->assertInstanceOf( User::class, $object->current() );
		$this->assertEquals( $username, $object->current()->mName );
	}

	public static function provideTestValid() {
		return [
			[ self::getRowWithUsername(), true ],
			[ false, false ],
		];
	}

	/**
	 * @dataProvider provideTestValid
	 */
	public function testValid( $input, bool $expected ) {
		$object = new UserArrayFromResult( $this->getMockResultWrapper( $input ) );
		$this->assertSame( $expected, $object->valid() );
	}

	public static function provideTestKey() {
		return [
			[ self::getRowWithUsername(), 0 ],
			[ self::getRowWithUsername( 'xSavitar' ), 0 ],
			[ (object)[], 0 ],
			[ false, 0 ],
		];
	}

	/**
	 * @dataProvider provideTestKey
	 */
	public function testKey( $input, int $expected ) {
		$object = new UserArrayFromResult( $this->getMockResultWrapper( $input ) );
		$this->assertSame( $expected, $object->key() );
	}

	public function testNextOnce() {
		$object = new UserArrayFromResult(
			$this->getMockResultWrapper( self::getRowWithUsername() )
		);
		$object->next();
		$this->assertSame( 1, $object->key() );
	}

	public function testNextTwice() {
		$object = new UserArrayFromResult(
			$this->getMockResultWrapper( self::getRowWithUsername() )
		);
		$object->next(); // once
		$object->next(); // twice
		$this->assertSame( 2, $object->key() );
	}

	public function testRewind() {
		$object = new UserArrayFromResult(
			$this->getMockResultWrapper( self::getRowWithUsername() )
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
