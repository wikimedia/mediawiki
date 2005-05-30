<?php
if (defined('MEDIAWIKI')) {
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @link http://exif.org/Exif2-2.PDF The Exif 2.2 specification
 * @bug 1555, 1947
 */

/**#@+
 * Exif tag type definition
 */
define('MW_EXIF_BYTE', 1);		# An 8-bit unsigned integer.
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
	 */
	 
	/**
	 * Exif tags grouped by category, the tagname itself is the key and the type
	 * is the value, in the case of more than one possible value type they are 
	 * seperated by commas.
	 *
	 * @access private
	 */
	var $mExif;
	
	/**
	 * A one dimentional array of all Exif tags
	 */
	var $mFlatExif;
	
	/**#@-*/

	/**
	 * Constructor
	 */
	function Exif() {
		/**
		 * Page numbers here refer to pages in the EXIF 2.2 standard
		 *
		 * @link http://exif.org/Exif2-2.PDF The Exif 2.2 specification
		 */
		$this->mExif = array(
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
					'FocalPlaneResolutionUnit' => MW_EXIF_SHORT,		# Focal plane resolution unit
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

		$this->makeFlatExifTags();
	}

	/**
	 * Generate a flat list of the exif tags
	 */
	function makeFlatExifTags() {
		$this->extractTags( $this->mExif );
	}
	
	/**
	 * A recursing extractor function used by makeFlatExifTags()
	 *
	 * Note: This used to use an array_walk function, but it made PHP5
	 * segfault, see `cvs diff -u -r 1.4 -r 1.5 Exif.php`
	 */
	function extractTags( $tagset ) {
		foreach( $tagset as $key => $val ) {
			if( is_array( $val ) ) {
				$this->extractTags( $val );
			} else {
				$this->mFlatExif[$key] = $val;
			}
		}
	}
	
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
	 * @param mixed $in The input value to check
	 * @return bool
	 */
	function isByte( $in ) {
		if ( sprintf('%d', $in) === $in && $in >= 0 && $in <= 255 ) {
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
		
		if ( strlen($in) > 1024 ) { // TODO: This might not be according to the spec
			$this->debug( $in, __FUNCTION__, 'input was too long' );
			return false;
		}
		
		return true;
	}

	function isShort( $in ) {
		if ( sprintf('%d', $in) === $in && $in >= 0 && $in <= 65536 ) {
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, true );
			return false;
		}
	}

	function isLong( $in ) {
		if ( sprintf('%d', $in) === $in && $in >= 0 && $in <= 4294967296 ) {
			$this->debug( $in, __FUNCTION__, true );
			return true;
		} else {
			$this->debug( $in, __FUNCTION__, false );
			return false;
		}
	}
	
	function isRational( $in ) {
		if ( preg_match( "/^(\d+)\/(\d+[1-9]|[1-9]\d*)$/", $in, $m ) ) { # Avoid division by zero
			return $this->isLong( $m[1] ) && $this->isLong( $m[2] );
		} else {
			$this->debug( $in, __FUNCTION__, 'fed a non-fraction value' );
			return false;
		}
	}

	function isUndefined( $in ) {
		$this->debug( $in, __FUNCTION__ );
		if ( preg_match( "/^\d{4}$/", $in ) ) { // Allow ExifVersion and FlashpixVersion
			return true;
		} else {
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
	 * @param string $tag The tag to check
	 * @param mixed  $val The value of the tag
	 * @return bool
	 */
	function validate( $tag, $val ) {
		// Fucks up if not typecast 
		switch( (string)$this->mFlatExif[$tag] ) {
			case (string)MW_EXIF_BYTE:
				return $this->isByte( $val );
			case (string)MW_EXIF_ASCII:
				return $this->isASCII( $val );
			case (string)MW_EXIF_SHORT:
				return $this->isShort( $val );
			case (string)MW_EXIF_LONG:
				return $this->isLong( $val );
			case (string)MW_EXIF_RATIONAL:
				return $this->isRational( $val );
			case (string)MW_EXIF_UNDEFINED:
				return $this->isUndefined( $val );
			case (string)MW_EXIF_SLONG:
				return $this->isSlong( $val );
			case (string)MW_EXIF_SRATIONAL:
				return $this->isSrational( $val );
			case (string)MW_EXIF_SHORT.','.MW_EXIF_LONG:
				return $this->isShort( $val ) || $this->isLong( $val );
			default:
				wfDebug( ucfirst( __CLASS__ ) . '::' . __FUNCTION__ .
					": The tag '$tag' in unknown (type: " . gettype( $val ) . "; content: '$val')\n" );
				return false;
		}
	}

	/**
	 * Numbers given by Exif user agents are often magical, that is they
	 * should be replaced by a detailed explanation depending on their
	 * value which most of the time are plain integers. This function
	 * formats Exif values into human readable form.
	 *
	 * @param string $tag The tag to be formatted
	 * @param mixed  $val The value of the tag
	 * @return string
	 */
	function format( $tag, $val ) {
		global $wgLang;
		
		switch( $tag ) {
		case 'Compression':
			switch( $val ) {
			case 1: case 6:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'PhotometricInterpretation':
			switch( $val ) {
			case 2: case 6:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
		
		case 'Orientation':
			switch( $val ) {
			case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
		
		case 'PlanarConfiguration':
			switch( $val ) {
			case 1: case 2:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
		
		// TODO: YCbCrSubSampling
		// TODO: YCbCrPositioning
		// TODO: If this field does not exists use 2
		case 'ResolutionUnit': #p26
			switch( $val ) {
			case 2: case 3:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
		
		// TODO: YCbCrCoefficients  #p27 (see annex E)
		case 'ExifVersion': case 'FlashpixVersion':
			return "$val"/100;
		
		case 'ColorSpace':
			switch( $val ) {
			case 1: case 'FFFF.H':
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
		
		case 'ComponentsConfiguration':
			switch( $val ) {
			case 0: case 1: case 2: case 3: case 4: case 5: case 6:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
		
		case 'DateTime':
		case 'DateTimeOriginal':
		case 'DateTimeDigitized':
			return $wgLang->timeanddate( wfTimestamp(TS_MW, $val) );
		
		case 'ExposureProgram':
			switch( $val ) {
			case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'MeteringMode':
			switch( $val ) {
			case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 255:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'LightSource':
			switch( $val ) {
			case 0: case 1: case 2: case 3: case 4: case 9: case 10: case 11:
			case 12: case 13: case 14: case 15: case 17: case 18: case 19: case 20:
			case 21: case 22: case 23: case 24: case 255:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		// TODO: Flash
		case 'SensingMethod':
			switch( $val ) {
			case 1: case 2: case 3: case 4: case 5: case 7: case 8:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'FileSource':
			switch( $val ) {
			case 3:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'SceneType':
			switch( $val ) {
			case 1:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'CustomRendered':
			switch( $val ) {
			case 0: case 1:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'ExposureMode':
			switch( $val ) {
			case 0: case 1: case 2:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'WhiteBalance':
			switch( $val ) {
			case 0: case 1:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'SceneCaptureType':
			switch( $val ) {
			case 0: case 1: case 2: case 3:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}

		case 'GainControl':
			switch( $val ) {
			case 0: case 1: case 2: case 3: case 4:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'Contrast':
			switch( $val ) {
			case 0: case 1: case 2:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'Saturation':
			switch( $val ) {
			case 0: case 1: case 2:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'Sharpness':
			switch( $val ) {
			case 0: case 1: case 2:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'SubjectDistanceRange':
			switch( $val ) {
			case 0: case 1: case 2: case 3:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'GPSLatitudeRef':
		case 'GPSDestLatitudeRef':
			switch( $val ) {
			case 'N': case 'S':
				return $this->msg( 'GPSLatitude', $val );
			default:
				return $val;
			}
			
		case 'GPSLongitudeRef':
		case 'GPSDestLongitudeRef':
			switch( $val ) {
			case 'E': case 'W':
				return $this->msg( 'GPSLongitude', $val );
			default:
				return $val;
			}
			
		case 'GPSStatus':
			switch( $val ) {
			case 'A': case 'V':
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'GPSMeasureMode':
			switch( $val ) {
			case 2: case 3:
				return $this->msg( $tag, $val );
			default:
				return $val;
			}
			
		case 'GPSSpeedRef':
		case 'GPSDestDistanceRef':
			switch( $val ) {
			case 'K': case 'M': case 'N':
				return $this->msg( 'GPSSpeed', $val );
			default:
				return $val;
			}
			
		case 'GPSTrackRef':
		case 'GPSImgDirectionRef':
		case 'GPSDestBearingRef':
			switch( $val ) {
			case 'T': case 'M':
				return $this->msg( 'GPSDirection', $val );
			default:
				return $val;
			}
			
		case 'GPSDateStamp':
			return $wgLang->date( substr( $val, 0, 4 ) . substr( $val, 5, 2 ) . substr( $val, 8, 2 ) . '000000' );

		// This is not in the Exif standard, just a special
		// case for our purposes which enables wikis to wikify
		// the make, model and software name to link to their articles.
		case 'Make':
		case 'Model':
		case 'Software':
			return wfMsg( strtolower( "exif-$tag-value" ), $val );
		default:
			if ( preg_match( '/^(\d+)\/(\d+)$/', $val, $m ) ) {
				return $m[2] != 0 ? $m[1]/$m[2] : $val;
			}
			return $val;
		}
	}

	/**
	 * Conviniance function for format()
	 *
	 * @param string $tag The tag name to pass on
	 * @param string $val The value of the tag
	 * @return string A wfMsg of "exif-$tag-$val" in lower case
	 */
	function msg( $tag, $val ) {
		return wfMsg( strtolower("exif-$tag-$val") );
	}

	/**
	 * Conviniance function for debugging output
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
			wfDebug( "$class::$fname: accepted: '$in' (type: $type)\n");
		elseif ( $action === false )
			wfDebug( "$class::$fname: rejected: '$in' (type: $type)\n");
		elseif ( $action === null )
			wfDebug( "$class::$fname: input was: '$in' (type: $type)\n");
		else
			wfDebug( "$class::$fname: $action (type: $type; content: '$in')\n");
	 }
}

} // MEDIAWIKI
