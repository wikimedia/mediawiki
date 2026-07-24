<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Tests\Integration\Specials\Redirects\SpecialRedirectToSpecialTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialChangePassword
 * @covers \MediaWiki\SpecialPage\SpecialRedirectToSpecial
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class SpecialChangePasswordTest extends SpecialRedirectToSpecialTestBase {

	/** @inheritDoc */
	protected function getRedirectName(): string {
		return 'ChangeCredentials';
	}

	/** @inheritDoc */
	protected function getRedirectSubpage(): string {
		return PasswordAuthenticationRequest::class;
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'ChangePassword' );
	}
}
