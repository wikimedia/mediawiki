<?php

// TODO
class XmlSelectTest extends MediaWikiTestCase {
	protected $select;

	protected function setUp() {
		$this->select = new XmlSelect();
	}
	protected function tearDown() {
		$this->select = null;
	}

	### START OF TESTS ###

	public function testConstructWithoutParameters() {
		$this->assertEquals( '<SELECT></SELECT>', $this->select->getHTML() );
	}

	/**
	 * Parameters are $name (false), $id (false), $default (false)
	 * @dataProvider provideConstructionParameters
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
	 *
	 */
	public function provideConstructionParameters() {
		return array(
			/**
			 * Values are set following a 3-bit Gray code where two successive
			 * values differ by only one value.
			 * See http://en.wikipedia.org/wiki/Gray_code
			 */
			#      $name   $id    $default
			array( false , false, false,  '<SELECT></SELECT>' ),
			array( false , false, 'foo',  '<SELECT></SELECT>' ),
			array( false , 'id' , 'foo',  '<SELECT id="id"></SELECT>' ),
			array( false , 'id' , false,  '<SELECT id="id"></SELECT>' ),
			array( 'name', 'id' , false,  '<SELECT name="name" id="id"></SELECT>' ),
			array( 'name', 'id' , 'foo',  '<SELECT name="name" id="id"></SELECT>' ),
			array( 'name', false, 'foo',  '<SELECT name="name"></SELECT>' ),
			array( 'name', false, false,  '<SELECT name="name"></SELECT>' ),
		);
	}

	# Begin XmlSelect::addOption() similar to Xml::option
	public function testAddOption() {
		$this->select->addOption( 'foo' );
		$this->assertEquals( '<SELECT><OPTION value="foo">foo</OPTION></SELECT>', $this->select->getHTML() );
	}
	public function testAddOptionWithDefault() {
		$this->select->addOption( 'foo', true );
		$this->assertEquals( '<SELECT><OPTION value="1">foo</OPTION></SELECT>', $this->select->getHTML() );
	}
	public function testAddOptionWithFalse() {
		$this->select->addOption( 'foo', false );
		$this->assertEquals( '<SELECT><OPTION value="foo">foo</OPTION></SELECT>', $this->select->getHTML() );
	}
	public function testAddOptionWithValueZero() {
		$this->select->addOption( 'foo', 0 );
		$this->assertEquals( '<SELECT><OPTION value="0">foo</OPTION></SELECT>', $this->select->getHTML() );
	}
	# End XmlSelect::addOption() similar to Xml::option

	public function testSetDefault() {
		$this->select->setDefault( 'bar1' );
		$this->select->addOption( 'foo1' );
		$this->select->addOption( 'bar1' );
		$this->select->addOption( 'foo2' );
		$this->assertEquals(
'<SELECT><OPTION value="foo1">foo1</OPTION>' . "\n" .
'<OPTION value="bar1" selected="">bar1</OPTION>' . "\n" .
'<OPTION value="foo2">foo2</OPTION></SELECT>', $this->select->getHTML() );
	}

	/**
	 * Adding default later on should set the correct selection or
	 * raise an exception.
	 * To handle this, we need to render the options in getHtml()
	 */
	public function testSetDefaultAfterAddingOptions() {
		$this->select->addOption( 'foo1' );
		$this->select->addOption( 'bar1' );
		$this->select->addOption( 'foo2' );
		$this->select->setDefault( 'bar1' ); # setting default after adding options
		$this->assertEquals(
'<SELECT><OPTION value="foo1">foo1</OPTION>' . "\n" .
'<OPTION value="bar1" selected="">bar1</OPTION>' . "\n" .
'<OPTION value="foo2">foo2</OPTION></SELECT>', $this->select->getHTML() );
	}

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

		# inexistant keys should give us 'null'
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
