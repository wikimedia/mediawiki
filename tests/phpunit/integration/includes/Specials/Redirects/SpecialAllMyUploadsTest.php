<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialAllMyUploads
 * @group Database
 */
class SpecialAllMyUploadsTest extends RedirectSpecialPageThatRequiresLoginTestBase {
	use TempUserTestTrait;

	public function testRedirectsToSpecialPage(): void {
		$context = RequestContext::getMain();
		$context->setRequest( new FauxRequest( [
			'limit' => '12',
			'ignored' => 'yes',
		] ) );
		$context->setUser( $this->getTestUser()->getUser() );

		[ $html ] = $this->executeSpecialPage( '', null, null, null, false, $context );

		$expectedSpecialPageTarget = SpecialPage::getTitleFor(
			'Listfiles',
			$context->getUser()->getName()
		);
		$this->assertSame(
			$expectedSpecialPageTarget->getFullUrlForRedirect( [ 'limit' => '12', 'ilshowall' => '1' ] ),
			$context->getOutput()->getRedirect(),
			'Did not redirect to the expected action'
		);
		$this->assertSame( '', $html );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'AllMyUploads' );
	}
}
