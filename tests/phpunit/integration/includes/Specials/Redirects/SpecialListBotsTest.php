<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

/**
 * @covers \MediaWiki\Specials\Redirects\SpecialListBots
 * @covers \MediaWiki\SpecialPage\SpecialRedirectToSpecial
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialListBotsTest extends SpecialRedirectToSpecialTestBase {

	/** @inheritDoc */
	protected function getRedirectName(): string {
		return 'Listusers';
	}

	/** @inheritDoc */
	protected function getRedirectSubpage(): string {
		return 'bot';
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Listbots' );
	}
}
