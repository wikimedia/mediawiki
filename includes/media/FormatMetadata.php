<?php
/**
 * Formatting of image metadata values into human readable form.
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
 * @ingroup Media
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, 2009 Brent Garber, 2010 Brian Wolff
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @see http://exif.org/Exif2-2.PDF The Exif 2.2 specification
 * @file
 */

/**
 * Format Image metadata values into a human readable form.
 *
 * Note lots of these messages use the prefix 'exif' even though
 * they may not be exif properties. For example 'exif-ImageDescription'
 * can be the Exif ImageDescription, or it could be the iptc-iim caption
 * property, or it could be the xmp dc:description property. This
 * is because these messages should be independent of how the data is
 * stored, sine the user doesn't care if the description is stored in xmp,
 * exif, etc only that its a description. (Additionally many of these properties
 * are merged together following the MWG standard, such that for example,
 * exif properties override XMP properties that mean the same thing if
 * there is a conflict).
 *
 * It should perhaps use a prefix like 'metadata' instead, but there
 * is already a large number of messages using the 'exif' prefix.
 *
 * @ingroup Media
 * @since 1.23 the class extends ContextSource and various formerly-public
 *   internal methods are private
 */
class FormatMetadata extends ContextSource {
	/**
	 * Only output a single language for multi-language fields
	 * @var bool
	 * @since 1.23
	 */
	protected $singleLang = false;

	/**
	 * Trigger only outputting single language for multilanguage fields
	 *
	 * @param bool $val
	 * @since 1.23
	 */
	public function setSingleLanguage( $val ) {
		$this->singleLang = $val;
	}

	/**
	 * Numbers given by Exif user agents are often magical, that is they
	 * should be replaced by a detailed explanation depending on their
	 * value which most of the time are plain integers. This function
	 * formats Exif (and other metadata) values into human readable form.
	 *
	 * This is the usual entry point for this class.
	 *
	 * @param array $tags The Exif data to format ( as returned by
	 *   Exif::getFilteredData() or BitmapMetadataHandler )
	 * @param bool|IContextSource $context Context to use (optional)
	 * @return array
	 */
	public static function getFormattedData( $tags, $context = false ) {
		$obj = new FormatMetadata;
		if ( $context ) {
			$obj->setContext( $context );
		}

		return $obj->makeFormattedData( $tags );
	}

	/**
	 * Numbers given by Exif user agents are often magical, that is they
	 * should be replaced by a detailed explanation depending on their
	 * value which most of the time are plain integers. This function
	 * formats Exif (and other metadata) values into human readable form.
	 *
	 * @param array $tags The Exif data to format ( as returned by
	 *   Exif::getFilteredData() or BitmapMetadataHandler )
	 * @return array
	 * @since 1.23
	 */
	public function makeFormattedData( $tags ) {
		$resolutionunit = !isset( $tags['ResolutionUnit'] ) || $tags['ResolutionUnit'] == 2 ? 2 : 3;
		unset( $tags['ResolutionUnit'] );

		foreach ( $tags as $tag => &$vals ) {

			// This seems ugly to wrap non-array's in an array just to unwrap again,
			// especially when most of the time it is not an array
			if ( !is_array( $tags[$tag] ) ) {
				$vals = array( $vals );
			}

			// _type is a special value to say what array type
			if ( isset( $tags[$tag]['_type'] ) ) {
				$type = $tags[$tag]['_type'];
				unset( $vals['_type'] );
			} else {
				$type = 'ul'; // default unordered list.
			}

			//This is done differently as the tag is an array.
			if ( $tag == 'GPSTimeStamp' && count( $vals ) === 3 ) {
				//hour min sec array

				$h = explode( '/', $vals[0] );
				$m = explode( '/', $vals[1] );
				$s = explode( '/', $vals[2] );

				// this should already be validated
				// when loaded from file, but it could
				// come from a foreign repo, so be
				// paranoid.
				if ( !isset( $h[1] )
					|| !isset( $m[1] )
					|| !isset( $s[1] )
					|| $h[1] == 0
					|| $m[1] == 0
					|| $s[1] == 0
				) {
					continue;
				}
				$tags[$tag] = str_pad( intval( $h[0] / $h[1] ), 2, '0', STR_PAD_LEFT )
					. ':' . str_pad( intval( $m[0] / $m[1] ), 2, '0', STR_PAD_LEFT )
					. ':' . str_pad( intval( $s[0] / $s[1] ), 2, '0', STR_PAD_LEFT );

				try {
					$time = wfTimestamp( TS_MW, '1971:01:01 ' . $tags[$tag] );
					// the 1971:01:01 is just a placeholder, and not shown to user.
					if ( $time && intval( $time ) > 0 ) {
						$tags[$tag] = $this->getLanguage()->time( $time );
					}
				} catch ( TimestampException $e ) {
					// This shouldn't happen, but we've seen bad formats
					// such as 4-digit seconds in the wild.
					// leave $tags[$tag] as-is
				}
				continue;
			}

			// The contact info is a multi-valued field
			// instead of the other props which are single
			// valued (mostly) so handle as a special case.
			if ( $tag === 'Contact' ) {
				$vals = $this->collapseContactInfo( $vals );
				continue;
			}

			foreach ( $vals as &$val ) {

				switch ( $tag ) {
					case 'Compression':
						switch ( $val ) {
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 7:
							case 8:
							case 32773:
							case 32946:
							case 34712:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'PhotometricInterpretation':
						switch ( $val ) {
							case 2:
							case 6:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'Orientation':
						switch ( $val ) {
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 7:
							case 8:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'PlanarConfiguration':
						switch ( $val ) {
							case 1:
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					// TODO: YCbCrSubSampling
					case 'YCbCrPositioning':
						switch ( $val ) {
							case 1:
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'XResolution':
					case 'YResolution':
						switch ( $resolutionunit ) {
							case 2:
								$val = $this->exifMsg( 'XYResolution', 'i', $this->formatNum( $val ) );
								break;
							case 3:
								$val = $this->exifMsg( 'XYResolution', 'c', $this->formatNum( $val ) );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					// TODO: YCbCrCoefficients  #p27 (see annex E)
					case 'ExifVersion':
					case 'FlashpixVersion':
						$val = "$val" / 100;
						break;

					case 'ColorSpace':
						switch ( $val ) {
							case 1:
							case 65535:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'ComponentsConfiguration':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'DateTime':
					case 'DateTimeOriginal':
					case 'DateTimeDigitized':
					case 'DateTimeReleased':
					case 'DateTimeExpires':
					case 'GPSDateStamp':
					case 'dc-date':
					case 'DateTimeMetadata':
						if ( $val == '0000:00:00 00:00:00' || $val == '    :  :     :  :  ' ) {
							$val = $this->msg( 'exif-unknowndate' )->text();
						} elseif ( preg_match(
							'/^(?:\d{4}):(?:\d\d):(?:\d\d) (?:\d\d):(?:\d\d):(?:\d\d)$/D',
							$val
						) ) {
							// Full date.
							$time = wfTimestamp( TS_MW, $val );
							if ( $time && intval( $time ) > 0 ) {
								$val = $this->getLanguage()->timeanddate( $time );
							}
						} elseif ( preg_match( '/^(?:\d{4}):(?:\d\d):(?:\d\d) (?:\d\d):(?:\d\d)$/D', $val ) ) {
							// No second field. Still format the same
							// since timeanddate doesn't include seconds anyways,
							// but second still available in api
							$time = wfTimestamp( TS_MW, $val . ':00' );
							if ( $time && intval( $time ) > 0 ) {
								$val = $this->getLanguage()->timeanddate( $time );
							}
						} elseif ( preg_match( '/^(?:\d{4}):(?:\d\d):(?:\d\d)$/D', $val ) ) {
							// If only the date but not the time is filled in.
							$time = wfTimestamp( TS_MW, substr( $val, 0, 4 )
								. substr( $val, 5, 2 )
								. substr( $val, 8, 2 )
								. '000000' );
							if ( $time && intval( $time ) > 0 ) {
								$val = $this->getLanguage()->date( $time );
							}
						}
						// else it will just output $val without formatting it.
						break;

					case 'ExposureProgram':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 7:
							case 8:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'SubjectDistance':
						$val = $this->exifMsg( $tag, '', $this->formatNum( $val ) );
						break;

					case 'MeteringMode':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 7:
							case 255:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'LightSource':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
							case 4:
							case 9:
							case 10:
							case 11:
							case 12:
							case 13:
							case 14:
							case 15:
							case 17:
							case 18:
							case 19:
							case 20:
							case 21:
							case 22:
							case 23:
							case 24:
							case 255:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'Flash':
						$flashDecode = array(
							'fired' => $val & bindec( '00000001' ),
							'return' => ( $val & bindec( '00000110' ) ) >> 1,
							'mode' => ( $val & bindec( '00011000' ) ) >> 3,
							'function' => ( $val & bindec( '00100000' ) ) >> 5,
							'redeye' => ( $val & bindec( '01000000' ) ) >> 6,
//						'reserved' => ($val & bindec( '10000000' )) >> 7,
						);
						$flashMsgs = array();
						# We do not need to handle unknown values since all are used.
						foreach ( $flashDecode as $subTag => $subValue ) {
							# We do not need any message for zeroed values.
							if ( $subTag != 'fired' && $subValue == 0 ) {
								continue;
							}
							$fullTag = $tag . '-' . $subTag;
							$flashMsgs[] = $this->exifMsg( $fullTag, $subValue );
						}
						$val = $this->getLanguage()->commaList( $flashMsgs );
						break;

					case 'FocalPlaneResolutionUnit':
						switch ( $val ) {
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'SensingMethod':
						switch ( $val ) {
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 7:
							case 8:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'FileSource':
						switch ( $val ) {
							case 3:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'SceneType':
						switch ( $val ) {
							case 1:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'CustomRendered':
						switch ( $val ) {
							case 0:
							case 1:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'ExposureMode':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'WhiteBalance':
						switch ( $val ) {
							case 0:
							case 1:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'SceneCaptureType':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GainControl':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
							case 4:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'Contrast':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'Saturation':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'Sharpness':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'SubjectDistanceRange':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					//The GPS...Ref values are kept for compatibility, probably won't be reached.
					case 'GPSLatitudeRef':
					case 'GPSDestLatitudeRef':
						switch ( $val ) {
							case 'N':
							case 'S':
								$val = $this->exifMsg( 'GPSLatitude', $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSLongitudeRef':
					case 'GPSDestLongitudeRef':
						switch ( $val ) {
							case 'E':
							case 'W':
								$val = $this->exifMsg( 'GPSLongitude', $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSAltitude':
						if ( $val < 0 ) {
							$val = $this->exifMsg( 'GPSAltitude', 'below-sealevel', $this->formatNum( -$val, 3 ) );
						} else {
							$val = $this->exifMsg( 'GPSAltitude', 'above-sealevel', $this->formatNum( $val, 3 ) );
						}
						break;

					case 'GPSStatus':
						switch ( $val ) {
							case 'A':
							case 'V':
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSMeasureMode':
						switch ( $val ) {
							case 2:
							case 3:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSTrackRef':
					case 'GPSImgDirectionRef':
					case 'GPSDestBearingRef':
						switch ( $val ) {
							case 'T':
							case 'M':
								$val = $this->exifMsg( 'GPSDirection', $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSLatitude':
					case 'GPSDestLatitude':
						$val = $this->formatCoords( $val, 'latitude' );
						break;
					case 'GPSLongitude':
					case 'GPSDestLongitude':
						$val = $this->formatCoords( $val, 'longitude' );
						break;

					case 'GPSSpeedRef':
						switch ( $val ) {
							case 'K':
							case 'M':
							case 'N':
								$val = $this->exifMsg( 'GPSSpeed', $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSDestDistanceRef':
						switch ( $val ) {
							case 'K':
							case 'M':
							case 'N':
								$val = $this->exifMsg( 'GPSDestDistance', $val );
								break;
							default:
								/* If not recognized, display as is. */
								break;
						}
						break;

					case 'GPSDOP':
						// See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)
						if ( $val <= 2 ) {
							$val = $this->exifMsg( $tag, 'excellent', $this->formatNum( $val ) );
						} elseif ( $val <= 5 ) {
							$val = $this->exifMsg( $tag, 'good', $this->formatNum( $val ) );
						} elseif ( $val <= 10 ) {
							$val = $this->exifMsg( $tag, 'moderate', $this->formatNum( $val ) );
						} elseif ( $val <= 20 ) {
							$val = $this->exifMsg( $tag, 'fair', $this->formatNum( $val ) );
						} else {
							$val = $this->exifMsg( $tag, 'poor', $this->formatNum( $val ) );
						}
						break;

					// This is not in the Exif standard, just a special
					// case for our purposes which enables wikis to wikify
					// the make, model and software name to link to their articles.
					case 'Make':
					case 'Model':
						$val = $this->exifMsg( $tag, '', $val );
						break;

					case 'Software':
						if ( is_array( $val ) ) {
							//if its a software, version array.
							$val = $this->msg( 'exif-software-version-value', $val[0], $val[1] )->text();
						} else {
							$val = $this->exifMsg( $tag, '', $val );
						}
						break;

					case 'ExposureTime':
						// Show the pretty fraction as well as decimal version
						$val = $this->msg( 'exif-exposuretime-format',
							$this->formatFraction( $val ), $this->formatNum( $val ) )->text();
						break;
					case 'ISOSpeedRatings':
						// If its = 65535 that means its at the
						// limit of the size of Exif::short and
						// is really higher.
						if ( $val == '65535' ) {
							$val = $this->exifMsg( $tag, 'overflow' );
						} else {
							$val = $this->formatNum( $val );
						}
						break;
					case 'FNumber':
						$val = $this->msg( 'exif-fnumber-format',
							$this->formatNum( $val ) )->text();
						break;

					case 'FocalLength':
					case 'FocalLengthIn35mmFilm':
						$val = $this->msg( 'exif-focallength-format',
							$this->formatNum( $val ) )->text();
						break;

					case 'MaxApertureValue':
						if ( strpos( $val, '/' ) !== false ) {
							// need to expand this earlier to calculate fNumber
							list( $n, $d ) = explode( '/', $val );
							if ( is_numeric( $n ) && is_numeric( $d ) ) {
								$val = $n / $d;
							}
						}
						if ( is_numeric( $val ) ) {
							$fNumber = pow( 2, $val / 2 );
							if ( $fNumber !== false ) {
								$val = $this->msg( 'exif-maxaperturevalue-value',
									$this->formatNum( $val ),
									$this->formatNum( $fNumber, 2 )
								)->text();
							}
						}
						break;

					case 'iimCategory':
						switch ( strtolower( $val ) ) {
							// See pg 29 of IPTC photo
							// metadata standard.
							case 'ace':
							case 'clj':
							case 'dis':
							case 'fin':
							case 'edu':
							case 'evn':
							case 'hth':
							case 'hum':
							case 'lab':
							case 'lif':
							case 'pol':
							case 'rel':
							case 'sci':
							case 'soi':
							case 'spo':
							case 'war':
							case 'wea':
								$val = $this->exifMsg(
									'iimcategory',
									$val
								);
						}
						break;
					case 'SubjectNewsCode':
						// Essentially like iimCategory.
						// 8 (numeric) digit hierarchical
						// classification. We decode the
						// first 2 digits, which provide
						// a broad category.
						$val = $this->convertNewsCode( $val );
						break;
					case 'Urgency':
						// 1-8 with 1 being highest, 5 normal
						// 0 is reserved, and 9 is 'user-defined'.
						$urgency = '';
						if ( $val == 0 || $val == 9 ) {
							$urgency = 'other';
						} elseif ( $val < 5 && $val > 1 ) {
							$urgency = 'high';
						} elseif ( $val == 5 ) {
							$urgency = 'normal';
						} elseif ( $val <= 8 && $val > 5 ) {
							$urgency = 'low';
						}

						if ( $urgency !== '' ) {
							$val = $this->exifMsg( 'urgency',
								$urgency, $val
							);
						}
						break;

					// Things that have a unit of pixels.
					case 'OriginalImageHeight':
					case 'OriginalImageWidth':
					case 'PixelXDimension':
					case 'PixelYDimension':
					case 'ImageWidth':
					case 'ImageLength':
						$val = $this->formatNum( $val ) . ' ' . $this->msg( 'unit-pixel' )->text();
						break;

					// Do not transform fields with pure text.
					// For some languages the formatNum()
					// conversion results to wrong output like
					// foo,bar@example,com or foo٫bar@example٫com.
					// Also some 'numeric' things like Scene codes
					// are included here as we really don't want
					// commas inserted.
					case 'ImageDescription':
					case 'Artist':
					case 'Copyright':
					case 'RelatedSoundFile':
					case 'ImageUniqueID':
					case 'SpectralSensitivity':
					case 'GPSSatellites':
					case 'GPSVersionID':
					case 'GPSMapDatum':
					case 'Keywords':
					case 'WorldRegionDest':
					case 'CountryDest':
					case 'CountryCodeDest':
					case 'ProvinceOrStateDest':
					case 'CityDest':
					case 'SublocationDest':
					case 'WorldRegionCreated':
					case 'CountryCreated':
					case 'CountryCodeCreated':
					case 'ProvinceOrStateCreated':
					case 'CityCreated':
					case 'SublocationCreated':
					case 'ObjectName':
					case 'SpecialInstructions':
					case 'Headline':
					case 'Credit':
					case 'Source':
					case 'EditStatus':
					case 'FixtureIdentifier':
					case 'LocationDest':
					case 'LocationDestCode':
					case 'Writer':
					case 'JPEGFileComment':
					case 'iimSupplementalCategory':
					case 'OriginalTransmissionRef':
					case 'Identifier':
					case 'dc-contributor':
					case 'dc-coverage':
					case 'dc-publisher':
					case 'dc-relation':
					case 'dc-rights':
					case 'dc-source':
					case 'dc-type':
					case 'Lens':
					case 'SerialNumber':
					case 'CameraOwnerName':
					case 'Label':
					case 'Nickname':
					case 'RightsCertificate':
					case 'CopyrightOwner':
					case 'UsageTerms':
					case 'WebStatement':
					case 'OriginalDocumentID':
					case 'LicenseUrl':
					case 'MorePermissionsUrl':
					case 'AttributionUrl':
					case 'PreferredAttributionName':
					case 'PNGFileComment':
					case 'Disclaimer':
					case 'ContentWarning':
					case 'GIFFileComment':
					case 'SceneCode':
					case 'IntellectualGenre':
					case 'Event':
					case 'OrginisationInImage':
					case 'PersonInImage':

						$val = htmlspecialchars( $val );
						break;

					case 'ObjectCycle':
						switch ( $val ) {
							case 'a':
							case 'p':
							case 'b':
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								$val = htmlspecialchars( $val );
								break;
						}
						break;
					case 'Copyrighted':
						switch ( $val ) {
							case 'True':
							case 'False':
								$val = $this->exifMsg( $tag, $val );
								break;
						}
						break;
					case 'Rating':
						if ( $val == '-1' ) {
							$val = $this->exifMsg( $tag, 'rejected' );
						} else {
							$val = $this->formatNum( $val );
						}
						break;

					case 'LanguageCode':
						$lang = Language::fetchLanguageName( strtolower( $val ), $this->getLanguage()->getCode() );
						if ( $lang ) {
							$val = htmlspecialchars( $lang );
						} else {
							$val = htmlspecialchars( $val );
						}
						break;

					default:
						$val = $this->formatNum( $val );
						break;
				}
			}
			// End formatting values, start flattening arrays.
			$vals = $this->flattenArrayReal( $vals, $type );
		}

		return $tags;
	}

	/**
	 * Flatten an array, using the content language for any messages.
	 *
	 * @param array $vals Array of values
	 * @param string $type Type of array (either lang, ul, ol).
	 *   lang = language assoc array with keys being the lang code
	 *   ul = unordered list, ol = ordered list
	 *   type can also come from the '_type' member of $vals.
	 * @param bool $noHtml If to avoid returning anything resembling HTML.
	 *   (Ugly hack for backwards compatibility with old MediaWiki).
	 * @param bool|IContextSource $context
	 * @return string Single value (in wiki-syntax).
	 * @since 1.23
	 */
	public static function flattenArrayContentLang( $vals, $type = 'ul',
		$noHtml = false, $context = false
	) {
		global $wgContLang;
		$obj = new FormatMetadata;
		if ( $context ) {
			$obj->setContext( $context );
		}
		$context = new DerivativeContext( $obj->getContext() );
		$context->setLanguage( $wgContLang );
		$obj->setContext( $context );

		return $obj->flattenArrayReal( $vals, $type, $noHtml );
	}

	/**
	 * Flatten an array, using the user language for any messages.
	 *
	 * @param array $vals Array of values
	 * @param string $type Type of array (either lang, ul, ol).
	 *   lang = language assoc array with keys being the lang code
	 *   ul = unordered list, ol = ordered list
	 *   type can also come from the '_type' member of $vals.
	 * @param bool $noHtml If to avoid returning anything resembling HTML.
	 *   (Ugly hack for backwards compatibility with old MediaWiki).
	 * @param bool|IContextSource $context
	 * @return string Single value (in wiki-syntax).
	 */
	public static function flattenArray( $vals, $type = 'ul', $noHtml = false, $context = false ) {
		$obj = new FormatMetadata;
		if ( $context ) {
			$obj->setContext( $context );
		}

		return $obj->flattenArrayReal( $vals, $type, $noHtml );
	}

	/**
	 * A function to collapse multivalued tags into a single value.
	 * This turns an array of (for example) authors into a bulleted list.
	 *
	 * This is public on the basis it might be useful outside of this class.
	 *
	 * @param array $vals Array of values
	 * @param string $type Type of array (either lang, ul, ol).
	 *     lang = language assoc array with keys being the lang code
	 *     ul = unordered list, ol = ordered list
	 *     type can also come from the '_type' member of $vals.
	 * @param bool $noHtml If to avoid returning anything resembling HTML.
	 *   (Ugly hack for backwards compatibility with old mediawiki).
	 * @return string Single value (in wiki-syntax).
	 * @since 1.23
	 */
	public function flattenArrayReal( $vals, $type = 'ul', $noHtml = false ) {
		if ( !is_array( $vals ) ) {
			return $vals; // do nothing if not an array;
		}

		if ( isset( $vals['_type'] ) ) {
			$type = $vals['_type'];
			unset( $vals['_type'] );
		}

		if ( !is_array( $vals ) ) {
			return $vals; // do nothing if not an array;
		} elseif ( count( $vals ) === 1 && $type !== 'lang' ) {
			return $vals[0];
		} elseif ( count( $vals ) === 0 ) {
			wfDebug( __METHOD__ . " metadata array with 0 elements!\n" );

			return ""; // paranoia. This should never happen
		} else {
			/* @todo FIXME: This should hide some of the list entries if there are
			 * say more than four. Especially if a field is translated into 20
			 * languages, we don't want to show them all by default
			 */
			switch ( $type ) {
				case 'lang':
					// Display default, followed by ContLang,
					// followed by the rest in no particular
					// order.

					// Todo: hide some items if really long list.

					$content = '';

					$priorityLanguages = $this->getPriorityLanguages();
					$defaultItem = false;
					$defaultLang = false;

					// If default is set, save it for later,
					// as we don't know if it's equal to
					// one of the lang codes. (In xmp
					// you specify the language for a
					// default property by having both
					// a default prop, and one in the language
					// that are identical)
					if ( isset( $vals['x-default'] ) ) {
						$defaultItem = $vals['x-default'];
						unset( $vals['x-default'] );
					}
					foreach ( $priorityLanguages as $pLang ) {
						if ( isset( $vals[$pLang] ) ) {
							$isDefault = false;
							if ( $vals[$pLang] === $defaultItem ) {
								$defaultItem = false;
								$isDefault = true;
							}
							$content .= $this->langItem(
								$vals[$pLang], $pLang,
								$isDefault, $noHtml );

							unset( $vals[$pLang] );

							if ( $this->singleLang ) {
								return Html::rawElement( 'span',
									array( 'lang' => $pLang ), $vals[$pLang] );
							}
						}
					}

					// Now do the rest.
					foreach ( $vals as $lang => $item ) {
						if ( $item === $defaultItem ) {
							$defaultLang = $lang;
							continue;
						}
						$content .= $this->langItem( $item,
							$lang, false, $noHtml );
						if ( $this->singleLang ) {
							return Html::rawElement( 'span',
								array( 'lang' => $lang ), $item );
						}
					}
					if ( $defaultItem !== false ) {
						$content = $this->langItem( $defaultItem,
								$defaultLang, true, $noHtml ) .
							$content;
						if ( $this->singleLang ) {
							return $defaultItem;
						}
					}
					if ( $noHtml ) {
						return $content;
					}

					return '<ul class="metadata-langlist">' .
					$content .
					'</ul>';
				case 'ol':
					if ( $noHtml ) {
						return "\n#" . implode( "\n#", $vals );
					}

					return "<ol><li>" . implode( "</li>\n<li>", $vals ) . '</li></ol>';
				case 'ul':
				default:
					if ( $noHtml ) {
						return "\n*" . implode( "\n*", $vals );
					}

					return "<ul><li>" . implode( "</li>\n<li>", $vals ) . '</li></ul>';
			}
		}
	}

	/** Helper function for creating lists of translations.
	 *
	 * @param string $value Value (this is not escaped)
	 * @param string $lang Lang code of item or false
	 * @param bool $default If it is default value.
	 * @param bool $noHtml If to avoid html (for back-compat)
	 * @throws MWException
	 * @return string Language item (Note: despite how this looks, this is
	 *   treated as wikitext, not as HTML).
	 */
	private function langItem( $value, $lang, $default = false, $noHtml = false ) {
		if ( $lang === false && $default === false ) {
			throw new MWException( '$lang and $default cannot both '
				. 'be false.' );
		}

		if ( $noHtml ) {
			$wrappedValue = $value;
		} else {
			$wrappedValue = '<span class="mw-metadata-lang-value">'
				. $value . '</span>';
		}

		if ( $lang === false ) {
			$msg = $this->msg( 'metadata-langitem-default', $wrappedValue );
			if ( $noHtml ) {
				return $msg->text() . "\n\n";
			} /* else */

			return '<li class="mw-metadata-lang-default">'
				. $msg->text()
				. "</li>\n";
		}

		$lowLang = strtolower( $lang );
		$langName = Language::fetchLanguageName( $lowLang );
		if ( $langName === '' ) {
			//try just the base language name. (aka en-US -> en ).
			list( $langPrefix ) = explode( '-', $lowLang, 2 );
			$langName = Language::fetchLanguageName( $langPrefix );
			if ( $langName === '' ) {
				// give up.
				$langName = $lang;
			}
		}
		// else we have a language specified

		$msg = $this->msg( 'metadata-langitem', $wrappedValue, $langName, $lang );
		if ( $noHtml ) {
			return '*' . $msg->text();
		} /* else: */

		$item = '<li class="mw-metadata-lang-code-'
			. $lang;
		if ( $default ) {
			$item .= ' mw-metadata-lang-default';
		}
		$item .= '" lang="' . $lang . '">';
		$item .= $msg->text();
		$item .= "</li>\n";

		return $item;
	}

	/**
	 * Convenience function for getFormattedData()
	 *
	 * @param string $tag The tag name to pass on
	 * @param string $val The value of the tag
	 * @param string $arg An argument to pass ($1)
	 * @param string $arg2 A 2nd argument to pass ($2)
	 * @return string The text content of "exif-$tag-$val" message in lower case
	 */
	private function exifMsg( $tag, $val, $arg = null, $arg2 = null ) {
		global $wgContLang;

		if ( $val === '' ) {
			$val = 'value';
		}

		return $this->msg( $wgContLang->lc( "exif-$tag-$val" ), $arg, $arg2 )->text();
	}

	/**
	 * Format a number, convert numbers from fractions into floating point
	 * numbers, joins arrays of numbers with commas.
	 *
	 * @param mixed $num The value to format
	 * @param float|int|bool $round Digits to round to or false.
	 * @return mixed A floating point number or whatever we were fed
	 */
	private function formatNum( $num, $round = false ) {
		$m = array();
		if ( is_array( $num ) ) {
			$out = array();
			foreach ( $num as $number ) {
				$out[] = $this->formatNum( $number );
			}

			return $this->getLanguage()->commaList( $out );
		}
		if ( preg_match( '/^(-?\d+)\/(\d+)$/', $num, $m ) ) {
			if ( $m[2] != 0 ) {
				$newNum = $m[1] / $m[2];
				if ( $round !== false ) {
					$newNum = round( $newNum, $round );
				}
			} else {
				$newNum = $num;
			}

			return $this->getLanguage()->formatNum( $newNum );
		} else {
			if ( is_numeric( $num ) && $round !== false ) {
				$num = round( $num, $round );
			}

			return $this->getLanguage()->formatNum( $num );
		}
	}

	/**
	 * Format a rational number, reducing fractions
	 *
	 * @param mixed $num The value to format
	 * @return mixed A floating point number or whatever we were fed
	 */
	private function formatFraction( $num ) {
		$m = array();
		if ( preg_match( '/^(-?\d+)\/(\d+)$/', $num, $m ) ) {
			$numerator = intval( $m[1] );
			$denominator = intval( $m[2] );
			$gcd = $this->gcd( abs( $numerator ), $denominator );
			if ( $gcd != 0 ) {
				// 0 shouldn't happen! ;)
				return $this->formatNum( $numerator / $gcd ) . '/' . $this->formatNum( $denominator / $gcd );
			}
		}

		return $this->formatNum( $num );
	}

	/**
	 * Calculate the greatest common divisor of two integers.
	 *
	 * @param int $a Numerator
	 * @param int $b Denominator
	 * @return int
	 */
	private function gcd( $a, $b ) {
		/*
			// http://en.wikipedia.org/wiki/Euclidean_algorithm
			// Recursive form would be:
			if( $b == 0 )
				return $a;
			else
				return gcd( $b, $a % $b );
		*/
		while ( $b != 0 ) {
			$remainder = $a % $b;

			// tail recursion...
			$a = $b;
			$b = $remainder;
		}

		return $a;
	}

	/**
	 * Fetch the human readable version of a news code.
	 * A news code is an 8 digit code. The first two
	 * digits are a general classification, so we just
	 * translate that.
	 *
	 * Note, leading 0's are significant, so this is
	 * a string, not an int.
	 *
	 * @param string $val The 8 digit news code.
	 * @return string The human readable form
	 */
	private function convertNewsCode( $val ) {
		if ( !preg_match( '/^\d{8}$/D', $val ) ) {
			// Not a valid news code.
			return $val;
		}
		$cat = '';
		switch ( substr( $val, 0, 2 ) ) {
			case '01':
				$cat = 'ace';
				break;
			case '02':
				$cat = 'clj';
				break;
			case '03':
				$cat = 'dis';
				break;
			case '04':
				$cat = 'fin';
				break;
			case '05':
				$cat = 'edu';
				break;
			case '06':
				$cat = 'evn';
				break;
			case '07':
				$cat = 'hth';
				break;
			case '08':
				$cat = 'hum';
				break;
			case '09':
				$cat = 'lab';
				break;
			case '10':
				$cat = 'lif';
				break;
			case '11':
				$cat = 'pol';
				break;
			case '12':
				$cat = 'rel';
				break;
			case '13':
				$cat = 'sci';
				break;
			case '14':
				$cat = 'soi';
				break;
			case '15':
				$cat = 'spo';
				break;
			case '16':
				$cat = 'war';
				break;
			case '17':
				$cat = 'wea';
				break;
		}
		if ( $cat !== '' ) {
			$catMsg = $this->exifMsg( 'iimcategory', $cat );
			$val = $this->exifMsg( 'subjectnewscode', '', $val, $catMsg );
		}

		return $val;
	}

	/**
	 * Format a coordinate value, convert numbers from floating point
	 * into degree minute second representation.
	 *
	 * @param int $coord Degrees, minutes and seconds
	 * @param string $type Latitude or longitude (for if its a NWS or E)
	 * @return mixed A floating point number or whatever we were fed
	 */
	private function formatCoords( $coord, $type ) {
		$ref = '';
		if ( $coord < 0 ) {
			$nCoord = -$coord;
			if ( $type === 'latitude' ) {
				$ref = 'S';
			} elseif ( $type === 'longitude' ) {
				$ref = 'W';
			}
		} else {
			$nCoord = $coord;
			if ( $type === 'latitude' ) {
				$ref = 'N';
			} elseif ( $type === 'longitude' ) {
				$ref = 'E';
			}
		}

		$deg = floor( $nCoord );
		$min = floor( ( $nCoord - $deg ) * 60.0 );
		$sec = round( ( ( $nCoord - $deg ) - $min / 60 ) * 3600, 2 );

		$deg = $this->formatNum( $deg );
		$min = $this->formatNum( $min );
		$sec = $this->formatNum( $sec );

		return $this->msg( 'exif-coordinate-format', $deg, $min, $sec, $ref, $coord )->text();
	}

	/**
	 * Format the contact info field into a single value.
	 *
	 * This function might be called from
	 * JpegHandler::convertMetadataVersion which is why it is
	 * public.
	 *
	 * @param array $vals Array with fields of the ContactInfo
	 *    struct defined in the IPTC4XMP spec. Or potentially
	 *    an array with one element that is a free form text
	 *    value from the older iptc iim 1:118 prop.
	 * @return string HTML-ish looking wikitext
	 * @since 1.23 no longer static
	 */
	public function collapseContactInfo( $vals ) {
		if ( !( isset( $vals['CiAdrExtadr'] )
			|| isset( $vals['CiAdrCity'] )
			|| isset( $vals['CiAdrCtry'] )
			|| isset( $vals['CiEmailWork'] )
			|| isset( $vals['CiTelWork'] )
			|| isset( $vals['CiAdrPcode'] )
			|| isset( $vals['CiAdrRegion'] )
			|| isset( $vals['CiUrlWork'] )
		) ) {
			// We don't have any sub-properties
			// This could happen if its using old
			// iptc that just had this as a free-form
			// text value.
			// Note: We run this through htmlspecialchars
			// partially to be consistent, and partially
			// because people often insert >, etc into
			// the metadata which should not be interpreted
			// but we still want to auto-link urls.
			foreach ( $vals as &$val ) {
				$val = htmlspecialchars( $val );
			}

			return $this->flattenArrayReal( $vals );
		} else {
			// We have a real ContactInfo field.
			// Its unclear if all these fields have to be
			// set, so assume they do not.
			$url = $tel = $street = $city = $country = '';
			$email = $postal = $region = '';

			// Also note, some of the class names this uses
			// are similar to those used by hCard. This is
			// mostly because they're sensible names. This
			// does not (and does not attempt to) output
			// stuff in the hCard microformat. However it
			// might output in the adr microformat.

			if ( isset( $vals['CiAdrExtadr'] ) ) {
				// Todo: This can potentially be multi-line.
				// Need to check how that works in XMP.
				$street = '<span class="extended-address">'
					. htmlspecialchars(
						$vals['CiAdrExtadr'] )
					. '</span>';
			}
			if ( isset( $vals['CiAdrCity'] ) ) {
				$city = '<span class="locality">'
					. htmlspecialchars( $vals['CiAdrCity'] )
					. '</span>';
			}
			if ( isset( $vals['CiAdrCtry'] ) ) {
				$country = '<span class="country-name">'
					. htmlspecialchars( $vals['CiAdrCtry'] )
					. '</span>';
			}
			if ( isset( $vals['CiEmailWork'] ) ) {
				$emails = array();
				// Have to split multiple emails at commas/new lines.
				$splitEmails = explode( "\n", $vals['CiEmailWork'] );
				foreach ( $splitEmails as $e1 ) {
					// Also split on comma
					foreach ( explode( ',', $e1 ) as $e2 ) {
						$finalEmail = trim( $e2 );
						if ( $finalEmail == ',' || $finalEmail == '' ) {
							continue;
						}
						if ( strpos( $finalEmail, '<' ) !== false ) {
							// Don't do fancy formatting to
							// "My name" <foo@bar.com> style stuff
							$emails[] = $finalEmail;
						} else {
							$emails[] = '[mailto:'
								. $finalEmail
								. ' <span class="email">'
								. $finalEmail
								. '</span>]';
						}
					}
				}
				$email = implode( ', ', $emails );
			}
			if ( isset( $vals['CiTelWork'] ) ) {
				$tel = '<span class="tel">'
					. htmlspecialchars( $vals['CiTelWork'] )
					. '</span>';
			}
			if ( isset( $vals['CiAdrPcode'] ) ) {
				$postal = '<span class="postal-code">'
					. htmlspecialchars(
						$vals['CiAdrPcode'] )
					. '</span>';
			}
			if ( isset( $vals['CiAdrRegion'] ) ) {
				// Note this is province/state.
				$region = '<span class="region">'
					. htmlspecialchars(
						$vals['CiAdrRegion'] )
					. '</span>';
			}
			if ( isset( $vals['CiUrlWork'] ) ) {
				$url = '<span class="url">'
					. htmlspecialchars( $vals['CiUrlWork'] )
					. '</span>';
			}

			return $this->msg( 'exif-contact-value', $email, $url,
				$street, $city, $region, $postal, $country,
				$tel )->text();
		}
	}

	/**
	 * Get a list of fields that are visible by default.
	 *
	 * @return array
	 * @since 1.23
	 */
	public static function getVisibleFields() {
		$fields = array();
		$lines = explode( "\n", wfMessage( 'metadata-fields' )->inContentLanguage()->text() );
		foreach ( $lines as $line ) {
			$matches = array();
			if ( preg_match( '/^\\*\s*(.*?)\s*$/', $line, $matches ) ) {
				$fields[] = $matches[1];
			}
		}
		$fields = array_map( 'strtolower', $fields );

		return $fields;
	}

	/**
	 * Get an array of extended metadata. (See the imageinfo API for format.)
	 *
	 * @param File $file File to use
	 * @return array [<property name> => ['value' => <value>]], or [] on error
	 * @since 1.23
	 */
	public function fetchExtendedMetadata( File $file ) {
		global $wgMemc;

		// If revision deleted, exit immediately
		if ( $file->isDeleted( File::DELETED_FILE ) ) {

			return array();
		}

		$cacheKey = wfMemcKey(
			'getExtendedMetadata',
			$this->getLanguage()->getCode(),
			(int)$this->singleLang,
			$file->getSha1()
		);

		$cachedValue = $wgMemc->get( $cacheKey );
		if (
			$cachedValue
			&& Hooks::run( 'ValidateExtendedMetadataCache', array( $cachedValue['timestamp'], $file ) )
		) {
			$extendedMetadata = $cachedValue['data'];
		} else {
			$maxCacheTime = ( $file instanceof ForeignAPIFile ) ? 60 * 60 * 12 : 60 * 60 * 24 * 30;
			$fileMetadata = $this->getExtendedMetadataFromFile( $file );
			$extendedMetadata = $this->getExtendedMetadataFromHook( $file, $fileMetadata, $maxCacheTime );
			if ( $this->singleLang ) {
				$this->resolveMultilangMetadata( $extendedMetadata );
			}
			$this->discardMultipleValues( $extendedMetadata );
			// Make sure the metadata won't break the API when an XML format is used.
			// This is an API-specific function so it would be cleaner to call it from
			// outside fetchExtendedMetadata, but this way we don't need to redo the
			// computation on a cache hit.
			$this->sanitizeArrayForAPI( $extendedMetadata );
			$valueToCache = array( 'data' => $extendedMetadata, 'timestamp' => wfTimestampNow() );
			$wgMemc->set( $cacheKey, $valueToCache, $maxCacheTime );
		}

		return $extendedMetadata;
	}

	/**
	 * Get file-based metadata in standardized format.
	 *
	 * Note that for a remote file, this might return metadata supplied by extensions.
	 *
	 * @param File $file File to use
	 * @return array [<property name> => ['value' => <value>]], or [] on error
	 * @since 1.23
	 */
	protected function getExtendedMetadataFromFile( File $file ) {
		// If this is a remote file accessed via an API request, we already
		// have remote metadata so we just ignore any local one
		if ( $file instanceof ForeignAPIFile ) {
			// In case of error we pretend no metadata - this will get cached.
			// Might or might not be a good idea.
			return $file->getExtendedMetadata() ?: array();
		}

		$uploadDate = wfTimestamp( TS_ISO_8601, $file->getTimestamp() );

		$fileMetadata = array(
			// This is modification time, which is close to "upload" time.
			'DateTime' => array(
				'value' => $uploadDate,
				'source' => 'mediawiki-metadata',
			),
		);

		$title = $file->getTitle();
		if ( $title ) {
			$text = $title->getText();
			$pos = strrpos( $text, '.' );

			if ( $pos ) {
				$name = substr( $text, 0, $pos );
			} else {
				$name = $text;
			}

			$fileMetadata['ObjectName'] = array(
				'value' => $name,
				'source' => 'mediawiki-metadata',
			);
		}

		return $fileMetadata;
	}

	/**
	 * Get additional metadata from hooks in standardized format.
	 *
	 * @param File $file File to use
	 * @param array $extendedMetadata
	 * @param int $maxCacheTime Hook handlers might use this parameter to override cache time
	 *
	 * @return array [<property name> => ['value' => <value>]], or [] on error
	 * @since 1.23
	 */
	protected function getExtendedMetadataFromHook( File $file, array $extendedMetadata,
		&$maxCacheTime
	) {

		Hooks::run( 'GetExtendedMetadata', array(
			&$extendedMetadata,
			$file,
			$this->getContext(),
			$this->singleLang,
			&$maxCacheTime
		) );

		$visible = array_flip( self::getVisibleFields() );
		foreach ( $extendedMetadata as $key => $value ) {
			if ( !isset( $visible[strtolower( $key )] ) ) {
				$extendedMetadata[$key]['hidden'] = '';
			}
		}

		return $extendedMetadata;
	}

	/**
	 * Turns an XMP-style multilang array into a single value.
	 * If the value is not a multilang array, it is returned unchanged.
	 * See mediawiki.org/wiki/Manual:File_metadata_handling#Multi-language_array_format
	 * @param mixed $value
	 * @return mixed Value in best language, null if there were no languages at all
	 * @since 1.23
	 */
	protected function resolveMultilangValue( $value ) {
		if (
			!is_array( $value )
			|| !isset( $value['_type'] )
			|| $value['_type'] != 'lang'
		) {
			return $value; // do nothing if not a multilang array
		}

		// choose the language best matching user or site settings
		$priorityLanguages = $this->getPriorityLanguages();
		foreach ( $priorityLanguages as $lang ) {
			if ( isset( $value[$lang] ) ) {
				return $value[$lang];
			}
		}

		// otherwise go with the default language, if set
		if ( isset( $value['x-default'] ) ) {
			return $value['x-default'];
		}

		// otherwise just return any one language
		unset( $value['_type'] );
		if ( !empty( $value ) ) {
			return reset( $value );
		}

		// this should not happen; signal error
		return null;
	}

	/**
	 * Turns an XMP-style multivalue array into a single value by dropping all but the first value.
	 * If the value is not a multivalue array (or a multivalue array inside a multilang array), it is returned unchanged.
	 * See mediawiki.org/wiki/Manual:File_metadata_handling#Multi-language_array_format
	 * @param mixed $value
	 * @return mixed The value, or the first value if there were multiple ones
	 * @since 1.25
	 */
	protected function resolveMultivalueValue( $value ) {
		if ( !is_array( $value ) ) {
			return $value;
		} elseif ( isset( $value['_type'] ) && $value['_type'] === 'lang' ) { // if this is a multilang array, process fields separately
			$newValue = array();
			foreach ( $value as $k => $v ) {
				$newValue[$k] = $this->resolveMultivalueValue( $v );
			}
			return $newValue;
		} else { // _type is 'ul' or 'ol' or missing in which case it defaults to 'ul'
			list( $k, $v ) = each( $value );
			if ( $k === '_type' ) {
				$v = current( $value );
			}
			return $v;
		}
	}

	/**
	 * Takes an array returned by the getExtendedMetadata* functions,
	 * and resolves multi-language values in it.
	 * @param array $metadata
	 * @since 1.23
	 */
	protected function resolveMultilangMetadata( &$metadata ) {
		if ( !is_array( $metadata ) ) {
			return;
		}
		foreach ( $metadata as &$field ) {
			if ( isset( $field['value'] ) ) {
				$field['value'] = $this->resolveMultilangValue( $field['value'] );
			}
		}
	}

	/**
	 * Takes an array returned by the getExtendedMetadata* functions,
	 * and turns all fields into single-valued ones by dropping extra values.
	 * @param array $metadata
	 * @since 1.25
	 */
	protected function discardMultipleValues( &$metadata ) {
		if ( !is_array( $metadata ) ) {
			return;
		}
		foreach ( $metadata as $key => &$field ) {
			if ( $key === 'Software' || $key === 'Contact' ) {
				// we skip some fields which have composite values. They are not particularly interesting
				// and you can get them via the metadata / commonmetadata APIs anyway.
				continue;
			}
			if ( isset( $field['value'] ) ) {
				$field['value'] = $this->resolveMultivalueValue( $field['value'] );
			}
		}

	}

	/**
	 * Makes sure the given array is a valid API response fragment
	 * @param array $arr
	 */
	protected function sanitizeArrayForAPI( &$arr ) {
		if ( !is_array( $arr ) ) {
			return;
		}

		$counter = 1;
		foreach ( $arr as $key => &$value ) {
			$sanitizedKey = $this->sanitizeKeyForAPI( $key );
			if ( $sanitizedKey !== $key ) {
				if ( isset( $arr[$sanitizedKey] ) ) {
					// Make the sanitized keys hopefully unique.
					// To make it definitely unique would be too much effort, given that
					// sanitizing is only needed for misformatted metadata anyway, but
					// this at least covers the case when $arr is numeric.
					$sanitizedKey .= $counter;
					++$counter;
				}
				$arr[$sanitizedKey] = $arr[$key];
				unset( $arr[$key] );
			}
			if ( is_array( $value ) ) {
				$this->sanitizeArrayForAPI( $value );
			}
		}

		// Handle API metadata keys (particularly "_type")
		$keys = array_filter( array_keys( $arr ), 'ApiResult::isMetadataKey' );
		if ( $keys ) {
			ApiResult::setPreserveKeysList( $arr, $keys );
		}
	}

	/**
	 * Turns a string into a valid API identifier.
	 * @param string $key
	 * @return string
	 * @since 1.23
	 */
	protected function sanitizeKeyForAPI( $key ) {
		// drop all characters which are not valid in an XML tag name
		// a bunch of non-ASCII letters would be valid but probably won't
		// be used so we take the easy way
		$key = preg_replace( '/[^a-zA-z0-9_:.-]/', '', $key );
		// drop characters which are invalid at the first position
		$key = preg_replace( '/^[\d-.]+/', '', $key );

		if ( $key == '' ) {
			$key = '_';
		}

		// special case for an internal keyword
		if ( $key == '_element' ) {
			$key = 'element';
		}

		return $key;
	}

	/**
	 * Returns a list of languages (first is best) to use when formatting multilang fields,
	 * based on user and site preferences.
	 * @return array
	 * @since 1.23
	 */
	protected function getPriorityLanguages() {
		$priorityLanguages =
			Language::getFallbacksIncludingSiteLanguage( $this->getLanguage()->getCode() );
		$priorityLanguages = array_merge(
			(array)$this->getLanguage()->getCode(),
			$priorityLanguages[0],
			$priorityLanguages[1]
		);

		return $priorityLanguages;
	}
}
