<?php
declare( strict_types = 1 );

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
			$args[2]->setUseParsoid();
		}
		$output = $parsoidParser->parse( ...$args );
		$html = $output->getContentHolderText();
		$this->assertStringContainsString( $expected, $html );
		$this->assertTrue(
			$output->getTitle()->isSameLinkAs( $args[1] )
		);
		$usedOptions = [
			'disableContentConversion',
			'interfaceMessage',
			'isMessage',
			'isPreview',
			'isSectionPreview',
			'useParsoid',
			'wrapclass',
		];
		$this->assertEqualsCanonicalizing( $usedOptions, $output->getUsedOptions() );

		$usedOptions = array_merge( $usedOptions, [
			'collapsibleSections',
			'enableSectionEditLinks', 'userlang',
			'disableTitleConversion', 'targetLanguage', 'variant'
		] );
		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$output = $pipeline->run( $output, $args[2], [] );
		$this->assertArrayEquals( $usedOptions, $output->getUsedOptions() );
	}

	public static function provideParsoidParserHtml() {
		return [
			[ [ "==Heading==\nHello, World" ], 'Hello, World' ],
			[ [ "__NOTOC__\n==Heading==" ], '<meta property="mw:PageProp/notoc"' ],
			// Once we support $linestart and other parser options we
			// can extend these tests.
		];
	}

	public function testParsoidParseRevisions() {
		$helloWorld = "==Heading==\nHello, World";

		$page = $this->getNonexistingTestPage( 'Test' );
		$this->editPage( $page, $helloWorld );
		$pageTitle = $page->getTitle();

		$parsoidParser = $this->getServiceContainer()
			->getParsoidParserFactory()->create();
		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid();
		$output = $parsoidParser->parse(
			$helloWorld,
			$pageTitle,
			$opts,
			true,
			true,
			$page->getRevisionRecord()->getId()
		);
		$html = $output->getContentHolderText();
		$this->assertStringContainsString( "Hello, World", $html );
		$this->assertTrue(
			$output->getTitle()->isSameLinkAs( $pageTitle )
		);
		$usedOptions = [
			'disableContentConversion',
			'interfaceMessage',
			'isMessage',
			'isPreview',
			'isSectionPreview',
			'useParsoid',
			'wrapclass',
		];
		$this->assertArrayEquals( $usedOptions, $output->getUsedOptions() );

		$pipeline = MediaWikiServices::getInstance()->getDefaultOutputPipeline();
		$usedOptions = array_merge( $usedOptions, [
			'collapsibleSections',
			'enableSectionEditLinks', 'userlang',
			'disableTitleConversion', 'targetLanguage', 'variant'
		] );
		$output = $pipeline->run( $output, $opts, [] );
		$this->assertArrayEquals( $usedOptions, $output->getUsedOptions() );
	}

	public function testLintsNotLogged() {
		$this->overrideConfigValue( MainConfigNames::ParsoidSettings, [ 'linting' => true ] );
		$page = $this->getExistingTestPage();
		$parsoidParser = $this->getServiceContainer()
			->getParsoidParserFactory()->create();
		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid();

		// Without a revision ID, the lints are not sent.
		$output = $parsoidParser->parse( '<b>Hello', $page, $opts );
		$this->assertTrue( $output->getExtensionData( ParsoidParser::LINTS_NOT_LOGGED ) );

		// With a revision ID, the lints are sent.
		$output = $parsoidParser->parse(
			$page->getContent()->getText(), $page, $opts,
			true, true, $page->getLatest()
		);
		$this->assertNull( $output->getExtensionData( ParsoidParser::LINTS_NOT_LOGGED ) );
	}

	public function testSelectiveUpdateSampleKeepsOptionsSafeToCache() {
		// Always run the selective update sample code
		$this->overrideConfigValue(
			MainConfigNames::ParsoidSelectiveUpdateSampleRate, 1
		);
		$page = $this->getNonexistingTestPage( 'Test' );
		$firstRev = $this->editPage( $page, 'first' )->getNewRevision();
		$secondRev = $this->editPage( $page, 'second' )->getNewRevision();

		$parsoidParser = $this->getServiceContainer()
			->getParsoidParserFactory()->create();
		$previousOpts = ParserOptions::newFromAnon();
		$previousOpts->setUseParsoid();
		$previousOutput = $parsoidParser->parse(
			'second', $page, $previousOpts,
			true, true, $secondRev->getId()
		);
		// The parser cache sets this value
		$previousOutput->setCacheRevisionId( $secondRev->getId() );

		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid();
		$this->assertTrue( $opts->isSafeToCache() );
		$output = $parsoidParser->parse(
			'first', $page, $opts,
			true, true, $firstRev->getId(), $previousOutput
		);
		$this->assertNull( $opts->getTargetLanguage() );
		$this->assertTrue( $opts->isSafeToCache() );

		// The output records the options that later code uses
		$this->assertNotContains( 'dateformat', $output->getUsedOptions() );
		$opts->getDateFormat();
		$this->assertContains( 'dateformat', $output->getUsedOptions() );
	}
}
