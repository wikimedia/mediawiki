<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\Stages\ParsoidLanguageConverter;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\ParserTests\TestUtils;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ParsoidLanguageConverter
 * @group Database
 */
class ParsoidLanguageConverterTest extends MediaWikiIntegrationTestCase {

	public function createStage(): ParsoidLanguageConverter {
		return new ParsoidLanguageConverter(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getParsoidSiteConfig(),
			$this->getServiceContainer()->getLanguageFactory(),
			$this->getServiceContainer()->getLanguageConverterFactory(),
			$this->getServiceContainer()->getTitleFactory(),
			$this->getServiceContainer()->getUrlUtils(),
			$this->getServiceContainer()->getLinkBatchFactory(),
		);
	}

	/**
	 * @dataProvider provideDocsToConvert
	 */
	public function testApplyTransformation(
		string $input, string $expected, string $pagelang,
	) {
		$languageFactory = $this->getServiceContainer()->getLanguageFactory();
		$conv = $this->createStage();
		$po = PageBundleParserOutputConverter::parserOutputFromPageBundle( new HtmlPageBundle( $input ) );
		$po->setTitle( Title::newFromText( 'Test page' ) );
		$variant = $languageFactory->getLanguage( new Bcp47CodeValue( $pagelang ) );
		$baseLang = $languageFactory->getParentLanguage( $variant );
		$popts = ParserOptions::newFromAnon();
		$popts->setTargetLanguage( $baseLang );
		$popts->setVariant( $variant );
		$opts = [];
		$po->setFromParserOptions( $popts );
		$transf = $conv->transform( $po, $popts, $opts );
		$res = $transf->getContentHolderText();
		self::assertEquals( $expected, TestUtils::stripParsoidIds( $res ) );
	}

	public static function provideDocsToConvert() {
		yield "Basic conversion" => [
			'<p>Latin text</p>',
			'<p>Латин теxт</p>',
			'sr-Cyrl',
		];
		yield "Don't convert inside <style>" => [
			'<p>Latin</p><style>.foo { display: none; }</style><p class="foo">More</p>',
			'<p>Латин</p><style>.foo { display: none; }</style><p class="foo">Море</p>',
			'sr-Cyrl',
		];
	}
}
