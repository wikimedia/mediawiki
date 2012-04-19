<?php

class TestContextTest extends MediaWikiTestCase {


	function testConstructWithoutOptions() {
		$this->assertInstanceOf(
			'RequestContext', new TestContext()
		);
		$this->assertInstanceOf(
			'RequestContext', new TestContext( array() )
		);
	}

	/**
	 * @expectedException MWException
	 */
	function testRejectUnsupportedParameter() {
		new TestContext( array(
			'IAmUnSupported' => 'really!',
		) );
	}

	function testHasADefaultTitle() {
		$c = new TestContext();
		$this->assertInstanceOf( 'Title', $c->getTitle() );
	}

	function testMakeTitlesFromString() {
		$c = new TestContext( array(
			'title' => 'Category:Main_Page',
		) );
		$this->assertInstanceOf( 'Title', $c->getTitle() );
		$this->assertEquals(
			NS_CATEGORY,
			$c->getTitle()->getNamespace()
		);
	}

	function testAcceptTitleObjects() {
		$c = new TestContext( array(
			'title' => Title::newFromText( 'Template:InfoBox' ),
		) );
		$this->assertInstanceOf( 'Title', $c->getTitle() );
		$this->assertEquals(
			NS_TEMPLATE,
			$c->getTitle()->getNamespace()
		);
	}

	// Add tests for other possible parameters
}
