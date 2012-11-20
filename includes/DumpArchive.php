<?php

/**
 * Copyright (C) 2011 Vitaliy Filippov <vitalif@mail.ru>
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
 */

/**
 * Stub dump archive class, does not support binary parts,
 * just passes through the XML one.
 */
class StubDumpArchive {

	var $fp = NULL, $buffer = '', $files = array(), $tempdir = '';
	var $mimetype = '', $extension = '', $mainFile = false;
	const BUFSIZE = 0x10000;

	/**
	 * Open an existing archive for importing
	 * Returns the constructed reader, or false in a case of failure
	 */
	function open( $archive ) {
		global $wgExportFormats;
		$this->mainFile = $archive;
		foreach ( $wgExportFormats as $format ) {
			try {
				$class = $format['reader'];
				return new $class( $this );
			} catch(Exception $e) {
			}
		}
		return false;
	}

	/**
	 * Get temporary filename for the main part
	 */
	function getMainPart() {
		return $this->mainFile;
	}

	/**
	 * Get temporary file name for a binary part
	 */
	function getBinary( $url ) {
		return false;
	}

	/**
	 * Create archive for writing, main file extension is $mainExt
	 */
	function create( $mainMimetype, $mainExtension ) {
		$this->mimetype = $mainMimetype;
		$this->extension = $mainExtension;
		$f = tempnam( wfTempDir(), 'exs' );
		$this->fp = fopen( $f, 'wb' );
		$this->files[ $f ] = true;
	}

	/**
	 * Write part of main ("index") stream (buffered)
	 */
	function write( $string ) {
		if ( !$this->fp ) {
			return;
		}
		if ( strlen( $this->buffer ) + strlen( $string ) < self::BUFSIZE ) {
			$this->buffer .= $string;
		} else {
			fwrite( $this->fp, $this->buffer );
			fwrite( $this->fp, $string );
			$this->buffer = '';
		}
	}

	/**
	 * Get the pseudo-URL for embedded file (object $file)
	 */
	function binUrl( File $file ) {
		return $file->getFullUrl();
	}

	/**
	 * Write binary file (object $file)
	 */
	function writeBinary( File $file ) {
		return false;
	}

	/**
	 * Finish writing
	 */
	function close() {
		if ( $this->fp ) {
			if ( $this->buffer !== '' ) {
				fwrite( $this->fp, $this->buffer );
			}
			fclose( $this->fp );
			$this->fp = NULL;
		}
	}

	/**
	 * Pack all files into an archive,
	 * return its name in $outFilename and MIME type in $outMimetype
	 */
	function getArchive( &$outFilename, &$outMimetype, &$outExtension ) {
		$f = array_keys( $this->files );
		$outFilename = $f[0];
		$outMimetype = $this->mimetype;
		$outExtension = $this->extension;
		return true;
	}

	/**
	 * Destructor
	 */
	function __destruct() {
		$this->cleanup();
	}

	/**
	 * Remove all temporary files and directory
	 */
	function cleanup() {
		if ( $this->files ) {
			foreach ( $this->files as $file => $true ) {
				if ( file_exists( $file ) ) {
					unlink( $file );
				}
			}
			$this->files = array();
		}
		if ( $this->tempdir ) {
			rmdir( $this->tempdir );
			$this->tempdir = '';
		}
	}
}

/**
 * Base class for dump "archiver", which takes the main stream (usually XML)
 * and the embedded binaries, and archives them to a single file.
 * This was multipart/related in old Mediawiki4Intranet versions,
 * will be ZIP in new ones, and can possibly be any other type of archive.
 */
class DumpArchive extends StubDumpArchive {

	var $mimetype = '', $extension = '';
	var $mainMimetype = '', $mainExtension = '';
	const BUFSIZE = 0x10000;

	/**
	 * Static method - constructs the importer with appropriate DumpArchive from file
	 */
	static function newFromFile( $file, $name = NULL ) {
		global $wgDumpArchiveByExt;
		$ext = '';
		if ( !$name ) {
			$name = $file;
		}
		if ( ( $p = strrpos( $name, '.' ) ) !== false ) {
			$ext = strtolower( substr( $name, $p + 1 ) );
		}
		if ( !isset( $wgDumpArchiveByExt[ $ext ] ) ) {
			$ext = '';
		}
		foreach ( $wgDumpArchiveByExt[ $ext ] as $class ) {
			$archive = new $class();
			$importer = $archive->open( $file );
			if ( $importer ) {
				return $importer;
			}
		}
		return NULL;
	}

	/**
	 * Constructor, generates an empty temporary directory
	 */
	function __construct() {
		$this->tempdir = tempnam( wfTempDir(), 'exp' );
		unlink( $this->tempdir );
		mkdir( $this->tempdir );
	}

	/**
	 * Open an existing archive (for importing)
	 * Returns the proper import reader, or false in a case of failure
	 */
	function open( $archive ) {
		global $wgExportFormats;
		if ( !$this->tryUnpack( $archive ) ) {
			return false;
		}
		$dir = opendir( $this->tempdir );
		while ( $file = readdir( $dir ) ) {
			if ( $file != '.' && $file != '..' ) {
				$this->files[ $this->tempdir . '/' . $file ] = true;
			}
		}
		closedir( $dir );
		foreach ( $wgExportFormats as $format ) {
			$f = $this->tempdir . '/Revisions.' . $format['extension'];
			if ( isset( $this->files[ $f ] ) ) {
				$this->mainFile = $this->tempdir . '/Revisions.' . $format['extension'];
				$class = $format['reader'];
				return new $class( $this );
			}
		}
		return false;
	}

	/**
	 * Unpack the archive
	 */
	function tryUnpack( $archive ) {
		return false;
	}

	/**
	 * Get temporary file name for a binary part
	 */
	function getBinary( $url ) {
		// Strip out archive:// prefix
		if ( substr( $url, 0, 10 ) != 'archive://' ) {
			return false;
		}
		$name = substr( $url, 10 );
		if ( isset( $this->files[ $this->tempdir . '/' . $name ] ) ) {
			return $this->tempdir . '/' . $name;
		}
		return false;
	}

	/**
	 * Create archive for writing, main file extension is $mainExt
	 */
	function create( $mainMimetype, $mainExtension ) {
		$this->mainMimetype = $mainMimetype;
		$this->mainExtension = $mainExtension;
		$f = $this->tempdir . '/Revisions.' . $this->mainExtension;
		$this->fp = fopen( $f, 'wb' );
		$this->files[ $f ] = true;
	}

	/**
	 * Generate a name for embedded file (object $file)
	 * By default $SHA1.bin
	 * Other archivers may desire to preserve original filenames
	 */
	function binName( File $file ) {
		return $file->getSha1() . '.bin';
	}

	/**
	 * Get the pseudo-URL for embedded file (object $file)
	 * By default, it's archive://$binName
	 */
	function binUrl( File $file ) {
		return 'archive://' . $this->binName( $file );
	}

	/**
	 * Write binary file (object $file)
	 */
	function writeBinary( File $file ) {
		$name = $this->tempdir . '/' . $this->binName( $file );
		if ( copy( $file->getLocalRefPath(), $name ) ) {
			$this->files[ $name ] = true;
			return true;
		}
		return false;
	}

	/**
	 * Pack all files into an archive,
	 * return its name in $outFilename and MIME type in $outMimetype
	 */
	function getArchive( &$outFilename, &$outMimetype, &$outExtension ) {
		$name = $this->tempdir . '/archive.' . $this->extension;
		if ( !$this->archive( $name ) ) {
			return false;
		}
		$this->files[ $name ] = true;
		$outFilename = $name;
		$outMimetype = $this->mimetype;
		$outExtension = $this->extension;
		return true;
	}

	/**
	 * Pack all files into an archive file with name $arcfn
	 */
	function archive( $arcfn ) {
		return false;
	}
}

/**
 * Support for "multipart" dump files, used in Mediawiki4Intranet in 2009-2011
 */
class OldMultipartDumpArchive extends DumpArchive {

	var $mimetype = 'multipart/related', $extension = 'multipart';
	var $parts = array();
	const BUFSIZE = 0x80000;

	/**
	 * Get temporary file name for a binary part
	 */
	function getBinary( $url ) {
		// Strip out multipart:// prefix
		if ( substr( $url, 0, 12 ) != 'multipart://' ) {
			return false;
		}
		$url = substr( $url, 12 );
		if ( isset( $this->parts[ $url ] ) ) {
			return $this->parts[ $url ];
		}
		return false;
	}

	/**
	 * Write binary file (object $file)
	 */
	function writeBinary( File $file ) {
		$name = tempnam( $this->tempdir, 'part' );
		if ( copy( $file->getPath(), $name ) ) {
			$this->parts[ $this->binName( $file ) ] = $name;
			return true;
		}
		return false;
	}

	/**
	 * Generate a name for embedded file (object $file)
	 */
	function binName( File $file ) {
		return $file->isOld ? $file->getArchiveName() : $file->getName();
	}

	/**
	 * Get the pseudo-URL for embedded file (object $file)
	 * Here it's multipart://$binName
	 */
	function binUrl( File $file ) {
		return 'multipart://' . $this->binName( $file );
	}

	/**
	 * Unpack the archive
	 */
	function tryUnpack( $archive ) {
		$fp = fopen( $archive, "rb" );
		if ( !$fp ) {
			return false;
		}
		$s = fgets( $fp );
		if ( preg_match( "/Content-Type:\s*multipart\/related; boundary=(\S+)\s*\n/s", $s, $m ) ) {
			$boundary = $m[1];
		} else {
			fclose( $fp );
			return false;
		}
		// Loop over parts
		while ( !feof( $fp ) ) {
			$s = trim( fgets( $fp ) );
			if ( $s != $boundary ) {
				break;
			}
			$part = array();
			// Read headers
			while ( $s != "\n" && $s != "\r\n" ) {
				$s = fgets( $fp );
				if ( preg_match( '/([a-z0-9\-\_]+):\s*(.*?)\s*$/is', $s, $m ) ) {
					$part[ str_replace( '-', '_', strtolower( $m[1] ) ) ] = $m[2];
				}
			}
			// Skip parts without Content-ID header
			if ( empty( $part['content_id'] ) ) {
				if ( !empty( $part['content_length'] ) &&
					$part['content_length'] > 0 ) {
					fseek( $fp, ftell( $fp ) + $part['content_length'], 0 );
				}
				continue;
			}
			// Preserve only main part's filename when unpacking for safety
			if ( $part['content_id'] == 'Revisions' ) {
				$tempfile = $this->tempdir . '/Revisions.xml';
			} else {
				$tempfile = tempnam( $this->tempdir, 'part' );
			}
			$tempfp = fopen( $tempfile, "wb" );
			if ( !$tempfp ) {
				// Error creating temporary file, skip it
				fseek( $fp, ftell( $fp ) + $part['content_length'], 0 );
				continue;
			}
			$this->parts[ $part['content_id'] ] = $tempfile;
			// Copy stream
			if ( isset( $part['content_length'] ) ) {
				$done = 0;
				$buf = true;
				while ( $done < $part['content_length'] && $buf ) {
					$buf = fread( $fp, min( self::BUFSIZE, $part['content_length'] - $done ) );
					fwrite( $tempfp, $buf );
					$done += strlen( $buf );
				}
			} else {
				// Main part was archived without Content-Length in old dumps :(
				$buf = fread( $fp, self::BUFSIZE );
				while ( $buf !== '' ) {
					if ( ( $p = strpos( $buf, "\n$boundary" ) ) !== false ) {
						fseek( $fp, $p + 1 - strlen( $buf ), 1 );
						fwrite( $tempfp, substr( $buf, 0, $p ) );
						$buf = '';
					} elseif ( strlen( $buf ) == self::BUFSIZE ) {
						fwrite( $tempfp, substr( $buf, 0, -1 -strlen( $boundary ) ) );
						$buf = substr( $buf, -1 -strlen( $boundary ) ) . fread( $fp, self::BUFSIZE - 1 - strlen( $boundary ) );
					} else {
						fwrite( $tempfp, $buf );
						$buf = '';
					}
				}
			}
			fclose( $tempfp );
		}
		fclose( $fp );
		return true;
	}

	/**
	 * Pack all files into an archive file with name $arcfn
	 */
	function archive( $arcfn ) {
		$fp = fopen( $arcfn, "wb" );
		if ( !$fp ) {
			return false;
		}
		$boundary = "--" . time();
		fwrite( $fp, "Content-Type: multipart/related; boundary=$boundary\n$boundary\n" );
		fwrite( $fp, "Content-Type: text/xml\nContent-ID: Revisions\n" .
			"Content-Length: " . filesize( $this->tempdir . '/Revisions' ) . "\n\n" );
		$tempfp = fopen( $this->tempdir . '/Revisions', "rb" );
		while ( ( $buf = fread( $tempfp, self::BUFSIZE ) ) !== '' ) {
			fwrite( $fp, $buf );
		}
		fclose( $tempfp );
		foreach ( $this->parts as $name => $file ) {
			fwrite( $fp, "$boundary\nContent-ID: $name\nContent-Length: " . filesize( $file ) . "\n\n" );
			$tempfp = fopen( $file, "rb" );
			while ( ( $buf = fread( $tempfp, self::BUFSIZE ) ) !== '' ) {
				fwrite( $fp, $buf );
			}
			fclose( $tempfp );
		}
		fclose( $fp );
		return true;
	}
}

/**
 * ZIPped dump archive (does not preserve uploaded file names)
 */
class ZipDumpArchive extends DumpArchive {

	var $mimetype = 'application/zip', $extension = 'zip';

	/**
	 * Unpack the archive
	 */
	function tryUnpack( $archive ) {
		global $wgUnzip;
		$retval = 0;
		$out = wfShellExec( wfEscapeShellArg( $wgUnzip, $archive, '-d', $this->tempdir ) . ' 2>&1', $retval );
		if ( $retval != 0 ) {
			wfDebug( __CLASS__ . ": unzip failed: $out\n" );
		}
		return true;
	}

	/**
	 * Pack all files into an archive
	 */
	function archive( $arcfn ) {
		global $wgZip;
		$args = array_merge( array( $wgZip, '-j', $arcfn ), array_keys( $this->files ) );
		$retval = 0;
		$out = wfShellExec(
			call_user_func_array( 'wfEscapeShellArg', $args ) . ' 2>&1',
			$retval, array(), array( 'time' => 0, 'memory' => 0, 'filesize' => 0 )
		);
		if ( $retval != 0 ) {
			wfDebug( __CLASS__ . ": zip failed: $out\n" );
		}
		return true;
	}

}
