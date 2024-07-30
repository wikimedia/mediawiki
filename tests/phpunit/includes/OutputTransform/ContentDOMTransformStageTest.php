<?php

namespace MediaWiki\OutputTransform;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tests\OutputTransform\DummyDOMTransformStage;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Parsoid\Core\PageBundle;

class ContentDOMTransformStageTest extends TestCase {

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
		PageBundleParserOutputConverter::applyPageBundleDataToParserOutput( new PageBundle( $html ), $po );
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

		// Parsoid, input is sullied with rich attributes
		$options = [ 'isParsoidContent' => true ];
		$po = $transform->transform( $po, null, $options );
		$text = $po->getContentHolderText();
		$this->assertNotEquals( $html, $text );
		// Without PageBundle data, attributes are inlined
		self::assertStringContainsString( "data-parsoid", $text );
	}

}
