<?php

/**
 * @covers SpecialCreateAccount
 */
class SpecialCreateAccountTest extends SpecialPageTestBase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialCreateAccount(
			$services->getAuthManager()
		);
	}

	public function testCheckPermissions() {
		$readOnlyMode = $this->getServiceContainer()->getReadOnlyMode();
		$readOnlyMode->setReason( 'Test' );

		$this->expectException( ErrorPageError::class );
		$specialPage = $this->newSpecialPage();
		$specialPage->checkPermissions();
	}
}
