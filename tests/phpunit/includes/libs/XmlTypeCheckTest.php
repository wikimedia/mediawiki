<?php
/**
 * PHPUnit tests for XMLTypeCheck.
 * @author physikerwelt
 * @group Xml
 * @covers XMLTypeCheck
 */
class XmlTypeCheckTest extends PHPUnit_Framework_TestCase {
	const WELL_FORMED_XML = "<root><child /></root>";
	const MAL_FORMED_XML = "<root><child /></error>";
	// @codingStandardsIgnoreStart Generic.Files.LineLength
	const XML_WITH_PIH = '<?xml version="1.0"?><?xml-stylesheet type="text/xsl" href="/w/index.php"?><svg><child /></svg>';
	// @codingStandardsIgnoreEnd

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

	/**
	 * @covers XMLTypeCheck::processingInstructionHandler
	 */
	public function testProcessingInstructionHandler() {
		$called = false;
		$testXML = new XmlTypeCheck(
			self::XML_WITH_PIH,
			null,
			false,
			[
				'processing_instruction_handler' => function() use ( &$called ) {
					$called = true;
				}
			]
		);
		$this->assertTrue( $called );
	}

}
