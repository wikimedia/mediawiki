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

}
