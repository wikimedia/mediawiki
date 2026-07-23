<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Title\Title;

/**
 * A base class for tests of special pages that extend {@link SpecialRedirectWithAction}. Used to
 * test the common functons of the special page
 */
abstract class SpecialRedirectWithActionTestBase extends SpecialPageTestBase {

	/**
	 * Returns the name of the action parameter being redirected to by the special page
	 */
	abstract protected function getAction(): string;

	/**
	 * Returns the prefix for messages used when displaying a form on the special page
	 */
	abstract protected function getMsgPrefix(): string;

	public function testRedirectsToAction(): void {
		$context = RequestContext::getMain();
		$context->setRequest( new FauxRequest( [
			'uselang' => 'de',
			'ignored' => 'abc',
		] ) );

		[ $html ] = $this->executeSpecialPage( 'Test', null, null, null, false, $context );

		$this->assertSame(
			wfAppendQuery(
				$this->getServiceContainer()->getMainConfig()->get( MainConfigNames::Script ),
				[ 'uselang' => 'de', 'title' => 'Test', 'action' => $this->getAction() ]
			),
			$context->getOutput()->getRedirect(),
			'Did not redirect to the expected action'
		);
		$this->assertSame( '', $html );
	}

	public function testDisplaysFormWithNoSubpage(): void {
		$context = RequestContext::getMain();
		$context->setLanguage( 'qqx' );

		[ $html ] = $this->executeSpecialPage( '', null, null, null, false, $context );

		$this->assertSame(
			'',
			$context->getOutput()->getRedirect(),
			'Did not redirect when no target specified in URL'
		);

		$formHtml = $this->assertSelectorMatchesOneElement( $html, '.mw-htmlform-ooui' );

		$this->assertStringContainsString( '(special' . $this->getMsgPrefix() . '-page)', $formHtml );
		$this->assertSelectorMatchesOneElement( $formHtml, 'input[type="text"][name="page"]' );

		$this->assertStringContainsString( '(special' . $this->getMsgPrefix() . '-submit)', $formHtml );
	}

	public function testSubmitRedirectForm(): void {
		$context = RequestContext::getMain();
		$context->setRequest( new FauxRequest( [ 'page' => 'Test' ], true ) );

		[ $html ] = $this->executeSpecialPage( '', null, null, null, false, $context );

		$this->assertSame(
			Title::newFromText( 'Test' )->getFullUrlForRedirect( [ 'action' => $this->getAction() ] ),
			$context->getOutput()->getRedirect(),
			'Did not redirect to the expected action'
		);
		$this->assertSame( '', $html );
	}
}
