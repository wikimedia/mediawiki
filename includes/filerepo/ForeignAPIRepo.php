<?php

/**
 * A foreign repository with a remote MediaWiki with an API thingy
 * Very hacky and inefficient
 * do not use :D
 *
 * @ingroup FileRepo
 */
class ForeignAPIRepo extends FileRepo {
	function __construct( $info ) {
		parent::__construct( $info );
		$this->mApiBase = $info['apibase']; // http://commons.wikimedia.org/w/api.php
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
	function findFile( $title, $time = false ) {
		$url = $this->mApiBase .
			'?' .
			wfArrayToCgi( array(
				'format' => 'json',
				'action' => 'query',
				'titles' => $title, // fixme -- canonical namespacea
				'prop' => 'imageinfo',
				'iiprop' => 'timestamp|user|comment|url|size|sha1|metadata' ) );
		$json = Http::get( $url );
		$data = json_decode( $json, true );
		
		if( isset( $data['query']['pages'] ) ) {
			foreach( $data['query']['pages'] as $pageid => $info ) {
				if( isset( $info['imageinfo'][0] ) ) {
					return new ForeignAPIFile( $title, $this, $info['imageinfo'][0] );
				}
			}
		}
		return false;
	}
}
