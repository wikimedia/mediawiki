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
	 * @return Session
	 */
	public function getSession( bool $csrfSafe = false ) {
		/** @var SessionProviderInterface|MockObject $session */
		$sessionProvider =
			$this->createNoOpMock( SessionProviderInterface::class, [ 'safeAgainstCsrf' ] );
		$sessionProvider->method( 'safeAgainstCsrf' )->willReturn( $csrfSafe );

		/** @var Session|MockObject $session */
		$session = $this->createNoOpMock( Session::class, [ 'getSessionId', 'getProvider' ] );
		$session->method( 'getSessionId' )->willReturn( new SessionId( 'test' ) );
		$session->method( 'getProvider' )->willReturn( $sessionProvider );

		return $session;
	}
}
