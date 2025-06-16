<?php

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Parser\Parsoid\ParsoidParser::parse
 * @group Database
 */
class ParsoidParserTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		// Limit reporting affects the options used
		$this->overrideConfigValue(
			MainConfigNames::EnableParserLimitReporting,
			false
		);
	}

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
		$html = $output->getRawText();
		$this->assertStringContainsString( $expected, $html );
		$this->assertSame(
			$args[1]->getPrefixedDBkey(),
			$output->getExtensionData( ParsoidParser::PARSOID_TITLE_KEY )
		);
		$usedOptions = [
			'collapsibleSections',
			'disableContentConversion',
			'interfaceMessage',
			'isMessage',
			'isPreview',
			'suppressSectionEditLinks',
			'useParsoid',
			'wrapclass',
		];
		$this->assertEqualsCanonicalizing( $usedOptions, $output->getUsedOptions() );

		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$pipeline->run( $output, $args[2], [] );
		$this->assertArrayEquals( $usedOptions, $output->getUsedOptions() );
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
		$opts = ParserOptions::newFromAnon();
		$output = $parsoidParser->parse(
			$helloWorld,
			$pageTitle,
			$opts,
			true,
			true,
			$page->getRevisionRecord()->getId()
		);
		$html = $output->getRawText();
		$this->assertStringContainsString( $helloWorld, $html );
		$this->assertSame(
			$pageTitle->getPrefixedDBkey(),
			$output->getExtensionData( ParsoidParser::PARSOID_TITLE_KEY )
		);
		$usedOptions = [
			'collapsibleSections',
			'disableContentConversion',
			'interfaceMessage',
			'isMessage',
			'isPreview',
			'suppressSectionEditLinks',
			'useParsoid',
			'wrapclass',
		];
		$this->assertArrayEquals( $usedOptions, $output->getUsedOptions() );

		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$pipeline->run( $output, $opts, [] );
		$this->assertArrayEquals( $usedOptions, $output->getUsedOptions() );
	}
}
