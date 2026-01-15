<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Language\Language;
use MediaWiki\Language\MessageCache;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Skin\Skin;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;

abstract class HandleTOCMarkersTestCommon extends OutputTransformStageTestBase {
	public function setUp(): void {
		$mock = $this->createMock( MessageCache::class );
		// This tests the fact that & is correctly and not doubly-escaped in the output
		$mock->method( 'get' )->willReturnCallback( static function ( $key ) {
			if ( $key === 'toc' ) {
				return "Content & more content";
			}
			return "($key)";
		} );
		$this->setService( 'MessageCache', $mock );
	}

	public static function provideShouldRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'allowTOC' => false, 'injectTOC' => false ] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'allowTOC' => false, 'injectTOC' => true ] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'allowTOC' => true, 'injectTOC' => true ] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'injectTOC' => true ] ],
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'allowTOC' => true ] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [
			[ new ParserOutput(), ParserOptions::newFromAnon(), [ 'allowTOC' => true, 'injectTOC' => false ] ]
		];
	}

	public function parentTestTransform( ParserOutput $parserOutput, ?ParserOptions $parserOptions, array $options,
								   ParserOutput $expected, string $message = '' ): void {
		if ( array_key_exists( 'userLang', $options ) ) {
			$lang = $this->createNoOpMock(
				Language::class, [ 'getCode', 'getHtmlCode', 'getDir' ]
			);
			$lang->method( 'getCode' )->willReturn( 'en' );
			$lang->method( 'getHtmlCode' )->willReturn( 'en' );
			$lang->method( 'getDir' )->willReturn( 'ltr' );
			$options['userLang'] = $lang;
		}
		if ( array_key_exists( 'skin', $options ) ) {
			$skin = $this->createNoOpMock(
				Skin::class, [ 'getLanguage' ]
			);
			$skin->method( 'getLanguage' )->willReturn( $options['userLang'] );
			$options['skin'] = $skin;
		}
		parent::testTransform( $parserOutput, $parserOptions, $options, $expected, $message = '' );
	}

	public static function yieldTransformTestCases(
		string $withToc, string $withoutToc, string $withCustomToc
	): iterable {
		$poTest1 = new ParserOutput( TestUtils::TEST_DOC );
		TestUtils::initSections( $poTest1 );
		$expectedWith = new ParserOutput( $withToc );
		TestUtils::initSections( $expectedWith );
		yield [ $poTest1, ParserOptions::newFromAnon(), [
			'userLang' => null,
			'skin' => null,
			'allowTOC' => true,
			'injectTOC' => true
		], $expectedWith, 'should insert TOC' ];

		$poTest2 = new ParserOutput( TestUtils::TEST_DOC );
		TestUtils::initSections( $poTest2 );
		$expectedWithout = new ParserOutput( $withoutToc );
		TestUtils::initSections( $expectedWithout );
		yield [ $poTest2, ParserOptions::newFromAnon(), [ 'allowTOC' => false ], $expectedWithout, 'should not insert TOC' ];

		$poTest3 = new ParserOutput( TestUtils::TEST_DOC . '<meta property="mw:PageProp/toc" />' );
		TestUtils::initSections( $poTest3 );
		$expectedWith = new ParserOutput( $withToc );
		TestUtils::initSections( $expectedWith );
		yield [ $poTest3, ParserOptions::newFromAnon(), [
			'userLang' => null,
			'skin' => null,
			'allowTOC' => true,
			'injectTOC' => true
		], $expectedWith, 'should insert TOC only once' ];

		$extData = [
			'mw:title' => 'my-title-msg',
			'mw:id' => 'my-id-test',
			'mw:class' => 'my-class-test',
		];
		$poTest4 = new ParserOutput( TestUtils::TEST_DOC );
		TestUtils::initSections( $poTest4 );
		$expected = new ParserOutput( $withCustomToc );
		TestUtils::initSections( $expected );
		foreach ( $extData as $key => $value ) {
			$poTest4->getTOCData()->setExtensionData( $key, $value );
			$expected->getTOCData()->setExtensionData( $key, $value );
		}
		yield "with custom TOC" => [ $poTest4, ParserOptions::newFromAnon(), [
			'userLang' => null,
			'skin' => null,
			'allowTOC' => true,
			'injectTOC' => true
		], $expected, 'should insert custom TOC' ];
	}

}
