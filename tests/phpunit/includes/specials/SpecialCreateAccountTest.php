<?php

use MediaWiki\Auth\AbstractPreAuthenticationProvider;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialCreateAccount;

/**
 * @covers \MediaWiki\Specials\SpecialCreateAccount
 * @group Database
 */
class SpecialCreateAccountTest extends SpecialPageTestBase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		$context = RequestContext::getMain();
		$page = new SpecialCreateAccount(
			$services->getAuthManager(),
			$services->getFormatterFactory()
		);
		$context->setTitle( $page->getPageTitle() );
		return $page;
	}

	public function testCheckPermissions() {
		$readOnlyMode = $this->getServiceContainer()->getReadOnlyMode();
		$readOnlyMode->setReason( 'Test' );

		$this->expectException( ErrorPageError::class );
		$specialPage = $this->newSpecialPage();
		$specialPage->checkPermissions();
	}

	/**
	 * Regression test for T360717 -- missing hidden fields from Special:CreateAccount
	 */
	public function testHiddenField() {
		$config = $this->getServiceContainer()->getMainConfig()->get( MainConfigNames::AuthManagerConfig );
		$config['preauth']['MockAuthProviderWithHiddenField'] = [
			'class' => MockAuthProviderWithHiddenField::class
		];
		$this->overrideConfigValue( MainConfigNames::AuthManagerConfig, $config );
		$specialPage = $this->newSpecialPage();
		$specialPage->execute( null );
		$html = $specialPage->getOutput()->getHTML();
		$this->assertStringContainsString(
			'<input id="mw-input-captchaId" name="captchaId" type="hidden" value="T360717">',
			$html
		);
	}
}

class MockAuthRequestWithHiddenField extends AuthenticationRequest {
	public function getFieldInfo() {
		return [
			'captchaId' => [
				'type' => 'hidden',
				'value' => 'T360717',
				'label' => '',
				'help' => '',
			],
		];
	}
}

class MockAuthProviderWithHiddenField extends AbstractPreAuthenticationProvider {
	public function getAuthenticationRequests( $action, array $options ) {
		return [ new MockAuthRequestWithHiddenField ];
	}
}
