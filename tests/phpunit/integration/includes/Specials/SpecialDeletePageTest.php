<?php

namespace MediaWiki\Tests\Integration\Specials;

/**
 * @covers \MediaWiki\Specials\SpecialDeletePage
 * @covers \MediaWiki\SpecialPage\SpecialRedirectWithAction
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialDeletePageTest extends SpecialRedirectWithActionTestBase {

	/** @inheritDoc */
	protected function getAction(): string {
		return 'delete';
	}

	/** @inheritDoc */
	protected function getMsgPrefix(): string {
		return 'deletepage';
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'DeletePage' );
	}
}
