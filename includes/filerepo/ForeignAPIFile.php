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

	function transform( $params, $flags = 0 ) {
		$thumbUrl = $this->repo->getThumbUrl(
			$this->getName(),
			isset( $params['width'] ) ? $params['width'] : -1,
			isset( $params['height'] ) ? $params['height'] : -1 );
		if( $thumbUrl ) {
			wfDebug( __METHOD__ . " got remote thumb $thumbUrl\n" );
			return $this->handler->getTransform( $this, 'bogus', $thumbUrl, $params );;
		}
		return false;
	}

	// Info we can get from API...
	public function getWidth( $page = 1 ) {
		return intval( $this->mInfo['width'] );
	}
	
	public function getHeight( $page = 1 ) {
		return intval( $this->mInfo['height'] );
	}
	
	public function getMetadata() {
		return serialize( (array)$this->mInfo['metadata'] );
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
	
	public function getDescription() {
		return $this->mInfo['comment'];
	}

	// Info we had to guess...
	function getMimeType() {
		return $this->mInfo['mime'];
	}
	
	function getMediaType() {
		return $this->mInfo['media_type'];
	}
	
	function getDescriptionUrl() {
		return isset( $this->mInfo['descriptionurl'] )
			? $this->mInfo['descriptionurl']
			: false;
	}
}
