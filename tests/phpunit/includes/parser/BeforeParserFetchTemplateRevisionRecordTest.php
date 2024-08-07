<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Content\WikitextContent;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Parser\Parser;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiLangTestCase;
use MockTitleTrait;
use ParserOptions;

/**
 * @group Database
 * @covers \MediaWiki\Parser\Parser
 */
class BeforeParserFetchTemplateRevisionRecordTest extends MediaWikiLangTestCase {
	use MockTitleTrait;

	private function checkResult( $expected, $actual ) {
		if ( ( $expected['revision-record'] ?? true ) === false ) {
			$this->assertSame( false, $actual['revision-record'] );
		} else {
			$this->assertNotNull( $actual['revision-record'] );
		}
		$this->assertSame( $expected['text'], $actual['text'] );
		$this->assertSame( $expected['finalTitle'], $actual['finalTitle']->getPrefixedText() );
		// Simplify 'deps'
		$simpleActualDeps = array_map(
			fn ( $dep ) => $dep['title']->getPrefixedText(),
			$actual['deps']
		);
		$this->assertArrayEquals( $expected['deps'], $simpleActualDeps );
	}

	public static function provideWithParser() {
		yield "Without \$parser" => [ false ];
		yield "With \$parser" => [ true ];
	}

	private function commonSetup( $suffix = null ) {
		if ( $suffix === null ) {
			$suffix = $this->getCallerName();
		}
		$parser = $this->getServiceContainer()->getParserFactory()->create();
		$parser->setOptions( ParserOptions::newFromAnon() );

		$page = $this->getNonexistingTestPage( "Base $suffix" );
		$this->editPage( $page, 'Base page content', 'Make testing content' );

		$redirectPage = $this->getNonexistingTestPage( "Redirect $suffix" );
		$this->editPage(
			$redirectPage,
			'#REDIRECT [[' . $page->getTitle()->getPrefixedText() . ']]',
			"Make redirect link for testing"
		);

		return [ $parser, $page, $redirectPage ];
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::statelessFetchTemplate
	 * @dataProvider provideWithParser
	 */
	public function testStatelessFetchTemplateBasic( bool $withParser ) {
		[ $parser, $page, $redirectPage ] = $this->commonSetup( __FUNCTION__ );

		// Basic redirect test
		$ret = Parser::statelessFetchTemplate(
			$redirectPage->getTitle(), $withParser ? $parser : null
		);
		$this->checkResult( [
			'text' => 'Base page content',
			'finalTitle' => 'Base ' . __FUNCTION__,
			'deps' => [
				'Base ' . __FUNCTION__,
				'Redirect ' . __FUNCTION__,
			],
		], $ret );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::statelessFetchTemplate
	 * @dataProvider provideWithParser
	 */
	public function testStatelessFetchTemplateSkip( bool $withParser ) {
		[ $parser, $page, $redirectPage ] = $this->commonSetup( __FUNCTION__ );

		// Create a hook to prevent resolution of the redirect
		$this->setTemporaryHook(
			'BeforeParserFetchTemplateRevisionRecord',
			static function ( ?LinkTarget $contextTitle, LinkTarget $title, bool &$skip, ?RevisionRecord &$revRecord ) {
				$skip = true;
			}
		);

		$ret = Parser::statelessFetchTemplate(
			$redirectPage->getTitle(), $withParser ? $parser : null
		);
		$this->checkResult( [
			'text' => false,
			'finalTitle' => 'Redirect ' . __FUNCTION__,
			'deps' => [
				'Redirect ' . __FUNCTION__,
			],
		], $ret );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::statelessFetchTemplate
	 * @dataProvider provideWithParser
	 */
	public function testStatelessFetchTemplateMissing( bool $withParser ) {
		[ $parser, $page, $redirectPage ] = $this->commonSetup( __FUNCTION__ );

		// Create a hook to redirect to a non-existing page
		$baseTitle = 'Base ' . __FUNCTION__;
		$missing = $this->getNonexistingTestPage( 'Missing ' . __FUNCTION__ );
		$this->setTemporaryHook(
			'BeforeParserFetchTemplateRevisionRecord',
			static function ( ?LinkTarget $contextTitle, LinkTarget $title, bool &$skip, ?RevisionRecord &$revRecord ) use ( $baseTitle, $missing ) {
				if ( $title->getPrefixedText() === $baseTitle ) {
					$revRecord = new MutableRevisionRecord( $missing->getTitle() );
				}
			}
		);
		$ret = Parser::statelessFetchTemplate(
			$redirectPage->getTitle(), $withParser ? $parser : null
		);
		$this->checkResult( [
			'text' => false,
			'finalTitle' => 'Missing ' . __FUNCTION__,
			'deps' => [
				'Base ' . __FUNCTION__,
				# The $missing page is duplicated in the deps here, but that's
				# harmless.
				'Missing ' . __FUNCTION__,
				'Missing ' . __FUNCTION__,
				'Redirect ' . __FUNCTION__,
			],
		], $ret );
	}

	/**
	 * @covers \MediaWiki\Parser\Parser::statelessFetchTemplate
	 * @dataProvider provideWithParser
	 */
	public function testStatelessFetchTemplateSubstituted( bool $withParser ) {
		[ $parser, $page, $redirectPage ] = $this->commonSetup( __FUNCTION__ );

		// Create a hook to redirect to a non-existing page
		$baseTitle = 'Base ' . __FUNCTION__;
		$subst = $this->getNonexistingTestPage( 'Subst ' . __FUNCTION__ );
		$this->setTemporaryHook(
			'BeforeParserFetchTemplateRevisionRecord',
			static function ( ?LinkTarget $contextTitle, LinkTarget $title, bool &$skip, ?RevisionRecord &$revRecord ) use ( $baseTitle, $subst ) {
				if ( $title->getPrefixedText() === $baseTitle ) {
					$revRecord = new MutableRevisionRecord( $subst->getTitle() );
					$revRecord->setContent( SlotRecord::MAIN, new WikitextContent( 'foo' ) );
				}
			}
		);
		$ret = Parser::statelessFetchTemplate(
			$redirectPage->getTitle(), $withParser ? $parser : null
		);
		$this->checkResult( [
			'text' => 'foo',
			'finalTitle' => 'Subst ' . __FUNCTION__,
			'deps' => [
				'Base ' . __FUNCTION__,
				'Subst ' . __FUNCTION__,
				'Redirect ' . __FUNCTION__,
			],
		], $ret );
	}
}
