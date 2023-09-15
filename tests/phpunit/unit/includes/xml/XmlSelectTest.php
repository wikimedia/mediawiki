<?php

/**
 * @group Xml
 */
class XmlSelectTest extends MediaWikiUnitTestCase {

	/**
	 * @covers XmlSelect::__construct
	 */
	public function testConstructWithoutParameters() {
		$select = new XmlSelect();
		$this->assertEquals( '<select></select>', $select->getHTML() );
	}

	/**
	 * Parameters are $name (false), $id (false), $default (false)
	 * @dataProvider provideConstructionParameters
	 * @covers XmlSelect::__construct
	 */
	public function testConstructParameters( $name, $id, $default, $expected ) {
		$select = new XmlSelect( $name, $id, $default );
		$this->assertEquals( $expected, $select->getHTML() );
	}

	/**
	 * Provide parameters for testConstructParameters() which use three
	 * parameters:
	 *  - $name    (default: false)
	 *  - $id      (default: false)
	 *  - $default (default: false)
	 * Provides a fourth parameters representing the expected HTML output
	 */
	public static function provideConstructionParameters() {
		return [
			/**
			 * Values are set following a 3-bit Gray code where two successive
			 * values differ by only one value.
			 * See https://en.wikipedia.org/wiki/Gray_code
			 */
			#      $name   $id    $default
			[ false, false, false, '<select></select>' ],
			[ false, false, 'foo', '<select></select>' ],
			[ false, 'id', 'foo', '<select id="id"></select>' ],
			[ false, 'id', false, '<select id="id"></select>' ],
			[ 'name', 'id', false, '<select name="name" id="id"></select>' ],
			[ 'name', 'id', 'foo', '<select name="name" id="id"></select>' ],
			[ 'name', false, 'foo', '<select name="name"></select>' ],
			[ 'name', false, false, '<select name="name"></select>' ],
		];
	}

	/**
	 * @covers XmlSelect::addOption
	 */
	public function testAddOptionNoValue() {
		$select = new XmlSelect();
		$select->addOption( 'foo' );
		$this->assertEquals(
			'<select><option value="foo">foo</option></select>',
			$select->getHTML()
		);
	}

	/**
	 * @dataProvider provideAddOption
	 * @covers XmlSelect::addOption
	 */
	public function testAddOption( $value, $expected ) {
		$select = new XmlSelect();
		$select->addOption( 'foo', $value );
		$this->assertEquals( $expected, $select->getHTML() );
	}

	public static function provideAddOption() {
		yield 'true' => [ true, '<select><option value="1">foo</option></select>' ];
		yield 'false' => [ false, '<select><option value="foo">foo</option></select>' ];
		yield 'zero' => [ 0, '<select><option value="0">foo</option></select>' ];
	}

	/**
	 * @covers XmlSelect::setDefault
	 */
	public function testSetDefault() {
		$select = new XmlSelect();
		$select->setDefault( 'bar1' );
		$select->addOption( 'foo1' );
		$select->addOption( 'bar1' );
		$select->addOption( 'foo2' );
		$this->assertEquals(
			'<select><option value="foo1">foo1</option>' . "\n" .
				'<option value="bar1" selected="">bar1</option>' . "\n" .
				'<option value="foo2">foo2</option></select>', $select->getHTML() );
	}

	/**
	 * Adding default later on should set the correct selection or
	 * raise an exception.
	 * To handle this, we need to render the options in getHtml()
	 * @covers XmlSelect::setDefault
	 */
	public function testSetDefaultAfterAddingOptions() {
		$select = new XmlSelect();
		$select->addOption( 'foo1' );
		$select->addOption( 'bar1' );
		$select->addOption( 'foo2' );
		$select->setDefault( 'bar1' ); # setting default after adding options
		$this->assertEquals(
			'<select><option value="foo1">foo1</option>' . "\n" .
				'<option value="bar1" selected="">bar1</option>' . "\n" .
				'<option value="foo2">foo2</option></select>', $select->getHTML() );
	}

	/**
	 * @covers XmlSelect::setAttribute
	 * @covers XmlSelect::getAttribute
	 */
	public function testGetAttributes() {
		# create some attributes
		$select = new XmlSelect();
		$select->setAttribute( 'dummy', 0x777 );
		$select->setAttribute( 'string', 'euro €' );
		$select->setAttribute( 1911, 'razor' );

		# verify we can retrieve them
		$this->assertSame(
			0x777,
			$select->getAttribute( 'dummy' )
		);
		$this->assertEquals(
			'euro €',
			$select->getAttribute( 'string' )
		);
		$this->assertEquals(
			'razor',
			$select->getAttribute( 1911 )
		);

		# inexistent keys should give us 'null'
		$this->assertNull(
			$select->getAttribute( 'I DO NOT EXIT' )
		);

		# verify string / integer
		$this->assertEquals(
			'razor',
			$select->getAttribute( '1911' )
		);
		$this->assertSame(
			0x777,
			$select->getAttribute( 'dummy' )
		);
	}
}
