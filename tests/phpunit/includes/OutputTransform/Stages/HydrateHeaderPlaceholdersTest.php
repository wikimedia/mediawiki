<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HydrateHeaderPlaceholders;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\HydrateHeaderPlaceholders
 */
class HydrateHeaderPlaceholdersTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HydrateHeaderPlaceholders(
			new ServiceOptions( [] ),
			new NullLogger(),
			true,
		);
	}

	public static function provideShouldRun(): array {
		$po = new ParserOutput();
		$po->setOutputFlag( ParserOutputFlags::HAS_SLOT_HEADERS );
		return [
			[ $po, ParserOptions::newFromAnon(), [] ]
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ]
		];
	}

	public static function provideTransform(): array {
		$text = "<h1><mw:slotheader>Header&amp;1</mw:slotheader></h1><h2><mw:slotheader>Header 2</mw:slotheader></h2>";
		$expectedText = "<h1>Header&1</h1><h2>Header 2</h2>";
		$po = new ParserOutput( $text );
		$po->setOutputFlag( ParserOutputFlags::HAS_SLOT_HEADERS );
		$po->getContentHolder()->setAsHtmlString( 'my fragment', $text );
		$expected = new ParserOutput( $expectedText );
		$expected->setOutputFlag( ParserOutputFlags::HAS_SLOT_HEADERS );
		$expected->getContentHolder()->setAsHtmlString( 'my fragment', $text );
		return [
			[ $po, ParserOptions::newFromAnon(), [], $expected ],
		];
	}
}
