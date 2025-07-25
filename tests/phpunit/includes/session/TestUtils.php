<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\MediaWikiServices;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionManager;
use PHPUnit\Framework\Assert;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use TestLogger;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * Utility functions for Session unit tests
 */
class TestUtils {

	/**
	 * Override the singleton for unit testing
	 * @param SessionManager|null $manager
	 * @return ScopedCallback|null
	 */
	public static function setSessionManagerSingleton( ?SessionManager $manager = null ) {
		$services = MediaWikiServices::getInstance();
		session_write_close();

		$oldInstance = $services->getSessionManager();

		$services->resetServiceForTesting( 'SessionManager' );
		if ( $manager ) {
			$services->redefineService( 'SessionManager', static fn () => $manager );
		}
		if ( $manager && PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( $manager );
		}

		return new ScopedCallback( static function () use ( $services, $oldInstance ) {
			if ( $oldInstance ) {
				$services->resetServiceForTesting( 'SessionManager' );
				$services->redefineService( 'SessionManager', static fn () => $oldInstance );
			}
			if ( $oldInstance && PHPSessionHandler::isInstalled() ) {
				PHPSessionHandler::install( $oldInstance );
			}
		} );
	}

	/**
	 * If you need a SessionBackend for testing but don't want to create a real
	 * one, use this.
	 * @return SessionBackend Unconfigured! Use reflection to set any private
	 *  fields necessary.
	 */
	public static function getDummySessionBackend() {
		$rc = new ReflectionClass( SessionBackend::class );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			Assert::markTestSkipped(
				'ReflectionClass::newInstanceWithoutConstructor isn\'t available'
			);
		}

		$ret = $rc->newInstanceWithoutConstructor();
		TestingAccessWrapper::newFromObject( $ret )->logger = new TestLogger;
		return $ret;
	}

	/**
	 * If you need a Session for testing but don't want to create a backend to
	 * construct one, use this.
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object|null $backend Object to serve as the SessionBackend
	 * @param int $index
	 * @param LoggerInterface|null $logger
	 * @return Session
	 */
	public static function getDummySession( $backend = null, $index = -1, $logger = null ) {
		$rc = new ReflectionClass( Session::class );

		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = $backend ?? new DummySessionBackend();
		$priv->index = $index;
		$priv->logger = $logger ?? new TestLogger();
		return $session;
	}

}
