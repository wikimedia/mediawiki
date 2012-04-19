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


	/**
	 * Verify that a dumb TestContext instance actually provides
	 * some default instances for well known accessors.
	 *
	 * @dataProvider provideContextElements
	 */
	function testHasADefaultTitle( $expectedClass, $accessor ) {
		$c = new TestContext();
		$this->assertInstanceOf( $expectedClass, $c->$accessor() );
	}

	function provideContextElements() {
		return array(
			# expected class, accessor
			array( 'User',       'getUser'    ),
			array( 'Language',   'getLang'    ),
			array( 'OutputPage', 'getOutput'  ),
			array( 'WebRequest', 'getRequest' ),
			array( 'Title',      'getTitle'   ),
		);
	}

	function testAcceptAnyCaseAsParametersKeys() {
		$c = new TestContext( array(
			'TITLE' => 'SomeTitle',
			'lAnG'  => 'en',
			'uSER'  => 'Anonymous_00',
		) );
		$this->assertInstanceOf( 'Title'   , $c->getTitle() );
		$this->assertInstanceOf( 'Language', $c->getLang()  );
		$this->assertInstanceOf( 'User'    , $c->getUser()  );
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

	function testMakeLanguageFromString() {
		$c = new TestContext( array(
			'lang' => 'fr',
		) );
		$this->assertInstanceOf( 'LanguageFr', $c->getLang() );
		// purge l10n cache to save up memory
		Language::getLocalisationCache()->unload( 'fr' );
	}

	function testAcceptLanguageObjects() {
		$c = new TestContext( array(
			'lang' => Language::factory( 'fr' ),
		) );
		$this->assertInstanceOf( 'LanguageFr', $c->getLang() );
		// purge l10n cache to save up memory
		Language::getLocalisationCache()->unload( 'fr' );
	}

	function testMakeUserFromString() {
		$c = new TestContext( array(
			'user' => 'John Doe',
		) );
		$this->assertInstanceOf( 'User', $c->getUser() );
		$this->assertEquals( 'John_Doe', $c->getUser()->getTitleKey() );
	}

	function testAcceptUserObjects() {
		$c = new TestContext( array(
			'user' => User::newFromName( 'John Doe' ),
		) );
		$this->assertInstanceOf( 'User', $c->getUser() );
		$this->assertEquals( 'John_Doe', $c->getUser()->getTitleKey() );
	}

	function testAcceptWebRequest() {
		$c = new TestContext( array(
			'request' => new FauxRequest(),
		) );
		$this->assertInstanceOf( 'WebRequest', $c->getRequest() );
	}

	// Add tests for other possible parameters
}
