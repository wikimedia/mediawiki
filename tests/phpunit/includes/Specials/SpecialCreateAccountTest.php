<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Specials;

use MediaWiki\Auth\AbstractPreAuthenticationProvider;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialCreateAccount;
use TestLogger;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @covers \MediaWiki\Specials\SpecialCreateAccount
 * @group Database
 */
class SpecialCreateAccountTest extends SpecialPageTestBase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage( ?IContextSource $context = null ): SpecialCreateAccount {
		$services = $this->getServiceContainer();
		$context ??= RequestContext::getMain();
		$page = new SpecialCreateAccount(
			$services->getAuthManager(),
			$services->getFormatterFactory(),
			$services->getUserIdentityUtils()
		);
		$page->setContext( $context );
		$context->setTitle( $page->getPageTitle() );
		return $page;
	}

	public function testCheckPermissions(): void {
		$readOnlyMode = $this->getServiceContainer()->getReadOnlyMode();
		$readOnlyMode->setReason( 'Test' );
		$testLogger = new TestLogger( true, null, true );
		$this->setLogger( 'authevents', $testLogger );

		$specialPage = $this->newSpecialPage();
		try {
			$specialPage->checkPermissions();
		} catch ( ErrorPageError ) {
			$buffer = $testLogger->getBuffer();
			$this->assertEquals( 'Account creation attempt', $buffer[0][1] );
			$this->assertEquals( 'readonlytext', $buffer[0][2]['status'] );
			$this->assertArrayHasKey( 'ua', $buffer[0][2] );
			return;
		}
		$this->fail( 'ErrorPageError exception was not thrown' );
	}

	/** @dataProvider provideCheckPermissionsWhenIpBlocked */
	public function testCheckPermissionsWhenIpBlocked( bool $createAccount, bool $expectError ): void {
		$this->setGroupPermissions( '*', 'createaccount', true );
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => RequestContext::getMain()->getRequest()->getIP(),
				'by' => $this->getTestSysop()->getUser(),
				'expiry' => 'infinity',
				'createAccount' => $createAccount,
			] );
		$testLogger = new TestLogger( true, null, true );
		$this->setLogger( 'authevents', $testLogger );

		$specialPage = $this->newSpecialPage();
		try {
			$specialPage->checkPermissions();
		} catch ( ErrorPageError ) {
			$this->assertTrue( $expectError, 'ErrorPageError exception was thrown' );
			$this->assertEquals( 'blockedtext', $testLogger->getBuffer()[0][2]['status'] );
			return;
		}
		$this->assertFalse( $expectError, 'ErrorPageError exception was not thrown' );
	}

	private function blockCurrentIpFromAccountCreation( array $query = [] ): void {
		$this->setGroupPermissions( '*', 'createaccount', true );
		$request = new FauxRequest( $query );
		RequestContext::getMain()->setRequest( $request );
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => $request->getIP(),
				'by' => $this->getTestSysop()->getUser(),
				'expiry' => 'infinity',
				'createAccount' => true,
			] );
	}

	public function testExecuteShowsErrorPageWithButtons(): void {
		$this->setUserLang( 'qqx' );
		$this->blockCurrentIpFromAccountCreation( [ 'returnto' => 'Some page', 'returntoquery' => 'foo=bar' ] );

		$specialPage = $this->newSpecialPage();
		$specialPage->execute( null );
		$output = $specialPage->getOutput();
		$html = $output->getHTML();

		$this->assertSame( '(createacct-error)', $output->getPageTitle() );
		$this->assertNull( DOMCompat::querySelector( DOMUtils::parseHTML( $html ), 'form' ) );
		$this->assertStringContainsString( '(blockedtext', $html );
		$this->assertStringNotContainsString( 'mw-returnto', $html );

		$links = $this->assertActionButtons( $html );
		$this->assertStringContainsString( 'returnto=Some+page', $links[0]->getAttribute( 'href' ) );
		$this->assertStringContainsString( 'Some_page', $links[1]->getAttribute( 'href' ) );
		$this->assertStringContainsString( 'foo=bar', $links[1]->getAttribute( 'href' ) );
	}

	/**
	 * Check that the page has the "Log in" and "Continue" buttons. Expects the qqx language.
	 *
	 * @param string $html
	 * @return Element[] The links of the buttons
	 */
	private function assertActionButtons( string $html ): array {
		$links = DOMCompat::querySelectorAll(
			DOMUtils::parseHTML( $html ), '.mw-createaccount-permissionerror-actions a'
		);
		$this->assertCount( 2, $links );

		$this->assertSame( '(createacct-permerror-login)', $links[0]->textContent );
		$this->assertStringContainsString( 'Special:UserLogin', $links[0]->getAttribute( 'href' ) );
		$this->assertStringContainsString( 'cdx-button--action-progressive', $links[0]->getAttribute( 'class' ) );

		$this->assertSame( '(createacct-permerror-return)', $links[1]->textContent );
		$this->assertStringNotContainsString( 'cdx-button--action-progressive', $links[1]->getAttribute( 'class' ) );

		return $links;
	}

	public static function provideCheckPermissionsWhenIpBlocked(): array {
		return [
			'Block prevents account creation' => [ true, true ],
			'Block allows account creation' => [ false, false ],
		];
	}

	/**
	 * Regression test for T360717 -- missing hidden fields from Special:CreateAccount
	 */
	public function testHiddenField(): void {
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

	public function testShouldShowTemporaryPasswordAndCreationReasonFieldsForRegisteredUser(): void {
		$user = $this->getTestUser()->getUser();

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $user );

		$specialPage = $this->newSpecialPage( $context );
		$specialPage->execute( null );
		$doc = self::getOutputHtml( $specialPage );

		$this->assertNotNull( $doc->getElementById( 'wpReason' ) );
		$this->assertNotNull( $doc->getElementById( 'wpCreateaccountMail' ) );
	}

	public function testShouldNotShowTemporaryPasswordAndCreationReasonFieldsForTempUser(): void {
		$req = new FauxRequest();
		$tempUser = $this->getServiceContainer()
			->getTempUserCreator()
			->create( null, $req )
			->getUser();

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $tempUser );

		$specialPage = $this->newSpecialPage( $context );
		$specialPage->execute( null );
		$doc = self::getOutputHtml( $specialPage );

		$this->assertNull(
			$doc->getElementById( 'wpReason' ),
			'Temporary users should not have to provide a reason for their account creation (T328718)'
		);
		$this->assertNull(
			$doc->getElementById( 'wpCreateaccountMail' ),
			'Temporary users should not have the option to have a temporary password sent on signup (T328718)'
		);
	}

	public function testShouldMarkEmailFieldAsOptional(): void {
		$this->overrideConfigValue( MainConfigNames::EmailConfirmToEdit, false );

		$specialPage = $this->newSpecialPage();
		$specialPage->execute( null );
		$doc = self::getOutputHtml( $specialPage );

		$this->assertNotNull(
			DOMCompat::querySelector( $doc, 'label[for="wpEmail"] .cdx-label__label__optional-flag' ),
			'The email address is optional, so its label must have the "(optional)" flag (T435145)'
		);
	}

	public function testShouldNotMarkEmailFieldAsOptionalWhenEmailIsRequired(): void {
		$this->overrideConfigValue( MainConfigNames::EmailConfirmToEdit, true );

		$specialPage = $this->newSpecialPage();
		$specialPage->execute( null );
		$doc = self::getOutputHtml( $specialPage );

		$this->assertNull(
			DOMCompat::querySelector( $doc, 'label[for="wpEmail"] .cdx-label__label__optional-flag' ),
			'The email address is required, so its label must not have the "(optional)" flag'
		);
	}

	/**
	 * Convenience function to get the parsed DOM of the HTML generated by the given special page.
	 * @param SpecialPage $page
	 * @return Document
	 */
	private static function getOutputHtml( SpecialPage $page ): Document {
		return DOMUtils::parseHTML( $page->getOutput()->getHTML() );
	}
}

class MockAuthRequestWithHiddenField extends AuthenticationRequest {
	public function getFieldInfo(): array {
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
	public function getAuthenticationRequests( $action, array $options ): array {
		return [ new MockAuthRequestWithHiddenField ];
	}
}
