<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Rest\Handler\Helper\HtmlShadowOutputHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\HtmlShadowOutputHelper
 * @group Database
 */
class HtmlShadowOutputHelperTest extends MediaWikiIntegrationTestCase {
	private function newHelper( $page ): HtmlShadowOutputHelper {
		$services = $this->getServiceContainer();
		return new HtmlShadowOutputHelper(
			$services->getShadowPageLoader(),
			$services->getTitleFormatter(),
			ParserOptions::newFromAnon(),
			$page
		);
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlShadowOutputHelper::getHtml
	 */
	public function testGetHtml() {
		$page = $this->getNonexistingTestPage( 'MediaWiki:Logouttext' );

		$helper = $this->newHelper( $page );

		$this->assertSame( 0, $page->getLatest() );

		// The served document is the full document, carried by the page bundle
		// (as PageHTMLHandler consumes it).
		$htmlresult = $helper->getPageBundle()->html;

		$this->assertStringContainsString( 'You are now logged out', $htmlresult );
		// Check that we have a full HTML document in English
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( 'content="en"', $htmlresult );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlShadowOutputHelper::getETag
	 */
	public function testGetETag() {
		$page = $this->getNonexistingTestPage( 'MediaWiki:Logouttext' );

		$helper = $this->newHelper( $page );

		$etag = $helper->getETag();

		$this->assertStringContainsString( '"message/', $etag );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlShadowOutputHelper::getHtml
	 */
	public function testGetHtmlWithLanguageCode() {
		$page = $this->getNonexistingTestPage( 'MediaWiki:Logouttext/de' );

		$helper = $this->newHelper( $page );

		$this->assertSame( 0, $page->getLatest() );

		// The served document is the full document; see testGetHtml().
		$htmlresult = $helper->getPageBundle()->html;

		$this->assertStringContainsString( 'Du bist nun abgemeldet', $htmlresult );
		// Check that we have a full HTML document in English
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( 'content="de"', $htmlresult );
	}
}
