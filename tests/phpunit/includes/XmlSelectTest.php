<?php

/**
 * @group Xml
 */
class XmlSelectTest extends MediaWikiTestCase {

	/**
	 * @var XmlSelect
	 */
	protected $select;

	protected function setUp() {
		parent::setUp();
		$this->select = new XmlSelect();
	}

	protected function tearDown() {
		parent::tearDown();
		$this->select = null;
	}

	/**
	 * @covers XmlSelect::__construct
	 */
	public function testConstructWithoutParameters() {
		$this->assertEquals( '<select></select>', $this->select->getHTML() );
	}

	/**
	 * Parameters are $name (false), $id (false), $default (false)
	 * @dataProvider provideConstructionParameters
	 * @covers XmlSelect::__construct
	 */
	public function testConstructParameters( $name, $id, $default, $expected ) {
		$this->select = new XmlSelect( $name, $id, $default );
		$this->assertEquals( $expected, $this->select->getHTML() );
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
	public function testAddOption() {
		$this->select->addOption( 'foo' );
		$this->assertEquals(
			'<select><option value="foo">foo</option></select>',
			$this->select->getHTML()
		);
	}

	/**
	 * @covers XmlSelect::addOption
	 */
	public function testAddOptionWithDefault() {
		$this->select->addOption( 'foo', true );
		$this->assertEquals(
			'<select><option value="1">foo</option></select>',
			$this->select->getHTML()
		);
	}

	/**
	 * @covers XmlSelect::addOption
	 */
	public function testAddOptionWithFalse() {
		$this->select->addOption( 'foo', false );
		$this->assertEquals(
			'<select><option value="foo">foo</option></select>',
			$this->select->getHTML()
		);
	}

	/**
	 * @covers XmlSelect::addOption
	 */
	public function testAddOptionWithValueZero() {
		$this->select->addOption( 'foo', 0 );
		$this->assertEquals(
			'<select><option value="0">foo</option></select>',
			$this->select->getHTML()
		);
	}

	/**
	 * @covers XmlSelect::setDefault
	 */
	public function testSetDefault() {
		$this->select->setDefault( 'bar1' );
		$this->select->addOption( 'foo1' );
		$this->select->addOption( 'bar1' );
		$this->select->addOption( 'foo2' );
		$this->assertEquals(
			'<select><option value="foo1">foo1</option>' . "\n" .
				'<option value="bar1" selected="">bar1</option>' . "\n" .
				'<option value="foo2">foo2</option></select>', $this->select->getHTML() );
	}

	/**
	 * Adding default later on should set the correct selection or
	 * raise an exception.
	 * To handle this, we need to render the options in getHtml()
	 * @covers XmlSelect::setDefault
	 */
	public function testSetDefaultAfterAddingOptions() {
		$this->select->addOption( 'foo1' );
		$this->select->addOption( 'bar1' );
		$this->select->addOption( 'foo2' );
		$this->select->setDefault( 'bar1' ); # setting default after adding options
		$this->assertEquals(
			'<select><option value="foo1">foo1</option>' . "\n" .
				'<option value="bar1" selected="">bar1</option>' . "\n" .
				'<option value="foo2">foo2</option></select>', $this->select->getHTML() );
	}

	/**
	 * @covers XmlSelect::setAttribute
	 * @covers XmlSelect::getAttribute
	 */
	public function testGetAttributes() {
		# create some attributes
		$this->select->setAttribute( 'dummy', 0x777 );
		$this->select->setAttribute( 'string', 'euro €' );
		$this->select->setAttribute( 1911, 'razor' );

		# verify we can retrieve them
		$this->assertEquals(
			$this->select->getAttribute( 'dummy' ),
			0x777
		);
		$this->assertEquals(
			$this->select->getAttribute( 'string' ),
			'euro €'
		);
		$this->assertEquals(
			$this->select->getAttribute( 1911 ),
			'razor'
		);

		# inexistent keys should give us 'null'
		$this->assertEquals(
			$this->select->getAttribute( 'I DO NOT EXIT' ),
			null
		);

		# verify string / integer
		$this->assertEquals(
			$this->select->getAttribute( '1911' ),
			'razor'
		);
		$this->assertEquals(
			$this->select->getAttribute( 'dummy' ),
			0x777
		);
	}
}
