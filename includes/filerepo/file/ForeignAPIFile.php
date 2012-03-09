<?php
/**
 * Foreign file accessible through api.php requests.
 *
 * @file
 * @ingroup FileAbstraction
 */

/**
 * Foreign file accessible through api.php requests.
 * Very hacky and inefficient, do not use :D
 *
 * @ingroup FileAbstraction
 */
class ForeignAPIFile extends File {
	private $mExists;

	protected $repoClass = 'ForeignApiRepo';

	/**
	 * @param $title
	 * @param $repo ForeignApiRepo
	 * @param $info
	 * @param bool $exists
	 */
	function __construct( $title, $repo, $info, $exists = false ) {
		parent::__construct( $title, $repo );

		$this->mInfo = $info;
		$this->mExists = $exists;

		$this->assertRepoDefined();
	}

	/**
	 * @param $title Title
	 * @param $repo ForeignApiRepo
	 * @return ForeignAPIFile|null
	 */
	static function newFromTitle( Title $title, $repo ) {
		$data = $repo->fetchImageQuery( array(
			'titles' => 'File:' . $title->getDBKey(),
			'iiprop' => self::getProps(),
			'prop'   => 'imageinfo',
			'iimetadataversion' => MediaHandler::getMetadataVersion()
		) );

		$info = $repo->getImageInfo( $data );

		if( $info ) {
			$lastRedirect = isset( $data['query']['redirects'] )
				? count( $data['query']['redirects'] ) - 1
				: -1;
			if( $lastRedirect >= 0 ) {
				$newtitle = Title::newFromText( $data['query']['redirects'][$lastRedirect]['to']);
				$img = new self( $newtitle, $repo, $info, true );
				if( $img ) {
					$img->redirectedFrom( $title->getDBkey() );
				}
			} else {
				$img = new self( $title, $repo, $info, true );
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

		// Note, the this->canRender() check above implies
		// that we have a handler, and it can do makeParamString.
		$otherParams = $this->handler->makeParamString( $params );

		$thumbUrl = $this->repo->getThumbUrlFromCache(
			$this->getName(),
			isset( $params['width'] ) ? $params['width'] : -1,
			isset( $params['height'] ) ? $params['height'] : -1,
			$otherParams );
		return $this->handler->getTransform( $this, 'bogus', $thumbUrl, $params );
	}

	// Info we can get from API...
	public function getWidth( $page = 1 ) {
		return isset( $this->mInfo['width'] ) ? intval( $this->mInfo['width'] ) : 0;
	}

	/**
	 * @param $page int
	 * @return int
	 */
	public function getHeight( $page = 1 ) {
		return isset( $this->mInfo['height'] ) ? intval( $this->mInfo['height'] ) : 0;
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
		return isset( $this->mInfo['sha1'] )
			? wfBaseConvert( strval( $this->mInfo['sha1'] ), 16, 36, 31 )
			: null;
	}

	function getTimestamp() {
		return wfTimestamp( TS_MW,
			isset( $this->mInfo['timestamp'] )
				? strval( $this->mInfo['timestamp'] )
				: null
		);
	}

	function getMimeType() {
		if( !isset( $this->mInfo['mime'] ) ) {
			$magic = MimeMagic::singleton();
			$this->mInfo['mime'] = $magic->guessTypesForExtension( $this->getExtension() );
		}
		return $this->mInfo['mime'];
	}

	/// @todo FIXME: May guess wrong on file types that can be eg audio or video
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
			$path = $this->repo->getZonePath('thumb') . '/' . $this->getHashPath( $this->getName() );
			if ( $suffix ) {
				$path = $path . $suffix . '/';
			}
			return $path;
		} else {
			return null;
		}
	}

	function getThumbnails() {
		$dir = $this->getThumbPath( $this->getName() );
		$iter = $this->repo->getBackend()->getFileList( array( 'dir' => $dir ) );

		$files = array();
		foreach ( $iter as $file ) {
			$files[] = $file;
		}

		return $files;
	}

	/**
	 * @see File::purgeCache()
	 */
	function purgeCache( $options = array() ) {
		$this->purgeThumbnails( $options );
		$this->purgeDescriptionPage();
	}

	function purgeDescriptionPage() {
		global $wgMemc, $wgContLang;

		$url = $this->repo->getDescriptionRenderUrl( $this->getName(), $wgContLang->getCode() );
		$key = $this->repo->getLocalCacheKey( 'RemoteFileDescription', 'url', md5($url) );

		$wgMemc->delete( $key );
	}

	function purgeThumbnails( $options = array() ) {
		global $wgMemc;

		$key = $this->repo->getLocalCacheKey( 'ForeignAPIRepo', 'ThumbUrl', $this->getName() );
		$wgMemc->delete( $key );

		$files = $this->getThumbnails();
		// Give media handler a chance to filter the purge list
		$handler = $this->getHandler();
		if ( $handler ) {
			$handler->filterThumbnailPurgeList( $files, $options );
		}

		$dir = $this->getThumbPath( $this->getName() );
		$purgeList = array();
		foreach ( $files as $file ) {
			$purgeList[] = "{$dir}{$file}";
		}

		# Delete the thumbnails
		$this->repo->cleanupBatch( $purgeList, FileRepo::SKIP_LOCKING );
		# Clear out the thumbnail directory if empty
		$this->repo->getBackend()->clean( array( 'dir' => $dir ) );
	}
}
