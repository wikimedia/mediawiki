<?php

require_once 'commandLine.inc';

class UploadDumper {
	
	function __construct( $args ) {
		global $IP, $wgUseSharedUploads;
		$this->mAction = 'fetchUsed';
		$this->mBasePath = $IP;
		$this->mShared = $wgUseSharedUploads;
		
		if( isset( $args['help'] ) ) {
			$this->mAction = 'help';
		}
		
		if( isset( $args['base'] ) ) {
			$this->mBasePath = $args['base'];
		}
	}
	
	function run() {
		$this->{$this->mAction}();
	}
	
	function help() {
		echo <<<END
Generates list of uploaded files which can be fed to tar or similar.
By default, outputs relative paths against the parent directory of
\$wgUploadDirectory.

Usage:
php dumpUploads.php [options] > list-o-files.txt

Options:
--base=<path>  Set base relative path instead of wiki include root

FIXME: other options not implemented yet ;)

--local        List all local files, used or not. No shared files included.
--used         Skip local images that are not used
--shared       Include images used from shared repository

END;
	}
	
	/**
	 * Fetch a list of all or used images from a particular image source.
	 * @param string $table
	 * @param string $directory Base directory where files are located
	 * @param bool $shared true to pass shared-dir settings to hash func
	 */
	function fetchUsed() {
		$dbr = wfGetDB( DB_SLAVE );
		$image = $dbr->tableName( 'image' );
		$imagelinks = $dbr->tableName( 'imagelinks' );
		
		$sql = "SELECT DISTINCT il_to, img_name
			FROM $imagelinks
			LEFT OUTER JOIN $image
			ON il_to=img_name";
		$result = $dbr->query( $sql );
		
		while( $row = $dbr->fetchObject( $result ) ) {
			if( is_null( $row->img_name ) ) {
				if( $this->mShared ) {
					$this->outputShared( $row->il_to );
				}
			} else {
				$this->outputLocal( $row->il_to );
			}
		}
		$dbr->freeResult( $result );
	}
	
	function outputLocal( $name ) {
		global $wgUploadDirectory;
		return $this->outputItem( $name, $wgUploadDirectory, false );
	}
	
	function outputShared( $name ) {
		global $wgSharedUploadDirectory;
		return $this->outputItem( $name, $wgSharedUploadDirectory, true );
	}
	
	function outputItem( $name, $directory, $shared ) {
		$filename = $directory .
			wfGetHashPath( $name, $shared ) .
			$name;
		$rel = $this->relativePath( $filename, $this->mBasePath );
		echo "$rel\n";
	}

	/**
	 * Return a relative path to $path from the base directory $base
	 * For instance relativePath( '/foo/bar/baz', '/foo' ) should return
	 * 'bar/baz'.
	 */
	function relativePath( $path, $base) {
		$path = explode( DIRECTORY_SEPARATOR, $path );
		$base = explode( DIRECTORY_SEPARATOR, $base );
		while( count( $base ) && $path[0] == $base[0] ) {
			array_shift( $path );
			array_shift( $base );
		}
		foreach( $base as $prefix ) {
			array_unshift( $path, '..' );
		}
		return implode( DIRECTORY_SEPARATOR, $path );
	}
}

$dumper = new UploadDumper( $options );
$dumper->run();

