<?php
/**
 * Foreign repository accessible through api.php requests.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A foreign repository with a remote MediaWiki with an API thingy
 *
 * Example config:
 *
 * $wgForeignFileRepos[] = array(
 *   'class'                  => 'ForeignAPIRepo',
 *   'name'                   => 'shared',
 *   'apibase'                => 'http://en.wikipedia.org/w/api.php',
 *   'fetchDescription'       => true, // Optional
 *   'descriptionCacheExpiry' => 3600,
 * );
 *
 * @ingroup FileRepo
 */
class ForeignAPIRepo extends FileRepo {
	/* This version string is used in the user agent for requests and will help
	 * server maintainers in identify ForeignAPI usage.
	 * Update the version every time you make breaking or significant changes. */
	const VERSION = "2.1";

	var $fileFactory = array( 'ForeignAPIFile', 'newFromTitle' );
	/* Check back with Commons after a day */
	var $apiThumbCacheExpiry = 86400; /* 24*60*60 */
	/* Redownload thumbnail files after a month */
	var $fileCacheExpiry = 2592000; /* 86400*30 */

	protected $mQueryCache = array();
	protected $mFileExists = array();

	function __construct( $info ) {
		global $wgLocalFileRepo;
		parent::__construct( $info );

		// http://commons.wikimedia.org/w/api.php
		$this->mApiBase = isset( $info['apibase'] ) ? $info['apibase'] : null;

		if( isset( $info['apiThumbCacheExpiry'] ) ) {
			$this->apiThumbCacheExpiry = $info['apiThumbCacheExpiry'];
		}
		if( isset( $info['fileCacheExpiry'] ) ) {
			$this->fileCacheExpiry = $info['fileCacheExpiry'];
		}
		if( !$this->scriptDirUrl ) {
			// hack for description fetches
			$this->scriptDirUrl = dirname( $this->mApiBase );
		}
		// If we can cache thumbs we can guess sane defaults for these
		if( $this->canCacheThumbs() && !$this->url ) {
			$this->url = $wgLocalFileRepo['url'];
		}
		if( $this->canCacheThumbs() && !$this->thumbUrl ) {
			$this->thumbUrl = $this->url . '/thumb';
		}
	}

	/**
	 * Per docs in FileRepo, this needs to return false if we don't support versioned
	 * files. Well, we don't.
	 *
	 * @return File
	 */
	function newFile( $title, $time = false ) {
		if ( $time ) {
			return false;
		}
		return parent::newFile( $title, $time );
	}

	/**
	 * No-ops
	 */

	function storeBatch( $triplets, $flags = 0 ) {
		return false;
	}

	function storeTemp( $originalName, $srcPath ) {
		return false;
	}

	function concatenate( $fileList, $targetPath, $flags = 0 ){
		return false;
	}

	function append( $srcPath, $toAppendPath, $flags = 0 ){
		return false;
	}

	function appendFinish( $toAppendPath ){
		return false;
	}

	function publishBatch( $triplets, $flags = 0 ) {
		return false;
	}

	function deleteBatch( $sourceDestPairs ) {
		return false;
	}

	function fileExistsBatch( $files, $flags = 0 ) {
		$results = array();
		foreach ( $files as $k => $f ) {
			if ( isset( $this->mFileExists[$k] ) ) {
				$results[$k] = true;
				unset( $files[$k] );
			} elseif( self::isVirtualUrl( $f ) ) {
				# @todo FIXME: We need to be able to handle virtual
				# URLs better, at least when we know they refer to the
				# same repo.
				$results[$k] = false;
				unset( $files[$k] );
			}
		}

		$data = $this->fetchImageQuery( array( 'titles' => implode( $files, '|' ),
											'prop' => 'imageinfo' ) );
		if( isset( $data['query']['pages'] ) ) {
			$i = 0;
			foreach( $files as $key => $file ) {
				$results[$key] = $this->mFileExists[$key] = !isset( $data['query']['pages'][$i]['missing'] );
				$i++;
			}
		}
		return $results;
	}

	function getFileProps( $virtualUrl ) {
		return false;
	}

	function fetchImageQuery( $query ) {
		global $wgMemc;

		$query = array_merge( $query,
			array(
				'format' => 'json',
				'action' => 'query',
				'redirects' => 'true'
			) );
		if ( $this->mApiBase ) {
			$url = wfAppendQuery( $this->mApiBase, $query );
		} else {
			$url = $this->makeUrl( $query, 'api' );
		}

		if( !isset( $this->mQueryCache[$url] ) ) {
			$key = $this->getLocalCacheKey( 'ForeignAPIRepo', 'Metadata', md5( $url ) );
			$data = $wgMemc->get( $key );
			if( !$data ) {
				$data = self::httpGet( $url );
				if ( !$data ) {
					return null;
				}
				$wgMemc->set( $key, $data, 3600 );
			}

			if( count( $this->mQueryCache ) > 100 ) {
				// Keep the cache from growing infinitely
				$this->mQueryCache = array();
			}
			$this->mQueryCache[$url] = $data;
		}
		return FormatJson::decode( $this->mQueryCache[$url], true );
	}

	function getImageInfo( $data ) {
		if( $data && isset( $data['query']['pages'] ) ) {
			foreach( $data['query']['pages'] as $info ) {
				if( isset( $info['imageinfo'][0] ) ) {
					return $info['imageinfo'][0];
				}
			}
		}
		return false;
	}

	function findBySha1( $hash ) {
		$results = $this->fetchImageQuery( array(
										'aisha1base36' => $hash,
										'aiprop'       => ForeignAPIFile::getProps(),
										'list'         => 'allimages', ) );
		$ret = array();
		if ( isset( $results['query']['allimages'] ) ) {
			foreach ( $results['query']['allimages'] as $img ) {
				// 1.14 was broken, doesn't return name attribute
				if( !isset( $img['name'] ) ) {
					continue;
				}
				$ret[] = new ForeignAPIFile( Title::makeTitle( NS_FILE, $img['name'] ), $this, $img );
			}
		}
		return $ret;
	}

	function getThumbUrl( $name, $width = -1, $height = -1, &$result = null, $otherParams = '' ) {
		$data = $this->fetchImageQuery( array(
			'titles' => 'File:' . $name,
			'iiprop' => 'url|timestamp',
			'iiurlwidth' => $width,
			'iiurlheight' => $height,
			'iiurlparam'  => $otherParams,
			'prop' => 'imageinfo' ) );
		$info = $this->getImageInfo( $data );

		if( $data && $info && isset( $info['thumburl'] ) ) {
			wfDebug( __METHOD__ . " got remote thumb " . $info['thumburl'] . "\n" );
			$result = $info;
			return $info['thumburl'];
		} else {
			return false;
		}
	}

	/**
	 * Return the imageurl from cache if possible
	 *
	 * If the url has been requested today, get it from cache
	 * Otherwise retrieve remote thumb url, check for local file.
	 *
	 * @param $name String is a dbkey form of a title
	 * @param $width
	 * @param $height
	 * @param String $param Other rendering parameters (page number, etc) from handler's makeParamString.
	 */
	function getThumbUrlFromCache( $name, $width, $height, $params="" ) {
		global $wgMemc;

		if ( !$this->canCacheThumbs() ) {
			$result = null; // can't pass "null" by reference, but it's ok as default value
			return $this->getThumbUrl( $name, $width, $height, $result, $params );
		}
		$key = $this->getLocalCacheKey( 'ForeignAPIRepo', 'ThumbUrl', $name );
		$sizekey = "$width:$height:$params";

		/* Get the array of urls that we already know */
		$knownThumbUrls = $wgMemc->get($key);
		if( !$knownThumbUrls ) {
			/* No knownThumbUrls for this file */
			$knownThumbUrls = array();
		} else {
			if( isset( $knownThumbUrls[$sizekey] ) ) {
				wfDebug( __METHOD__ . ': Got thumburl from local cache: ' .
					"{$knownThumbUrls[$sizekey]} \n");
				return $knownThumbUrls[$sizekey];
			}
			/* This size is not yet known */
		}

		$metadata = null;
		$foreignUrl = $this->getThumbUrl( $name, $width, $height, $metadata, $params );

		if( !$foreignUrl ) {
			wfDebug( __METHOD__ . " Could not find thumburl\n" );
			return false;
		}

		// We need the same filename as the remote one :)
		$fileName = rawurldecode( pathinfo( $foreignUrl, PATHINFO_BASENAME ) );
		if( !$this->validateFilename( $fileName ) ) {
			wfDebug( __METHOD__ . " The deduced filename $fileName is not safe\n" );
			return false;
		}
		$localPath =  $this->getZonePath( 'thumb' ) . "/" . $this->getHashPath( $name ) . $name;
		$localFilename = $localPath . "/" . $fileName;
		$localUrl =  $this->getZoneUrl( 'thumb' ) . "/" . $this->getHashPath( $name ) . rawurlencode( $name ) . "/" . rawurlencode( $fileName );

		if( $this->fileExists( $localFilename ) && isset( $metadata['timestamp'] ) ) {
			wfDebug( __METHOD__ . " Thumbnail was already downloaded before\n" );
			$modified = $this->getFileTimestamp( $localFilename );
			$remoteModified = strtotime( $metadata['timestamp'] );
			$current = time();
			$diff = abs( $modified - $current );
			if( $remoteModified < $modified && $diff < $this->fileCacheExpiry ) {
				/* Use our current and already downloaded thumbnail */
				$knownThumbUrls[$sizekey] = $localUrl;
				$wgMemc->set( $key, $knownThumbUrls, $this->apiThumbCacheExpiry );
				return $localUrl;
			}
			/* There is a new Commons file, or existing thumbnail older than a month */
		}
		$thumb = self::httpGet( $foreignUrl );
		if( !$thumb ) {
			wfDebug( __METHOD__ . " Could not download thumb\n" );
			return false;
		}

		# @todo FIXME: Delete old thumbs that aren't being used. Maintenance script?
		wfSuppressWarnings();
		$backend = $this->getBackend();
		$op = array( 'op' => 'create', 'dst' => $localFilename, 'content' => $thumb );
		if( !$backend->doOperation( $op )->isOK() ) {
			wfRestoreWarnings();
			wfDebug( __METHOD__ . " could not write to thumb path\n" );
			return $foreignUrl;
		}
		wfRestoreWarnings();
		$knownThumbUrls[$sizekey] = $localUrl;
		$wgMemc->set( $key, $knownThumbUrls, $this->apiThumbCacheExpiry );
		wfDebug( __METHOD__ . " got local thumb $localUrl, saving to cache \n" );
		return $localUrl;
	}

	/**
	 * @see FileRepo::getZoneUrl()
	 */
	function getZoneUrl( $zone ) {
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'thumb':
				return $this->thumbUrl;
			default:
				return parent::getZoneUrl( $zone );
		}
	}

	/**
	 * Get the local directory corresponding to one of the basic zones
	 */
	function getZonePath( $zone ) {
		$supported = array( 'public', 'thumb' );
		if ( in_array( $zone, $supported ) ) {
			return parent::getZonePath( $zone );
		}
		return false;
	}

	/**
	 * Are we locally caching the thumbnails?
	 * @return bool
	 */
	public function canCacheThumbs() {
		return ( $this->apiThumbCacheExpiry > 0 );
	}

	/**
	 * The user agent the ForeignAPIRepo will use.
	 */
	public static function getUserAgent() {
		return Http::userAgent() . " ForeignAPIRepo/" . self::VERSION;
	}

	/**
	 * Like a Http:get request, but with custom User-Agent.
	 * @see Http:get
	 */
	public static function httpGet( $url, $timeout = 'default', $options = array() ) {
		$options['timeout'] = $timeout;
		/* Http::get */
		$url = wfExpandUrl( $url, PROTO_HTTP );
		wfDebug( "ForeignAPIRepo: HTTP GET: $url\n" );
		$options['method'] = "GET";

		if ( !isset( $options['timeout'] ) ) {
		        $options['timeout'] = 'default';
		}

		$req = MWHttpRequest::factory( $url, $options );
		$req->setUserAgent( ForeignAPIRepo::getUserAgent() );
		$status = $req->execute();

		if ( $status->isOK() ) {
		        return $req->getContent();
		} else {
		        return false;
		}
	}

	function enumFiles( $callback ) {
		throw new MWException( 'enumFiles is not supported by ' . get_class( $this ) );
	}
}
