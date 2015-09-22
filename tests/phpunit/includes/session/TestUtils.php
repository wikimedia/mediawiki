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

}
