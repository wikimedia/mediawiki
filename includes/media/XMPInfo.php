<?php
/**
* This class is just a container for a big array
* used by XMPReader to determine which XMP items to
* extract.
*/
class XMPInfo {

	/** get the items array
	 * @return Array XMP item configuration array.
	*/
	public static function getItems ( ) {
		if( !self::$ranHooks ) {
			// This is for if someone makes a custom metadata extension.
			// For example, a medical wiki might want to decode DICOM xmp properties.
			wfRunHooks('XMPGetInfo', Array(&self::$items));
			self::$ranHooks = true; // Only want to do this once.
		}
		return self::$items;
	}

	static private $ranHooks = false;

	/**
	* XMPInfo::$items keeps a list of all the items
	* we are interested to extract, as well as
	* information about the item like what type
	* it is.
	*
	* Format is an array of namespaces,
	* each containing an array of tags
	* each tag is an array of information about the
	* tag, including:
	*       * map_group - what group (used for precedence during conflicts)
	*       * mode - What type of item (self::MODE_SIMPLE usually, see above for all values)
	*       * validate - method to validate input. Could also post-process the input. A string value is assumed to be a static method of XMPValidate. Can also take a array( 'className', 'methodName' ).
	*       * choices  - array of potential values (format of 'value' => true ). Only used with validateClosed
	*	* rangeLow and rangeHigh - alternative to choices for numeric ranges. Again for validateClosed only.
	*       * children - for MODE_STRUCT items, allowed children.
	*	* structPart - Indicates that this element can only appear as a member of a structure.
	*
	* currently this just has a bunch of exif values as this class is only half-done
	*/

	static private $items = array(
		'http://ns.adobe.com/exif/1.0/' => array(
			'ApertureValue' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'BrightnessValue' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'CompressedBitsPerPixel' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'DigitalZoomRatio' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'ExposureBiasValue' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'ExposureIndex' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'ExposureTime' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'FlashEnergy' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational',
			),
			'FNumber' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'FocalLength' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'FocalPlaneXResolution' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'FocalPlaneYResolution' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'GPSAltitude' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational',
			),
			'GPSDestBearing' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'GPSDestDistance' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'GPSDOP' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'GPSImgDirection' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'GPSSpeed' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'GPSTrack' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'MaxApertureValue'  => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'ShutterSpeedValue' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			'SubjectDistance'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational'
			),
			/* Flash */
			'Flash'             => array(
				'mode'      => XMPReader::MODE_STRUCT,
				'children'  => array(
					'Fired'      => true,
					'Function'   => true,
					'Mode'       => true,
					'RedEyeMode' => true,
					'Return'     => true,
				),
				'validate'  => 'validateFlash',
				'map_group' => 'exif',
			),
			'Fired'             => array(
				'map_group' => 'exif',
				'validate'  => 'validateBoolean',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'Function'          => array(
				'map_group' => 'exif',
				'validate'  => 'validateBoolean',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'Mode'              => array(
				'map_group' => 'exif',
				'validate'  => 'validateClosed',
				'mode'      => XMPReader::MODE_SIMPLE,
				'choices'   => array( '0' => true, '1' => true,
						'2' => true, '3' => true ),
				'structPart'=> true,
			),
			'Return'            => array(
				'map_group' => 'exif',
				'validate'  => 'validateClosed',
				'mode'      => XMPReader::MODE_SIMPLE,
				'choices'   => array( '0' => true,
						'2' => true, '3' => true ),
				'structPart'=> true,
			),
			'RedEyeMode'        => array(
				'map_group' => 'exif',
				'validate'  => 'validateBoolean',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			/* End Flash */
			'ISOSpeedRatings'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateInteger'
			),
			/* end rational things */
			'ColorSpace'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true, '65535' => true ),
			),
			'ComponentsConfiguration'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true, '2' => true, '3' => true, '4' => true,
						'5' => true, '6' => true )
			),
			'Contrast'          => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '0' => true, '1' => true, '2' => true )
			),
			'CustomRendered'    => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '0' => true, '1' => true )
			),
			'DateTimeOriginal'  => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateDate',
			),
			'DateTimeDigitized' => array(  /* xmp:CreateDate */
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateDate',
			),
			/* todo: there might be interesting information in
			 * exif:DeviceSettingDescription, but need to find an
			 * example
			 */
			'ExifVersion'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'ExposureMode'      => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 2,
			),
			'ExposureProgram'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 8,
			),
			'FileSource'        => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '3' => true )
			),
			'FlashpixVersion'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'FocalLengthIn35mmFilm' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
			),
			'FocalPlaneResolutionUnit' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '2' => true, '3' => true ),
			),
			'GainControl'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 4,
			),
			/* this value is post-processed out later */
			'GPSAltitudeRef'    => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '0' => true, '1' => true ),
			),
			'GPSAreaInformation' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'GPSDestBearingRef' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( 'T' => true, 'M' => true ),
			),
			'GPSDestDistanceRef' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( 'K' => true, 'M' => true,
						'N' => true ),
			),
			'GPSDestLatitude'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateGPS',
			),
			'GPSDestLongitude'  => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateGPS',
			),
			'GPSDifferential'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '0' => true, '1' => true ),
			),
			'GPSImgDirectionRef' => array(
				'map_group'  => 'exif',
				'mode'       => XMPReader::MODE_SIMPLE,
				'validate'   => 'validateClosed',
				'choices'    => array( 'T' => true, 'M' => true ),
			),
			'GPSLatitude'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateGPS',
			),
			'GPSLongitude'      => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateGPS',
			),
			'GPSMapDatum'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'GPSMeasureMode'    => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '2' => true, '3' => true )
			),
			'GPSProcessingMethod' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'GPSSatellites'     => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'GPSSpeedRef'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( 'K' => true, 'M' => true,
						'N' => true ),
			),
			'GPSStatus'         => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( 'A' => true, 'V' => true )
			),
			'GPSTimeStamp'      => array(
				'map_group' => 'exif',
				// Note: in exif, GPSDateStamp does not include
				// the time, where here it does.
				'map_name'  => 'GPSDateStamp',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateDate',
			),
			'GPSTrackRef'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( 'T' => true, 'M' => true )
			),
			'GPSVersionID'      => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'ImageUniqueID'     => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'LightSource'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				/* can't use a range, as it skips... */
				'choices'   =>  array( '0' => true, '1' => true,
					'2' => true, '3' => true, '4' => true,
					'9' => true, '10' => true, '11' => true,
					'12' => true, '13' => true,
					'14' => true, '15' => true,
					'17' => true, '18' => true,
					'19' => true, '20' => true,
					'21' => true, '22' => true,
					'23' => true, '24' => true,
					'255' => true,
				),
			),
			'MeteringMode'      => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 6,
				'choices'   => array( '255' => true ),
			),
			/* Pixel(X|Y)Dimension are rather useless, but for
			 * completeness since we do it with exif.
			 */
			'PixelXDimension'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
			),
			'PixelYDimension'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
			),
			'Saturation'        => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 2,
			),
			'SceneCaptureType'  => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 3,
			),
			'SceneType'         => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true ),
			),
			// Note, 6 is not valid SensingMethod.
			'SensingMethod'     => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 1,
				'rangeHigh' => 5,
				'choices'   => array( '7' => true, 8 => true ),
			),
			'Sharpness'         => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 2,
			),
			'SpectralSensitivity' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			// This tag should perhaps be displayed to user better.
			'SubjectArea'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateInteger',
			),
			'SubjectDistanceRange' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'rangeLow'  => 0,
				'rangeHigh' => 3,
			),
			'SubjectLocation'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateInteger',
			),
			'UserComment'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_LANG,
			),
			'WhiteBalance'      => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '0' => true, '1' => true )
			),
		),
		'http://ns.adobe.com/tiff/1.0/' => array(
			'Artist'            => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'BitsPerSample'     => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateInteger',
			),
			'Compression'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true, '6' => true ),
			),
			/* this prop should not be used in XMP. dc:rights is the correct prop */
			'Copyright'         => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_LANG,
			),
			'DateTime'          => array(  /* proper prop is xmp:ModifyDate */
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateDate',
			),
			'ImageDescription'  => array(  /* proper one is dc:description */
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_LANG,
			),
			'ImageLength'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
			),
			'ImageWidth'        => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
			),
			'Make'              => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'Model'             => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			/**** Do not extract this property
			 * It interferes with auto exif rotation.
			 * 'Orientation'       => array(
			 *	'map_group' => 'exif',
			 *	'mode'      => XMPReader::MODE_SIMPLE,
			 *	'validate'  => 'validateClosed',
			 *	'choices'   => array( '1' => true, '2' => true, '3' => true, '4' => true, 5 => true,
			 *			'6' => true, '7' => true, '8' => true ),
			 *),
			 ******/
			'PhotometricInterpretation' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '2' => true, '6' => true ),
			),
			'PlanerConfiguration' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true, '2' => true ),
			),
			'PrimaryChromaticities' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateRational',
			),
			'ReferenceBlackWhite' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateRational',
			),
			'ResolutionUnit'    => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '2' => true, '3' => true ),
			),
			'SamplesPerPixel'   => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
			),
			'Software'          => array(  /* see xmp:CreatorTool */
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			/* ignore TransferFunction */
			'WhitePoint'        => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateRational',
			),
			'XResolution'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational',
			),
			'YResolution'       => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRational',
			),
			'YCbCrCoefficients' => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateRational',
			),
			'YCbCrPositioning'  => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true, '2' => true ),
			),
			'YCbCrSubSampling'  => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateClosed',
				'choices'   => array( '1' => true, '2' => true ),
			),
		),
		'http://ns.adobe.com/exif/1.0/aux/' => array(
			'Lens'              => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'SerialNumber'      => array(
				'map_group' => 'exif',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'OwnerName'         => array(
				'map_group' => 'exif',
				'map_name'  => 'CameraOwnerName',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
		),
		'http://purl.org/dc/elements/1.1/' => array(
			'title'             => array(
				'map_group' => 'general',
				'map_name'  => 'ObjectName',
				'mode'      => XMPReader::MODE_LANG
			),
			'description'       => array(
				'map_group' => 'general',
				'map_name'  => 'ImageDescription',
				'mode'      => XMPReader::MODE_LANG
			),
			'contributor'       => array(
				'map_group' => 'general',
				'map_name'  => 'dc-contributor',
				'mode'      => XMPReader::MODE_BAG
			),
			'coverage'          => array(
				'map_group' => 'general',
				'map_name'  => 'dc-coverage',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'creator'           => array(
				'map_group' => 'general',
				'map_name'  => 'Artist', //map with exif Artist, iptc byline (2:80)
				'mode'      => XMPReader::MODE_SEQ,
			),
			'date'              => array(
				'map_group' => 'general',
				// Note, not mapped with other date properties, as this type of date is
				// non-specific: "A point or period of time associated with an event in
				//  the lifecycle of the resource"
				'map_name'  => 'dc-date',
				'mode'      => XMPReader::MODE_SEQ,
				'validate'  => 'validateDate',
			),
			/* Do not extract dc:format, as we've got better ways to determine mimetype */
			'identifier'        => array(
				'map_group' => 'deprecated',
				'map_name'  => 'Identifier',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'language'          => array(
				'map_group' => 'general',
				'map_name'  => 'LanguageCode', /* mapped with iptc 2:135 */
				'mode'      => XMPReader::MODE_BAG,
				'validate'  => 'validateLangCode',
			),
			'publisher'         => array(
				'map_group' => 'general',
				'map_name'  => 'dc-publisher',
				'mode'      => XMPReader::MODE_BAG,
			),
			// for related images/resources
			'relation'          => array(
				'map_group' => 'general',
				'map_name'  => 'dc-relation',
				'mode'      => XMPReader::MODE_BAG,
			),
			'rights'            => array(
				'map_group' => 'general',
				'map_name'  => 'Copyright',
				'mode'      => XMPReader::MODE_LANG,
			),
			// Note: source is not mapped with iptc source, since iptc
			// source describes the source of the image in terms of a person
			// who provided the image, where this is to describe an image that the
			// current one is based on.
			'source'            => array(
				'map_group' => 'general',
				'map_name'  => 'dc-source',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'subject'           => array(
				'map_group' => 'general',
				'map_name'  => 'Keywords', /* maps to iptc 2:25 */
				'mode'      => XMPReader::MODE_BAG,
			),
			'type'              => array(
				'map_group' => 'general',
				'map_name'  => 'dc-type',
				'mode'      => XMPReader::MODE_BAG,
			),
		),
		'http://ns.adobe.com/xap/1.0/' => array(
			'CreateDate' => array(
				'map_group' => 'general',
				'map_name' => 'DateTimeDigitized',
				'mode'     => XMPReader::MODE_SIMPLE,
				'validate' => 'validateDate',
			),
			'CreatorTool' => array(
				'map_group' => 'general',
				'map_name'  => 'Software',
				'mode'      => XMPReader::MODE_SIMPLE
			),
			'Identifier' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_BAG,
			),
			'Label' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'ModifyDate' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'DateTime',
				'validate'  => 'validateDate',
			),
			'MetadataDate' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				// map_name to be consistent with other date names.
				'map_name'  => 'DateTimeMetadata',
				'validate'  => 'validateDate',
			),
			'Nickname' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'Rating' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateRating',
			),
		),
		'http://ns.adobe.com/xap/1.0/rights/' => array(
			'Certificate' => array(
				'map_group' => 'general',
				'map_name'  => 'RightsCertificate',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'Marked' => array(
				'map_group' => 'general',
				'map_name'  => 'Copyrighted',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateBoolean',
			),
			'Owner' => array(
				'map_group' => 'general',
				'map_name'  => 'CopyrightOwner',
				'mode'      => XMPReader::MODE_BAG,
			),
			// this seems similar to dc:rights.
			'UsageTerms' => array(
				'map_group' => 'general',
				'mode' => XMPReader::MODE_LANG,
			),
			'WebStatement' => array(
				'map_group' => 'general',
				'mode' => XMPReader::MODE_SIMPLE,
			),
		),
		// XMP media management.
		'http://ns.adobe.com/xap/1.0/mm/' => array(
			// if we extract the exif UniqueImageID, might
			// as well do this too.
			'OriginalDocumentID' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			// It might also be useful to do xmpMM:LastURL
			// and xmpMM:DerivedFrom as you can potentially,
			// get the url of this document/source for this
			// document. However whats more likely is you'd
			// get a file:// url for the path of the doc,
			// which is somewhat of a privacy issue.
		),
		'http://creativecommons.org/ns#' => array(
			'license' => array(
				'map_name'  => 'LicenseUrl',
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'morePermissions' => array(
				'map_name'  => 'MorePermissionsUrl',
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'attributionURL' => array(
				'map_group' => 'general',
				'map_name'  => 'AttributionUrl',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'attributionName' => array(
				'map_group' => 'general',
				'map_name'  => 'PreferredAttributionName',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
		),
		//Note, this property affects how jpeg metadata is extracted.
		'http://ns.adobe.com/xmp/note/' => array(
			'HasExtendedXMP' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
		),
		/* Note, in iptc schemas, the legacy properties are denoted
		 * as deprecated, since other properties should used instead,
		 * and properties marked as deprecated in the standard are
		 * are marked as general here as they don't have replacements
		 */
		'http://ns.adobe.com/photoshop/1.0/' => array(
			'City' => array(
				'map_group' => 'deprecated',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'CityDest',
			),
			'Country' => array(
				'map_group' => 'deprecated',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'CountryDest',
			),
			'State' => array(
				'map_group' => 'deprecated',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'ProvinceOrStateDest',
			),
			'DateCreated' => array(
				'map_group' => 'deprecated',
				// marking as deprecated as the xmp prop preferred
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'DateTimeOriginal',
				'validate'  => 'validateDate',
				// note this prop is an XMP, not IPTC date
			),
			'CaptionWriter' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'Writer',
			),
			'Instructions' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'SpecialInstructions',
			),
			'TransmissionReference' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'OriginalTransmissionRef',
			),
			'AuthorsPosition' => array(
				/* This corresponds with 2:85
				 * By-line Title, which needs to be
				 * handled weirdly to correspond
				 * with iptc/exif. */
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE
			),
			'Credit' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'Source' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'Urgency' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'Category' => array(
				// Note, this prop is deprecated, but in general
				// group since it doesn't have a replacement.
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'iimCategory',
			),
			'SupplementalCategories' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_BAG,
				'map_name'  => 'iimSupplementalCategory',
			),
			'Headline' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE
			),
		),
		'http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/' => array(
			'CountryCode' => array(
				'map_group' => 'deprecated',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'CountryCodeDest',
			),
			'IntellectualGenre' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			// Note, this is a six digit code.
			// See: http://cv.iptc.org/newscodes/scene/
			// Since these aren't really all that common,
			// we just show the number.
			'Scene' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_BAG,
				'validate'  => 'validateInteger',
				'map_name'  => 'SceneCode',
			),
			/* Note: SubjectCode should be an 8 ascii digits.
			 * it is not really an integer (has leading 0's,
			 * cannot have a +/- sign), but validateInteger
			 * will let it through.
			 */
			'SubjectCode' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_BAG,
				'map_name'  => 'SubjectNewsCode',
				'validate'  => 'validateInteger'
			),
			'Location' => array(
				'map_group' => 'deprecated',
				'mode'      => XMPReader::MODE_SIMPLE,
				'map_name'  => 'SublocationDest',
			),
			'CreatorContactInfo' => array(
				/* Note this maps to 2:118 in iim
				 * (Contact) field. However those field
				 * types are slightly different - 2:118
				 * is free form text field, where this
				 * is more structured.
				 */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_STRUCT,
				'map_name'  => 'Contact',
				'children'  => array(
					'CiAdrExtadr' => true,
					'CiAdrCity'   => true,
					'CiAdrCtry'   => true,
					'CiEmailWork' => true,
					'CiTelWork'   => true,
					'CiAdrPcode'  => true,
					'CiAdrRegion' => true,
					'CiUrlWork'   => true,
				),
			),
			'CiAdrExtadr' => array( /* address */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiAdrCity' => array( /* city */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiAdrCtry' => array( /* country */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiEmailWork' => array( /* email (possibly separated by ',') */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiTelWork' => array( /* telephone */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiAdrPcode' => array( /* postal code */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiAdrRegion' => array( /* province/state */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CiUrlWork' => array( /* url. Multiple may be separated by comma. */
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			/* End contact info struct properties */
		),
		'http://iptc.org/std/Iptc4xmpExt/2008-02-29/' => array(
			'Event' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
			),
			'OrganisationInImageName' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_BAG,
				'map_name'  => 'OrganisationInImage'
			),
			'PersonInImage' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_BAG,
			),
			'MaxAvailHeight' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
				'map_name'  => 'OriginalImageHeight',
			),
			'MaxAvailWidth' => array(
				'map_group' => 'general',
				'mode'      => XMPReader::MODE_SIMPLE,
				'validate'  => 'validateInteger',
				'map_name'  => 'OriginalImageWidth',
			),
			// LocationShown and LocationCreated are handled
			// specially because they are hierarchical, but we
			// also want to merge with the old non-hierarchical.
			'LocationShown' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_BAGSTRUCT,
				'children'  => array(
					'WorldRegion' => true,
					'CountryCode' => true, /* iso code */
					'CountryName' => true,
					'ProvinceState' => true,
					'City' => true,
					'Sublocation' => true,
				),
			),
			'LocationCreated' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_BAGSTRUCT,
				'children'  => array(
					'WorldRegion' => true,
					'CountryCode' => true, /* iso code */
					'CountryName' => true,
					'ProvinceState' => true,
					'City' => true,
					'Sublocation' => true,
				),
			),
			'WorldRegion' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CountryCode' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'CountryName' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
				'map_name'  => 'Country',
			),
			'ProvinceState' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
				'map_name'  => 'ProvinceOrState',
			),
			'City' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),
			'Sublocation' => array(
				'map_group' => 'special',
				'mode'      => XMPReader::MODE_SIMPLE,
				'structPart'=> true,
			),

			/* Other props that might be interesting but
			 * Not currently extracted:
			 * ArtworkOrObject, (info about objects in picture)
			 * DigitalSourceType
			 * RegistryId
			 */
		),

		/* Plus props we might want to consider:
		 * (Note: some of these have unclear/incomplete definitions
		 * from the iptc4xmp standard).
		 * ImageSupplier (kind of like iptc source field)
		 * ImageSupplierId (id code for image from supplier)
		 * CopyrightOwner
		 * ImageCreator
		 * Licensor
		 * Various model release fields
		 * Property release fields.
		 */
	);
}
