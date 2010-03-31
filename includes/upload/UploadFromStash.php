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
		$sessionData = $request->getSessionData( self::SESSION_KEYNAME );
		return self::isValidSessionKey(
			$request->getInt( 'wpSessionKey' ),
			$sessionData
		);
	}

	public function initialize( $name, $sessionKey, $sessionData ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */

			$this->initializePathInfo( $name,
				$this->getRealPath ( $sessionData['mTempPath'] ),
				$sessionData['mFileSize'],
				false
			);
			
			$this->mSessionKey = $sessionKey;
			$this->mVirtualTempPath = $sessionData['mTempPath'];
			$this->mFileProps = $sessionData['mFileProps'];
	}

	public function initializeFromRequest( &$request ) {
		$sessionKey = $request->getInt( 'wpSessionKey' );
		$sessionData = $request->getSessionData( self::SESSION_KEYNAME );

		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );
		return $this->initialize( $desiredDestName, $sessionKey, $sessionData[$sessionKey] );
	}

	/**
	 * File has been previously verified so no need to do so again.
	 */
	protected function verifyFile() {
		return true;
	}


	/**
	 * There is no need to stash the image twice
	 */
	public function stashSession() {
		if ( !empty( $this->mSessionKey ) )
			return $this->mSessionKey;
		return parent::stashSession();
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @return success
	 */
	public function unsaveUploadedFile() {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$success = $repo->freeTemp( $this->mVirtualTempPath );
		return $success;
	}

}