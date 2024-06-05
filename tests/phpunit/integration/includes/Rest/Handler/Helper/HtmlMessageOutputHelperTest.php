<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper
 * @group Database
 */
class HtmlMessageOutputHelperTest extends MediaWikiIntegrationTestCase {
	private function newHelper( $page ): HtmlMessageOutputHelper {
		return new HtmlMessageOutputHelper( $page );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper::init
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper::getHtml
	 */
	public function testGetHtml() {
		$page = $this->getNonexistingTestPage( 'MediaWiki:Logouttext' );

		$helper = $this->newHelper( $page );

		$this->assertSame( 0, $page->getLatest() );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'You are now logged out', $htmlresult );
		// Check that we have a full HTML document in English
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( 'content="en"', $htmlresult );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper::init
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper::getETag
	 */
	public function testGetETag() {
		$page = $this->getNonexistingTestPage( 'MediaWiki:Logouttext' );

		$helper = $this->newHelper( $page );

		$etag = $helper->getETag();

		$this->assertStringContainsString( '"message/', $etag );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper::init
	 * @covers \MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper::getHtml
	 */
	public function testGetHtmlWithLanguageCode() {
		$page = $this->getNonexistingTestPage( 'MediaWiki:Logouttext/de' );

		$helper = $this->newHelper( $page );

		$this->assertSame( 0, $page->getLatest() );

		$htmlresult = $helper->getHtml()->getRawText();

		$this->assertStringContainsString( 'Du bist nun abgemeldet', $htmlresult );
		// Check that we have a full HTML document in English
		$this->assertStringContainsString( '<html', $htmlresult );
		$this->assertStringContainsString( 'content="de"', $htmlresult );
	}
}
