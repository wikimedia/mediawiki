<?php

use MediaWiki\MediaWikiServices;

class SpecialPasswordResetTest extends FormSpecialPageTestCase {
	/**
	 * {@inheritdoc}
	 */
	protected function newSpecialPage() {
		return new SpecialPasswordReset(
			MediaWikiServices::getInstance()->getPasswordReset()
		);
	}
}
