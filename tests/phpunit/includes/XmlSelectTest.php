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
		$this->assertEquals( '<select></select>', $this->select->getHTML() );
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
			array( false , false, false,  '<select></select>' ),
			array( false , false, 'foo',  '<select></select>' ),
			array( false , 'id' , 'foo',  '<select id="id"></select>' ),
			array( false , 'id' , false,  '<select id="id"></select>' ),
			array( 'name', 'id' , false,  '<select name="name" id="id"></select>' ),
			array( 'name', 'id' , 'foo',  '<select name="name" id="id"></select>' ),
			array( 'name', false, 'foo',  '<select name="name"></select>' ),
			array( 'name', false, false,  '<select name="name"></select>' ),
		);
	}

	# Begin XmlSelect::addOption() similar to Xml::option
	public function testAddOption() {
		$this->select->addOption( 'foo' );
		$this->assertEquals( '<select><option value="foo">foo</option></select>', $this->select->getHTML() );
	}
	public function testAddOptionWithDefault() {
		$this->select->addOption( 'foo', true );
		$this->assertEquals( '<select><option value="1">foo</option></select>', $this->select->getHTML() );
	}
	public function testAddOptionWithFalse() {
		$this->select->addOption( 'foo', false );
		$this->assertEquals( '<select><option value="foo">foo</option></select>', $this->select->getHTML() );
	}
	public function testAddOptionWithValueZero() {
		$this->select->addOption( 'foo', 0 );
		$this->assertEquals( '<select><option value="0">foo</option></select>', $this->select->getHTML() );
	}
	# End XmlSelect::addOption() similar to Xml::option

	public function testSetDefault() {
		$this->select->setDefault( 'bar1' );
		$this->select->addOption( 'foo1' );
		$this->select->addOption( 'bar1' );
		$this->select->addOption( 'foo2' );
		$this->assertEquals(
'<select><option value="foo1">foo1</option>
<option value="bar1" selected="selected">bar1</option>
<option value="foo2">foo2</option></select>', $this->select->getHTML() );
	}

	/**
	 * Adding default later on should set the correct selection or
	 * raise an exception.
	 * To handle this, we need to render the options in getHtml()
	 */
	public function testSetDefaultAfterAddingOptions() {
		$this->markTestSkipped( 'XmlSelect::setDefault() need to apply to previously added options');

		$this->select->addOption( 'foo1' );
		$this->select->addOption( 'bar1' );
		$this->select->addOption( 'foo2' );
		$this->select->setDefault( 'bar1' ); # setting default after adding options
		$this->assertEquals(
'<select><option value="foo1">foo1</option>
<option value="bar1" selected="selected">bar1</option>
<option value="foo2">foo2</option></select>', $this->select->getHTML() );
	}

}
