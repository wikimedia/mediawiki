<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Specials\SpecialRandomRootPage
 * @covers \MediaWiki\Specials\SpecialRandomPage
 * @group Database
 */
class SpecialRandomRootPageTest extends SpecialPageTestBase {

	public function testExecutePicksRootPageAsRedirectTarget(): void {
		$this->editPage( 'Test', 'Test content' );
		$this->editPage( 'TestRedirect', '#REDIRECT [[Test]]' );
		$this->editPage( 'Test/test', 'Test content for subpage' );

		$context = RequestContext::getMain();
		[ $html ] = $this->executeSpecialPage( '', null, null, null, false, $context );

		$this->assertSame(
			Title::makeTitle( NS_MAIN, 'Test' )->getFullUrlForRedirect(),
			$context->getOutput()->getRedirect()
		);
		$this->assertSame( '', $html );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Randomrootpage' );
	}
}
