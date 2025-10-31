<?php

namespace MediaWiki\Tests\Content\Transform;

use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Content\Transform\ContentTransformer
 */
class ContentTransformerTest extends MediaWikiIntegrationTestCase {

	public static function preSaveTransformProvider() {
		return [
			[
				new WikitextContent( 'Test ~~~' ),
				'Test [[Special:Contributions/127.0.0.1|127.0.0.1]]'
			],
		];
	}

	/**
	 * @dataProvider preSaveTransformProvider
	 */
	public function testPreSaveTransform( $content, $expectedContainText ) {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		$services = $this->getServiceContainer();
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$user = new User();
		$user->setName( "127.0.0.1" );
		$options = ParserOptions::newFromUser( $user );

		$newContent = $services->getContentTransformer()->preSaveTransform( $content, $title, $user, $options );
		$this->assertSame( $expectedContainText, $newContent->serialize() );
	}

	public static function preloadTransformProvider() {
		return [
			[
				new WikitextContent( '{{Foo}}<noinclude> censored</noinclude> information <!-- is very secret -->' ),
				'{{Foo}} information <!-- is very secret -->'
			],
		];
	}

	/**
	 * @dataProvider preloadTransformProvider
	 */
	public function testPreloadTransform( $content, $expectedContainText ) {
		$services = $this->getServiceContainer();
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$options = ParserOptions::newFromAnon();

		$newContent = $services->getContentTransformer()->preloadTransform( $content, $title, $options );
		$this->assertSame( $expectedContainText, $newContent->serialize() );
	}
}
