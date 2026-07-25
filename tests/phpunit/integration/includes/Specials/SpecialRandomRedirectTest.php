<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Specials\SpecialRandomRedirect
 * @covers \MediaWiki\Specials\SpecialRandomPage
 * @group Database
 */
class SpecialRandomRedirectTest extends SpecialPageTestBase {

	public function testExecutePicksRedirectAsRedirectTarget(): void {
		$this->editPage( 'Test', 'Test content' );
		$this->editPage( 'TestRedirect', '#REDIRECT [[Test]]' );
		$this->editPage( 'Test2', 'Test content for second page' );

		$context = RequestContext::getMain();
		[ $html ] = $this->executeSpecialPage( '', null, null, null, false, $context );

		$this->assertSame(
			Title::makeTitle( NS_MAIN, 'TestRedirect' )->getFullUrlForRedirect( [ 'redirect' => 'no' ] ),
			$context->getOutput()->getRedirect()
		);
		$this->assertSame( '', $html );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Randomredirect' );
	}
}
