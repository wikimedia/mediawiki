<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialMypage
 * @group Database
 */
class SpecialMypageTest extends RedirectSpecialPageThatRequiresLoginTestBase {
	use TempUserTestTrait;

	/** @dataProvider provideRedirectsToSpecialPage */
	public function testRedirectsToSpecialPage( string $sourceSubpage ): void {
		$context = RequestContext::getMain();
		$context->setUser( $this->getTestUser()->getUser() );

		[ $html ] = $this->executeSpecialPage( $sourceSubpage, null, null, null, false, $context );

		$expectedPageTarget = Title::makeTitle(
			NS_USER,
			$sourceSubpage === '' ?
				$context->getUser()->getName() :
				$context->getUser()->getName() . '/' . $sourceSubpage
		);
		$this->assertSame(
			$expectedPageTarget->getFullUrlForRedirect(),
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
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Mypage' );
	}
}
