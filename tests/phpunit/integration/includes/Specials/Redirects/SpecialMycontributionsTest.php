<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialMycontributions
 * @group Database
 */
class SpecialMycontributionsTest extends RedirectSpecialPageThatRequiresLoginTestBase {
	use TempUserTestTrait;

	/** @dataProvider provideRedirectsToSpecialPage */
	public function testRedirectsToSpecialPage( string $sourceSubpage ): void {
		$context = RequestContext::getMain();
		$context->setRequest( new FauxRequest( [
			'limit' => '123',
			'year' => '2026',
			'ignored' => 'yes',
		] ) );
		$context->setUser( $this->getTestUser()->getUser() );

		[ $html ] = $this->executeSpecialPage( $sourceSubpage, null, null, null, false, $context );

		$expectedSpecialPageTarget = SpecialPage::getTitleFor(
			'Contributions',
			$context->getUser()->getName()
		);
		$this->assertSame(
			$expectedSpecialPageTarget->getFullUrlForRedirect( [ 'limit' => '123', 'year' => '2026' ] ),
			$context->getOutput()->getRedirect(),
			'Did not redirect to the expected action'
		);
		$this->assertSame( '', $html );
	}

	public static function provideRedirectsToSpecialPage(): array {
		return [
			'Source special page has no subpage' => [ 'sourceSubpage' => '' ],
			'Source special page has subpage' => [ 'sourceSubpage' => '' ],
		];
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Mycontributions' );
	}
}
