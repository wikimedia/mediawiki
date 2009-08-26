<?php
/**
 * @file
 * @ingroup upload
 * 
 * Implements uploading from previously stored file.
 * 
 * @author Bryan Tong Minh
 */
 
class UploadFromStash extends UploadBase {
	public static function isValidSessionKey( $key, $sessionData ) {
		return !empty( $key ) && 
			is_array( $sessionData ) && 
			isset( $sessionData[$key] ) && 
			isset( $sessionData[$key]['version'] ) && 
			$sessionData[$key]['version'] == self::SESSION_VERSION;
	}

	public static function isValidRequest( $request ) {
		$sessionData = $request->getSessionData( 'wsUploadData' );
		return self::isValidSessionKey( 
			$request->getInt( 'wpSessionKey' ),
			$sessionData
		);
	}
	/*
	 * some $na vars for uploadBase method compatibility.
	 */
	public function initialize( $name, $sessionData, $na, $na2=false ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */
			parent::initialize( $name,
				$sessionData['mTempPath'],
				$sessionData['mFileSize'],
				false
			);

			$this->mFileProps = $sessionData['mFileProps'];
	}

	public function initializeFromRequest( &$request ) {
		$sessionKey = $request->getInt( 'wpSessionKey' );
		$sessionData = $request->getSessionData('wsUploadData');

		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );
		return $this->initialize( $desiredDestName, $sessionData[$sessionKey], false );
	}

	/**
	 * File has been previously verified so no need to do so again.
	 */
	protected function verifyFile() {
		return true;
	}

	/**
	 * We're here from "ignore warnings anyway" so return just OK
	 */
	public function checkWarnings() {
		return array();
	}

}