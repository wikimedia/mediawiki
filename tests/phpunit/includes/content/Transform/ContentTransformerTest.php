<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

class ContentTransformerTest extends MediaWikiIntegrationTestCase {

	public function preSaveTransformProvider() {
		return [
			[
				new WikitextContent( 'Test ~~~' ),
				'Test [[Special:Contributions/127.0.0.1|127.0.0.1]]'
			],
		];
	}

	/**
	 * @covers MediaWiki\Content\Transform\ContentTransformer::preSaveTransform
	 *
	 * @dataProvider preSaveTransformProvider
	 */
	public function testPreSaveTransform( $content, $expectedContainText ) {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		$services = $this->getServiceContainer();
		$title = Title::newFromText( 'Test' );
		$user = new User();
		$user->setName( "127.0.0.1" );
		$options = ParserOptions::newFromUser( $user );

		$newContent = $services->getContentTransformer()->preSaveTransform( $content, $title, $user, $options );
		$this->assertSame( $expectedContainText, $newContent->serialize() );
	}

	public function preloadTransformProvider() {
		return [
			[
				new WikitextContent( '{{Foo}}<noinclude> censored</noinclude> information <!-- is very secret -->' ),
				'{{Foo}} information <!-- is very secret -->'
			],
		];
	}

	/**
	 * @covers MediaWiki\Content\Transform\ContentTransformer::preloadTransform
	 *
	 * @dataProvider preloadTransformProvider
	 */
	public function testPreloadTransform( $content, $expectedContainText ) {
		$services = $this->getServiceContainer();
		$title = Title::newFromText( 'Test' );
		$options = ParserOptions::newFromAnon();

		$newContent = $services->getContentTransformer()->preloadTransform( $content, $title, $options );
		$this->assertSame( $expectedContainText, $newContent->serialize() );
	}
}
