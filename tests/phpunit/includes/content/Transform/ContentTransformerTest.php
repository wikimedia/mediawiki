<?php

use MediaWiki\MediaWikiServices;

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
		$services = MediaWikiServices::getInstance();
		$title = Title::newFromText( 'Test' );
		$user = new User();
		$user->setName( "127.0.0.1" );
		$options = ParserOptions::newFromUser( $user );

		$newContent = $services->getContentTransformer()->preSaveTransform( $content, $title, $user, $options );
		$this->assertSame( $expectedContainText, $newContent->serialize() );
	}
}
