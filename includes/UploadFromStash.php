<?php

class UploadFromStash extends UploadFromBase {
	function initialize( &$sessionData ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */

			$this->mTempPath         = $sessionData['mTempPath'];
			$this->mFileSize         = $sessionData['mFileSize'];
			$this->mSrcName          = $sessionData['mSrcName'];
			$this->mFileProps        = $sessionData['mFileProps'];
			$this->mStashed          = true;
			$this->mRemoveTempFile   = false;
	}
	
	/*
	 * File has been previously verified so no need to do so again.
	 */
	protected function verifyFile( $tmpfile, $extension ) {
		return true;
	}
	/*
	 * We're here from "ignore warnings anyway" so return just OK
	 */
	function checkWarnings( &$resultDetails ) {
		return self::OK;
	}
}
