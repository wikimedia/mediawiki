<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWikiUnitTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @covers \MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter
 */
class PageBundleParserOutputConverterTest extends MediaWikiUnitTestCase {
	/** @dataProvider provideParserOutputFromPageBundle */
	public function testParserOutputFromPageBundle( HtmlPageBundle $pageBundle ) {
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle );
		$this->assertSame( $pageBundle->html, $output->getRawText() );

		$outputPageBundle = $output->getContentHolder()->getBasePageBundle();
		$this->assertEquals( $pageBundle->toBasePageBundle(), $outputPageBundle );
	}

	/** @dataProvider provideParserOutputFromPageBundle */
	public function testParserOutputFromPageBundleShouldPreserveMetadata( HtmlPageBundle $pageBundle ) {
		// Create a ParserOutput with some metadata properties already set.
		$original = new ParserOutput();
		$original->setExtensionData( 'test-key', 'test-data' );
		$original->setOutputFlag( ParserOutputFlags::NO_GALLERY );
		$original->setPageProperty( 'forcetoc', '' );
		$original->recordOption( 'test1' );
		$original->recordOption( 'test2' );
		$original->setLanguage( new Bcp47CodeValue( 'fr' ) );

		// This should preserve the metadata.
		$output = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle, $original );
		$this->assertSame( $pageBundle->html, $output->getRawText() );

		// Check the page bundle data
		$outputPageBundle = $output->getContentHolder()->getBasePageBundle();
		$this->assertEquals( $pageBundle->toBasePageBundle(), $outputPageBundle );

		// Check our additional metadata properties
		$this->assertSame( 'test-data', $output->getExtensionData( 'test-key' ) );
		$this->assertSame( true, $output->getOutputFlag( ParserOutputFlags::NO_GALLERY ) );
		$this->assertSame( '', $output->getPageProperty( 'forcetoc' ) );
		$this->assertSame( [ 'test1', 'test2' ], $output->getUsedOptions() );

		// Some meta-data can be overridden
		if ( isset( $pageBundle->headers['content-language'] ) ) {
			$this->assertSame(
				$pageBundle->headers['content-language'],
				(string)$output->getLanguage()
			);
		} else {
			$this->assertSame( 'fr', (string)$output->getLanguage() );
		}

		// Check that $original and $output can be modified independently of each other
		$original->setRawText( 'new text version' );
		$this->assertNotSame( 'new text version', $output->getRawText() );
	}

	public static function provideParserOutputFromPageBundle() {
		yield 'should convert HtmlPageBundle containing data-parsoid and data-mw' => [
			new HtmlPageBundle(
				html: 'html content',
				parsoid: [ 'ids' => '1.33' ],
				mw: [ 'ids' => '1.33' ], // looks bogus
				version: '1.x',
				headers: [ 'content-language' => 'abc' ],
				contentmodel: 'testing'
			)
		];

		yield 'should convert HtmlPageBundle that contains no data-parsoid or data-mw' => [
			new HtmlPageBundle(
				html: 'html content',
				parsoid: [],
				mw: [],
				version: '1.x',
				headers: [ 'content-language' => null ]
			)
		];
	}

	/** @dataProvider providePageBundleFromParserOutput */
	public function testPageBundleFromParserOutput( ParserOutput $parserOutput ) {
		$pageBundle = PageBundleParserOutputConverter::htmlPageBundleFromParserOutput( $parserOutput );

		$this->assertSame( $parserOutput->getRawText(), $pageBundle->html );

		$outputPageBundle = $parserOutput->getContentHolder()->getBasePageBundle();
		$this->assertEquals( $pageBundle->toBasePageBundle(), $outputPageBundle );

		if ( $parserOutput->getLanguage() ) {
			$this->assertSame(
				$parserOutput->getLanguage()->toBcp47Code(),
				$pageBundle->headers['content-language']
			);
		}
	}

	public static function providePageBundleFromParserOutput() {
		yield 'should convert ParsoidOutput containing data-parsoid and data-mw' => [
			self::getParsoidOutput(
				new HtmlPageBundle(
					html: 'hello world',
					parsoid: [ 'ids' => '1.22' ],
					mw: [],
					version: '2.x',
					headers: [ 'content-language' => 'xyz' ]
				)
			)
		];

		yield 'should convert ParsoidOutput that does not contain data-parsoid or data-mw' => [
			self::getParsoidOutput(
				HtmlPageBundle::newEmpty( html: 'hello world' )
			)
		];
	}

	public function testLanguageTransfer() {
		$parserOutput = self::getParsoidOutput( HtmlPageBundle::newEmpty( '' ) );
		$parserOutput->setLanguage( new Bcp47CodeValue( 'de' ) );
		$pb = PageBundleParserOutputConverter::htmlPageBundleFromParserOutput( $parserOutput );
		$this->assertIsString( $pb->headers['content-language'] );
		$this->assertEquals( 'de', $pb->headers['content-language'] );
	}

	private static function getParsoidOutput(
		HtmlPageBundle $pb
	): ParserOutput {
		return PageBundleParserOutputConverter::parserOutputFromPageBundle( $pb );
	}
}
