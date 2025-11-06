<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialUserLogout;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use TestUser;

/**
 * @covers \MediaWiki\Specials\SpecialUserLogout
 * @group Database
 */
class SpecialUserLogoutTest extends SpecialPageTestBase {

	use TempUserTestTrait;

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		return new SpecialUserLogout( $this->getServiceContainer()->getTempUserConfig() );
	}

	public function testUserLogoutComplete() {
		$oldName = __METHOD__;
		$user = ( new TestUser( $oldName ) )->getUser();

		$session = RequestContext::getMain()->getRequest()->getSession();
		$session->setUser( $user );
		$fauxRequest = new FauxRequest(
			[ 'wpEditToken' => $session->getToken( 'logoutToken' ) ],
			/* $wasPosted= */ true,
			$session
		);

		$oldNameInHook = null;
		$this->setTemporaryHook(
			'UserLogoutComplete',
			static function ( $user, $injected_html, $oldName ) use ( &$oldNameInHook ) {
				$oldNameInHook = $oldName;
			}
		);

		[ $html ] = $this->executeSpecialPage( '', $fauxRequest, 'qqx', $user, true );
		// Check that the page title and page content are as expected for a normal user logout
		$this->assertStringContainsString( '(logouttext:', $html );
		$this->assertStringContainsString( '(userlogout)', $html );

		$this->assertEquals(
			$oldName,
			$oldNameInHook,
			'old name in UserLogoutComplete hook was incorrect'
		);
	}

	public function testExecuteForTemporaryAccount() {
		$this->enableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();

		$session = RequestContext::getMain()->getRequest()->getSession();
		$session->setUser( $user );
		$fauxRequest = new FauxRequest( [ 'wpEditToken' => $session->getToken( 'logoutToken' ) ], true, $session );

		[ $html ] = $this->executeSpecialPage( '', $fauxRequest, 'qqx', $user, true );
		// Check that the page title and page content are as expected for the temporary account logout
		$this->assertStringContainsString( '(logouttext-for-temporary-account:', $html );
		$this->assertStringContainsString( '(templogout)', $html );
	}

	public function testViewForTemporaryAccountAfterApiLogout() {
		$user = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );

		$fauxRequest = new FauxRequest( [ 'wasTempUser' => 1 ] );

		[ $html ] = $this->executeSpecialPage( '', $fauxRequest, 'qqx', $user, true );
		// Check that the page title and page content are as expected for the temporary account logout
		$this->assertStringContainsString( '(logouttext-for-temporary-account:', $html );
		$this->assertStringContainsString( '(templogout)', $html );
	}

	public function testViewForTemporaryAccount() {
		$this->enableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();

		[ $html ] = $this->executeSpecialPage( '', null, 'qqx', $user, true );
		// Check that the page title is as expected for a temporary account and that the submit button is present
		$this->assertStringContainsString( '(templogout)', $html );
		$this->assertStringNotContainsString( '(userlogout-continue)', $html );
		$this->assertStringContainsString( '(userlogout-submit)', $html );
	}

	public function testViewForNamedAccount() {
		$user = $this->getTestUser()->getUser();

		[ $html ] = $this->executeSpecialPage( '', null, 'qqx', $user, true );

		$this->assertStringNotContainsString( '(templogout)', $html );
		$this->assertStringContainsString( '(userlogout-continue)', $html );
		$this->assertStringContainsString( '(userlogout-submit)', $html );
	}
}
