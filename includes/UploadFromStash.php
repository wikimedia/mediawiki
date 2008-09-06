<?php

class UploadFromStash extends UploadBase {
	static function isValidSessionKey( $key, $sessionData ) {
		return !empty( $key ) && 
			is_array( $sessionData ) && 
			isset( $sessionData[$key] ) && 
			isset( $sessionData[$key]['version'] ) && 
			$sessionData[$key]['version'] == self::SESSION_VERSION
		;
	}
	static function isValidRequest( $request ) {
		$sessionData = $request->getSessionData('wsUploadData');
		return self::isValidSessionKey( 
			$request->getInt( 'wpSessionKey' ),
			$sessionData
		);
	}
	
	function initialize( $name, $sessionData ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */
			$this->initialize( $name, 
				$sessionData['mTempPath'], 
				$sessionData['mFileSize'],
				false
			);

			$this->mFileProps        = $sessionData['mFileProps'];
	}
	function initializeFromRequest( &$request ) {
		$sessionKey = $request->getInt( 'wpSessionKey' );
		$sessionData = $request->getSessionData('wsUploadData');
		
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );
			
		return $this->initialize( $desiredDestName, $sessionData[$sessionKey] );
	}
	
	/*
	 * File has been previously verified so no need to do so again.
	 */
	protected function verifyFile( $tmpfile ) {
		return true;
	}
	/*
	 * We're here from "ignore warnings anyway" so return just OK
	 */
	function checkWarnings() {
		return array();
	}
}
