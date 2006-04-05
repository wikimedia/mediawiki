<?php
/**
 * @package MediaWiki
 * @subpackage Metadata
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
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
 * @link http://exif.org/Exif2-2.PDF The Exif 2.2 specification
 * @bug 1555, 1947
 */

/**#@+
 * Exif tag type definition
 */
define('MW_EXIF_BYTE', 1);		# An 8-bit (1-byte) unsigned integer.
define('MW_EXIF_ASCII', 2);		# An 8-bit byte containing one 7-bit ASCII code. The final byte is terminated with NULL.
define('MW_EXIF_SHORT', 3);		# A 16-bit (2-byte) unsigned integer.
define('MW_EXIF_LONG', 4);		# A 32-bit (4-byte) unsigned integer.
define('MW_EXIF_RATIONAL', 5);		# Two LONGs. The first LONG is the numerator and the second LONG expresses the denominator
define('MW_EXIF_UNDEFINED', 7);		# An 8-bit byte that can take any value depending on the field definition
define('MW_EXIF_SLONG', 9);		# A 32-bit (4-byte) signed integer (2's complement notation),
define('MW_EXIF_SRATIONAL', 10);	# Two SLONGs. The first SLONG is the numerator and the second SLONG is the denominator.
/**#@-*/


/**
 * @package MediaWiki
 * @subpackage Metadata
 */
class Exif {
	/**#@+
	 * @var array
	 * @access private
	 */

	/**
	 * Exif tags grouped by category, the tagname itself is the key and the type
	 * is the value, in the case of more than one possible value type they are
	 * seperated by commas.
	 */
	var $mExifTags;

	/**
	 * A one dimentional array of all Exif tags
	 */
	var $mFlatExifTags;

	/**
	 * The raw Exif data returned by exif_read_data()
	 */
	var $mRawExifData;

	/**
	 * A Filtered version of $mRawExifData that has been pruned of invalid
	 * tags and tags that contain content they shouldn't contain according
	 * to the Exif specification
	 */
	var $mFilteredExifData;

	/**
	 * Filtered and formatted Exif data, see FormatExif::getFormattedData()
	 */
	var $mFormattedExifData;

	/**#@-*/

	/**#@+
	 * @var string
	 * @access private
	 */

	/**
	 * The file being processed
	 */
	var $file;

	/**
	 * The basename of the file being processed
	 */
	var $basename;

	/**
	 * The private log to log to
	 */
	var $log = 'exif';

	/**#@-*/

	/**
	 * Constructor
	 *
	 * @param string $file
	 */
	function Exif( $file ) {
		/**
		 * Page numbers here refer to pages in the EXIF 2.2 standard
		 *
		 * @link http://exif.org/Exif2-2.PDF The Exif 2.2 specification
		 */
		$this->mExifTags = array(
			# TIFF Rev. 6.0 Attribute Information (p22)
			'tiff' => array(
				# Tags relating to image structure
				'structure' => array(
					'ImageWidth' => MW_EXIF_SHORT.','.MW_EXIF_LONG,		# Image width
					'ImageLength' => MW_EXIF_SHORT.','.MW_EXIF_LONG,	# Image height
					'BitsPerSample' => MW_EXIF_SHORT,			# Number of bits per component
					# "When a primary image is JPEG compressed, this designation is not"
					# "necessary and is omitted." (p23)
					'Compression' => MW_EXIF_SHORT,				# Compression scheme #p23
					'PhotometricInterpretation' => MW_EXIF_SHORT,		# Pixel composition #p23
					'Orientation' => MW_EXIF_SHORT,				# Orientation of image #p24
					'SamplesPerPixel' => MW_EXIF_SHORT,			# Number of components
					'PlanarConfiguration' => MW_EXIF_SHORT,			# Image data arrangement #p24
					'YCbCrSubSampling' => MW_EXIF_SHORT,			# Subsampling ratio of Y to C #p24
					'YCbCrPositioning' => MW_EXIF_SHORT,			# Y and C positioning #p24-25
					'XResolution' => MW_EXIF_RATIONAL,			# Image resolution in width direction
					'YResolution' => MW_EXIF_RATIONAL,			# Image resolution in height direction
					'ResolutionUnit' => MW_EXIF_SHORT,			# Unit of X and Y resolution #(p26)
				),

				# Tags relating to recording offset
				'offset' => array(
					'StripOffsets' => MW_EXIF_SHORT.','.MW_EXIF_LONG,			# Image data location
					'RowsPerStrip' => MW_EXIF_SHORT.','.MW_EXIF_LONG,			# Number of rows per strip
					'StripByteCounts' => MW_EXIF_SHORT.','.MW_EXIF_LONG,			# Bytes per compressed strip
					'JPEGInterchangeFormat' => MW_EXIF_SHORT.','.MW_EXIF_LONG,		# Offset to JPEG SOI
					'JPEGInterchangeFormatLength' => MW_EXIF_SHORT.','.MW_EXIF_LONG,	# Bytes of JPEG data
				),

				# Tags relating to image data characteristics
				'characteristics' => array(
					'TransferFunction' => MW_EXIF_SHORT,		# Transfer function
					'WhitePoint' => MW_EXIF_RATIONAL,		# White point chromaticity
					'PrimaryChromaticities' => MW_EXIF_RATIONAL,	# Chromaticities of primarities
					'YCbCrCoefficients' => MW_EXIF_RATIONAL,	# Color space transformation matrix coefficients #p27
					'ReferenceBlackWhite' => MW_EXIF_RATIONAL	# Pair of black and white reference values
				),

				# Other tags
				'other' => array(
					'DateTime' => MW_EXIF_ASCII,            # File change date and time
					'ImageDescription' => MW_EXIF_ASCII,    # Image title
					'Make' => MW_EXIF_ASCII,                # Image input equipment manufacturer
					'Model' => MW_EXIF_ASCII,               # Image input equipment model
					'Software' => MW_EXIF_ASCII,            # Software used
					'Artist' => MW_EXIF_ASCII,              # Person who created the image
					'Copyright' => MW_EXIF_ASCII,           # Copyright holder
				),
			),

			# Exif IFD Attribute Information (p30-31)
			'exif' => array(
				# Tags relating to version
				'version' => array(
					# TODO: NOTE: Nonexistence of this field is taken to mean nonconformance
					# to the EXIF 2.1 AND 2.2 standards
					'ExifVersion' => MW_EXIF_UNDEFINED,	# Exif version
					'FlashpixVersion' => MW_EXIF_UNDEFINED, # Supported Flashpix version #p32
				),

				# Tags relating to Image Data Characteristics
				'characteristics' => array(
					'ColorSpace' => MW_EXIF_SHORT,		# Color space information #p32
				),

				# Tags relating to image configuration
				'configuration' => array(
					'ComponentsConfiguration' => MW_EXIF_UNDEFINED,		# Meaning of each component #p33
					'CompressedBitsPerPixel' => MW_EXIF_RATIONAL,		# Image compression mode
					'PixelYDimension' => MW_EXIF_SHORT.','.MW_EXIF_LONG,	# Valid image width
					'PixelXDimension' => MW_EXIF_SHORT.','.MW_EXIF_LONG,	# Valind image height
				),

				# Tags relating to related user information
				'user' => array(
					'MakerNote' => MW_EXIF_UNDEFINED,			# Manufacturer notes
					'UserComment' => MW_EXIF_UNDEFINED,			# User comments #p34
				),

				# Tags relating to related file information
				'related' => array(
					'RelatedSoundFile' => MW_EXIF_ASCII,			# Related audio file
				),

				# Tags relating to date and time
				'dateandtime' => array(
					'DateTimeOriginal' => MW_EXIF_ASCII,			# Date and time of original data generation #p36
					'DateTimeDigitized' => MW_EXIF_ASCII,			# Date and time of original data generation
					'SubSecTime' => MW_EXIF_ASCII,				# DateTime subseconds
					'SubSecTimeOriginal' => MW_EXIF_ASCII,			# DateTimeOriginal subseconds
					'SubSecTimeDigitized' => MW_EXIF_ASCII,			# DateTimeDigitized subseconds
				),

				# Tags relating to picture-taking conditions (p31)
				'conditions' => array(
					'ExposureTime' => MW_EXIF_RATIONAL,			# Exposure time
					'FNumber' => MW_EXIF_RATIONAL,				# F Number
					'ExposureProgram' => MW_EXIF_SHORT,			# Exposure Program #p38
					'SpectralSensitivity' => MW_EXIF_ASCII,			# Spectral sensitivity
					'ISOSpeedRatings' => MW_EXIF_SHORT,			# ISO speed rating
					'OECF' => MW_EXIF_UNDEFINED,				# Optoelectronic conversion factor
					'ShutterSpeedValue' => MW_EXIF_SRATIONAL,		# Shutter speed
					'ApertureValue' => MW_EXIF_RATIONAL,			# Aperture
					'BrightnessValue' => MW_EXIF_SRATIONAL,			# Brightness
					'ExposureBiasValue' => MW_EXIF_SRATIONAL,		# Exposure bias
					'MaxApertureValue' => MW_EXIF_RATIONAL,			# Maximum land aperture
					'SubjectDistance' => MW_EXIF_RATIONAL,			# Subject distance
					'MeteringMode' => MW_EXIF_SHORT,			# Metering mode #p40
					'LightSource' => MW_EXIF_SHORT,				# Light source #p40-41
					'Flash' => MW_EXIF_SHORT,				# Flash #p41-42
					'FocalLength' => MW_EXIF_RATIONAL,			# Lens focal length
					'SubjectArea' => MW_EXIF_SHORT,				# Subject area
					'FlashEnergy' => MW_EXIF_RATIONAL,			# Flash energy
					'SpatialFrequencyResponse' => MW_EXIF_UNDEFINED,	# Spatial frequency response
					'FocalPlaneXResolution' => MW_EXIF_RATIONAL,		# Focal plane X resolution
					'FocalPlaneYResolution' => MW_EXIF_RATIONAL,		# Focal plane Y resolution
					'FocalPlaneResolutionUnit' => MW_EXIF_SHORT,		# Focal plane resolution unit #p46
					'SubjectLocation' => MW_EXIF_SHORT,			# Subject location
					'ExposureIndex' => MW_EXIF_RATIONAL,			# Exposure index
					'SensingMethod' => MW_EXIF_SHORT,			# Sensing method #p46
					'FileSource' => MW_EXIF_UNDEFINED,			# File source #p47
					'SceneType' => MW_EXIF_UNDEFINED,			# Scene type #p47
					'CFAPattern' => MW_EXIF_UNDEFINED,			# CFA pattern
					'CustomRendered' => MW_EXIF_SHORT,			# Custom image processing #p48
					'ExposureMode' => MW_EXIF_SHORT,			# Exposure mode #p48
					'WhiteBalance' => MW_EXIF_SHORT,			# White Balance #p49
					'DigitalZoomRatio' => MW_EXIF_RATIONAL,			# Digital zoom ration
					'FocalLengthIn35mmFilm' => MW_EXIF_SHORT,		# Focal length in 35 mm film
					'SceneCaptureType' => MW_EXIF_SHORT,			# Scene capture type #p49
					'GainControl' => MW_EXIF_RATIONAL,			# Scene control #p49-50
					'Contrast' => MW_EXIF_SHORT,				# Contrast #p50
					'Saturation' => MW_EXIF_SHORT,				# Saturation #p50
					'Sharpness' => MW_EXIF_SHORT,				# Sharpness #p50
					'DeviceSettingDescription' => MW_EXIF_UNDEFINED,	# Desice settings description
					'SubjectDistanceRange' => MW_EXIF_SHORT,		# Subject distance range #p51
				),

				'other' => array(
					'ImageUniqueID' => MW_EXIF_ASCII,	# Unique image ID
				),
			),

			# GPS Attribute Information (p52)
			'gps' => array(
				'GPSVersionID' => MW_EXIF_BYTE,			# GPS tag version
				'GPSLatitudeRef' => MW_EXIF_ASCII,		# North or South Latitude #p52-53
				'GPSLatitude' => MW_EXIF_RATIONAL,		# Latitude
				'GPSLongitudeRef' => MW_EXIF_ASCII,		# East or West Longitude #p53
				'GPSLongitude' => MW_EXIF_RATIONAL,		# Longitude
				'GPSAltitudeRef' => MW_EXIF_BYTE,		# Altitude reference
				'GPSAltitude' => MW_EXIF_RATIONAL,		# Altitude
				'GPSTimeStamp' => MW_EXIF_RATIONAL,		# GPS time (atomic clock)
				'GPSSatellites' => MW_EXIF_ASCII,		# Satellites used for measurement
				'GPSStatus' => MW_EXIF_ASCII,			# Receiver status #p54
				'GPSMeasureMode' => MW_EXIF_ASCII,		# Measurement mode #p54-55
				'GPSDOP' => MW_EXIF_RATIONAL,			# Measurement precision
				'GPSSpeedRef' => MW_EXIF_ASCII,			# Speed unit #p55
				'GPSSpeed' => MW_EXIF_RATIONAL,			# Speed of GPS receiver
				'GPSTrackRef' => MW_EXIF_ASCII,			# Reference for direction of movement #p55
				'GPSTrack' => MW_EXIF_RATIONAL,			# Direction of movement
				'GPSImgDirectionRef' => MW_EXIF_ASCII,		# Reference for direction of image #p56
				'GPSImgDirection' => MW_EXIF_RATIONAL,		# Direction of image
				'GPSMapDatum' => MW_EXIF_ASCII,			# Geodetic survey data used
				'GPSDestLatitudeRef' => MW_EXIF_ASCII,		# Reference for latitude of destination #p56
				'GPSDestLatitude' => MW_EXIF_RATIONAL,		# Latitude destination
				'GPSDestLongitudeRef' => MW_EXIF_ASCII,		# Reference for longitude of destination #p57
				'GPSDestLongitude' => MW_EXIF_RATIONAL,		# Longitude of destination
				'GPSDestBearingRef' => MW_EXIF_ASCII,		# Reference for bearing of destination #p57
				'GPSDestBearing' => MW_EXIF_RATIONAL,		# Bearing of destination
				'GPSDestDistanceRef' => MW_EXIF_ASCII,		# Reference for distance to destination #p57-58
				'GPSDestDistance' => MW_EXIF_RATIONAL,		# Distance to destination
				'GPSProcessingMethod' => MW_EXIF_UNDEFINED,	# Name of GPS processing method
				'GPSAreaInformation' => MW_EXIF_UNDEFINED,	# Name of GPS area
				'GPSDateStamp' => MW_EXIF_ASCII,		# GPS date
				'GPSDifferential' => MW_EXIF_SHORT,		# GPS differential correction
			),
		);

		$this->file = $file;
		$this->basename = basename( $this->file );

		$this->makeFlatExifTags();

		$this->debugFile( $this->basename, __FUNCTION__, true );
		wfSuppressWarnings();
		$data = exif_read_data( $this->file );
		wfRestoreWarnings();
		/**
		 * exif_read_data() will return false on invalid input, such as
		 * when somebody uploads a file called something.jpeg
		 * containing random gibberish.
		 */
		$this->mRawExifData = $data ? $data : array();

		$this->makeFilteredData();
		$this->makeFormattedData();

		$this->debugFile( __FUNCTION__, false );
	}

	/**#@+
	 * @access private
	 */
	/**
	 * Generate a flat list of the exif tags
	 */
	function makeFlatExifTags() {
		$this->extractTags( $this->mExifTags );
	}

	/**
	 * A recursing extractor function used by makeFlatExifTags()
	 *
	 * Note: This used to use an array_walk function, but it made PHP5
	 * segfault, see `cvs diff -u -r 1.4 -r 1.5 Exif.php`
	 */
	function extractTags( &$tagset ) {
		foreach( $tagset as $key => $val ) {
			if( is_array( $val ) ) {
				$this->extractTags( $val );
			} else {
				$this->mFlatExifTags[$key] = $val;
			}
		}
	}

	/**
	 * Make $this->mFilteredExifData
	 */
	function makeFilteredData() {
		$this->mFilteredExifData = $this->mRawExifData;

		foreach( $this->mFilteredExifData as $k => $v ) {
			if ( !in_array( $k, array_keys( $this->mFlatExifTags ) ) ) {
				$this->debug( $v, __FUNCTION__, "'$k' is not a valid Exif tag" );
				unset( $this->mFilteredExifData[$k] );
			}
		}

		foreach( $this->mFilteredExifData as $k => $v ) {
			if ( !$this->validate($k, $v) ) {
				$this->debug( $v, __FUNCTION__, "'$k' contained invalid data" );
				unset( $this->mFilteredExifData[$k] );
			}
		}
	}

	function makeFormattedData( $data = null ) {
		$format = new FormatExif( $this->getFilteredData() );
		$this->mFormattedExifData = $format->getFormattedData();
	}
	/**#@-*/

	/**#@+
	 * @return array
	 */
	/**
	 * Get $this->mRawExifData
	 */
	function getData() {
		return $this->mRawExifData;
	}

	/**
	 * Get $this->mFilteredExifData
	 */
	function getFilteredData() {
		return $this->mFilteredExifData;
	}

	/**
	 * Get $this->mFormattedExifData
	 */
	function getFormattedData() {
		return $this->mFormattedExifData;
	}
	/**#@-*/

	/**
	 * The version of the output format
	 *
	 * Before the actual metadata information is saved in the database we
	 * strip some of it since we don't want to save things like thumbnails
	 * which usually accompany Exif data. This value gets saved in the
	 * database along with the actual Exif data, and if the version in the
	 * database doesn't equal the value returned by this function the Exif
	 * data is regenerated.
	 *
	 * @return int
	 */
	function version() {
		return 1; // We don't need no bloddy constants!
	}

	/**#@+
	 * Validates if a tag value is of the type it should be according to the Exif spec
	 *
	 * @access private
	 *
	 * @param mixed $in The input value to check
	 * @return bool
	 */
	function isByte( $in ) {
		if ( sprintf('%d', $in) == $in && $in >= 0 && $in <= 255 ) {
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, false );
			return false;
		}
	}

	function isASCII( $in ) {
		if ( preg_match( "/[^\x0a\x20-\x7e]/", $in ) ) {
			$this->debug( $in, __FUNCTION__, 'found a character not in our whitelist' );
			return false;
		}

		if ( preg_match( "/^\s*$/", $in ) ) {
			$this->debug( $in, __FUNCTION__, 'input consisted solely of whitespace' );
			return false;
		}

		return true;
	}

	function isShort( $in ) {
		if ( sprintf('%d', $in) == $in && $in >= 0 && $in <= 65536 ) {
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, false );
			return false;
		}
	}

	function isLong( $in ) {
		if ( sprintf('%d', $in) == $in && $in >= 0 && $in <= 4294967296 ) {
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, false );
			return false;
		}
	}

	function isRational( $in ) {
		if ( @preg_match( "/^(\d+)\/(\d+[1-9]|[1-9]\d*)$/", $in, $m ) ) { # Avoid division by zero
			return $this->isLong( $m[1] ) && $this->isLong( $m[2] );
		} else {
			$this->debug( $in, __FUNCTION__, 'fed a non-fraction value' );
			return false;
		}
	}

	function isUndefined( $in ) {
		if ( preg_match( "/^\d{4}$/", $in ) ) { // Allow ExifVersion and FlashpixVersion
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, false );
			return false;
		}
	}

	function isSlong( $in ) {
		if ( $this->isLong( abs( $in ) ) ) {
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, false );
			return false;
		}
	}

	function isSrational( $in ) {
		if ( preg_match( "/^(\d+)\/(\d+[1-9]|[1-9]\d*)$/", $in, $m ) ) { # Avoid division by zero
			return $this->isSlong( $m[0] ) && $this->isSlong( $m[1] );
		} else {
			$this->debug( $in, __FUNCTION__, 'fed a non-fraction value' );
			return false;
		}
	}
	/**#@-*/

	/**
	 * Validates if a tag has a legal value according to the Exif spec
	 *
	 * @access private
	 *
	 * @param string $tag The tag to check
	 * @param mixed  $val The value of the tag
	 * @return bool
	 */
	function validate( $tag, $val ) {
		$debug = "tag is '$tag'";
		// Fucks up if not typecast
		switch( (string)$this->mFlatExifTags[$tag] ) {
			case (string)MW_EXIF_BYTE:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isByte( $val );
			case (string)MW_EXIF_ASCII:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isASCII( $val );
			case (string)MW_EXIF_SHORT:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isShort( $val );
			case (string)MW_EXIF_LONG:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isLong( $val );
			case (string)MW_EXIF_RATIONAL:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isRational( $val );
			case (string)MW_EXIF_UNDEFINED:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isUndefined( $val );
			case (string)MW_EXIF_SLONG:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isSlong( $val );
			case (string)MW_EXIF_SRATIONAL:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isSrational( $val );
			case (string)MW_EXIF_SHORT.','.MW_EXIF_LONG:
				$this->debug( $val, __FUNCTION__, $debug );
				return $this->isShort( $val ) || $this->isLong( $val );
			default:
				$this->debug( $val, __FUNCTION__, "The tag '$tag' is unknown" );
				return false;
		}
	}

	/**
	 * Conviniance function for debugging output
	 *
	 * @access private
	 *
	 * @param mixed $in
	 * @param string $fname
	 * @param mixed $action
	 */
	 function debug( $in, $fname, $action = null ) {
		$type = gettype( $in );
		$class = ucfirst( __CLASS__ );
		if ( $type === 'array' )
			$in = print_r( $in, true );

		if ( $action === true )
			wfDebugLog( $this->log, "$class::$fname: accepted: '$in' (type: $type)\n");
		elseif ( $action === false )
			wfDebugLog( $this->log, "$class::$fname: rejected: '$in' (type: $type)\n");
		elseif ( $action === null )
			wfDebugLog( $this->log, "$class::$fname: input was: '$in' (type: $type)\n");
		else
			wfDebugLog( $this->log, "$class::$fname: $action (type: $type; content: '$in')\n");
	}

	/**
	 * Conviniance function for debugging output
	 *
	 * @access private
	 *
	 * @param string $basename The name of the file being processed
	 * @paran string $fname The name of the function calling this function
	 * @param bool $bool $io Specify whether we're beginning or ending
	 */
	function debugFile( $fname, $io ) {
		$class = ucfirst( __CLASS__ );
		if ( $io )
			wfDebugLog( $this->log, "$class::$fname: begin processing: '{$this->basename}'\n" );
		else
			wfDebugLog( $this->log, "$class::$fname: end processing: '{$this->basename}'\n" );
	}

}

/**
 * @package MediaWiki
 * @subpackage Metadata
 */
class FormatExif {
	/**
	 * The Exif data to format
	 *
	 * @var array
	 * @access private
	 */
	var $mExif;

	/**
	 * Constructor
	 *
	 * @param array $exif The Exif data to format ( as returned by
	 *                    Exif::getFilteredData() )
	 */
	function FormatExif( $exif ) {
		$this->mExif = $exif;
	}

	/**
	 * Numbers given by Exif user agents are often magical, that is they
	 * should be replaced by a detailed explanation depending on their
	 * value which most of the time are plain integers. This function
	 * formats Exif values into human readable form.
	 *
	 * @return array
	 */
	function getFormattedData() {
		global $wgLang;

		$tags =& $this->mExif;

		$resolutionunit = !isset( $tags['ResolutionUnit'] ) || $tags['ResolutionUnit'] == 2 ? 2 : 3;
		unset( $tags['ResolutionUnit'] );

		foreach( $tags as $tag => $val ) {
			switch( $tag ) {
			case 'Compression':
				switch( $val ) {
				case 1: case 6:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'PhotometricInterpretation':
				switch( $val ) {
				case 2: case 6:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'Orientation':
				switch( $val ) {
				case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'PlanarConfiguration':
				switch( $val ) {
				case 1: case 2:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			// TODO: YCbCrSubSampling
			// TODO: YCbCrPositioning

			case 'XResolution':
			case 'YResolution':
				switch( $resolutionunit ) {
					case 2:
						$tags[$tag] = $this->msg( 'XYResolution', 'i', $this->formatNum( $val ) );
						break;
					case 3:
						$this->msg( 'XYResolution', 'c', $this->formatNum( $val ) );
						break;
					default:
						$tags[$tag] = $val;
						break;
				}
				break;

			// TODO: YCbCrCoefficients  #p27 (see annex E)
			case 'ExifVersion': case 'FlashpixVersion':
				$tags[$tag] = "$val"/100;
				break;

			case 'ColorSpace':
				switch( $val ) {
				case 1: case 'FFFF.H':
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'ComponentsConfiguration':
				switch( $val ) {
				case 0: case 1: case 2: case 3: case 4: case 5: case 6:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'DateTime':
			case 'DateTimeOriginal':
			case 'DateTimeDigitized':
				if( preg_match( "/^(\d{4}):(\d\d):(\d\d) (\d\d):(\d\d):(\d\d)$/", $val ) ) {
					$tags[$tag] = $wgLang->timeanddate( wfTimestamp(TS_MW, $val) );
				}
				break;

			case 'ExposureProgram':
				switch( $val ) {
				case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'SubjectDistance':
				$tags[$tag] = $this->msg( $tag, '', $this->formatNum( $val ) );
				break;

			case 'MeteringMode':
				switch( $val ) {
				case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 255:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'LightSource':
				switch( $val ) {
				case 0: case 1: case 2: case 3: case 4: case 9: case 10: case 11:
				case 12: case 13: case 14: case 15: case 17: case 18: case 19: case 20:
				case 21: case 22: case 23: case 24: case 255:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			// TODO: Flash
			case 'FocalPlaneResolutionUnit':
				switch( $val ) {
				case 2:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'SensingMethod':
				switch( $val ) {
				case 1: case 2: case 3: case 4: case 5: case 7: case 8:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'FileSource':
				switch( $val ) {
				case 3:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'SceneType':
				switch( $val ) {
				case 1:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'CustomRendered':
				switch( $val ) {
				case 0: case 1:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'ExposureMode':
				switch( $val ) {
				case 0: case 1: case 2:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'WhiteBalance':
				switch( $val ) {
				case 0: case 1:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'SceneCaptureType':
				switch( $val ) {
				case 0: case 1: case 2: case 3:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GainControl':
				switch( $val ) {
				case 0: case 1: case 2: case 3: case 4:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'Contrast':
				switch( $val ) {
				case 0: case 1: case 2:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'Saturation':
				switch( $val ) {
				case 0: case 1: case 2:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'Sharpness':
				switch( $val ) {
				case 0: case 1: case 2:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'SubjectDistanceRange':
				switch( $val ) {
				case 0: case 1: case 2: case 3:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSLatitudeRef':
			case 'GPSDestLatitudeRef':
				switch( $val ) {
				case 'N': case 'S':
					$tags[$tag] = $this->msg( 'GPSLatitude', $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSLongitudeRef':
			case 'GPSDestLongitudeRef':
				switch( $val ) {
				case 'E': case 'W':
					$tags[$tag] = $this->msg( 'GPSLongitude', $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSStatus':
				switch( $val ) {
				case 'A': case 'V':
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSMeasureMode':
				switch( $val ) {
				case 2: case 3:
					$tags[$tag] = $this->msg( $tag, $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSSpeedRef':
			case 'GPSDestDistanceRef':
				switch( $val ) {
				case 'K': case 'M': case 'N':
					$tags[$tag] = $this->msg( 'GPSSpeed', $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSTrackRef':
			case 'GPSImgDirectionRef':
			case 'GPSDestBearingRef':
				switch( $val ) {
				case 'T': case 'M':
					$tags[$tag] = $this->msg( 'GPSDirection', $val );
					break;
				default:
					$tags[$tag] = $val;
					break;
				}
				break;

			case 'GPSDateStamp':
				$tags[$tag] = $wgLang->date( substr( $val, 0, 4 ) . substr( $val, 5, 2 ) . substr( $val, 8, 2 ) . '000000' );
				break;

			// This is not in the Exif standard, just a special
			// case for our purposes which enables wikis to wikify
			// the make, model and software name to link to their articles.
			case 'Make':
			case 'Model':
			case 'Software':
				$tags[$tag] = $this->msg( $tag, '', $val );
				break;

			case 'ExposureTime':
				// Show the pretty fraction as well as decimal version
				$tags[$tag] = wfMsg( 'exif-exposuretime-format',
					$this->formatFraction( $val ), $this->formatNum( $val ) );
				break;

			case 'FNumber':
				$tags[$tag] = wfMsg( 'exif-fnumber-format',
					$this->formatNum( $val ) );
				break;

			case 'FocalLength':
				$tags[$tag] = wfMsg( 'exif-focallength-format',
					$this->formatNum( $val ) );
				break;

			default:
				$tags[$tag] = $this->formatNum( $val );
				break;
			}
		}

		return $tags;
	}

	/**
	 * Conviniance function for getFormattedData()
	 *
	 * @access private
	 *
	 * @param string $tag The tag name to pass on
	 * @param string $val The value of the tag
	 * @param string $arg An argument to pass ($1)
	 * @return string A wfMsg of "exif-$tag-$val" in lower case
	 */
	function msg( $tag, $val, $arg = null ) {
		global $wgContLang;

		if ($val === '')
			$val = 'value';
		return wfMsg( $wgContLang->lc( "exif-$tag-$val" ), $arg );
	}

	/**
	 * Format a number, convert numbers from fractions into floating point
	 * numbers
	 *
	 * @access private
	 *
	 * @param mixed $num The value to format
	 * @return mixed A floating point number or whatever we were fed
	 */
	function formatNum( $num ) {
		if ( preg_match( '/^(\d+)\/(\d+)$/', $num, $m ) )
			return $m[2] != 0 ? $m[1] / $m[2] : $num;
		else
			return $num;
	}

	/**
	 * Format a rational number, reducing fractions
	 *
	 * @access private
	 *
	 * @param mixed $num The value to format
	 * @return mixed A floating point number or whatever we were fed
	 */
	function formatFraction( $num ) {
		if ( preg_match( '/^(\d+)\/(\d+)$/', $num, $m ) ) {
			$numerator = intval( $m[1] );
			$denominator = intval( $m[2] );
			$gcd = $this->gcd( $numerator, $denominator );
			if( $gcd != 0 ) {
				// 0 shouldn't happen! ;)
				return $numerator / $gcd . '/' . $denominator / $gcd;
			}
		}
		return $this->formatNum( $num );
	}

	/**
	 * Calculate the greatest common divisor of two integers.
	 *
	 * @param int $a
	 * @param int $b
	 * @return int
	 * @access private
	 */
	function gcd( $a, $b ) {
		/*
			// http://en.wikipedia.org/wiki/Euclidean_algorithm
			// Recursive form would be:
			if( $b == 0 )
				return $a;
			else
				return gcd( $b, $a % $b );
		*/
		while( $b != 0 ) {
			$remainder = $a % $b;

			// tail recursion...
			$a = $b;
			$b = $remainder;
		}
		return $a;
	}
}
?>
