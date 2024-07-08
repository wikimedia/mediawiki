<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleSectionLinks;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;
use ParserOptions;
use Psr\Log\NullLogger;
use Skin;

/** @covers \MediaWiki\OutputTransform\Stages\HandleSectionLinks */
class HandleSectionLinksTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HandleSectionLinks(
			new ServiceOptions(
				HandleSectionLinks::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::ParserEnableLegacyHeadingDOM => false,
				] )
			),
			new NullLogger(),
			$this->getServiceContainer()->getTitleFactory()
		);
	}

	public function provideShouldRun(): array {
		return [ [ new ParserOutput(), null, [] ] ];
	}

	public function provideShouldNotRun(): array {
		return [ [ new ParserOutput(), null, [ 'isParsoidContent' => true ] ] ];
	}

	private static function newParserOutput(
		?string $rawText = null,
		?ParserOptions $parserOptions = null,
		string ...$flags
	) {
		$po = new ParserOutput();
		if ( $rawText !== null ) {
			$po->setRawText( $rawText );
		}
		if ( $parserOptions !== null ) {
			$po->setFromParserOptions( $parserOptions );
		}
		foreach ( $flags as $f ) {
			$po->setOutputFlag( $f );
		}
		return $po;
	}

	public function provideTransform(): iterable {
		yield "TEST_DOC default: with links" => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS_NEW_MARKUP )
		];
		yield "TEST_DOC default ParserOptions: with links" => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			ParserOptions::newFromAnon(), [],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS_NEW_MARKUP )
		];
		yield 'TEST_DOC disabled via $options: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [ 'enableSectionEditLinks' => false ],
			self::newParserOutput( TestUtils::TEST_DOC_WITHOUT_LINKS_NEW_MARKUP )
		];
		$pOptsNoLinks = ParserOptions::newFromAnon();
		$pOptsNoLinks->setSuppressSectionEditLinks();
		yield 'TEST_DOC disabled via ParserOptions: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC, $pOptsNoLinks ),
			$pOptsNoLinks, [],
			self::newParserOutput( TestUtils::TEST_DOC_WITHOUT_LINKS_NEW_MARKUP, $pOptsNoLinks )
		];
		yield 'TEST_DOC enabled via $options: with links' => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [ 'enableSectionEditLinks' => true ],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS_NEW_MARKUP )
		];
		$legacyMarkupSkin = $this->getMockBuilder( Skin::class )
			->setConstructorArgs( [ [ 'name' => 'whatever', 'supportsMwHeading' => false ] ] )
			->getMockForAbstractClass();
		yield "TEST_DOC legacy markup: with links" => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [ 'skin' => $legacyMarkupSkin ],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS_LEGACY_MARKUP )
		];
		yield 'TEST_DOC legacy markup: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [ 'skin' => $legacyMarkupSkin, 'enableSectionEditLinks' => false ],
			self::newParserOutput( TestUtils::TEST_DOC_WITHOUT_LINKS_LEGACY_MARKUP )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS default: with links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITH_LINKS_NEW_MARKUP )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS disabled via $options: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [ 'enableSectionEditLinks' => false ],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITHOUT_LINKS_NEW_MARKUP )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS disabled via ParserOptions: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS, $pOptsNoLinks ),
			$pOptsNoLinks, [],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITHOUT_LINKS_NEW_MARKUP, $pOptsNoLinks )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS enabled via $options: with links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [ 'enableSectionEditLinks' => true ],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITH_LINKS_NEW_MARKUP )
		];
		yield "TEST_DOC_ANGLE_BRACKETS legacy markup: with links" => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [ 'skin' => $legacyMarkupSkin ],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITH_LINKS_LEGACY_MARKUP )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS legacy markup: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [ 'skin' => $legacyMarkupSkin, 'enableSectionEditLinks' => false ],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITHOUT_LINKS_LEGACY_MARKUP )
		];
	}
}
