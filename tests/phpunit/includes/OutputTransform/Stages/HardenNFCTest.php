<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HardenNFC;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\HardenNFC
 */
class HardenNFCTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HardenNFC(
			new ServiceOptions( [] ),
			new NullLogger()
		);
	}

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [] ]
		];
	}

	public static function provideShouldNotRun(): array {
		self::markTestSkipped( 'HydrateHeaderPlaceHolders should always run' );
	}

	public static function provideTransform(): array {
		$text = "<h1>\u{0338}</h1>";
		$expectedText = "<h1>&#x338;</h1>";
		return [
			[ new ParserOutput( $text ), null, [], new ParserOutput( $expectedText ) ],
		];
	}
}
