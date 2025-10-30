<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * Basic tests for Parser::getPreloadText
 * @author Antoine Musso
 *
 * @covers \MediaWiki\Parser\Parser
 * @covers \MediaWiki\Parser\StripState
 *
 * @covers \MediaWiki\Parser\Preprocessor_Hash
 * @covers \MediaWiki\Parser\PPDStack_Hash
 * @covers \MediaWiki\Parser\PPDStackElement_Hash
 * @covers \MediaWiki\Parser\PPDPart_Hash
 * @covers \MediaWiki\Parser\PPFrame_Hash
 * @covers \MediaWiki\Parser\PPTemplateFrame_Hash
 * @covers \MediaWiki\Parser\PPCustomFrame_Hash
 * @covers \MediaWiki\Parser\PPNode_Hash_Tree
 * @covers \MediaWiki\Parser\PPNode_Hash_Text
 * @covers \MediaWiki\Parser\PPNode_Hash_Array
 * @covers \MediaWiki\Parser\PPNode_Hash_Attr
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

	protected function setUp(): void {
		parent::setUp();
		$services = $this->getServiceContainer();

		$this->testParserOptions = ParserOptions::newFromUserAndLang( new User,
			$this->getServiceContainer()->getContentLanguage() );

		$this->testParser = $services->getParserFactory()->create();
		$this->testParser->setOptions( $this->testParserOptions );
		$this->testParser->clearState();

		$this->title = Title::makeTitle( NS_MAIN, 'Preload Test' );
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
