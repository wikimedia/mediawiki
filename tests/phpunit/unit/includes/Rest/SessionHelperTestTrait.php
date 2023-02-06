<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Session\Session;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * A trait for testing Handler classes.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 * @package MediaWiki\Tests\Rest
 */
trait SessionHelperTestTrait {
	/**
	 * @param bool $csrfSafe
	 * @return Session&MockObject
	 */
	public function getSession( bool $csrfSafe ) {
		/** @var SessionProviderInterface&MockObject $session */
		$sessionProvider = $this->createMock( SessionProviderInterface::class );
		$sessionProvider->method( 'safeAgainstCsrf' )->willReturn( $csrfSafe );

		/** @var Session&MockObject $session */
		$session = $this->createMock( Session::class );
		$session->method( 'getSessionId' )->willReturn( new SessionId( 'test' ) );
		$session->method( 'getProvider' )->willReturn( $sessionProvider );

		return $session;
	}
}
