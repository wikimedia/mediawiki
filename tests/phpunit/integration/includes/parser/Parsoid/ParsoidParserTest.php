<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use ParserOptions;

/**
 * @covers \MediaWiki\Parser\Parsoid\ParsoidParser::parse
 * @group Database
 */
class ParsoidParserTest extends MediaWikiIntegrationTestCase {

	/** @dataProvider provideParsoidParserHtml */
	public function testParsoidParserHtml( $args, $expected, $getTextOpts = [] ) {
		$parsoidParser = $this->getServiceContainer()
			->getParsoidParserFactory()->create();
		if ( is_string( $args[1] ?? '' ) ) {
			// Make a PageReference from a string
			$args[1] = Title::newFromText( $args[1] ?? 'Main Page' );
		}
		if ( ( $args[2] ?? null ) === null ) {
			// Make default ParserOptions if none are provided
			$args[2] = ParserOptions::newFromAnon();
		}
		$output = $parsoidParser->parse( ...$args );
		$html = $output->getText( $getTextOpts );
		$this->assertStringContainsString( $expected, $html );
		$this->assertSame(
			$args[1]->getPrefixedDBkey(),
			$output->getExtensionData( ParsoidParser::PARSOID_TITLE_KEY )
		);
		$this->assertSame( [
			'disableContentConversion', 'interfaceMessage', 'wrapclass', 'suppressSectionEditLinks', 'isPreview', 'maxIncludeSize'
		], $output->getUsedOptions() );
	}

	public static function provideParsoidParserHtml() {
		return [
			[ [ 'Hello, World' ], 'Hello, World' ],
			[ [ '__NOTOC__' ], '<meta property="mw:PageProp/notoc"' ],
			// Once we support $linestart and other parser options we
			// can extend these tests.
		];
	}

	public function testParsoidParseRevisions() {
		$helloWorld = 'Hello, World';

		$page = $this->getNonexistingTestPage( 'Test' );
		$this->editPage( $page, $helloWorld );
		$pageTitle = $page->getTitle();

		$parsoidParser = $this->getServiceContainer()
			->getParsoidParserFactory()->create();
		$output = $parsoidParser->parse(
			$helloWorld,
			$pageTitle,
			ParserOptions::newFromAnon(),
			true,
			true,
			$page->getRevisionRecord()->getId()
		);
		$html = $output->getText();
		$this->assertStringContainsString( $helloWorld, $html );
		$this->assertSame(
			$pageTitle->getPrefixedDBkey(),
			$output->getExtensionData( ParsoidParser::PARSOID_TITLE_KEY )
		);
		$this->assertSame( [
			'disableContentConversion', 'interfaceMessage', 'wrapclass', 'suppressSectionEditLinks', 'isPreview', 'maxIncludeSize'
		], $output->getUsedOptions() );
	}
}
