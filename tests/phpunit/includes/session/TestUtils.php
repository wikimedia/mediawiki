<?php

namespace MediaWiki\Session;

/**
 * Utility functions for Session unit tests
 */
class TestUtils {

	/**
	 * Override the singleton for unit testing
	 * @param SessionManager|null $manager
	 * @return \\ScopedCallback|null
	 */
	public static function setSessionManagerSingleton( SessionManager $manager = null ) {
		session_write_close();

		$rInstance = new \ReflectionProperty(
			'MediaWiki\\Session\\SessionManager', 'instance'
		);
		$rInstance->setAccessible( true );
		$rGlobalSession = new \ReflectionProperty(
			'MediaWiki\\Session\\SessionManager', 'globalSession'
		);
		$rGlobalSession->setAccessible( true );
		$rGlobalSessionRequest = new \ReflectionProperty(
			'MediaWiki\\Session\\SessionManager', 'globalSessionRequest'
		);
		$rGlobalSessionRequest->setAccessible( true );

		$oldInstance = $rInstance->getValue();

		$reset = array(
			array( $rInstance, $oldInstance ),
			array( $rGlobalSession, $rGlobalSession->getValue() ),
			array( $rGlobalSessionRequest, $rGlobalSessionRequest->getValue() ),
		);

		$rInstance->setValue( $manager );
		$rGlobalSession->setValue( null );
		$rGlobalSessionRequest->setValue( null );
		if ( $manager && PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( $manager );
		}

		return new \ScopedCallback( function () use ( &$reset, $oldInstance ) {
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
		$rc = new \ReflectionClass( 'MediaWiki\\Session\\SessionBackend' );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			\PHPUnit_Framework_Assert::markTestSkipped(
				'ReflectionClass::newInstanceWithoutConstructor isn\'t available'
			);
		}

		return $rc->newInstanceWithoutConstructor();
	}

	/**
	 * If you need a Session for testing but don't want to create a backend to
	 * construct one, use this.
	 * @param object $backend Object to serve as the SessionBackend
	 * @param int $index Index
	 * @return Session
	 */
	public static function getDummySession( $backend = null, $index = -1 ) {
		$rc = new \ReflectionClass( 'MediaWiki\\Session\\Session' );
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
		return $session;
	}

}
