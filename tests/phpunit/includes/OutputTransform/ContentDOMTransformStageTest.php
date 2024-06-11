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

	/**
	 * Regression test for T365036 - checking that a very basic ParserOutput continues serializing after going
	 * through a ContentDOMTransformStage
	 * @covers \MediaWiki\OutputTransform\ContentDOMTransformStage::transformDOM
	 */
	public function testTransform() {
		$html = "<div>some output</div>";
		$po = new ParserOutput( $html );
		PageBundleParserOutputConverter::applyPageBundleDataToParserOutput( new PageBundle( $html ), $po );
		$transform = new DummyDOMTransformStage(
			new ServiceOptions( [] ),
			new NullLogger()
		);
		$options = [];
		$po = $transform->transform( $po, null, $options );
		$json = MediaWikiServices::getInstance()->getJsonCodec()->serialize( $po );
		self::assertStringContainsString( "parsoid-page-bundle", $json );
	}
}
