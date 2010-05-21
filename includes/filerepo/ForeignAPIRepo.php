<?php

/**
 * A foreign repository with a remote MediaWiki with an API thingy
 * Very hacky and inefficient
 * do not use except for testing :D
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
	var $fileFactory = array( 'ForeignAPIFile', 'newFromTitle' );
	var $apiThumbCacheExpiry = 86400;
	protected $mQueryCache = array();
	protected $mFileExists = array();

	function __construct( $info ) {
		parent::__construct( $info );
		$this->mApiBase = $info['apibase']; // http://commons.wikimedia.org/w/api.php
		if( isset( $info['apiThumbCacheExpiry'] ) ) {
			$this->apiThumbCacheExpiry = $info['apiThumbCacheExpiry'];
		}
		if( !$this->scriptDirUrl ) {
			// hack for description fetches
			$this->scriptDirUrl = dirname( $this->mApiBase );
		}
		// If we can cache thumbs we can guess sane defaults for these
		if( $this->canCacheThumbs() && !$this->url ) {
			global $wgLocalFileRepo;
			$this->url = $wgLocalFileRepo['url'];
		}
		if( $this->canCacheThumbs() && !$this->thumbUrl ) {
			$this->thumbUrl = $this->url . '/thumb';
		}
	}

	/**
	 * Per docs in FileRepo, this needs to return false if we don't support versioned
	 * files. Well, we don't.
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
	function append( $srcPath, $toAppendPath, $flags = 0 ){
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
				# TODO! FIXME! We need to be able to handle virtual
				# URLs better, at least when we know they refer to the
				# same repo.
				$results[$k] = false;
				unset( $files[$k] );
			}
		}

		$results = $this->fetchImageQuery( array( 'titles' => implode( $files, '|' ),
											'prop' => 'imageinfo' ) );
		if( isset( $data['query']['pages'] ) ) {
			$i = 0;
			foreach( $files as $key => $file ) {
				$results[$key] = $this->mFileExists[$key] = !isset( $data['query']['pages'][$i]['missing'] );
				$i++;
			}
		}
	}
	function getFileProps( $virtualUrl ) {
		return false;
	}

	function fetchImageQuery( $query ) {
		global $wgMemc;

		$url = $this->mApiBase .
			'?' .
			wfArrayToCgi(
				array_merge( $query,
					array(
						'format' => 'json',
						'action' => 'query',
						'redirects' => 'true' ) ) );

		if( !isset( $this->mQueryCache[$url] ) ) {
			$key = $this->getLocalCacheKey( 'ForeignAPIRepo', 'Metadata', md5( $url ) );
			$data = $wgMemc->get( $key );
			if( !$data ) {
				$data = Http::get( $url );
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
			foreach( $data['query']['pages'] as $pageid => $info ) {
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
										'aiprop'       => 'timestamp|user|comment|url|size|sha1|metadata|mime',
										'list'         => 'allimages', ) );
		$ret = array();
		if ( isset( $results['query']['allimages'] ) ) {
			foreach ( $results['query']['allimages'] as $img ) {
				$ret[] = new ForeignAPIFile( Title::makeTitle( NS_FILE, $img['name'] ), $this, $img );
			}
		}
		return $ret;
	}

	function getThumbUrl( $name, $width=-1, $height=-1 ) {
		$data = $this->fetchImageQuery( array(
			'titles' => 'File:' . $name,
			'iiprop' => 'url',
			'iiurlwidth' => $width,
			'iiurlheight' => $height,
			'prop' => 'imageinfo' ) );
		$info = $this->getImageInfo( $data );

		if( $data && $info && $info['thumburl'] ) {
			wfDebug( __METHOD__ . " got remote thumb " . $info['thumburl'] . "\n" );
			return $info['thumburl'];
		} else {
			return false;
		}
	}

	function getThumbUrlFromCache( $name, $width, $height ) {
		global $wgMemc, $wgUploadPath, $wgServer, $wgUploadDirectory;

		if ( !$this->canCacheThumbs() ) {
			return $this->getThumbUrl( $name, $width, $height );
		}

		$key = $this->getLocalCacheKey( 'ForeignAPIRepo', 'ThumbUrl', $name );
		if ( $thumbUrl = $wgMemc->get($key) ) {
			wfDebug("Got thumb from local cache. $thumbUrl \n");
			return $thumbUrl;
		}
		else {
			$foreignUrl = $this->getThumbUrl( $name, $width, $height );
			if( !$foreignUrl ) {
				wfDebug( __METHOD__ . " Could not find thumburl\n" );
				return false;
			}
			$thumb = Http::get( $foreignUrl );
			if( !$thumb ) {
				wfDebug( __METHOD__ . " Could not download thumb\n" );
				return false;
			}
			// We need the same filename as the remote one :)
			$fileName = rawurldecode( pathinfo( $foreignUrl, PATHINFO_BASENAME ) );
			$path = 'thumb/' . $this->getHashPath( $name ) . $name . "/";
			if ( !is_dir($wgUploadDirectory . '/' . $path) ) {
				wfMkdirParents($wgUploadDirectory . '/' . $path);
			}
			$localUrl =  $wgServer . $wgUploadPath . '/' . $path . $fileName;
			# FIXME: Delete old thumbs that aren't being used. Maintenance script?
			if( !file_put_contents($wgUploadDirectory . '/' . $path . $fileName, $thumb ) ) {
				wfDebug( __METHOD__ . " could not write to thumb path\n" );
				return $foreignUrl;
			}
			$wgMemc->set( $key, $localUrl, $this->apiThumbCacheExpiry );
			wfDebug( __METHOD__ . " got local thumb $localUrl, saving to cache \n" );
			return $localUrl;
		}
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
	 * Are we locally caching the thumbnails?
	 * @return bool
	 */
	public function canCacheThumbs() {
		return ( $this->apiThumbCacheExpiry > 0 );
	}
}
