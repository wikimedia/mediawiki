<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls
 */
class ExpandToAbsoluteUrlsTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new ExpandToAbsoluteUrls(
			new ServiceOptions( [] ),
			new NullLogger()
		);
	}

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [ 'absoluteURLs' => true ] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [ 'absoluteURLs' => false ] ],
			[ new ParserOutput(), null, [] ],
		];
	}

	public static function provideTransform(): array {
		$options = [];
		return [
			[ new ParserOutput( '' ), null, $options, new ParserOutput( '' ) ],
			[ new ParserOutput( '<p>test</p>' ), null, $options, new ParserOutput( '<p>test</p>' ) ],
			[
				new ParserOutput( '<a href="/wiki/Test">test</a>' ),
				null, $options,
				new ParserOutput( '<a href="//TEST_SERVER/wiki/Test">test</a>' )
			],
			[
				new ParserOutput( '<a href="//TEST_SERVER/wiki/Test">test</a>' ),
				null, $options,
				new ParserOutput( '<a href="//TEST_SERVER/wiki/Test">test</a>' )
			],
			[
				new ParserOutput( '<a href="https://TEST_SERVER/wiki/Test">test</a>' ),
				null, $options,
				new ParserOutput( '<a href="https://TEST_SERVER/wiki/Test">test</a>' )
			],
			[
				new ParserOutput( '<a href="https://en.wikipedia.org/wiki/Test">test</a>' ),
				null, $options,
				new ParserOutput( '<a href="https://en.wikipedia.org/wiki/Test">test</a>' )
			],
		];
	}
}
