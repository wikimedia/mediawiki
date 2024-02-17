<?php

use MediaWiki\Specials\SpecialPasswordReset;
use MediaWiki\Tests\SpecialPage\FormSpecialPageTestCase;

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
