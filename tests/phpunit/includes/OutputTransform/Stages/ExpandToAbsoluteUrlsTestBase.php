<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;

abstract class ExpandToAbsoluteUrlsTestBase extends OutputTransformStageTestBase {

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'absoluteURLs' => true ] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'absoluteURLs' => false ] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ],
		];
	}

	public static function provideTransform(): array {
		$options = [];
		$res = [
			[ '', '' ],
			[ '<p>test</p>', '<p>test</p>' ],
			[ '<a href="/wiki/Test">test</a>', '<a href="//TEST_SERVER/wiki/Test">test</a>' ],
			[ '<a href="//TEST_SERVER/wiki/Test">test</a>', '<a href="//TEST_SERVER/wiki/Test">test</a>' ],
			[ '<a href="https://TEST_SERVER/wiki/Test">test</a>', '<a href="https://TEST_SERVER/wiki/Test">test</a>' ],
			[ '<a href="https://en.wikipedia.org/wiki/Test">test</a>', '<a href="https://en.wikipedia.org/wiki/Test">test</a>' ]
		];

		return array_map( static function ( $a ): array {
			$options = [];
			$input = new ParserOutput( $a[0] );
			$input->getContentHolder()->setAsHtmlString( 'some fragment', $a[0] );
			$expected = new ParserOutput( $a[1] );
			$expected->getContentHolder()->setAsHtmlString( 'some fragment', $a[1] );
			return [ $input, ParserOptions::newFromAnon(), $options, $expected ];
		}, $res );
	}
}
