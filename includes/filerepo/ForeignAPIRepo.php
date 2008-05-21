<?php

/**
 * A foreign repository with a remote MediaWiki with an API thingy
 * Very hacky and inefficient
 * do not use except for testing :D
 *
 * Example config:
 *
 * $wgForeignFileRepos[] = array(
 *   'class'            => 'ForeignAPIRepo',
 *   'name'             => 'shared',
 *   'apibase'          => 'http://en.wikipedia.org/w/api.php',
 *   'fetchDescription' => true, // Optional
 * );
 *
 * @ingroup FileRepo
 */
class ForeignAPIRepo extends FileRepo {
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
	function newFile( $title, $time = false ) {
		return false;
	}
	
	protected function queryImage( $query ) {
		$url = $this->mApiBase .
			'?' .
			wfArrayToCgi(
				array_merge( $query,
					array(
						'format' => 'json',
						'action' => 'query',
						'prop' => 'imageinfo' ) ) );
		$json = Http::get( $url );
		$data = json_decode( $json, true );
		
		if( isset( $data['query']['pages'] ) ) {
			foreach( $data['query']['pages'] as $pageid => $info ) {
				if( isset( $info['imageinfo'][0] ) ) {
					return $info['imageinfo'][0];
				}
			}
		}
		return false;
	}
	
	function findFile( $title, $time = false ) {
		$info = $this->queryImage( array(
			'titles' => 'Image:' . $title->getText(),
			'prop' => 'imageinfo',
			'iiprop' => 'timestamp|user|comment|url|size|sha1|metadata|mimetype' ) );
		if( $info ) {
			return new ForeignAPIFile( $title, $this, $info );
		} else {
			return false;
		}
	}
	
	function getThumbUrl( $name, $width=-1, $height=-1 ) {
		$info = $this->queryImage( array(
			'titles' => 'Image:' . $name,
			'iiprop' => 'url',
			'iiurlwidth' => $width,
			'iiurlheight' => $height ) );
		if( $info ) {
			return $info['thumburl'];
		} else {
			return false;
		}
	}
}
