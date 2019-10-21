<?php

use Wikimedia\Assert\ParameterTypeException;

/**
 * @covers TextSlotDiffRenderer
 */
class TextSlotDiffRendererTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideGetDiff
	 * @param array|null $oldContentArgs To pass to makeContent() (if not null)
	 * @param array|null $newContentArgs
	 * @param string|Exception $expectedResult
	 * @throws Exception
	 */
	public function testGetDiff(
		array $oldContentArgs = null, array $newContentArgs = null, $expectedResult
	) {
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => DummyContentHandlerForTesting::class,
			'testing-nontext' => DummyNonTextContentHandler::class,
		] );

		$oldContent = $oldContentArgs ? self::makeContent( ...$oldContentArgs ) : null;
		$newContent = $newContentArgs ? self::makeContent( ...$newContentArgs ) : null;

		if ( $expectedResult instanceof Exception ) {
			$this->setExpectedException( get_class( $expectedResult ), $expectedResult->getMessage() );
		}

		$slotDiffRenderer = $this->getTextSlotDiffRenderer();
		$diff = $slotDiffRenderer->getDiff( $oldContent, $newContent );
		if ( $expectedResult instanceof Exception ) {
			return;
		}
		$plainDiff = $this->getPlainDiff( $diff );
		$this->assertSame( $expectedResult, $plainDiff );
	}

	public static function provideGetDiff() {
		return [
			'same text' => [
				[ "aaa\nbbb\nccc" ],
				[ "aaa\nbbb\nccc" ],
				"",
			],
			'different text' => [
				[ "aaa\nbbb\nccc" ],
				[ "aaa\nxxx\nccc" ],
				" aaa aaa\n-bbb+xxx\n ccc ccc",
			],
			'no right content' => [
				[ "aaa\nbbb\nccc" ],
				null,
				"-aaa+ \n-bbb \n-ccc ",
			],
			'no left content' => [
				null,
				[ "aaa\nbbb\nccc" ],
				"- +aaa\n +bbb\n +ccc",
			],
			'no content' => [
				null,
				null,
				new InvalidArgumentException( '$oldContent and $newContent cannot both be null' ),
			],
			'non-text left content' => [
				[ '', 'testing-nontext' ],
				[ "aaa\nbbb\nccc" ],
				new ParameterTypeException( '$oldContent', 'TextContent|null' ),
			],
			'non-text right content' => [
				[ "aaa\nbbb\nccc" ],
				[ '', 'testing-nontext' ],
				new ParameterTypeException( '$newContent', 'TextContent|null' ),
			],
		];
	}

	// no separate test for getTextDiff() as getDiff() is just a thin wrapper around it

	/**
	 * @return TextSlotDiffRenderer
	 */
	private function getTextSlotDiffRenderer() {
		$slotDiffRenderer = new TextSlotDiffRenderer();
		$slotDiffRenderer->setStatsdDataFactory( new NullStatsdDataFactory() );
		$slotDiffRenderer->setLanguage( Language::factory( 'en' ) );
		$slotDiffRenderer->setEngine( TextSlotDiffRenderer::ENGINE_PHP );
		return $slotDiffRenderer;
	}

	/**
	 * Convert a HTML diff to a human-readable format and hopefully make the test less fragile.
	 * @param string diff
	 * @return string
	 */
	private function getPlainDiff( $diff ) {
		$replacements = [
			html_entity_decode( '&nbsp;' ) => ' ',
			html_entity_decode( '&minus;' ) => '-',
		];
		return str_replace( array_keys( $replacements ), array_values( $replacements ),
			trim( strip_tags( $diff ), "\n" ) );
	}

	/**
	 * @param string $str
	 * @param string $model
	 * @return null|TextContent
	 */
	private static function makeContent( $str, $model = CONTENT_MODEL_TEXT ) {
		return ContentHandler::makeContent( $str, null, $model );
	}

}
