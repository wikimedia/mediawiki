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
		$this->mInfo = $info;
	}
	
	static function newFromTitle( $title, $repo ) {
		$info = $repo->getImageInfo( $title );
		if( $info ) {
			return new ForeignAPIFile( $title, $repo, $info );
		} else {
			return null;
		}
	}
	
	// Dummy functions...
	public function exists() {
		return true;
	}
	
	public function getPath() {
		return false;
	}

	function transform( $params, $flags = 0 ) {
		if ( $this->repo->apiThumbCacheExpiry > 0 && $this->repo->apiThumbCacheDir ) {
			$thumbUrl = $this->repo->getThumbUrlFromCache(
				$this->getName(),
				isset( $params['width'] ) ? $params['width'] : -1,
				isset( $params['height'] ) ? $params['height'] : -1 );
		} else {
			$thumbUrl = $this->repo->getThumbUrl(
				$this->getName(),
				isset( $params['width'] ) ? $params['width'] : -1,
				isset( $params['height'] ) ? $params['height'] : -1 );
		}
		if( $thumbUrl ) {
			return $this->handler->getTransform( $this, 'bogus', $thumbUrl, $params );;
		}
		return false;
	}

	// Info we can get from API...
	public function getWidth( $page = 1 ) {
		return intval( @$this->mInfo['width'] );
	}
	
	public function getHeight( $page = 1 ) {
		return intval( @$this->mInfo['height'] );
	}
	
	public function getMetadata() {
		return serialize( (array)@$this->mInfo['metadata'] );
	}
	
	public function getSize() {
		return intval( @$this->mInfo['size'] );
	}
	
	public function getUrl() {
		return strval( @$this->mInfo['url'] );
	}

	public function getUser( $method='text' ) {
		return strval( @$this->mInfo['user'] );
	}
	
	public function getDescription() {
		return strval( @$this->mInfo['comment'] );
	}

	function getSha1() {
		return wfBaseConvert( strval( @$this->mInfo['sha1'] ), 16, 36, 31 );
	}
	
	function getTimestamp() {
		return wfTimestamp( TS_MW, strval( @$this->mInfo['timestamp'] ) );
	}
	
	function getMimeType() {
		if( empty( $info['mime'] ) ) {
			$magic = MimeMagic::singleton();
			$info['mime'] = $magic->guessTypesForExtension( $this->getExtension() );
		}
		return $info['mime'];
	}
	
	/// @fixme May guess wrong on file types that can be eg audio or video
	function getMediaType() {
		$magic = MimeMagic::singleton();
		return $magic->getMediaType( null, $this->getMimeType() );
	}
	
	function getDescriptionUrl() {
		return isset( $this->mInfo['descriptionurl'] )
			? $this->mInfo['descriptionurl']
			: false;
	}
	
	/**
	 * Only useful if we're locally caching thumbs anyway...
	 */
	function getThumbPath( $suffix = '' ) {
		$ret = null;
		if ( $this->repo->apiThumbCacheExpiry > 0 && $this->repo->apiThumbCacheDir ) {
			global $wgUploadDirectory;
			$path = $wgUploadDirectory . '/' . $this->repo->apiThumbCacheDir . '/' . $this->repo->name . '/';
			if ( $suffix ) {
				$path = $path . $suffix . '/';
			}
			return $path;
		}
		else {
			return null;	
		}
	}
	
	function getThumbnails() {
		$files = array();
		$dir = $this->getThumbPath( $this->getName() );
		if ( is_dir( $dir ) ) {
			$handle = opendir( $dir );
			if ( $handle ) {
				while ( false !== ( $file = readdir($handle) ) ) {
					if ( $file{0} != '.'  ) {
						$files[] = $file;
					}
				}
				closedir( $handle );
			}
		}
		return $files;
	}
	
	function purgeCache() {
		$this->purgeThumbnails();
		$this->purgeDescriptionPage();
	}
	
	function purgeDescriptionPage() {
		global $wgMemc;
		$url = $this->repo->getDescriptionRenderUrl( $this->getName() );
		$key = wfMemcKey( 'RemoteFileDescription', 'url', md5($url) );
		$wgMemc->delete( $key );
	}
	
	function purgeThumbnails() {
		global $wgMemc;
		$key = wfMemcKey( 'ForeignAPIRepo', 'ThumbUrl', $this->getName() );
		$wgMemc->delete( $key );
		$files = $this->getThumbnails();
		$dir = $this->getThumbPath( $this->getName() );
		foreach ( $files as $file ) {
			unlink( $dir . $file );
		}
		if ( is_dir( $dir ) ) {
			rmdir( $dir ); // Might have already gone away, spews errors if we don't.
		}
	}
}
