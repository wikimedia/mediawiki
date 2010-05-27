<?php
/**
 * @file
 * @ingroup upload
 *
 * @author Bryan Tong Minh
 *
 * Implements regular file uploads
 */
class UploadFromFile extends UploadBase {


	function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getFileName( 'wpUploadFile' );
		return $this->initializePathInfo(
			$desiredDestName,
			$request->getFileTempName( 'wpUploadFile' ),
			$request->getFileSize( 'wpUploadFile' )
		);
	}
	/**
	 * Entry point for upload from file.
	 */
	function initialize( $name, $tempPath, $fileSize ) {
		 return $this->initializePathInfo( $name, $tempPath, $fileSize );
	}
	static function isValidRequest( $request ) {
		return (bool)$request->getFileTempName( 'wpUploadFile' );
	}
}
