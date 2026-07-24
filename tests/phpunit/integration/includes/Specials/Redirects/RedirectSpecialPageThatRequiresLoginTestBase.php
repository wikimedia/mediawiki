<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * A base class that provides common tests for special pages that redirect elsewhere, but
 * require the user be logged in when temporary accounts are enabled.
 */
abstract class RedirectSpecialPageThatRequiresLoginTestBase extends SpecialPageTestBase {
	use TempUserTestTrait;

	public function testRequiresLoginWhenLoggedOut(): void {
		$this->enableAutoCreateTempUser();

		$context = RequestContext::getMain();
		$context->setUser( $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' ) );

		$this->expectException( UserNotLoggedIn::class );
		$this->executeSpecialPage( '', null, null, null, false, $context );
	}

	public function testGetRedirectForLoggedOutUser(): void {
		$this->enableAutoCreateTempUser();

		$context = RequestContext::getMain();
		$context->setUser( $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' ) );

		$this->assertFalse( $this->newSpecialPage()->getRedirect( '' ) );
	}
}
