<?php
/**
 * Basic tests for Parser::getPreloadText
 * @author Antoine Musso
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

	/**
	 * @covers Parser::getPreloadText
	 */
	public function testPreloadSimpleText() {
		$this->assertPreloaded( 'simple', 'simple' );
	}

	/**
	 * @covers Parser::getPreloadText
	 */
	public function testPreloadedPreIsUnstripped() {
		$this->assertPreloaded(
			'<pre>monospaced</pre>',
			'<pre>monospaced</pre>',
			'<pre> in preloaded text must be unstripped (bug 27467)'
		);
	}

	/**
	 * @covers Parser::getPreloadText
	 */
	public function testPreloadedNowikiIsUnstripped() {
		$this->assertPreloaded(
			'<nowiki>[[Dummy title]]</nowiki>',
			'<nowiki>[[Dummy title]]</nowiki>',
			'<nowiki> in preloaded text must be unstripped (bug 27467)'
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
