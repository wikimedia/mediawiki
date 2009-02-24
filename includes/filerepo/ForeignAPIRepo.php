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
	var $apiThumbCacheExpiry = 0;
	protected $mQueryCache = array();
	
	function __construct( $info ) {
		parent::__construct( $info );
		$this->mApiBase = $info['apibase']; // http://commons.wikimedia.org/w/api.php
		if( !$this->scriptDirUrl ) {
			// hack for description fetches
			$this->scriptDirUrl = dirname( $this->mApiBase );
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
	function publishBatch( $triplets, $flags = 0 ) {
		return false;
	}
	function deleteBatch( $sourceDestPairs ) {
		return false;
	}
	function getFileProps( $virtualUrl ) {
		return false;
	}
	
	protected function queryImage( $query ) {
		$data = $this->fetchImageQuery( $query );
		
		if( isset( $data['query']['pages'] ) ) {
			foreach( $data['query']['pages'] as $pageid => $info ) {
				if( isset( $info['imageinfo'][0] ) ) {
					return $info['imageinfo'][0];
				}
			}
		}
		return false;
	}
	
	protected function fetchImageQuery( $query ) {
		global $wgMemc;
		
		$url = $this->mApiBase .
			'?' .
			wfArrayToCgi(
				array_merge( $query,
					array(
						'format' => 'json',
						'action' => 'query' ) ) );
		
		if( !isset( $this->mQueryCache[$url] ) ) {
			$key = wfMemcKey( 'ForeignAPIRepo', 'Metadata', md5( $url ) );
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
		return json_decode( $this->mQueryCache[$url], true );
	}
	
	function getImageInfo( $title, $time = false ) {
		return $this->queryImage( array(
			'titles' => 'Image:' . $title->getText(),
			'iiprop' => 'timestamp|user|comment|url|size|sha1|metadata|mime',
			'prop' => 'imageinfo' ) );
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
		$info = $this->queryImage( array(
			'titles' => 'Image:' . $name,
			'iiprop' => 'url',
			'iiurlwidth' => $width,
			'iiurlheight' => $height,
			'prop' => 'imageinfo' ) );
		if( $info ) {
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
		
		$key = wfMemcKey( 'ForeignAPIRepo', 'ThumbUrl', $name );
		if ( $thumbUrl = $wgMemc->get($key) ) {
			wfDebug("Got thumb from local cache. $thumbUrl \n");
			return $thumbUrl;
		}
		else {
			$foreignUrl = $this->getThumbUrl( $name, $width, $height );
			
			// We need the same filename as the remote one :)
			$fileName = ltrim( substr( $foreignUrl, strrpos( $foreignUrl, '/' ) ), '/' );
			$path = 'thumb/' . $this->getHashPath( $name ) . $name . "/";
			if ( !is_dir($wgUploadDirectory . '/' . $path) ) {
				wfMkdirParents($wgUploadDirectory . '/' . $path);
			}
			if ( !is_writable( $wgUploadDirectory . '/' . $path . $fileName ) ) {
				wfDebug( __METHOD__ . " could not write to thumb path\n" );
				return $foreignUrl;
			}
			$localUrl =  $wgServer . $wgUploadPath . '/' . $path . $fileName;
			$thumb = Http::get( $foreignUrl );
			# FIXME: Delete old thumbs that aren't being used. Maintenance script?
			file_put_contents($wgUploadDirectory . '/' . $path . $fileName, $thumb );
			$wgMemc->set( $key, $localUrl, $this->apiThumbCacheExpiry );
			wfDebug( __METHOD__ . " got local thumb $localUrl, saving to cache \n" );
			return $localUrl;
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
