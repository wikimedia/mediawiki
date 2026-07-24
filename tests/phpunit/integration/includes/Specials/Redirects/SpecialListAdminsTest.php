<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialListAdmins
 * @covers \MediaWiki\SpecialPage\SpecialRedirectToSpecial
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialListAdminsTest extends SpecialRedirectToSpecialTestBase {

	/** @inheritDoc */
	protected function getRedirectName(): string {
		return 'Listusers';
	}

	/** @inheritDoc */
	protected function getRedirectSubpage(): string {
		return 'sysop';
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Listadmins' );
	}
}
