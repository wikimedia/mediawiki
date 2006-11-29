<?php
/**
 * Support for detecting/validating DjVu image files and getting
 * some basic file metadata (resolution etc)
 *
 * File format docs are available in source package for DjVuLibre:
 * http://djvulibre.djvuzone.org/
 *
 *
 * Copyright (C) 2006 Brion Vibber <brion@pobox.com>
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
 * @package MediaWiki
 */

class DjVuImage {
	function __construct( $filename ) {
		$this->mFilename = $filename;
	}
	
	/**
	 * Check if the given file is indeed a valid DjVu image file
	 * @return bool
	 */
	public function isValid() {
		$info = $this->getInfo();
		return $info !== false;
	}
	
	
	/**
	 * Return data in the style of getimagesize()
	 * @return array or false on failure
	 */
	public function getImageSize() {
		$data = $this->getInfo();
		
		if( $data !== false ) {
			$width  = $data['width'];
			$height = $data['height'];
			
			return array( $width, $height, 'DjVu',
				"width=\"$width\" height=\"$height\"" );
		}
		return false;
	}
	
	// ---------
	
	/**
	 * For debugging; dump the IFF chunk structure
	 */
	function dump() {
		$file = fopen( $this->mFilename, 'rb' );
		$header = fread( $file, 12 );
		extract( unpack( 'a4magic/a4chunk/NchunkLength', $header ) );
		echo "$chunk $chunkLength\n";
		$this->dumpForm( $file, $chunkLength, 1 );
		fclose( $file );
	}
	
	private function dumpForm( $file, $length, $indent ) {
		$start = ftell( $file );
		$secondary = fread( $file, 4 );
		echo str_repeat( ' ', $indent * 4 ) . "($secondary)\n";
		while( ftell( $file ) - $start < $length ) {
			$chunkHeader = fread( $file, 8 );
			if( $chunkHeader == '' ) {
				break;
			}
			extract( unpack( 'a4chunk/NchunkLength', $chunkHeader ) );
			echo str_repeat( ' ', $indent * 4 ) . "$chunk $chunkLength\n";
			
			if( $chunk == 'FORM' ) {
				$this->dumpForm( $file, $chunkLength, $indent + 1 );
			} else {
				fseek( $file, $chunkLength, SEEK_CUR );
				if( $chunkLength & 1 == 1 ) {
					// Padding byte between chunks
					fseek( $file, 1, SEEK_CUR );
				}
			}
		}
	}
	
	function getInfo() {
		$file = fopen( $this->mFilename, 'rb' );
		if( $file === false ) {
			wfDebug( __METHOD__ . ": missing or failed file read\n" );
			return false;
		}
		
		$header = fread( $file, 16 );
		$info = false;
		
		if( strlen( $header ) < 16 ) {
			wfDebug( __METHOD__ . ": too short file header\n" );
		} else {
			extract( unpack( 'a4magic/a4form/NformLength/a4subtype', $header ) );
			
			if( $magic != 'AT&T' ) {
				wfDebug( __METHOD__ . ": not a DjVu file\n" );
			} elseif( $subtype == 'DJVU' ) {
				// Single-page document
				$info = $this->getPageInfo( $file, $formLength );
			} elseif( $subtype == 'DJVM' ) {
				// Multi-page document
				$info = $this->getMultiPageInfo( $file, $formLength );
			} else  {
				wfDebug( __METHOD__ . ": unrecognized DJVU file type '$formType'\n" );
			}
		}
		fclose( $file );
		return $info;
	}
	
	private function readChunk( $file ) {
		$header = fread( $file, 8 );
		if( strlen( $header ) < 8 ) {
			return array( false, 0 );
		} else {
			extract( unpack( 'a4chunk/Nlength', $header ) );
			return array( $chunk, $length );
		}
	}
	
	private function skipChunk( $file, $chunkLength ) {
		fseek( $file, $chunkLength, SEEK_CUR );
		
		if( $chunkLength & 0x01 == 1 && !feof( $file ) ) {
			// padding byte
			fseek( $file, 1, SEEK_CUR );
		}
	}
	
	private function getMultiPageInfo( $file, $formLength ) {
		// For now, we'll just look for the first page in the file
		// and report its information, hoping others are the same size.
		$start = ftell( $file );
		do {
			list( $chunk, $length ) = $this->readChunk( $file );
			if( !$chunk ) {
				break;
			}
			
			if( $chunk == 'FORM' ) {
				$subtype = fread( $file, 4 );
				if( $subtype == 'DJVU' ) {
					wfDebug( __METHOD__ . ": found first subpage\n" );
					return $this->getPageInfo( $file, $length );
				}
				$this->skipChunk( $file, $length - 4 );
			} else {
				wfDebug( __METHOD__ . ": skipping '$chunk' chunk\n" );
				$this->skipChunk( $file, $length );
			}
		} while( $length != 0 && !feof( $file ) && ftell( $file ) - $start < $formLength );
		
		wfDebug( __METHOD__ . ": multi-page DJVU file contained no pages\n" );
		return false;
	}
	
	private function getPageInfo( $file, $formLength ) {
		list( $chunk, $length ) = $this->readChunk( $file );
		if( $chunk != 'INFO' ) {
			wfDebug( __METHOD__ . ": expected INFO chunk, got '$chunk'\n" );
			return false;
		}
		
		if( $length < 9 ) {
			wfDebug( __METHOD__ . ": INFO should be 9 or 10 bytes, found $length\n" );
			return false;
		}
		$data = fread( $file, $length );
		if( strlen( $data ) < $length ) {
			wfDebug( __METHOD__ . ": INFO chunk cut off\n" );
			return false;
		}
		
		extract( unpack(
			'nwidth/' .
			'nheight/' .
			'Cminor/' .
			'Cmajor/' .
			'vresolution/' .
			'Cgamma', $data ) );
		# Newer files have rotation info in byte 10, but we don't use it yet.
		
		return array(
			'width' => $width,
			'height' => $height,
			'version' => "$major.$minor",
			'resolution' => $resolution,
			'gamma' => $gamma / 10.0 );
	}

	/**
	 * Return an XML string describing the DjVu image
	 * @return string
	 */
	function retrieveMetaData() {
		global $wgDjvuToXML;
		if ( isset( $wgDjvuToXML ) ) {
			$cmd = $wgDjvuToXML . ' --without-anno --without-text ' . $this->mFilename;
			$xml = wfShellExec( $cmd );
		} else {
			$xml = null;
		}
		return $xml;
	}
		
}


?>
