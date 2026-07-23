<?php

namespace MediaWiki\Tests\Integration\Specials;

/**
 * @covers \MediaWiki\Specials\SpecialEditPage
 * @covers \MediaWiki\SpecialPage\SpecialRedirectWithAction
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialEditPageTest extends SpecialRedirectWithActionTestBase {

	/** @inheritDoc */
	protected function getAction(): string {
		return 'edit';
	}

	/** @inheritDoc */
	protected function getMsgPrefix(): string {
		return 'editpage';
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'EditPage' );
	}
}
