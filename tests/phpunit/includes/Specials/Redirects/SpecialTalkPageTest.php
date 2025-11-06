<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\Redirects\SpecialTalkPage;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialTalkPage
 *
 * @license GPL-2.0-or-later
 */
class SpecialTalkPageTest extends MediaWikiIntegrationTestCase {

	private function executeSpecialPageAndGetOutput(
		string $subpage = '',
		?string $target = null
	): OutputPage {
		$services = $this->getServiceContainer();
		$context = new RequestContext();
		if ( $target !== null ) {
			$request = new FauxRequest( [ 'target' => $target ], true );
		} else {
			$request = new FauxRequest();
		}
		$context->setRequest( $request );
		$context->setTitle( Title::newFromText( 'Special:TalkPage' ) );
		$context->setLanguage( $services->getLanguageFactory()->getLanguage( 'qqx' ) );
		$page = new SpecialTalkPage( $services->getTitleParser() );
		$page->setContext( $context );

		$page->execute( $subpage );

		return $page->getOutput();
	}

	/** @dataProvider provideRedirects */
	public function testRedirect( $subpage, $target, $expectedUrl, $expectedRedirect ) {
		$output = $this->executeSpecialPageAndGetOutput( $subpage, $target );

		$this->assertSame( $expectedUrl, $output->getRedirect(), 'should redirect to URL' );

		$this->assertHTMLEquals( $expectedRedirect, $output->getHTML(), 'redirect should contain appropriate HTML' );
	}

	public static function provideRedirects() {
		$subjectTitleText = 'MediaWiki:ok';
		$subjectTitle = Title::newFromText( $subjectTitleText );
		$talkTitle = $subjectTitle->getTalkPageIfDefined();
		$talkUrl = $talkTitle->getFullUrlForRedirect();

		$standardRedirectHTML = "<div class=\"mw-specialpage-summary\">\n<p>(talkpage-summary)\n</p>\n</div>";

		yield [ $subjectTitleText, null, $talkUrl, $standardRedirectHTML ];
		yield [ '', $subjectTitleText, $talkUrl, $standardRedirectHTML ];
	}

	/** @dataProvider provideNoRedirects */
	public function testNoRedirect( $subpage, $target, ...$expectedHtmls ) {
		$output = $this->executeSpecialPageAndGetOutput( $subpage, $target );

		$this->assertSame( '', $output->getRedirect(), 'should not redirect' );
		foreach ( $expectedHtmls as $expectedHtml ) {
			$this->assertStringContainsString(
				$expectedHtml,
				$output->getHTML(),
				'should contain HTML'
			);
		}
		$this->assertStringContainsString(
			'<form',
			$output->getHTML(),
			'should contain form'
		);
	}

	public static function provideNoRedirects() {
		yield [ '', null ];
		yield [ 'Special:TalkPage', null, 'title-invalid-talk-namespace', "value='Special:TalkPage'" ];
		yield [ '', 'Special:TalkPage', 'title-invalid-talk-namespace', "value='Special:TalkPage'" ];
		yield [ '', '<>', 'title-invalid-characters', "value='&lt;&gt;'" ];
	}

}
