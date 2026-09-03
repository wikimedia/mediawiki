<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HydrateHeaderPlaceholders;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\OutputTransform\Stages\HydrateHeaderPlaceholders
 */
class HydrateHeaderPlaceholdersTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		$roleReg = $this->createMock( SlotRoleRegistry::class );
		$this->setService( 'SlotRoleRegistry', $roleReg );

		return new HydrateHeaderPlaceholders(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getSlotRoleRegistry(),
		);
	}

	public static function provideShouldRun(): array {
		$po = new ParserOutput();
		$po->appendExtensionData( 'core:slots', 'test' );
		return [
			[ $po, ParserOptions::newFromAnon(), [] ]
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ]
		];
	}

	public static function provideTransform(): iterable {
		$text = "<h1><mw:slotheader>Header&amp;1</mw:slotheader></h1><h2><mw:slotheader>Header 2</mw:slotheader></h2>";
		$expectedText = "<h1>Header&1</h1><h2>Header 2</h2>";
		$po = new ParserOutput( $text );
		$po->appendExtensionData( 'core:slots', 'test' );
		$po->getContentHolder()->setAsHtmlString( 'slot-test', $text );
		$expected = new ParserOutput( $expectedText );
		$expected->setExtensionData( 'core:hydrated', true );
		$expected->appendExtensionData( 'core:slots', 'test' );
		$expected->getContentHolder()->setAsHtmlString( 'slot-test', $text );
		yield "legacy" => [ $po, ParserOptions::newFromAnon(), [], $expected ];

		$text = "<h1>main</h1>Hi";
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new HtmlPageBundle( $text ),
			isParsoidContent: true,
		);
		$po->getContentHolder()->setAsHtmlString( 'slot-test', 'Ho' );
		$po->appendExtensionData( 'core:slots', 'test' );
		$po->getContentHolder()->getAsDom();

		$expectedText = '<h1>main</h1>Hi<h1 class="mw-slot-header">test</h1>Ho';
		$expected = PageBundleParserOutputConverter::parserOutputFromPageBundle(
			new HtmlPageBundle( $expectedText ),
			isParsoidContent: true,
		);
		$expected->appendExtensionData( 'core:slots', 'test' );
		$expected->setExtensionData( 'core:hydrated', true );
		$expected->getContentHolder()->getAsDom();

		yield "parsoid" => [ $po, ParserOptions::newFromAnon(), [], $expected ];
	}
}
