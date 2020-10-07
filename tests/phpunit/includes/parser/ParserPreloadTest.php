<?php

use MediaWiki\MediaWikiServices;

/**
 * Basic tests for Parser::getPreloadText
 * @author Antoine Musso
 *
 * @covers Parser
 * @covers StripState
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
class ParserPreloadTest extends MediaWikiIntegrationTestCase {
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

	protected function setUp() : void {
		parent::setUp();
		$services = MediaWikiServices::getInstance();

		$this->testParserOptions = ParserOptions::newFromUserAndLang( new User,
			MediaWikiServices::getInstance()->getContentLanguage() );

		$this->testParser = $services->getParserFactory()->create();
		$this->testParser->setOptions( $this->testParserOptions );
		$this->testParser->clearState();

		$this->title = Title::newFromText( 'Preload Test' );
	}

	protected function tearDown() : void {
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
