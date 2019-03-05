<?php

class SpecialPasswordResetTest extends FormSpecialPageTestCase {
	/**
	 * {@inheritdoc}
	 */
	protected function newSpecialPage() {
		return new SpecialPasswordReset();
	}
}
