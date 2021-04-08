<?php

use MediaWiki\MediaWikiServices;

class SpecialPasswordResetTest extends FormSpecialPageTestCase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialPasswordReset(
			MediaWikiServices::getInstance()->getPasswordReset()
		);
	}
}
