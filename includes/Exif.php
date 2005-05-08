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
	
	/**
	 * A one dimentional array of all Exif tags that we'd want to save in
	 * the database or present on a page.
	 */
	var $mValidExif;

	/**#@-*/

	/**
	 * Constructor
	 */
	function Exif() {
		/**#@+
		 * Exif tag type definition
		 */
		define('BYTE', 1);		# An 8-bit unsigned integer.
		define('ASCII', 2);		# An 8-bit byte containing one 7-bit ASCII code. The final byte is terminated with NULL.
		define('SHORT', 3);		# A 16-bit (2-byte) unsigned integer.
		define('LONG', 4);		# A 32-bit (4-byte) unsigned integer.
		define('RATIONAL', 5);		# Two LONGs. The first LONG is the numerator and the second LONG expresses the denominator
		define('UNDEFINED', 7);		# An 8-bit byte that can take any value depending on the field definition
		define('SLONG', 9);		# A 32-bit (4-byte) signed integer (2's complement notation),
		define('SRATIONAL', 10);	# Two SLONGs. The first SLONG is the numerator and the second SLONG is the denominator.
		/**#@-*/

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
					'ImageWidth' => SHORT.','.LONG,		# Image width
					'ImageLength' => SHORT.','.LONG,	# Image height
					'BitsPerSample' => SHORT,		# Number of bits per component
					# "When a primary image is JPEG compressed, this designation is not"
					# "necessary and is omitted." (p23)
					'Compression' => SHORT,			# Compression scheme #p23
					'PhotometricInterpretation' => SHORT,	# Pixel composition #p23
					'Orientation' => SHORT,			# Orientation of image #p24
					'SamplesPerPixel' => SHORT,		# Number of components
					'PlanarConfiguration' => SHORT,		# Image data arrangement #p24
					'YCbCrSubSampling' => SHORT,		# Subsampling ratio of Y to C #p24
					'YCbCrPositioning' => SHORT,		# Y and C positioning #p24-25
					'XResolution' => RATIONAL,		# Image resolution in width direction
					'YResolution' => RATIONAL,		# Image resolution in height direction
					'ResolutionUnit' => SHORT,		# Unit of X and Y resolution #(p26)
				),
				
				# Tags relating to recording offset
				'offset' => array(
					'StripOffsets' => SHORT.','.LONG,			# Image data location
					'RowsPerStrip' => SHORT.','.LONG,			# Number of rows per strip
					'StripByteCounts' => SHORT.','.LONG,			# Bytes per compressed strip
					'JPEGInterchangeFormat' => SHORT.','.LONG,		# Offset to JPEG SOI
					'JPEGInterchangeFormatLength' => SHORT.','.LONG,	# Bytes of JPEG data
				),
			
				# Tags relating to image data characteristics
				'characteristics' => array(
					'TransferFunction' => SHORT,		# Transfer function
					'WhitePoint' => RATIONAL,		# White point chromaticity
					'PrimaryChromaticities' => RATIONAL,	# Chromaticities of primarities
					'YCbCrCoefficients' => RATIONAL,	# Color space transformation matrix coefficients #p27
					'ReferenceBlackWhite' => RATIONAL	# Pair of black and white reference values
				),
			
				# Other tags
				'other' => array(
					'DateTime' => ASCII,            # File change date and time
					'ImageDescription' => ASCII,    # Image title
					'Make' => ASCII,                # Image input equipment manufacturer
					'Model' => ASCII,               # Image input equipment model
					'Software' => ASCII,            # Software used
					'Artist' => ASCII,              # Person who created the image
					'Copyright' => ASCII,           # Copyright holder
				),
			),
		
			# Exif IFD Attribute Information (p30-31)
			'exif' => array(
				# Tags relating to version
				'version' => array(
					# TODO: NOTE: Nonexistence of this field is taken to mean nonconformance
					# to the EXIF 2.1 AND 2.2 standards
					'ExifVersion' => UNDEFINED,	# Exif version
					'FlashpixVersion' => UNDEFINED, # Supported Flashpix version #p32
				),
				
				# Tags relating to Image Data Characteristics
				'characteristics' => array(
					'ColorSpace' => SHORT,		# Color space information #p32
				),
		
				# Tags relating to image configuration
				'configuration' => array(
					'ComponentsConfiguration' => UNDEFINED,		# Meaning of each component #p33
					'CompressedBitsPerPixel' => RATIONAL,		# Image compression mode
					'PixelYDimension' => SHORT.','.LONG,		# Valid image width
					'PixelXDimension' => SHORT.','.LONG,		# Valind image height
				),
				
				# Tags relating to related user information
				'user' => array(
					'MakerNote' => UNDEFINED,			# Manufacturer notes
					'UserComment' => UNDEFINED,			# User comments #p34
				),
		
				# Tags relating to related file information
				'related' => array(
					'RelatedSoundFile' => ASCII,			# Related audio file
				),
		
				# Tags relating to date and time
				'dateandtime' => array(
					'DateTimeOriginal' => ASCII,			# Date and time of original data generation #p36
					'DateTimeDigitized' => ASCII,			# Date and time of original data generation
					'SubSecTime' => ASCII,				# DateTime subseconds 
					'SubSecTimeOriginal' => ASCII,			# DateTimeOriginal subseconds
					'SubSecTimeDigitized' => ASCII,			# DateTimeDigitized subseconds
				),
				
				# Tags relating to picture-taking conditions (p31)
				'conditions' => array(
					'ExposureTime' => RATIONAL,			# Exposure time
					'FNumber' => RATIONAL,				# F Number
					'ExposureProgram' => SHORT,			# Exposure Program #p38
					'SpectralSensitivity' => ASCII,			# Spectral sensitivity
					'ISOSpeedRatings' => SHORT,			# ISO speed rating
					'OECF' => UNDEFINED,				# Optoelectronic conversion factor
					'ShutterSpeedValue' => SRATIONAL,		# Shutter speed
					'ApertureValue' => RATIONAL,			# Aperture
					'BrightnessValue' => SRATIONAL,			# Brightness
					'ExposureBiasValue' => SRATIONAL,		# Exposure bias
					'MaxApertureValue' => RATIONAL,			# Maximum land aperture
					'SubjectDistance' => RATIONAL,			# Subject distance
					'MeteringMode' => SHORT,			# Metering mode #p40
					'LightSource' => SHORT,				# Light source #p40-41
					'Flash' => SHORT,				# Flash #p41-42
					'FocalLength' => RATIONAL,			# Lens focal length
					'SubjectArea' => SHORT,				# Subject area
					'FlashEnergy' => RATIONAL,			# Flash energy
					'SpatialFrequencyResponse' => UNDEFINED,	# Spatial frequency response
					'FocalPlaneXResolution' => RATIONAL,		# Focal plane X resolution
					'FocalPlaneYResolution' => RATIONAL,		# Focal plane Y resolution
					'FocalPlaneResolutionUnit' => SHORT,		# Focal plane resolution unit
					'SubjectLocation' => SHORT,			# Subject location
					'ExposureIndex' => RATIONAL,			# Exposure index
					'SensingMethod' => SHORT,			# Sensing method #p46
					'FileSource' => UNDEFINED,			# File source #p47
					'SceneType' => UNDEFINED,			# Scene type #p47
					'CFAPattern' => UNDEFINED,			# CFA pattern
					'CustomRendered' => SHORT,			# Custom image processing #p48
					'ExposureMode' => SHORT,			# Exposure mode #p48
					'WhiteBalance' => SHORT,			# White Balance #p49
					'DigitalZoomRatio' => RATIONAL,			# Digital zoom ration
					'FocalLengthIn35mmFilm' => SHORT,		# Focal length in 35 mm film
					'SceneCaptureType' => SHORT,			# Scene capture type #p49
					'GainControl' => RATIONAL,			# Scene control #p49-50
					'Contrast' => SHORT,				# Contrast #p50
					'Saturation' => SHORT,				# Saturation #p50
					'Sharpness' => SHORT,				# Sharpness #p50
					'DeviceSettingDescription' => UNDEFINED,	# Desice settings description
					'SubjectDistanceRange' => SHORT,		# Subject distance range #p51
				),
				
				'other' => array(
					'ImageUniqueID' => ASCII,	# Unique image ID
				),
			),
		
			# GPS Attribute Information (p52)
			'gps' => array(
				'GPSVersionID' => BYTE,			# GPS tag version
				'GPSLatitudeRef' => ASCII,		# North or South Latitude #p52-53
				'GPSLatitude' => RATIONAL,		# Latitude
				'GPSLongitudeRef' => ASCII,		# East or West Longitude #p53
				'GPSLongitude' => RATIONAL,		# Longitude
				'GPSAltitudeRef' => BYTE,		# Altitude reference
				'GPSAltitude' => RATIONAL,		# Altitude
				'GPSTimeStamp' => RATIONAL,		# GPS time (atomic clock)
				'GPSSatellites' => ASCII,		# Satellites used for measurement
				'GPSStatus' => ASCII,			# Receiver status #p54
				'GPSMeasureMode' => ASCII,		# Measurement mode #p54-55
				'GPSDOP' => RATIONAL,			# Measurement precision
				'GPSSpeedRef' => ASCII,			# Speed unit #p55
				'GPSSpeed' => RATIONAL,			# Speed of GPS receiver
				'GPSTrackRef' => ASCII,			# Reference for direction of movement #p55
				'GPSTrack' => RATIONAL,			# Direction of movement
				'GPSImgDirectionRef' => ASCII,		# Reference for direction of image #p56
				'GPSImgDirection' => RATIONAL,		# Direction of image
				'GPSMapDatum' => ASCII,			# Geodetic survey data used
				'GPSDestLatitudeRef' => ASCII,		# Reference for latitude of destination #p56
				'GPSDestLatitude' => RATIONAL,		# Latitude destination
				'GPSDestLongitudeRef' => ASCII,		# Reference for longitude of destination #p57
				'GPSDestLongitude' => RATIONAL,		# Longitude of destination
				'GPSDestBearingRef' => ASCII,		# Reference for bearing of destination #p57
				'GPSDestBearing' => RATIONAL,		# Bearing of destination
				'GPSDestDistanceRef' => ASCII,		# Reference for distance to destination #p57-58
				'GPSDestDistance' => RATIONAL,		# Distance to destination
				'GPSProcessingMethod' => UNDEFINED,	# Name of GPS processing method
				'GPSAreaInformation' => UNDEFINED,	# Name of GPS area
				'GPSDateStamp' => ASCII,		# GPS date
				'GPSDifferential' => SHORT,		# GPS differential correction
			),
		);

		$this->makeFlatExifTags();
		$this->makeValidExifTags();
	}

	/**
	 * Get the raw list of exiftags
	 *
	 * @access private
	 * @return array
	*/
	function getExif() {
		return $this->mExif;
	}

	/**
	 * Generate a flat list of the exif tags
	 */
	function makeFlatExifTags() {
		$exif = $this->getExif();
		array_walk($exif, array(&$this, 'callback')); // note the reference
	}
	
	/**
	 * A callback function used by makeFlatExifTags()
	 */
	function callback($val, $key) {
		if (gettype($val) === 'array')
			array_walk($val, array(&$this, 'callback'));
		else
			$this->mFlatExif[$key] = $val;
	}

	/**
	 * Produce a list of all Exif tags appropriate for user output
	 *
	 * Produce a list of all tags that we want to show in output, in order not to 
	 * output binary gibberish such as raw thumbnails we strip all tags
	 * with the datatype of UNDEFINED.
	 *
	 * @todo We might actually want to display some of the UNDEFINED
	 *       stuff, but we strip it for now.
	 */
	function makeValidExifTags() {
		foreach($this->mFlatExif as $key => $val) {
			if (strpos( $val, (string)UNDEFINED ) !== false)
				continue;
			$this->mValidExif[] = $key;
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
		return is_numeric($in) && $in >= 0 && $in <= 255;
	}
	
	function isASCII( $in ) {
		return true; // TODO: FIXME
	}

	function isShort( $in ) {
		return is_numeric($in) && $in >= 0 && $in <= 65536;
	}

	function isLong( $in ) {
		return is_numeric($in) && $in >= 0 && $in <= 4294967296;

	}
	
	function isRational( $in ) {
		$in = explode( '/', $in, 2 );
		return $this->isLong($in[0]) && $this->isLong($in[1]);
	}

	function isUndefined( $in ) {
		return true;
	}

	function isSlong( $in ) {
		return $this->isLong( abs( $in ) );
	}

	function isSrational( $in ) {
		$in = explode( '/', $in, 2 );
		return $this->isSlong($in[0]) && $this->isSlong($in[1]);
	}
	/**#@-*/

	/**
	 * Validates if a tag has a legal value according to the Exif spec, presumes
	 * that the given tag is valid ( has been checked in advance with
	 * $this->mValidExif )
	 *
	 * @param string $tag The tag to check
	 * @param mixed  $val The value of the tag
	 * @return bool
	 */
	function validate( $tag, $val ) {
		// Fucks up if not typecast 
		switch((string)$this->mFlatExif[$tag]) {
			case (string)BYTE:
				return $this->isByte( $val );
			case (string)ASCII:
				return $this->isASCII( $val );
			case (string)SHORT:
				return $this->isShort( $val );
			case (string)LONG:
				return $this->isLong( $val );
			case (string)RATIONAL:
				return $this->isRational( $val );
			case (string)UNDEFINED:
				return $this->isUndefined( $val );
			case (string)SLONG:
				return $this->isSlong( $val );
			case (string)SRATIONAL:
				return $this->isSrational( $val );
			case (string)SHORT.','.LONG:
				return $this->isShort( $val ) || $this->isLong( $val );
			default:
				wfDebug( "Exif: The tag \"$tag\" had an invalid value: \"$val\"\n" );
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
		
		switch ($tag) {
			case 'Compression':
				switch ($val) {
					case 1: case 6:
						return $this->msg( $tag, $val );
				}
			
			case 'PhotometricInterpretation':
				switch ($val) {
					case 2: case 6:
						return $this->msg( $tag, $val );
				}
			
			case 'Orientation':
				switch ($val) {
					case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
						return $this->msg( $tag, $val );
				}
			
			// TODO: If this field does not exist use 1
			case 'PlanarConfiguration':
				switch ($val) {
					case 1: case 2:
						return $this->msg( $tag, $val );
				}
			
			// TODO: YCbCrSubSampling
			// TODO: YCbCrPositioning
			// TODO: If this field does not exists use 2
			case 'ResolutionUnit': #p26
				switch ($val) {
					case 2: case 3:
						return $this->msg( $tag, $val );
				}
			
			// TODO: YCbCrCoefficients  #p27 (see annex E)
			case 'ExifVersion': case 'FlashpixVersion':
				return "$val"/100;
			
			case 'ColorSpace':
				switch ($val) {
					case 1: case 'FFFF.H':
						return $this->msg( $tag, $val );
				}
			
			case 'ComponentsConfiguration':
				switch ($val) {
					case 0: case 1: case 2: case 3: case 4: case 5: case 6:
						return $this->msg( $tag, $val );
				}
			
			case 'DateTimeOriginal':
			case 'DateTimeDigitized':
				return $wgLang->timeanddate( wfTimestamp(TS_MW, $val) );
			case 'ExposureProgram':
				switch ($val) {
					case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
						return $this->msg( $tag, $val );
				}
			case 'MeteringMode':
				switch ($val) {
					case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 255:
						return $this->msg( $tag, $val );
				}
			case 'LightSource':
				switch ($val) {
					case 0: case 1: case 2: case 3: case 4: case 9: case 10: case 11:
					case 12: case 13: case 14: case 15: case 17: case 18: case 19: case 20:
					case 21: case 22: case 23: case 24: case 255:
						return $this->msg( $tag, $val );
				}
			// TODO: Flash
			case 'SensingMethod':
				switch ($val) {
					case 1: case 2: case 3: case 4: case 5: case 7: case 8:
						return $this->msg( $tag, $val );
				}
			case 'FileSource':
				switch ($val) {
					case 3:
						return $this->msg( $tag, $val );
				}
			case 'SceneType':
				switch ($val) {
					case 1:
						return $this->msg( $tag, $val );
				}
			case 'CustomRendered':
				switch ($val) {
					case 0: case 1:
						return $this->msg( $tag, $val );
				}
			case 'ExposureMode':
				switch ($val) {
					case 0: case 1: case 2:
						return $this->msg( $tag, $val );
				}
			case 'WhiteBalance':
				switch ($val) {
					case 0: case 1:
						return $this->msg( $tag, $val );
				}
			case 'SceneCaptureType':
				switch ($val) {
					case 0: case 1: case 2: case 3:
						return $this->msg( $tag, $val );
				}
			case 'GainControl':
				switch ($val) {
					case 0: case 1: case 2: case 3: case 4:
						return $this->msg( $tag, $val );
				}
			case 'Contrast':
				switch ($val) {
					case 0: case 1: case 2:
						return $this->msg( $tag, $val );
				}
			case 'Saturation':
				switch ($val) {
					case 0: case 1: case 2:
						return $this->msg( $tag, $val );
				}
			case 'Sharpness':
				switch ($val) {
					case 0: case 1: case 2:
						return $this->msg( $tag, $val );
				}
			case 'SubjectDistanceRange':
				switch ($val) {
					case 0: case 1: case 2: case 3:
						return $this->msg( $tag, $val );
				}
			case 'GPSLatitudeRef':
				switch ($val) {
					case 'N': case 'S':
						return $this->msg( $tag, $val );
				}
			case 'GPSLongitudeRef':
				switch ($val) {
					case 'E': case 'W':
						return $this->msg( $tag, $val );
				}
			case 'GPSStatus':
				switch ($val) {
					case 'A': case 'V':
						return $this->msg( $tag, $val );
				}
			case 'GPSMeasureMode':
				switch ($val) {
					case 2: case 3:
						return $this->msg( $tag, $val );
				}
			case 'GPSSpeedRef':
				switch ($val) {
					case 'K': case 'M': case 'N':
						return $this->msg( $tag, $val );
				}
			case 'GPSTrackRef':
				switch ($val) {
					case 'T': case 'M':
						return $this->msg( $tag, $val );
				}
			case 'GPSImgDirectionRef':
				switch ($val) {
					case 'T': case 'M':
						return $this->msg( $tag, $val );
				}
			case 'GPSDestLatitudeRef':
				switch ($val) {
					case 'N': case 'S':
						return $this->msg( $tag, $val );
				}
			case 'GPSDestLongitudeRef':
				switch ($val) {
					case 'E': case 'W':
						return $this->msg( $tag, $val );
				}
			case 'GPSDestBearingRef':
				switch ($val) {
					case 'T': case 'M':
						return $this->msg( $tag, $val );					
				}
			case 'GPSDateStamp':
				return $wgLang->date( substr($val, 0, 4) . substr($val, 5, 2) . substr($val, 8, 2) . 000000 );

			// This is not in the Exif standard, just a special
			// case for our purposes which enables wikis to wikify
			// the make and model to write articles about them.
			case 'Make': case 'Model':
				return wfMsg( strtolower( "exif-$tag-value" ), $val );
			default:
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
}
} // MEDIAWIKI
