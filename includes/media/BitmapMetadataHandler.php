<?php
/**
Class to deal with reconciling and extracting metadata from bitmap images.
This is meant to comply with http://www.metadataworkinggroup.org/pdf/mwg_guidance.pdf

This sort of acts as an intermediary between MediaHandler::getMetadata
and the various metadata extractors.

@todo other image formats.
*/
class BitmapMetadataHandler {

	private $metadata = Array();
	private $metaPriority = Array(
		20 => Array( 'other' ),
		40 => Array( 'native' ),
		60 => Array( 'iptc-good-hash', 'iptc-no-hash' ),
		70 => Array( 'xmp-deprecated' ),
		80 => Array( 'xmp-general' ),
		90 => Array( 'xmp-exif' ),
		100 => Array( 'iptc-bad-hash' ),
		120 => Array( 'exif' ),
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
		$this->iptcType = JpegMetadataExtractor::doPSIR( $app13 );

		$iptc = IPTC::parse( $app13 );
		$this->addMetadata( $iptc, $this->iptcType );
	}


	/** get exif info using exif class.
	* Basically what used to be in BitmapHandler::getMetadata().
	* Just calls stuff in the Exif class.
	*/
	function getExif ( $filename ) {
		if ( file_exists( $filename ) ) {
			$exif = new Exif( $filename );
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
	* @param string $file filename (with full path)
	* @return metadata result array.
	* @throws MWException on invalid file.
	*/
	static function Jpeg ( $filename ) {
		$showXMP = function_exists( 'xml_parser_create_ns' );
		$meta = new self();
		$meta->getExif( $filename );

		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		if ( isset( $seg['COM'] ) && isset( $seg['COM'][0] ) ) {
			$meta->addMetadata( Array( 'JPEGFileComment' => $seg['COM'] ), 'native' );
		}
		if ( isset( $seg['PSIR'] ) ) {
			$meta->doApp13( $seg['PSIR'] );
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
	 * @param $filename full path to file
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

}
