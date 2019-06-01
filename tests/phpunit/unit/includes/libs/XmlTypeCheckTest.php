<?php
/**
 * PHPUnit tests for XMLTypeCheck.
 * @author physikerwelt
 * @group Xml
 * @covers XMLTypeCheck
 */
class XmlTypeCheckTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	const WELL_FORMED_XML = "<root><child /></root>";
	const MAL_FORMED_XML = "<root><child /></error>";
	// phpcs:ignore Generic.Files.LineLength
	const XML_WITH_PIH = '<?xml version="1.0"?><?xml-stylesheet type="text/xsl" href="/w/index.php"?><svg><child /></svg>';

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
	 * Verify we check for recursive entity DOS
	 *
	 * (If the DOS isn't properly handled, the test runner will probably go OOM...)
	 */
	public function testRecursiveEntity() {
		$xml = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE foo [
	<!ENTITY test "&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;">
	<!ENTITY a "&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;">
	<!ENTITY b "&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;">
	<!ENTITY c "&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;">
	<!ENTITY d "&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;">
	<!ENTITY e "&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;">
	<!ENTITY f "&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;&g;">
	<!ENTITY g "-00000000000000000000000000000000000000000000000000000000000000000000000-">
]>
<foo>
<bar>&test;</bar>
</foo>
XML;
		$check = XmlTypeCheck::newFromString( $xml );
		$this->assertFalse( $check->wellFormed );
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
				'processing_instruction_handler' => function () use ( &$called ) {
					$called = true;
				}
			]
		);
		$this->assertTrue( $called );
	}

}
