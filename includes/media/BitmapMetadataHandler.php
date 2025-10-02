<?php
/**
 * Extraction of metadata from different bitmap image types.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\XMPReader\Reader as XMPReader;

/**
 * Class to deal with reconciling and extracting metadata from bitmap images.
 * This is meant to comply with http://www.metadataworkinggroup.org/pdf/mwg_guidance.pdf
 *
 * This sort of acts as an intermediary between MediaHandler::getMetadata
 * and the various metadata extractors.
 *
 * @todo Other image formats.
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should become a stateless service, or a handler managed
 *       registry for metadata handlers for different file types.
 * @ingroup Media
 */
class BitmapMetadataHandler {
	/** @var array */
	private $metadata = [];

	/** @var array Metadata priority */
	private $metaPriority = [
		20 => [ 'other' ],
		40 => [ 'native' ],
		60 => [ 'iptc-good-hash', 'iptc-no-hash' ],
		70 => [ 'xmp-deprecated' ],
		80 => [ 'xmp-general' ],
		90 => [ 'xmp-exif' ],
		100 => [ 'iptc-bad-hash' ],
		120 => [ 'exif' ],
	];

	/** @var string */
	private $iptcType = 'iptc-no-hash';

	/**
	 * This does the photoshop image resource app13 block
	 * of interest, IPTC-IIM metadata is stored here.
	 *
	 * Mostly just calls doPSIR and doIPTC
	 *
	 * @param string $app13 String containing app13 block from jpeg file
	 */
	private function doApp13( $app13 ) {
		try {
			$this->iptcType = JpegMetadataExtractor::doPSIR( $app13 );
		} catch ( InvalidPSIRException $e ) {
			// Error reading the iptc hash information.
			// This probably means the App13 segment is something other than what we expect.
			// However, still try to read it, and treat it as if the hash didn't exist.
			wfDebug( "Error parsing iptc data of file: " . $e->getMessage() );
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
	 * @param string $filename
	 * @param string $byteOrder
	 */
	public function getExif( $filename, $byteOrder ) {
		$showEXIF = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::ShowEXIF );
		if ( file_exists( $filename ) && $showEXIF ) {
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
	 * @param array $metaArray Array of metadata values
	 * @param string $type Type. defaults to other. if two things have the same type they're merged
	 */
	public function addMetadata( $metaArray, $type = 'other' ) {
		if ( isset( $this->metadata[$type] ) ) {
			/* merge with old data */
			$metaArray += $this->metadata[$type];
		}

		$this->metadata[$type] = $metaArray;
	}

	/**
	 * Merge together the various types of metadata
	 * the different types have different priorities,
	 * and are merged in order.
	 *
	 * This function is generally called by the media handlers' getMetadata()
	 *
	 * @return array
	 */
	public function getMetadataArray() {
		// this seems a bit ugly... This is all so its merged in right order
		// based on the MWG recommendation.
		$temp = [];
		krsort( $this->metaPriority );
		foreach ( $this->metaPriority as $pri ) {
			foreach ( $pri as $type ) {
				if ( isset( $this->metadata[$type] ) ) {
					// Do some special casing for multilingual values.
					// Don't discard translations if also as a simple value.
					foreach ( $this->metadata[$type] as $itemName => $item ) {
						if ( is_array( $item ) && isset( $item['_type'] ) && $item['_type'] === 'lang' &&
							isset( $temp[$itemName] ) && !is_array( $temp[$itemName] )
						) {
							$default = $temp[$itemName];
							$temp[$itemName] = $item;
							$temp[$itemName]['x-default'] = $default;
							unset( $this->metadata[$type][$itemName] );
						}
					}

					$temp += $this->metadata[$type];
				}
			}
		}

		return $temp;
	}

	/** Main entry point for jpeg's.
	 *
	 * @param string $filename Filename (with full path)
	 * @return array Metadata result array.
	 * @throws InvalidJpegException
	 */
	public static function Jpeg( $filename ) {
		$showXMP = XMPReader::isSupported();
		$meta = new self();

		$seg = JpegMetadataExtractor::segmentSplitter( $filename );

		if ( isset( $seg['SOF'] ) ) {
			$meta->addMetadata( [ 'SOF' => $seg['SOF'] ] );
		}
		if ( isset( $seg['COM'] ) && isset( $seg['COM'][0] ) ) {
			$meta->addMetadata( [ 'JPEGFileComment' => $seg['COM'] ], 'native' );
		}
		if ( isset( $seg['PSIR'] ) && count( $seg['PSIR'] ) > 0 ) {
			foreach ( $seg['PSIR'] as $curPSIRValue ) {
				$meta->doApp13( $curPSIRValue );
			}
		}
		if ( isset( $seg['XMP'] ) && $showXMP ) {
			$xmp = new XMPReader( LoggerFactory::getInstance( 'XMP' ), $filename );
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

		$meta->getExif( $filename, $seg['byteOrder'] ?? 'BE' );

		return $meta->getMetadataArray();
	}

	/** Entry point for png
	 * At some point in the future this might
	 * merge the png various tEXt chunks to that
	 * are interesting, but for now it only does XMP
	 *
	 * @param string $filename Full path to file
	 * @return array Array for storage in img_metadata.
	 */
	public static function PNG( $filename ) {
		$showXMP = XMPReader::isSupported();

		$meta = new self();
		$array = PNGMetadataExtractor::getMetadata( $filename );
		if ( isset( $array['text']['xmp']['x-default'] )
			&& $array['text']['xmp']['x-default'] !== '' && $showXMP
		) {
			$xmp = new XMPReader( LoggerFactory::getInstance( 'XMP' ), $filename );
			$xmp->parse( $array['text']['xmp']['x-default'] );
			$xmpRes = $xmp->getResults();
			foreach ( $xmpRes as $type => $xmpSection ) {
				$meta->addMetadata( $xmpSection, $type );
			}
		}

		if ( $array['exif'] ) {
			// The Exif section is essentially an embedded tiff file,
			// so just extract it and read it.
			$tmpFile = MediaWikiServices::getInstance()->
				getTempFSFileFactory()->
				newTempFSFile( 'png-exif_', 'tiff' );
			$exifDataFile = $tmpFile->getPath();
			file_put_contents( $exifDataFile, $array['exif'] );
			$byteOrder = self::getTiffByteOrder( $exifDataFile );
			$meta->getExif( $exifDataFile, $byteOrder );
		}
		unset( $array['exif'] );
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
	 * @param string $filename Full path to file
	 * @return array Metadata array
	 */
	public static function GIF( $filename ) {
		$meta = new self();
		$baseArray = GIFMetadataExtractor::getMetadata( $filename );

		if ( count( $baseArray['comment'] ) > 0 ) {
			$meta->addMetadata( [ 'GIFFileComment' => $baseArray['comment'] ], 'native' );
		}

		if ( $baseArray['xmp'] !== '' && XMPReader::isSupported() ) {
			$xmp = new XMPReader( LoggerFactory::getInstance( 'XMP' ), $filename );
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
	 * @todo Add XMP support, so this function actually makes sense to put here.
	 *
	 * The various exceptions this throws are caught later.
	 * @param string $filename
	 * @throws InvalidTiffException
	 * @return array The metadata.
	 */
	public static function Tiff( $filename ) {
		if ( file_exists( $filename ) ) {
			$byteOrder = self::getTiffByteOrder( $filename );
			if ( !$byteOrder ) {
				throw new InvalidTiffException(
					'Error determining byte order of {filename}',
					[ 'filename' => $filename ]
				);
			}
			$exif = new Exif( $filename, $byteOrder );
			$data = $exif->getFilteredData();
			if ( $data ) {
				$data['MEDIAWIKI_EXIF_VERSION'] = Exif::version();

				return $data;
			} else {
				throw new InvalidTiffException(
					'Could not extract data from tiff file {filename}',
					[ 'filename' => $filename ]
				);
			}
		} else {
			throw new InvalidTiffException(
				"File {filename} doesn't exist",
				[ 'filename' => $filename ]
			);
		}
	}

	/**
	 * Read the first 2 bytes of a tiff file to figure out
	 * Little Endian or Big Endian. Needed for exif stuff.
	 *
	 * @param string $filename
	 * @return string|false 'BE' or 'LE' or false
	 */
	public static function getTiffByteOrder( $filename ) {
		$fh = fopen( $filename, 'rb' );
		if ( !$fh ) {
			return false;
		}
		$head = fread( $fh, 2 );
		fclose( $fh );

		switch ( $head ) {
			case 'II':
				return 'LE'; // II for intel.
			case 'MM':
				return 'BE'; // MM for motorla.
			default:
				return false; // Something went wrong.

		}
	}
}
