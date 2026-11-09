<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialPrefixIndex
 * @group Database
 */
class SpecialPrefixIndexTest extends SpecialPageTestBase {

	public function testRedirectsMarkedOnListItem(): void {
		$this->editPage( 'Help:PrefixTest', 'Test content' );
		$this->editPage( 'Help:PrefixTestRedirect', '#REDIRECT [[Help:PrefixTest]]' );

		[ $html ] = $this->executeSpecialPage( 'PrefixTest', new FauxRequest( [ 'namespace' => NS_HELP ] ) );

		// Same markup as Special:AllPages (T444041)
		$redirectItem = $this->assertSelectorMatchesOneElement( $html, '.mw-prefixindex-list li.allpagesredirect' );
		$this->assertStringContainsString( 'PrefixTestRedirect', $redirectItem );
		$this->assertStringNotContainsString( '<div', $redirectItem );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Prefixindex' );
	}
}
