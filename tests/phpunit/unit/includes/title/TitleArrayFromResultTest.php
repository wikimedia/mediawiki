<?php

use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;

/**
 * @author Addshore
 * @covers \MediaWiki\Title\TitleArrayFromResult
 */
class TitleArrayFromResultTest extends MediaWikiUnitTestCase {

	private function getMockResultWrapper( $row = null, $numRows = 1 ) {
		$resultWrapper = $this->createMock( Wikimedia\Rdbms\IResultWrapper::class );
		$resultWrapper->expects( $this->atLeastOnce() )
			->method( 'current' )
			->willReturn( $row );
		$resultWrapper->method( 'numRows' )
			->willReturn( $numRows );

		return $resultWrapper;
	}

	private static function getRowWithTitle( $namespace = 3, $title = 'foo' ) {
		return (object)[
			'page_namespace' => $namespace,
			'page_title' => $title,
		];
	}

	/**
	 * @covers \MediaWiki\Title\TitleArrayFromResult::__construct
	 */
	public function testConstructionWithFalseRow() {
		$row = false;
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = new TitleArrayFromResult( $resultWrapper );

		$this->assertEquals( $resultWrapper, $object->res );
		$this->assertSame( 0, $object->key );
		$this->assertEquals( $row, $object->current );
	}

	/**
	 * @covers \MediaWiki\Title\TitleArrayFromResult::__construct
	 */
	public function testConstructionWithRow() {
		$namespace = 0;
		$title = 'foo';
		$row = self::getRowWithTitle( $namespace, $title );
		$resultWrapper = $this->getMockResultWrapper( $row );

		$object = new TitleArrayFromResult( $resultWrapper );

		$this->assertEquals( $resultWrapper, $object->res );
		$this->assertSame( 0, $object->key );
		$this->assertInstanceOf( Title::class, $object->current );
		$this->assertEquals( $namespace, $object->current->getNamespace() );
		$this->assertEquals( $title, $object->current->getText() );
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
	 * @covers \MediaWiki\Title\TitleArrayFromResult::count
	 */
	public function testCountWithVaryingValues( $numRows ) {
		$object = new TitleArrayFromResult( $this->getMockResultWrapper(
			self::getRowWithTitle(),
			$numRows
		) );
		$this->assertEquals( $numRows, $object->count() );
	}

	/**
	 * @covers \MediaWiki\Title\TitleArrayFromResult::current
	 */
	public function testCurrentAfterConstruction() {
		$namespace = 0;
		$title = 'foo';
		$row = self::getRowWithTitle( $namespace, $title );
		$object = new TitleArrayFromResult( $this->getMockResultWrapper( $row ) );
		$this->assertInstanceOf( Title::class, $object->current() );
		$this->assertEquals( $namespace, $object->current->getNamespace() );
		$this->assertEquals( $title, $object->current->getText() );
	}

	public static function provideTestValid() {
		return [
			[ self::getRowWithTitle(), true ],
			[ false, false ],
		];
	}

	/**
	 * @dataProvider provideTestValid
	 * @covers \MediaWiki\Title\TitleArrayFromResult::valid
	 */
	public function testValid( $input, $expected ) {
		$object = new TitleArrayFromResult( $this->getMockResultWrapper( $input ) );
		$this->assertEquals( $expected, $object->valid() );
	}

	// @todo unit test for key()
	// @todo unit test for next()
	// @todo unit test for rewind()
}
