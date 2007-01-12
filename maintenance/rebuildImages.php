<?php
/*
 * Script to update image metadata records
 *
 * Usage: php rebuildImages.php [--missing] [--dry-run]
 * Options:
 *   --missing  Crawl the uploads dir for images without records, and
 *              add them only.
 *
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @author Brion Vibber <brion at pobox.com>
 * @package MediaWiki
 * @subpackage maintenance
 */

$options = array( 'missing', 'dry-run' );

require_once( 'commandLine.inc' );
require_once( 'FiveUpgrade.inc' );

class ImageBuilder extends FiveUpgrade {
	function ImageBuilder( $dryrun = false ) {
		parent::FiveUpgrade();

		$this->maxLag = 10; # if slaves are lagged more than 10 secs, wait
		$this->dryrun = $dryrun;
	}

	function build() {
		$this->buildImage();
		$this->buildOldImage();
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
			$completed,   // $completed does not appear to be defined.
			$this->count,
			$rate,        // $rate does not appear to be defined.
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
				if( !$this->dryrun ) {
					$this->dbw->update( $table,
						$update,
						array( $key => $row->$key ),
						$fname );
				}
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

		global $wgMemc;
		$key = wfMemcKey( "Image", md5( $row->img_name ) );
		$wgMemc->delete( $key );

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

	function crawlMissing() {
		global $wgUploadDirectory, $wgHashedUploadDirectory;
		if( $wgHashedUploadDirectory ) {
			for( $i = 0; $i < 16; $i++ ) {
				for( $j = 0; $j < 16; $j++ ) {
					$dir = sprintf( '%s%s%01x%s%02x',
						$wgUploadDirectory,
						DIRECTORY_SEPARATOR,
						$i,
						DIRECTORY_SEPARATOR,
						$i * 16 + $j );
					$this->crawlDirectory( $dir );
				}
			}
		} else {
			$this->crawlDirectory( $wgUploadDirectory );
		}
	}

	function crawlDirectory( $dir ) {
		if( !file_exists( $dir ) ) {
			return $this->log( "no directory, skipping $dir" );
		}
		if( !is_dir( $dir ) ) {
			return $this->log( "not a directory?! skipping $dir" );
		}
		if( !is_readable( $dir ) ) {
			return $this->log( "dir not readable, skipping $dir" );
		}
		$source = opendir( $dir );
		if( $source === false ) {
			return $this->log( "couldn't open dir, skipping $dir" );
		}

		$this->log( "crawling $dir" );
		while( false !== ( $filename = readdir( $source ) ) ) {
			$fullpath = $dir . DIRECTORY_SEPARATOR . $filename;
			if( is_dir( $fullpath ) ) {
				continue;
			}
			if( is_link( $fullpath ) ) {
				$this->log( "skipping symlink at $fullpath" );
				continue;
			}
			$this->checkMissingImage( $filename, $fullpath );
		}
		closedir( $source );
	}

	function checkMissingImage( $filename, $fullpath ) {
		$fname = 'ImageBuilder::checkMissingImage';
		$row = $this->dbw->selectRow( 'image',
			array( 'img_name' ),
			array( 'img_name' => $filename ),
			$fname );

		if( $row ) {
			// already known, move on
			return;
		} else {
			$this->addMissingImage( $filename, $fullpath );
		}
	}

	function addMissingImage( $filename, $fullpath ) {
		$fname = 'ImageBuilder::addMissingImage';

		$size = filesize( $fullpath );
		$info = $this->imageInfo( $filename );
		$timestamp = $this->dbw->timestamp( filemtime( $fullpath ) );

		global $wgContLang;
		$altname = $wgContLang->checkTitleEncoding( $filename );
		if( $altname != $filename ) {
			if( $this->dryrun ) {
				$filename = $altname;
				$this->log( "Estimating transcoding... $altname" );
			} else {
				$filename = $this->renameFile( $filename );
			}
		}

		if( $filename == '' ) {
			$this->log( "Empty filename for $fullpath" );
			return;
		}

		$fields = array(
			'img_name'       => $filename,
			'img_size'       => $size,
			'img_width'      => $info['width'],
			'img_height'     => $info['height'],
			'img_metadata'   => '', // filled in on-demand
			'img_bits'       => $info['bits'],
			'img_media_type' => $info['media'],
			'img_major_mime' => $info['major'],
			'img_minor_mime' => $info['minor'],
			'img_description' => '(recovered file, missing upload log entry)',
			'img_user'        => 0,
			'img_user_text'   => 'Conversion script',
			'img_timestamp'   => $timestamp );
		if( !$this->dryrun ) {
			$this->dbw->insert( 'image', $fields, $fname );
		}
		$this->log( $fullpath );
	}
}

$builder = new ImageBuilder( isset( $options['dry-run'] ) );
if( isset( $options['missing'] ) ) {
	$builder->crawlMissing();
} else {
	$builder->build();
}

?>
