<?php

use MediaWiki\Specials\SpecialPasswordReset;

class SpecialPasswordResetTest extends FormSpecialPageTestCase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialPasswordReset(
			$this->getServiceContainer()->getPasswordReset()
		);
	}
}
