<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tests\OutputTransform\DummyDOMTransformStage;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

class ContentDOMTransformStageTest extends TestCase {
	use MediaWikiCoversValidator;

	public function createStage(): ContentDOMTransformStage {
		return new DummyDOMTransformStage(
			new ServiceOptions( [] ),
			new NullLogger()
		);
	}

	/**
	 * Regression test for T365036 - checking that a very basic ParserOutput continues serializing after going
	 * through a ContentDOMTransformStage
	 * @covers \MediaWiki\OutputTransform\ContentDOMTransformStage::transformDOM
	 */
	public function testTransform() {
		$html = "<div>some output</div>";
		$po = new ParserOutput( $html );
		PageBundleParserOutputConverter::applyPageBundleDataToParserOutput( new HtmlPageBundle( $html ), $po );
		$transform = $this->createStage();
		$options = [ 'isParsoidContent' => true ];
		$po = $transform->transform( $po, null, $options );
		$json = MediaWikiServices::getInstance()->getJsonCodec()->serialize( $po );
		self::assertStringContainsString( "parsoid-page-bundle", $json );
	}

	/**
	 * @covers \MediaWiki\OutputTransform\ContentDOMTransformStage::parsoidTransform
	 * @covers \MediaWiki\OutputTransform\ContentDOMTransformStage::legacyTransform
	 */
	public function testTransformOption() {
		$html = "<div>some output</div>";
		$po = new ParserOutput( $html );
		$transform = $this->createStage();

		// Legacy, should roundtrip the input
		$options = [ 'isParsoidContent' => false ];
		$po = $transform->transform( $po, null, $options );
		$text = $po->getContentHolderText();
		$this->assertEquals( $html, $text );

		// Parsoid, also roundtrips the input since document creation marks it as new
		$options = [ 'isParsoidContent' => true ];
		$po = $transform->transform( $po, null, $options );
		$text = $po->getContentHolderText();
		$this->assertEquals( $html, $text );
	}

}
