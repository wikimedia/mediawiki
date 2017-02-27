<?php
/**
 * Basic tests for Parser::getPreloadText
 * @author Antoine Musso
 *
 * @covers Parser
 * @covers StripState
 *
 * @covers Preprocessor_DOM
 * @covers PPDStack
 * @covers PPDStackElement
 * @covers PPDPart
 * @covers PPFrame_DOM
 * @covers PPTemplateFrame_DOM
 * @covers PPCustomFrame_DOM
 * @covers PPNode_DOM
 *
 * @covers Preprocessor_Hash
 * @covers PPDStack_Hash
 * @covers PPDStackElement_Hash
 * @covers PPDPart_Hash
 * @covers PPFrame_Hash
 * @covers PPTemplateFrame_Hash
 * @covers PPCustomFrame_Hash
 * @covers PPNode_Hash_Tree
 * @covers PPNode_Hash_Text
 * @covers PPNode_Hash_Array
 * @covers PPNode_Hash_Attr
 */
class ParserPreloadTest extends MediaWikiTestCase {
	/**
	 * @var Parser
	 */
	private $testParser;
	/**
	 * @var ParserOptions
	 */
	private $testParserOptions;
	/**
	 * @var Title
	 */
	private $title;

	protected function setUp() {
		global $wgContLang;

		parent::setUp();
		$this->testParserOptions = ParserOptions::newFromUserAndLang( new User, $wgContLang );

		$this->testParser = new Parser();
		$this->testParser->Options( $this->testParserOptions );
		$this->testParser->clearState();

		$this->title = Title::newFromText( 'Preload Test' );
	}

	protected function tearDown() {
		parent::tearDown();

		unset( $this->testParser );
		unset( $this->title );
	}

	public function testPreloadSimpleText() {
		$this->assertPreloaded( 'simple', 'simple' );
	}

	public function testPreloadedPreIsUnstripped() {
		$this->assertPreloaded(
			'<pre>monospaced</pre>',
			'<pre>monospaced</pre>',
			'<pre> in preloaded text must be unstripped (T29467)'
		);
	}

	public function testPreloadedNowikiIsUnstripped() {
		$this->assertPreloaded(
			'<nowiki>[[Dummy title]]</nowiki>',
			'<nowiki>[[Dummy title]]</nowiki>',
			'<nowiki> in preloaded text must be unstripped (T29467)'
		);
	}

	protected function assertPreloaded( $expected, $text, $msg = '' ) {
		$this->assertEquals(
			$expected,
			$this->testParser->getPreloadText(
				$text,
				$this->title,
				$this->testParserOptions
			),
			$msg
		);
	}
}
