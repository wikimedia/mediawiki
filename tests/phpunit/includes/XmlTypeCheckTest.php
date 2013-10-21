<?php
/**
 * PHPUnit tests for XMLTypeCheck.
 * @author physikerwelt
 * @group Xml
 * @covers XMLTypeCheck
 */
class XmlTypeCheckTest extends MediaWikiTestCase {
	const WELL_FORMED_XML = "<root><child /></root>";
	const MAL_FORMED_XML = "<root><child /></error>";

	/**
	 * @covers XMLTypeCheck::newFromString
	 * @covers XMLTypeCheck::getRootElement
	 */
	public function testWellFormedXML() {
		$testXML = XmlTypeCheck::newFromString( self::WELL_FORMED_XML );
		$this->assertTrue( $testXML->wellFormed );
		$this->assertEquals( 'root', $testXML->getRootElement() );
	}

	/**
	 * @covers XMLTypeCheck::newFromString
	 */
	public function testMalFormedXML() {
		$testXML = XmlTypeCheck::newFromString( self::MAL_FORMED_XML );
		$this->assertFalse( $testXML->wellFormed );
	}

}
