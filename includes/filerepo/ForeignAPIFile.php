<?php

/** 
 * Very hacky and inefficient
 * do not use :D
 *
 * @ingroup FileRepo
 */
class ForeignAPIFile extends File {
	function __construct( $title, $repo, $info ) {
		parent::__construct( $title, $repo );
		
		// For some reason API doesn't currently provide type info
		$magic = MimeMagic::singleton();
		$info['mime'] = $magic->guessTypesForExtension( $this->getExtension() );
		list( $info['major_mime'], $info['minor_mime'] ) = self::splitMime( $info['mime'] );
		$info['media_type'] = $magic->getMediaType( null, $info['mime'] );
		
		$this->mInfo = $info;
	}
	
	// Dummy functions...
	public function exists() {
		return true;
	}
	
	public function getPath() {
		return false;
	}
	
	function getThumbPath( $suffix = false ) {
		return false; // hrmmm
	}
	
	function getThumbUrl( $suffix = false ) {
		return false; // FLKDSJLKFDJS
	}
	
	// Info we can get from API...
	public function getWidth( $page = 1 ) {
		return intval( $this->mInfo['width'] );
	}
	
	public function getHeight( $page = 1 ) {
		return intval( $this->mInfo['height'] );
	}
	
	public function getMetadata() {
		return $this->mInfo['metadata'];
	}
	
	public function getSize() {
		return intval( $this->mInfo['size'] );
	}
	
	public function getUrl() {
		return $this->mInfo['url'];
	}

	public function getUser( $method='text' ) {
		return $this->mInfo['user'];
	}
	
	public function getComment() {
		return $this->mInfo['comment'];
	}

	// Info we had to guess...
	function getMimeType() {
		return $this->mInfo['mime'];
	}
	
	function getMediaType() {
		return $this->mInfo['media_type'];
	}
}
