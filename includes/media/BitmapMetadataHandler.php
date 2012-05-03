<?php
/**
 * Extraction of metadata from different bitmap image types.
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
 * @file
 * @ingroup Media
 */

/**
 * Class to deal with reconciling and extracting metadata from bitmap images.
 * This is meant to comply with http://www.metadataworkinggroup.org/pdf/mwg_guidance.pdf
 *
 * This sort of acts as an intermediary between MediaHandler::getMetadata
 * and the various metadata extractors.
 *
 * @todo other image formats.
 * @ingroup Media
 */
class BitmapMetadataHandler {

	private $metadata = array();
	private $metaPriority = array(
		20 => array( 'other' ),
		40 => array( 'native' ),
		60 => array( 'iptc-good-hash', 'iptc-no-hash' ),
		70 => array( 'xmp-deprecated' ),
		80 => array( 'xmp-general' ),
		90 => array( 'xmp-exif' ),
		100 => array( 'iptc-bad-hash' ),
		120 => array( 'exif' ),
	);
	private $iptcType = 'iptc-no-hash';

	/**
	* This does the photoshop image resource app13 block
	* of interest, IPTC-IIM metadata is stored here.
	*
	* Mostly just calls doPSIR and doIPTC
	*
	* @param String $app13 String containing app13 block from jpeg file
	*/
	private function doApp13 ( $app13 ) {
		try {
			$this->iptcType = JpegMetadataExtractor::doPSIR( $app13 );
		} catch ( MWException $e ) {
			// Error reading the iptc hash information.
			// This probably means the App13 segment is something other than what we expect.
			// However, still try to read it, and treat it as if the hash didn't exist.
			wfDebug( "Error parsing iptc data of file: " . $e->getMessage() . "\n" );
			$this->iptcType = 'iptc-no-hash';
		}

		$iptc = IPTC::parse( $app13 );
		$this->addMetadata( $iptc, $this->iptcType );
	}


	/**
	 * Get exif info using exif class.
	 * Basically what used to be in BitmapHandler::getMetadata().
	 * Just calls stuff in the Exif class.
	 *
	 * Parameters are passed to the Exif class.
	 *
	 * @param $filename string
	 * @param $byteOrder string
	 */
	function getExif ( $filename, $byteOrder ) {
		global $wgShowEXIF;
		if ( file_exists( $filename ) && $wgShowEXIF ) {
			$exif = new Exif( $filename, $byteOrder );
			$data = $exif->getFilteredData();
			if ( $data ) {
				$this->addMetadata( $data, 'exif' );
			}
		}
	}
	/** Add misc metadata. Warning: atm if the metadata category
	* doesn't have a priority, it will be silently discarded.
	*
	* @param Array $metaArray array of metadata values
	* @param string $type type. defaults to other. if two things have the same type they're merged
	*/
	function addMetadata ( $metaArray, $type = 'other' ) {
		if ( isset( $this->metadata[$type] ) ) {
			/* merge with old data */
			$metaArray = $metaArray + $this->metadata[$type];
		}

		$this->metadata[$type] = $metaArray;
	}

	/**
	* Merge together the various types of metadata
	* the different types have different priorites,
	* and are merged in order.
	*
	* This function is generally called by the media handlers' getMetadata()
	*
	* @return Array metadata array
	*/
	function getMetadataArray () {
		// this seems a bit ugly... This is all so its merged in right order
		// based on the MWG recomendation.
		$temp = Array();
		krsort( $this->metaPriority );
		foreach ( $this->metaPriority as $pri ) {
			foreach ( $pri as $type ) {
				if ( isset( $this->metadata[$type] ) ) {
					// Do some special casing for multilingual values.
					// Don't discard translations if also as a simple value.
					foreach ( $this->metadata[$type] as $itemName => $item ) {
						if ( is_array( $item ) && isset( $item['_type'] ) && $item['_type'] === 'lang' ) {
							if ( isset( $temp[$itemName] ) && !is_array( $temp[$itemName] ) ) {
								$default = $temp[$itemName];
								$temp[$itemName] = $item;
								$temp[$itemName]['x-default'] = $default;
								unset( $this->metadata[$type][$itemName] );
							}
						}
					}

					$temp = $temp + $this->metadata[$type];
				}
			}
		}
		return $temp;
	}

	/** Main entry point for jpeg's.
	 *
	 * @param $filename string filename (with full path)
	 * @return array metadata result array.
	 * @throws MWException on invalid file.
	 */
	static function Jpeg ( $filename ) {
		$showXMP = function_exists( 'xml_parser_create_ns' );
		$meta = new self();

		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		if ( isset( $seg['COM'] ) && isset( $seg['COM'][0] ) ) {
			$meta->addMetadata( Array( 'JPEGFileComment' => $seg['COM'] ), 'native' );
		}
		if ( isset( $seg['PSIR'] ) && count( $seg['PSIR'] ) > 0 ) {
			foreach( $seg['PSIR'] as $curPSIRValue ) {
				$meta->doApp13( $curPSIRValue );
			}
		}
		if ( isset( $seg['XMP'] ) && $showXMP ) {
			$xmp = new XMPReader();
			$xmp->parse( $seg['XMP'] );
			foreach ( $seg['XMP_ext'] as $xmpExt ) {
				/* Support for extended xmp in jpeg files
				 * is not well tested and a bit fragile.
				 */
				$xmp->parseExtended( $xmpExt );

			}
			$res = $xmp->getResults();
			foreach ( $res as $type => $array ) {
				$meta->addMetadata( $array, $type );
			}
		}
		if ( isset( $seg['byteOrder'] ) ) {
			$meta->getExif( $filename, $seg['byteOrder'] );
		}
		return $meta->getMetadataArray();
	}

	/** Entry point for png
	 * At some point in the future this might
	 * merge the png various tEXt chunks to that
	 * are interesting, but for now it only does XMP
	 *
	 * @param $filename String full path to file
	 * @return Array Array for storage in img_metadata.
	 */
	static public function PNG ( $filename ) {
		$showXMP = function_exists( 'xml_parser_create_ns' );

		$meta = new self();
		$array = PNGMetadataExtractor::getMetadata( $filename );
		if ( isset( $array['text']['xmp']['x-default'] ) && $array['text']['xmp']['x-default'] !== '' && $showXMP ) {
			$xmp = new XMPReader();
			$xmp->parse( $array['text']['xmp']['x-default'] );
			$xmpRes = $xmp->getResults();
			foreach ( $xmpRes as $type => $xmpSection ) {
				$meta->addMetadata( $xmpSection, $type );
			}
		}
		unset( $array['text']['xmp'] );
		$meta->addMetadata( $array['text'], 'native' );
		unset( $array['text'] );
		$array['metadata'] = $meta->getMetadataArray();
		$array['metadata']['_MW_PNG_VERSION'] = PNGMetadataExtractor::VERSION;
		return $array;
	}

	/** function for gif images.
	 *
	 * They don't really have native metadata, so just merges together
	 * XMP and image comment.
	 *
	 * @param $filename string full path to file
	 * @return Array metadata array
	 */
	static public function GIF ( $filename ) {

		$meta = new self();
		$baseArray = GIFMetadataExtractor::getMetadata( $filename );

		if ( count( $baseArray['comment'] ) > 0 ) {
			$meta->addMetadata( array( 'GIFFileComment' => $baseArray['comment'] ), 'native' );
		}

		if ( $baseArray['xmp'] !== '' && function_exists( 'xml_parser_create_ns' ) ) {
			$xmp = new XMPReader();
			$xmp->parse( $baseArray['xmp'] );
			$xmpRes = $xmp->getResults();
			foreach ( $xmpRes as $type => $xmpSection ) {
				$meta->addMetadata( $xmpSection, $type );
			}

		}

		unset( $baseArray['comment'] );
		unset( $baseArray['xmp'] );
	
		$baseArray['metadata'] = $meta->getMetadataArray();
		$baseArray['metadata']['_MW_GIF_VERSION'] = GIFMetadataExtractor::VERSION;
		return $baseArray;
	}

	/**
	 * This doesn't do much yet, but eventually I plan to add
	 * XMP support for Tiff. (PHP's exif support already extracts
	 * but needs some further processing because PHP's exif support
	 * is stupid...)
	 *
	 * @todo Add XMP support, so this function actually makes
	 * sense to put here.
	 *
	 * The various exceptions this throws are caught later.
	 * @param $filename String
	 * @return Array The metadata.
	 */
	static public function Tiff ( $filename ) {
		if ( file_exists( $filename ) ) {
			$byteOrder = self::getTiffByteOrder( $filename );
			if ( !$byteOrder ) {
				throw new MWException( "Error determining byte order of $filename" );
			}
			$exif = new Exif( $filename, $byteOrder );
			$data = $exif->getFilteredData();
			if ( $data ) {
				$data['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
				return $data;
			} else {
				throw new MWException( "Could not extract data from tiff file $filename" );
			}
		} else {
			throw new MWException( "File doesn't exist - $filename" );
		}
	}
	/**
	 * Read the first 2 bytes of a tiff file to figure out
	 * Little Endian or Big Endian. Needed for exif stuff.
	 *
	 * @param $filename String The filename
	 * @return String 'BE' or 'LE' or false
	 */
	static function getTiffByteOrder( $filename ) {
		$fh = fopen( $filename, 'rb' );
		if ( !$fh ) return false;
		$head = fread( $fh, 2 );
		fclose( $fh );

		switch( $head ) {
			case 'II':
				return 'LE'; // II for intel.
			case 'MM':
				return 'BE'; // MM for motorla.
			default:
				return false; // Something went wrong.

		}
	}


}
