<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialMylog
 * @group Database
 */
class SpecialMylogTest extends RedirectSpecialPageThatRequiresLoginTestBase {
	use TempUserTestTrait;

	/** @dataProvider provideRedirectsToSpecialPage */
	public function testRedirectsToSpecialPage( string $sourceSubpage ): void {
		$context = RequestContext::getMain();
		$context->setRequest( new FauxRequest( [
			'page' => 'Test',
			'ignored' => 'yes',
		] ) );
		$context->setUser( $this->getTestUser()->getUser() );

		[ $html ] = $this->executeSpecialPage( $sourceSubpage, null, null, null, false, $context );

		$expectedSpecialPageTarget = SpecialPage::getTitleFor(
			'Log',
			$sourceSubpage === '' ?
				$context->getUser()->getName() :
				$sourceSubpage . '/' . $context->getUser()->getName()
		);
		$this->assertSame(
			$expectedSpecialPageTarget->getFullUrlForRedirect( [ 'page' => 'Test' ] ),
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

	public function testGetSubpagesForPrefixSearch(): void {
		$this->overrideConfigValue( MainConfigNames::LogTypes, [ 'test', 'testing' ] );
		$this->assertSame(
			[ 'all', 'test', 'testing' ],
			$this->newSpecialPage()->getSubpagesForPrefixSearch()
		);
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Mylog' );
	}
}
