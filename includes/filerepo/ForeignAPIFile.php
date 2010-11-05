<?php
/**
 * Foreign file accessible through api.php requests.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * Foreign file accessible through api.php requests.
 * Very hacky and inefficient, do not use :D
 *
 * @ingroup FileRepo
 */
class ForeignAPIFile extends File {
	
	private $mExists;
	
	function __construct( $title, $repo, $info, $exists = false ) {
		parent::__construct( $title, $repo );
		$this->mInfo = $info;
		$this->mExists = $exists;
	}
	
	static function newFromTitle( $title, $repo ) {
		$data = $repo->fetchImageQuery( array(
                        'titles' => 'File:' . $title->getDBKey(),
                        'iiprop' => self::getProps(),
                        'prop' => 'imageinfo' ) );

		$info = $repo->getImageInfo( $data );

		if( $data && $info) {
			if( isset( $data['query']['redirects'][0] ) ) {
				$newtitle = Title::newFromText( $data['query']['redirects'][0]['to']);
				$img = new ForeignAPIFile( $newtitle, $repo, $info, true );
				if( $img ) $img->redirectedFrom( $title->getDBkey() );
			} else {
				$img = new ForeignAPIFile( $title, $repo, $info, true );
			}
			return $img;
		} else {
			return null;
		}
	}
	
	/**
	 * Get the property string for iiprop and aiprop
	 */
	static function getProps() {
		return 'timestamp|user|comment|url|size|sha1|metadata|mime';
	}
	
	// Dummy functions...
	public function exists() {
		return $this->mExists;
	}
	
	public function getPath() {
		return false;
	}

	function transform( $params, $flags = 0 ) {
		if( !$this->canRender() ) {
			// show icon
			return parent::transform( $params, $flags );
		}
		$thumbUrl = $this->repo->getThumbUrlFromCache(
				$this->getName(),
				isset( $params['width'] ) ? $params['width'] : -1,
				isset( $params['height'] ) ? $params['height'] : -1 );
		return $this->handler->getTransform( $this, 'bogus', $thumbUrl, $params );
	}

	// Info we can get from API...
	public function getWidth( $page = 1 ) {
		return intval( @$this->mInfo['width'] );
	}
	
	public function getHeight( $page = 1 ) {
		return intval( @$this->mInfo['height'] );
	}
	
	public function getMetadata() {
		if ( isset( $this->mInfo['metadata'] ) ) {
			return serialize( self::parseMetadata( $this->mInfo['metadata'] ) );
		}
		return null;
	}
	
	public static function parseMetadata( $metadata ) {
		if( !is_array( $metadata ) ) {
			return $metadata;
		}
		$ret = array();
		foreach( $metadata as $meta ) {
			$ret[ $meta['name'] ] = self::parseMetadata( $meta['value'] );
		}
		return $ret;
	}
	
	public function getSize() {
		return isset( $this->mInfo['size'] ) ? intval( $this->mInfo['size'] ) : null;
	}
	
	public function getUrl() {
		return isset( $this->mInfo['url'] ) ? strval( $this->mInfo['url'] ) : null;
	}

	public function getUser( $method='text' ) {
		return isset( $this->mInfo['user'] ) ? strval( $this->mInfo['user'] ) : null;
	}
	
	public function getDescription() {
		return isset( $this->mInfo['comment'] ) ? strval( $this->mInfo['comment'] ) : null;
	}

	function getSha1() {
		return isset( $this->mInfo['sha1'] ) ? 
			wfBaseConvert( strval( $this->mInfo['sha1'] ), 16, 36, 31 ) : 
			null;
	}
	
	function getTimestamp() {
		return wfTimestamp( TS_MW, 
			isset( $this->mInfo['timestamp'] ) ?
			strval( $this->mInfo['timestamp'] ) : 
			null
		);
	}
	
	function getMimeType() {
		if( !isset( $this->mInfo['mime'] ) ) {
			$magic = MimeMagic::singleton();
			$this->mInfo['mime'] = $magic->guessTypesForExtension( $this->getExtension() );
		}
		return $this->mInfo['mime'];
	}
	
	/// @todo Fixme: may guess wrong on file types that can be eg audio or video
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
		if ( $this->repo->canCacheThumbs() ) {
			global $wgUploadDirectory;
			$path = $this->repo->getZonePath('thumb') . '/' . $this->getHashPath( $this->getName() );
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
		global $wgMemc, $wgContLang;
		$url = $this->repo->getDescriptionRenderUrl( $this->getName(), $wgContLang->getCode() );
		$key = $this->repo->getLocalCacheKey( 'RemoteFileDescription', 'url', md5($url) );
		$wgMemc->delete( $key );
	}
	
	function purgeThumbnails() {
		global $wgMemc;
		$key = $this->repo->getLocalCacheKey( 'ForeignAPIRepo', 'ThumbUrl', $this->getName() );
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
