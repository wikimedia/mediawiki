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
						'action' => 'query',
						'prop' => 'imageinfo' ) ) );
		
		if( !isset( $this->mQueryCache[$url] ) ) {
			$key = wfMemcKey( 'ForeignAPIRepo', 'Metadata', md5( $url ) );
			$data = $wgMemc->get( $key );
			if( !$data ) {
				$data = Http::get( $url );
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
			'iiprop' => 'timestamp|user|comment|url|size|sha1|metadata|mime' ) );
	}
	
	function getThumbUrl( $name, $width=-1, $height=-1 ) {
		$info = $this->queryImage( array(
			'titles' => 'Image:' . $name,
			'iiprop' => 'url',
			'iiurlwidth' => $width,
			'iiurlheight' => $height ) );
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
			return $this->getThumbUrl();
		}
		
		$key = wfMemcKey( 'ForeignAPIRepo', 'ThumbUrl', $name );
		if ( $thumbUrl = $wgMemc->get($key) ) {
			wfDebug("Got thumb from local cache. $thumbUrl \n");
			return $thumbUrl;
		}
		else {
			$foreignUrl = $this->getThumbUrl( $name, $width, $height );
			$path = $this->apiThumbCacheDir . '/' . $this->name . '/' .
						$name . '/';
			if ( !is_dir($wgUploadDirectory . '/' . $path) ) {
				wfMkdirParents($wgUploadDirectory . '/' . $path);
			}
			$localUrl =  $wgServer . $wgUploadPath . '/' . $path . $width . 'px-' . $name;
			$thumb = Http::get( $foreignUrl );
			# FIXME: Delete old thumbs that aren't being used. Maintenance script?
			file_put_contents($wgUploadDirectory . '/' . $path . $width . 'px-' . $name, $thumb );
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
		return ( $this->apiThumbCacheExpiry > 0 && $this->apiThumbCacheDir );
	}
}
