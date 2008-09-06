<?php

class UploadFromUpload extends UploadBase {

	function initializeFromRequest( &$request ) {
		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );
		
		return $this->initialize( 
			$desiredDestName, 
			$request->getFileTempName( 'wpUploadFile' ), 
			$request->getFileSize( 'wpUploadFile' ) 
		);
	}
	
	static function isValidRequest( $request ) {
		return (bool)$request->getFileTempName( 'wpUploadFile' );
	}
}
