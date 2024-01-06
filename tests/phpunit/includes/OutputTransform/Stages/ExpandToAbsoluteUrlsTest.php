<?php
namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\OutputTransformStageTest;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls
 */
class ExpandToAbsoluteUrlsTest extends OutputTransformStageTest {

	public function createStage(): OutputTransformStage {
		return new ExpandToAbsoluteUrls();
	}

	public function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [ 'absoluteURLs' => true ] ],
		];
	}

	public function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), null, [ 'absoluteURLs' => false ] ],
			[ new ParserOutput(), null, [] ],
		];
	}

	public function provideTransform(): array {
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
