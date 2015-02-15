<?php

/**
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class ParserOutputTest extends MediaWikiTestCase {

	public static function provideIsLinkInternal() {
		return array(
			// Different domains
			array( false, 'http://example.org', 'http://mediawiki.org' ),
			// Same domains
			array( true, 'http://example.org', 'http://example.org' ),
			array( true, 'https://example.org', 'https://example.org' ),
			array( true, '//example.org', '//example.org' ),
			// Same domain different cases
			array( true, 'http://example.org', 'http://EXAMPLE.ORG' ),
			// Paths, queries, and fragments are not relevant
			array( true, 'http://example.org', 'http://example.org/wiki/Main_Page' ),
			array( true, 'http://example.org', 'http://example.org?my=query' ),
			array( true, 'http://example.org', 'http://example.org#its-a-fragment' ),
			// Different protocols
			array( false, 'http://example.org', 'https://example.org' ),
			array( false, 'https://example.org', 'http://example.org' ),
			// Protocol relative servers always match http and https links
			array( true, '//example.org', 'http://example.org' ),
			array( true, '//example.org', 'https://example.org' ),
			// But they don't match strange things like this
			array( false, '//example.org', 'irc://example.org' ),
		);
	}

	/**
	 * Test to make sure ParserOutput::isLinkInternal behaves properly
	 * @dataProvider provideIsLinkInternal
	 * @covers ParserOutput::isLinkInternal
	 */
	public function testIsLinkInternal( $shouldMatch, $server, $url ) {
		$this->assertEquals( $shouldMatch, ParserOutput::isLinkInternal( $server, $url ) );
	}

	/**
	 * @covers ParserOutput::setExtensionData
	 * @covers ParserOutput::getExtensionData
	 */
	public function testExtensionData() {
		$po = new ParserOutput();

		$po->setExtensionData( "one", "Foo" );

		$this->assertEquals( "Foo", $po->getExtensionData( "one" ) );
		$this->assertNull( $po->getExtensionData( "spam" ) );

		$po->setExtensionData( "two", "Bar" );
		$this->assertEquals( "Foo", $po->getExtensionData( "one" ) );
		$this->assertEquals( "Bar", $po->getExtensionData( "two" ) );

		$po->setExtensionData( "one", null );
		$this->assertNull( $po->getExtensionData( "one" ) );
		$this->assertEquals( "Bar", $po->getExtensionData( "two" ) );
	}

	/**
	 * @covers ParserOutput::setProperty
	 * @covers ParserOutput::getProperty
	 * @covers ParserOutput::unsetProperty
	 * @covers ParserOutput::getProperties
	 */
	public function testProperties() {
		$po = new ParserOutput();

		$po->setProperty( 'foo', 'val' );

		$properties = $po->getProperties();
		$this->assertEquals( $po->getProperty( 'foo' ), 'val' );
		$this->assertEquals( $properties['foo'], 'val' );

		$po->setProperty( 'foo', 'second val' );

		$properties = $po->getProperties();
		$this->assertEquals( $po->getProperty( 'foo' ), 'second val' );
		$this->assertEquals( $properties['foo'], 'second val' );

		$po->unsetProperty( 'foo' );

		$properties = $po->getProperties();
		$this->assertEquals( $po->getProperty( 'foo' ), false );
		$this->assertArrayNotHasKey( 'foo', $properties );
	}

}
