<?php

/**
 * @group Language
 */
class LanguageMessageLocalizerTest extends MediaWikiTestCase {

	public function testLanguageMessageLocalizer() {
		$language = Language::factory( 'en' );
		$messageLocalizer = new LanguageMessageLocalizer( $language );
		$expected = wfMessage( 'delete' )->inLanguage( $language );
		$this->assertSame( $expected->plain(), $messageLocalizer->msg( 'delete' )->plain() );
	}
}
