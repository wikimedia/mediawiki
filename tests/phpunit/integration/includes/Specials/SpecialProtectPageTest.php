<?php

namespace MediaWiki\Tests\Integration\Specials;

/**
 * @covers \MediaWiki\Specials\SpecialProtectPage
 * @covers \MediaWiki\SpecialPage\SpecialRedirectWithAction
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialProtectPageTest extends SpecialRedirectWithActionTestBase {

	/** @inheritDoc */
	protected function getAction(): string {
		return 'protect';
	}

	/** @inheritDoc */
	protected function getMsgPrefix(): string {
		return 'protectpage';
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'ProtectPage' );
	}
}
