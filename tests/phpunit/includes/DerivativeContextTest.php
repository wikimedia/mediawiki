<?php

class DerivativeContextTest extends MediaWikiTestCase {

	public function setUp() {
		global $wgUseDatabaseMessages;

		parent::setUp();

		$this->oldwgUseDatabaseMessages = $wgUseDatabaseMessages;
		$wgUseDatabaseMessages = true;
	}

	public function tearDown() {
		$wgUseDatabaseMessages = $this->oldwgUseDatabaseMessages;

		parent::tearDown();
	}

	public function testContextLanguage() {
		global $wgLanguageCode;

		$context = new RequestContext();
		$context->setLanguage( $wgLanguageCode );
		$derivContext = new DerivativeContext( $context );

		// Pick a language other than the site default
		$lang = ( $wgLanguageCode === 'aa' ) ? 'ab' : 'aa';
		$dbKey = "A";
		$transDbKey = "{$dbKey}/{$lang}";
		$nativeText = "default";
		$transText = "trans";

		$derivContext->setLanguage( $lang );

		$msgTitle = Title::newFromText( $dbKey, NS_MEDIAWIKI );

		$page = new WikiPage( $msgTitle );
		$content = ContentHandler::makeContent( $nativeText, $msgTitle, null );
		$page->doEditContent( $content, "a comment", EDIT_NEW );

		$transTitle = Title::newFromText( $transDbKey, NS_MEDIAWIKI );
		$page = new WikiPage( $transTitle );
		$content = ContentHandler::makeContent( $transText, $transTitle, null );
		$page->doEditContent( $content, "a comment", EDIT_NEW );

		$this->assertEquals( $derivContext->msg( $dbKey )->text(), $transText,
			"DerivativeContext->setLanguage causes the translated message to be fetched." );
	}
}
