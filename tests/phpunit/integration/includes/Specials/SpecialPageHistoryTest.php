<?php

namespace MediaWiki\Tests\Integration\Specials;

/**
 * @covers \MediaWiki\Specials\SpecialPageHistory
 * @covers \MediaWiki\SpecialPage\SpecialRedirectWithAction
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialPageHistoryTest extends SpecialRedirectWithActionTestBase {

	/** @inheritDoc */
	protected function getAction(): string {
		return 'history';
	}

	/** @inheritDoc */
	protected function getMsgPrefix(): string {
		return 'pagehistory';
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'PageHistory' );
	}
}
