<?php

require_once( 'commandLine.inc' );

class ImageBuilder {
	function ImageBuilder() {
		global $wgDatabase;
		
		$this->dbw =& $this->newConnection();
		$this->dbr =& $this->streamConnection();
		
		$this->maxLag    = 10; # if slaves are lagged more than 10 secs, wait
	}
	
	function build() {
		$this->buildImage();
		$this->buildOldImage();
	}
	
	/**
	 * Open a connection to the master server with the admin rights.
	 * @return Database
	 * @access private
	 */
	function &newConnection() {
		global $wgDBadminuser, $wgDBadminpassword;
		global $wgDBserver, $wgDBname;
		$db =& new Database( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );
		return $db;
	}
	
	/**
	 * Open a second connection to the master server, with buffering off.
	 * This will let us stream large datasets in and write in chunks on the
	 * other end.
	 * @return Database
	 * @access private
	 */
	function &streamConnection() {
		$timeout = 3600 * 24;
		$db =& $this->newConnection();
		$db->bufferResults( false );
		$db->query( "SET net_read_timeout=$timeout" );
		$db->query( "SET net_write_timeout=$timeout" );
		return $db;
	}
	
	/**
	 * Dump timestamp and message to output
	 * @param string $message
	 * @access private
	 */
	function log( $message ) {
		echo wfTimestamp( TS_DB ) . ': ' . $message . "\n";
		flush();
	}
	
	function init( $count, $table ) {
		$this->processed = 0;
		$this->updated = 0;
		$this->count = $count;
		$this->startTime = wfTime();
		$this->table = $table;
	}
	
	function progress( $updated ) {
		$this->updated += $updated;
		$this->processed++;
		if( $this->processed % 100 != 0 ) {
			return;
		}
		$portion = $this->processed / $this->count;
		$updateRate = $this->updated / $this->processed;
		
		$now = wfTime();
		$delta = $now - $this->startTime;
		$estimatedTotalTime = $delta / $portion;
		$eta = $this->startTime + $estimatedTotalTime;
		
		printf( "%s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec <%.2f%% updated>\n",
			wfTimestamp( TS_DB, intval( $now ) ),
			$portion * 100.0,
			$this->table,
			wfTimestamp( TS_DB, intval( $eta ) ),
			$completed,
			$this->count,
			$rate,
			$updateRate * 100.0 );
		flush();
	}
	
	function buildTable( $table, $key, $callback ) {
		$fname = 'ImageBuilder::buildTable';
		
		$count = $this->dbw->selectField( $table, 'count(*)', '', $fname );
		$this->init( $count, $table );
		$this->log( "Processing $table..." );
		
		$tableName = $this->dbr->tableName( $table );
		$sql = "SELECT * FROM $tableName";
		$result = $this->dbr->query( $sql, $fname );
		
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$update = call_user_func( $callback, $row );
			if( is_array( $update ) ) {
				$this->dbw->update( $table,
					$update,
					array( $key => $row->$key ),
					$fname );
				$this->progress( 1 );
			} else {
				$this->progress( 0 );
			}
		}
		$this->log( "Finished $table... $this->updated of $this->processed rows updated" );
		$this->dbr->freeResult( $result );
	}
	
	function buildImage() {
		$callback = array( &$this, 'imageCallback' );
		$this->buildTable( 'image', 'img_name', $callback );
	}
	
	function imageCallback( $row ) {
		if( $row->img_width ) {
			// Already processed
			return null;
		}
		
		// Fill in the new image info fields
		$info = $this->imageInfo( $row->img_name );
		return array(
			'img_width'      => $info['width'],
			'img_height'     => $info['height'],
			'img_bits'       => $info['bits'],
			'img_media_type' => $info['media'],
			'img_major_mime' => $info['major'],
			'img_minor_mime' => $info['minor'] );
	}
	
	
	function buildOldImage() {
		$this->buildTable( 'oldimage', 'oi_archive_name',
			array( &$this, 'oldimageCallback' ) );
	}
	
	function oldimageCallback( $row ) {
		if( $row->oi_width ) {
			return null;
		}
		
		// Fill in the new image info fields
		$info = $this->imageInfo( $row->oi_archive_name, 'wfImageArchiveDir', $row->oi_name );
		return array(
			'oi_width'  => $info['width' ],
			'oi_height' => $info['height'],
			'oi_bits'   => $info['bits'  ] );
	}
	
	function imageInfo( $name, $subdirCallback='wfImageDir', $basename = null ) {
		if( is_null( $basename ) ) $basename = $name;
		$dir = call_user_func( $subdirCallback, $basename );
		$filename = $dir . '/' . $name;
		$info = array(
			'width'  => 0,
			'height' => 0,
			'bits'   => 0,
			'media'  => '',
			'major'  => '',
			'minor'  => '' );
		
		$magic =& wfGetMimeMagic();
		$mime = $magic->guessMimeType( $filename, true );
		list( $info['major'], $info['minor'] ) = explode( '/', $mime );
		
		$info['media'] = $magic->getMediaType( $filename, $mime );
		
		# Height and width
		$gis = false;
		if( $mime == 'image/svg' ) {
			$gis = wfGetSVGsize( $this->imagePath );
		} elseif( $magic->isPHPImageType( $mime ) ) {
			$gis = getimagesize( $filename );
		} else {
			$this->log( "Surprising mime type: $mime" );
		}
		if( $gis ) {
			$info['width' ] = $gis[0];
			$info['height'] = $gis[1];
		}
		if( isset( $gis['bits'] ) ) {
			$info['bits'] = $gis['bits'];
		}
		
		return $info;
	}
	
}

$builder = new ImageBuilder();
$builder->build();

?>
