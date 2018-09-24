<?php

use Wikimedia\Assert\ParameterTypeException;

/**
 * @covers TextSlotDiffRenderer
 */
class TextSlotDiffRendererTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideGetDiff
	 * @param Content|null $oldContent
	 * @param Content|null $newContent
	 * @param string|Exception $expectedResult
	 * @throws Exception
	 */
	public function testGetDiff(
		Content $oldContent = null, Content $newContent = null, $expectedResult
	) {
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

	public function provideGetDiff() {
		$this->mergeMwGlobalArrayValue( 'wgContentHandlers', [
			'testing' => DummyContentHandlerForTesting::class,
			'testing-nontext' => DummyNonTextContentHandler::class,
		] );

		return [
			'same text' => [
				$this->makeContent( "aaa\nbbb\nccc" ),
				$this->makeContent( "aaa\nbbb\nccc" ),
				"",
			],
			'different text' => [
				$this->makeContent( "aaa\nbbb\nccc" ),
				$this->makeContent( "aaa\nxxx\nccc" ),
				" aaa aaa\n-bbb+xxx\n ccc ccc",
			],
			'no right content' => [
				$this->makeContent( "aaa\nbbb\nccc" ),
				null,
				"-aaa+ \n-bbb \n-ccc ",
			],
			'no left content' => [
				null,
				$this->makeContent( "aaa\nbbb\nccc" ),
				"- +aaa\n +bbb\n +ccc",
			],
			'no content' => [
				null,
				null,
				new InvalidArgumentException( '$oldContent and $newContent cannot both be null' ),
			],
			'non-text left content' => [
				$this->makeContent( '', 'testing-nontext' ),
				$this->makeContent( "aaa\nbbb\nccc" ),
				new ParameterTypeException( '$oldContent', 'TextContent|null' ),
			],
			'non-text right content' => [
				$this->makeContent( "aaa\nbbb\nccc" ),
				$this->makeContent( '', 'testing-nontext' ),
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
		$slotDiffRenderer->setWikiDiff2MovedParagraphDetectionCutoff( 0 );
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
	private function makeContent( $str, $model = CONTENT_MODEL_TEXT ) {
		return ContentHandler::makeContent( $str, null, $model );
	}

}
