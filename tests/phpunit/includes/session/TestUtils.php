<?php

namespace MediaWiki\Session;

use Psr\Log\LoggerInterface;

/**
 * Utility functions for Session unit tests
 */
class TestUtils {

	/**
	 * Override the singleton for unit testing
	 * @param SessionManager|null $manager
	 * @return \\Wikimedia\ScopedCallback|null
	 */
	public static function setSessionManagerSingleton( SessionManager $manager = null ) {
		session_write_close();

		$rInstance = new \ReflectionProperty(
			SessionManager::class, 'instance'
		);
		$rInstance->setAccessible( true );
		$rGlobalSession = new \ReflectionProperty(
			SessionManager::class, 'globalSession'
		);
		$rGlobalSession->setAccessible( true );
		$rGlobalSessionRequest = new \ReflectionProperty(
			SessionManager::class, 'globalSessionRequest'
		);
		$rGlobalSessionRequest->setAccessible( true );

		$oldInstance = $rInstance->getValue();

		$reset = [
			[ $rInstance, $oldInstance ],
			[ $rGlobalSession, $rGlobalSession->getValue() ],
			[ $rGlobalSessionRequest, $rGlobalSessionRequest->getValue() ],
		];

		$rInstance->setValue( $manager );
		$rGlobalSession->setValue( null );
		$rGlobalSessionRequest->setValue( null );
		if ( $manager && PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( $manager );
		}

		return new \Wikimedia\ScopedCallback( function () use ( &$reset, $oldInstance ) {
			foreach ( $reset as &$arr ) {
				$arr[0]->setValue( $arr[1] );
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
		$rc = new \ReflectionClass( SessionBackend::class );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			\PHPUnit_Framework_Assert::markTestSkipped(
				'ReflectionClass::newInstanceWithoutConstructor isn\'t available'
			);
		}

		$ret = $rc->newInstanceWithoutConstructor();
		\TestingAccessWrapper::newFromObject( $ret )->logger = new \TestLogger;
		return $ret;
	}

	/**
	 * If you need a Session for testing but don't want to create a backend to
	 * construct one, use this.
	 * @param object $backend Object to serve as the SessionBackend
	 * @param int $index Index
	 * @param LoggerInterface $logger
	 * @return Session
	 */
	public static function getDummySession( $backend = null, $index = -1, $logger = null ) {
		$rc = new \ReflectionClass( Session::class );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			\PHPUnit_Framework_Assert::markTestSkipped(
				'ReflectionClass::newInstanceWithoutConstructor isn\'t available'
			);
		}

		if ( $backend === null ) {
			$backend = new DummySessionBackend;
		}

		$session = $rc->newInstanceWithoutConstructor();
		$priv = \TestingAccessWrapper::newFromObject( $session );
		$priv->backend = $backend;
		$priv->index = $index;
		$priv->logger = $logger ?: new \TestLogger;
		return $session;
	}

}
