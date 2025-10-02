<?php
/**
 * Formatting of image metadata values into human readable form.
 *
 * @license GPL-2.0-or-later
 * @ingroup Media
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, 2009 Brent Garber, 2010 Brian Wolff
 * @license GPL-2.0-or-later
 * @see http://exif.org/Exif2-2.PDF The Exif 2.2 specification
 * @file
 */

use MediaWiki\Api\ApiResult;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\ForeignAPIFile;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

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
	use ProtectedHookAccessorTrait;

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
	 * @param IContextSource|false $context
	 * @return array
	 */
	public static function getFormattedData( $tags, $context = false ) {
		$obj = new self;
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

		// Ignore these complex values
		unset( $tags['HasExtendedXMP'] );
		unset( $tags['AuthorsPosition'] );
		unset( $tags['LocationCreated'] );
		unset( $tags['LocationShown'] );
		unset( $tags['GPSAltitudeRef'] );

		foreach ( $tags as $tag => &$vals ) {
			// This seems ugly to wrap non-array's in an array just to unwrap again,
			// especially when most of the time it is not an array
			if ( !is_array( $vals ) ) {
				$vals = [ $vals ];
			}

			// _type is a special value to say what array type
			if ( isset( $vals['_type'] ) ) {
				$type = $vals['_type'];
				unset( $vals['_type'] );
			} else {
				$type = 'ul'; // default unordered list.
			}

			// _formatted is a special value to indicate the subclass
			// already handled & formatted this tag as wikitext
			if ( isset( $tags[$tag]['_formatted'] ) ) {
				$tags[$tag] = $this->flattenArrayReal(
					$tags[$tag]['_formatted'], $type
				);
				continue;
			}

			// This is done differently as the tag is an array.
			if ( $tag === 'GPSTimeStamp' && count( $vals ) === 3 ) {
				// hour min sec array

				$h = explode( '/', $vals[0], 2 );
				$m = explode( '/', $vals[1], 2 );
				$s = explode( '/', $vals[2], 2 );

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
				$vals = str_pad( (string)( (int)$h[0] / (int)$h[1] ), 2, '0', STR_PAD_LEFT )
					. ':' . str_pad( (string)( (int)$m[0] / (int)$m[1] ), 2, '0', STR_PAD_LEFT )
					. ':' . str_pad( (string)( (int)$s[0] / (int)$s[1] ), 2, '0', STR_PAD_LEFT );

				$time = wfTimestamp( TS_MW, '1971:01:01 ' . $vals );
				// the 1971:01:01 is just a placeholder, and not shown to user.
				if ( $time && (int)$time > 0 ) {
					$vals = $this->getLanguage()->time( $time );
				}
				continue;
			}

			// The contact info is a multi-valued field
			// instead of the other props which are single
			// valued (mostly) so handle as a special case.
			if ( $tag === 'Contact' || $tag === 'CreatorContactInfo' ) {
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
								$val = $this->literal( $val );
								break;
						}
						break;

					case 'PhotometricInterpretation':
						switch ( $val ) {
							case 0:
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 8:
							case 9:
							case 10:
							case 32803:
							case 34892:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
								break;
						}
						break;

					// TODO: YCbCrCoefficients  #p27 (see annex E)
					case 'ExifVersion':
					// PHP likes to be the odd one out with casing of FlashPixVersion;
					// https://www.exif.org/Exif2-2.PDF#page=32 and
					// https://www.digitalgalen.net/Documents/External/XMP/XMPSpecificationPart2.pdf#page=51
					// both use FlashpixVersion. However, since at least 2002, PHP has used FlashPixVersion at
					// https://github.com/php/php-src/blame/master/ext/exif/exif.c#L725
					case 'FlashPixVersion':
					// But we can still get the correct casing from
					// Wikimedia\XMPReader on PDFs
					case 'FlashpixVersion':
						$val = $this->literal( (int)$val / 100 );
						break;

					case 'ColorSpace':
						switch ( $val ) {
							case 1:
							case 65535:
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
					case 'FirstPhotoDate':
					case 'LastPhotoDate':
						if ( $val === null ) {
							// T384879 - we don't need to call literal to turn this into a string, but
							// we might as well call it for consistency and future proofing of the default value
							$val = $this->literal( $val );
							break;
						}

						if ( $val === '0000:00:00 00:00:00' || $val === '    :  :     :  :  ' ) {
							$val = $this->msg( 'exif-unknowndate' )->text();
							break;
						}
						if ( preg_match(
							'/^(?:\d{4}):(?:\d\d):(?:\d\d) (?:\d\d):(?:\d\d):(?:\d\d)$/D',
							$val
						) ) {
							// Full date.
							$time = wfTimestamp( TS_MW, $val );
							if ( $time && (int)$time > 0 ) {
								$val = $this->getLanguage()->timeanddate( $time );
								break;
							}
						} elseif ( preg_match( '/^(?:\d{4}):(?:\d\d):(?:\d\d) (?:\d\d):(?:\d\d)$/D', $val ) ) {
							// No second field. Still format the same
							// since timeanddate doesn't include seconds anyways,
							// but second still available in api
							$time = wfTimestamp( TS_MW, $val . ':00' );
							if ( $time && (int)$time > 0 ) {
								$val = $this->getLanguage()->timeanddate( $time );
								break;
							}
						} elseif ( preg_match( '/^(?:\d{4}):(?:\d\d):(?:\d\d)$/D', $val ) ) {
							// If only the date but not the time is filled in.
							$time = wfTimestamp( TS_MW, substr( $val, 0, 4 )
								. substr( $val, 5, 2 )
								. substr( $val, 8, 2 )
								. '000000' );
							if ( $time && (int)$time > 0 ) {
								$val = $this->getLanguage()->date( $time );
								break;
							}
						}
						// else it will just output $val without formatting it.
						$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
								break;
						}
						break;

					case 'Flash':
						if ( $val === '' ) {
							$val = 0;
						}
						$flashDecode = [
							'fired' => $val & 0b00000001,
							'return' => ( $val & 0b00000110 ) >> 1,
							'mode' => ( $val & 0b00011000 ) >> 3,
							'function' => ( $val & 0b00100000 ) >> 5,
							'redeye' => ( $val & 0b01000000 ) >> 6,
							// 'reserved' => ( $val & 0b10000000 ) >> 7,
						];
						$flashMsgs = [];
						# We do not need to handle unknown values since all are used.
						foreach ( $flashDecode as $subTag => $subValue ) {
							# We do not need any message for zeroed values.
							if ( $subTag !== 'fired' && $subValue === 0 ) {
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
								break;
						}
						break;

					case 'CustomRendered':
						switch ( $val ) {
							case 0: /* normal */
							case 1: /* custom */
								/* The following are unofficial Apple additions */
							case 2: /* HDR (no original saved) */
							case 3: /* HDR (original saved) */
							case 4: /* Original (for HDR) */
								/* Yes 5 is not present ;) */
							case 6: /* Panorama */
							case 7: /* Portrait HDR */
							case 8: /* Portrait */
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								/* If not recognized, display as is. */
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
								break;
						}
						break;

					// The GPS...Ref values are kept for compatibility, probably won't be reached.
					case 'GPSLatitudeRef':
					case 'GPSDestLatitudeRef':
						switch ( $val ) {
							case 'N':
							case 'S':
								$val = $this->exifMsg( 'GPSLatitude', $val );
								break;
							default:
								/* If not recognized, display as is. */
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
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
								$val = $this->literal( $val );
								break;
						}
						break;

					case 'GPSDOP':
						// See https://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)
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
						$val = $this->exifMsg( $tag, '', $this->literal( $val ) );
						break;

					case 'Software':
						if ( is_array( $val ) ) {
							if ( count( $val ) > 1 ) {
								// if its a software, version array.
								$val = $this->msg(
									'exif-software-version-value',
									$this->literal( $val[0] ),
									$this->literal( $val[1] )
								)->text();
							} else {
								// https://phabricator.wikimedia.org/T178130
								$val = $this->exifMsg( $tag, '', $this->literal( $val[0] ) );
							}
						} else {
							$val = $this->exifMsg( $tag, '', $this->literal( $val ) );
						}
						break;

					case 'ExposureTime':
						// Show the pretty fraction as well as decimal version
						$val = $this->msg( 'exif-exposuretime-format',
							$this->formatFraction( $val ), $this->formatNum( $val ) )->text();
						break;
					case 'ISOSpeedRatings':
						// If it's 65535 that means it's at the
						// limit of the size of Exif::short and
						// is really higher.
						if ( $val === '65535' ) {
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
						if ( str_contains( $val, '/' ) ) {
							// need to expand this earlier to calculate fNumber
							[ $n, $d ] = explode( '/', $val, 2 );
							if ( is_numeric( $n ) && is_numeric( $d ) ) {
								$val = (int)$n / (int)$d;
							}
						}
						if ( is_numeric( $val ) ) {
							$fNumber = 2 ** ( $val / 2 );
							if ( is_finite( $fNumber ) ) {
								$val = $this->msg( 'exif-maxaperturevalue-value',
									$this->formatNum( $val ),
									$this->formatNum( $fNumber, 2 )
								)->text();
								break;
							}
						}
						$val = $this->literal( $val );
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
								break;
							default:
								$val = $this->literal( $val );
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
						if ( $val === 0 || $val === 9 ) {
							$urgency = 'other';
						} elseif ( $val < 5 && $val > 1 ) {
							$urgency = 'high';
						} elseif ( $val === 5 ) {
							$urgency = 'normal';
						} elseif ( $val <= 8 && $val > 5 ) {
							$urgency = 'low';
						}

						if ( $urgency !== '' ) {
							$val = $this->exifMsg( 'urgency',
								$urgency, $this->literal( $val )
							);
						} else {
							$val = $this->literal( $val );
						}
						break;
					case 'DigitalSourceType':
						// Should be a url starting with
						// http://cv.iptc.org/newscodes/digitalsourcetype/
						if ( str_starts_with( $val, 'http://cv.iptc.org/newscodes/digitalsourcetype/' ) ) {
							$code = substr( $val, 47 );
							$msg = $this->msg( 'exif-digitalsourcetype-' . strtolower( $code ) );
							if ( !$msg->isDisabled() ) {
								$val = $msg->text();
								break;
							}
						}
						$val = $this->literal( $val );
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
					case 'UserComment':
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
					case 'OrganisationInImage':
					case 'PersonInImage':
					case 'CaptureSoftware':
					case 'GPSAreaInformation':
					case 'GPSProcessingMethod':
					case 'StitchingSoftware':
					case 'SubSecTime':
					case 'SubSecTimeOriginal':
					case 'SubSecTimeDigitized':
						$val = $this->literal( $val );
						break;

					case 'ProjectionType':
						switch ( $val ) {
							case 'equirectangular':
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								$val = $this->literal( $val );
								break;
						}
						break;
					case 'ObjectCycle':
						switch ( $val ) {
							case 'a':
							case 'p':
							case 'b':
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								$val = $this->literal( $val );
								break;
						}
						break;
					case 'Copyrighted':
					case 'UsePanoramaViewer':
					case 'ExposureLockUsed':
						switch ( $val ) {
							case 'True':
							case 'False':
								$val = $this->exifMsg( $tag, $val );
								break;
							default:
								$val = $this->literal( $val );
								break;
						}
						break;
					case 'Rating':
						if ( $val === '-1' ) {
							$val = $this->exifMsg( $tag, 'rejected' );
						} else {
							$val = $this->formatNum( $val );
						}
						break;

					case 'LanguageCode':
						$lang = MediaWikiServices::getInstance()
							->getLanguageNameUtils()
							->getLanguageName( strtolower( $val ), $this->getLanguage()->getCode() );
						$val = $this->literal( $lang ?: $val );
						break;

					default:
						$val = $this->formatNum( $val, false, $tag );
						break;
				}
			}
			// End formatting values, start flattening arrays.
			$vals = $this->flattenArrayReal( $vals, $type );
		}

		return $tags;
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
	 * @internal
	 */
	public function flattenArrayReal( $vals, $type = 'ul', $noHtml = false ) {
		if ( !is_array( $vals ) ) {
			return $vals; // do nothing if not an array;
		}

		if ( isset( $vals['_type'] ) ) {
			$type = $vals['_type'];
			unset( $vals['_type'] );
		}

		if ( count( $vals ) === 1 && $type !== 'lang' && isset( $vals[0] ) ) {
			return $vals[0];
		}
		if ( count( $vals ) === 0 ) {
			wfDebug( __METHOD__ . " metadata array with 0 elements!" );

			return ""; // paranoia. This should never happen
		}
		// Check if $vals contains nested arrays
		$containsNestedArrays = in_array( true, array_map( 'is_array', $vals ), true );
		if ( $containsNestedArrays ) {
			wfLogWarning( __METHOD__ . ': Invalid $vals, contains nested arrays: ' . json_encode( $vals ) );
		}

		/* @todo FIXME: This should hide some of the list entries if there are
		 * say more than four. Especially if a field is translated into 20
		 * languages, we don't want to show them all by default
		 */
		switch ( $type ) {
			case 'lang':
				// Display default, followed by ContentLanguage,
				// followed by the rest in no particular order.

				// Todo: hide some items if really long list.

				$content = '';

				$priorityLanguages = $this->getPriorityLanguages();
				$defaultItem = false;
				$defaultLang = false;

				// If default is set, save it for later,
				// as we don't know if it's equal to one of the lang codes.
				// (In xmp you specify the language for a default property by having
				// both a default prop, and one in the language that are identical)
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
						$content .= $this->langItem( $vals[$pLang], $pLang, $isDefault, $noHtml );

						unset( $vals[$pLang] );

						if ( $this->singleLang ) {
							return Html::rawElement( 'span', [ 'lang' => $pLang ], $vals[$pLang] );
						}
					}
				}

				// Now do the rest.
				foreach ( $vals as $lang => $item ) {
					if ( $item === $defaultItem ) {
						$defaultLang = $lang;
						continue;
					}
					$content .= $this->langItem( $item, $lang, false, $noHtml );
					if ( $this->singleLang ) {
						return Html::rawElement( 'span', [ 'lang' => $lang ], $item );
					}
				}
				if ( $defaultItem !== false ) {
					$content = $this->langItem( $defaultItem, $defaultLang, true, $noHtml ) . $content;
					if ( $this->singleLang ) {
						return $defaultItem;
					}
				}
				if ( $noHtml ) {
					return $content;
				}

				return '<ul class="metadata-langlist">' . $content . '</ul>';
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

	/** Helper function for creating lists of translations.
	 *
	 * @param string $value Value (this is not escaped)
	 * @param string $lang Lang code of item or false
	 * @param bool $default If it is default value.
	 * @param bool $noHtml If to avoid html (for back-compat)
	 * @return string Language item (Note: despite how this looks, this is
	 *   treated as wikitext, not as HTML).
	 */
	private function langItem( $value, $lang, $default = false, $noHtml = false ) {
		if ( $lang === false && $default === false ) {
			throw new InvalidArgumentException( '$lang and $default cannot both be false.' );
		}

		if ( $noHtml ) {
			$wrappedValue = $this->literal( $value );
		} else {
			$wrappedValue = '<span class="mw-metadata-lang-value">' . $this->literal( $value ) . '</span>';
		}

		if ( $lang === false ) {
			$msg = $this->msg( 'metadata-langitem-default', $wrappedValue );
			if ( $noHtml ) {
				return $msg->text() . "\n\n";
			} /* else */

			return '<li class="mw-metadata-lang-default">' . $msg->text() . "</li>\n";
		}

		$lowLang = strtolower( $lang );
		$languageNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();
		$langName = $languageNameUtils->getLanguageName( $lowLang );
		if ( $langName === '' ) {
			// try just the base language name. (aka en-US -> en ).
			$langPrefix = explode( '-', $lowLang, 2 )[0];
			$langName = $languageNameUtils->getLanguageName( $langPrefix );
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

		$item = '<li class="mw-metadata-lang-code-' . $lang;
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
	 * @param string|int|null $val The literal value
	 * @return string The value, properly escaped as wikitext -- with some
	 *   exceptions to allow auto-linking, etc.
	 */
	protected function literal( $val ): string {
		if ( $val === null ) {
			return '';
		}
		// T266707: historically this has used htmlspecialchars to protect
		// the string contents, but it should probably be changed to use
		// wfEscapeWikitext() instead -- however, "we still want to auto-link
		// urls" so wfEscapeWikitext isn't *quite* right...
		return htmlspecialchars( $val );
	}

	/**
	 * Convenience function for getFormattedData()
	 *
	 * @param string $tag The tag name to pass on
	 * @param string|int $val The value of the tag
	 * @param string|null $arg A wikitext argument to pass ($1)
	 * @param string|null $arg2 A 2nd wikitext argument to pass ($2)
	 * @return string The text content of "exif-$tag-$val" message in lower case
	 */
	private function exifMsg( $tag, $val, $arg = null, $arg2 = null ) {
		if ( $val === '' ) {
			$val = 'value';
		}

		return $this->msg(
			MediaWikiServices::getInstance()->getContentLanguage()->lc( "exif-$tag-$val" ),
			$arg,
			$arg2
		)->text();
	}

	/**
	 * Format a number, convert numbers from fractions into floating point
	 * numbers, joins arrays of numbers with commas.
	 *
	 * @param mixed $num The value to format
	 * @param float|int|false $round Digits to round to or false.
	 * @param string|null $tagName (optional) The name of the tag (for debugging)
	 * @return mixed A floating point number or whatever we were fed
	 */
	private function formatNum( $num, $round = false, $tagName = null ) {
		$m = [];
		if ( is_array( $num ) ) {
			$out = [];
			foreach ( $num as $number ) {
				$out[] = $this->formatNum( $number, $round, $tagName );
			}

			return $this->getLanguage()->commaList( $out );
		}
		if ( is_numeric( $num ) ) {
			if ( $round !== false ) {
				$num = round( $num, $round );
			}
			return $this->getLanguage()->formatNum( $num );
		}
		$num ??= '';
		if ( preg_match( '/^(-?\d+)\/(\d+)$/', $num, $m ) ) {
			if ( $m[2] !== 0 ) {
				$newNum = (int)$m[1] / (int)$m[2];
				if ( $round !== false ) {
					$newNum = round( $newNum, $round );
				}
			} else {
				$newNum = $num;
			}

			return $this->getLanguage()->formatNum( $newNum );
		}
		# T267370: there are a lot of strange EXIF tags floating around.
		LoggerFactory::getInstance( 'formatnum' )->warning(
			'FormatMetadata::formatNum with non-numeric value',
			[
				'tag' => $tagName,
				'value' => $num,
			]
		);
		return $this->literal( $num );
	}

	/**
	 * Format a rational number, reducing fractions
	 *
	 * @param mixed $num The value to format
	 * @return mixed A floating point number or whatever we were fed
	 */
	private function formatFraction( $num ) {
		$m = [];
		$num ??= '';
		if ( preg_match( '/^(-?\d+)\/(\d+)$/', $num, $m ) ) {
			$numerator = (int)$m[1];
			$denominator = (int)$m[2];
			$gcd = $this->gcd( abs( $numerator ), $denominator );
			if ( $gcd !== 0 ) {
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
			// https://en.wikipedia.org/wiki/Euclidean_algorithm
			// Recursive form would be:
			if ( $b == 0 )
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
			$val = $this->exifMsg( 'subjectnewscode', '', $this->literal( $val ), $catMsg );
		}

		return $val;
	}

	/**
	 * Format a coordinate value, convert numbers from floating point
	 * into degree minute second representation.
	 *
	 * @param float|string $coord Expected to be a number or numeric string in degrees
	 * @param string $type "latitude" or "longitude"
	 * @return string
	 */
	private function formatCoords( $coord, string $type ) {
		if ( !is_numeric( $coord ) ) {
			wfDebugLog( 'exif', __METHOD__ . ": \"$coord\" is not a number" );
			return $this->literal( (string)$coord );
		}

		$ref = '';
		if ( $coord < 0 ) {
			$nCoord = -$coord;
			if ( $type === 'latitude' ) {
				$ref = 'S';
			} elseif ( $type === 'longitude' ) {
				$ref = 'W';
			}
		} else {
			$nCoord = (float)$coord;
			if ( $type === 'latitude' ) {
				$ref = 'N';
			} elseif ( $type === 'longitude' ) {
				$ref = 'E';
			}
		}

		$deg = floor( $nCoord );
		$min = floor( ( $nCoord - $deg ) * 60 );
		$sec = round( ( ( $nCoord - $deg ) * 60 - $min ) * 60, 2 );

		$deg = $this->formatNum( $deg );
		$min = $this->formatNum( $min );
		$sec = $this->formatNum( $sec );

		// Note the default message "$1° $2′ $3″ $4" ignores the 5th parameter
		return $this->msg( 'exif-coordinate-format', $deg, $min, $sec, $ref, $this->literal( $coord ) )->text();
	}

	/**
	 * Format the contact info field into a single value.
	 *
	 * This function might be called from
	 * ExifBitmapHandler::convertMetadataVersion which is why it is
	 * public.
	 *
	 * @param array $vals Array with fields of the ContactInfo
	 *    struct defined in the IPTC4XMP spec. Or potentially
	 *    an array with one element that is a free form text
	 *    value from the older iptc iim 1:118 prop.
	 * @return string HTML-ish looking wikitext
	 * @since 1.23 no longer static
	 */
	public function collapseContactInfo( array $vals ) {
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
			// Note: people often insert >, etc into
			// the metadata which should not be interpreted
			// but we still want to auto-link urls.
			foreach ( $vals as &$val ) {
				$val = $this->literal( $val );
			}

			return $this->flattenArrayReal( $vals );
		}

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
				. $this->literal(
					$vals['CiAdrExtadr'] )
				. '</span>';
		}
		if ( isset( $vals['CiAdrCity'] ) ) {
			$city = '<span class="locality">'
				. $this->literal( $vals['CiAdrCity'] )
				. '</span>';
		}
		if ( isset( $vals['CiAdrCtry'] ) ) {
			$country = '<span class="country-name">'
				. $this->literal( $vals['CiAdrCtry'] )
				. '</span>';
		}
		if ( isset( $vals['CiEmailWork'] ) ) {
			$emails = [];
			// Have to split multiple emails at commas/new lines.
			$splitEmails = explode( "\n", $vals['CiEmailWork'] );
			foreach ( $splitEmails as $e1 ) {
				// Also split on comma
				foreach ( explode( ',', $e1 ) as $e2 ) {
					$finalEmail = trim( $e2 );
					if ( $finalEmail === ',' || $finalEmail === '' ) {
						continue;
					}
					if ( str_contains( $finalEmail, '<' ) ) {
						// Don't do fancy formatting to
						// "My name" <foo@bar.com> style stuff
						$emails[] = $this->literal( $finalEmail );
					} else {
						$emails[] = '[mailto:'
							. $finalEmail
							. ' <span class="email">'
							. $this->literal( $finalEmail )
							. '</span>]';
					}
				}
			}
			$email = implode( ', ', $emails );
		}
		if ( isset( $vals['CiTelWork'] ) ) {
			$tel = '<span class="tel">'
				. $this->literal( $vals['CiTelWork'] )
				. '</span>';
		}
		if ( isset( $vals['CiAdrPcode'] ) ) {
			$postal = '<span class="postal-code">'
				. $this->literal( $vals['CiAdrPcode'] )
				. '</span>';
		}
		if ( isset( $vals['CiAdrRegion'] ) ) {
			// Note this is province/state.
			$region = '<span class="region">'
				. $this->literal( $vals['CiAdrRegion'] )
				. '</span>';
		}
		if ( isset( $vals['CiUrlWork'] ) ) {
			$url = '<span class="url">'
				. $this->literal( $vals['CiUrlWork'] )
				. '</span>';
		}

		return $this->msg( 'exif-contact-value', $email, $url,
			$street, $city, $region, $postal, $country, $tel )->text();
	}

	/**
	 * Get a list of fields that are visible by default.
	 *
	 * @return string[]
	 * @since 1.23
	 */
	public static function getVisibleFields() {
		$fields = [];
		$lines = explode( "\n", wfMessage( 'metadata-fields' )->inContentLanguage()->text() );
		foreach ( $lines as $line ) {
			$matches = [];
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
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		// If revision deleted, exit immediately
		if ( $file->isDeleted( File::DELETED_FILE ) ) {
			return [];
		}

		$cacheKey = $cache->makeKey(
			'getExtendedMetadata',
			$this->getLanguage()->getCode(),
			(int)$this->singleLang,
			$file->getSha1()
		);
		$maxCacheTime = ( $file instanceof ForeignAPIFile ) ? 60 * 60 * 12 : 60 * 60 * 24 * 30;

		$cachedValue = $cache->getWithSetCallback(
			$cacheKey,
			$maxCacheTime,
			function () use ( $file ) {
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

				return [ 'data' => $extendedMetadata, 'timestamp' => wfTimestampNow() ];
			},
			[
				'touchedCallback' => function ( $value ) use ( $file ) {
					if (
						!$this->getHookRunner()->onValidateExtendedMetadataCache( $value['timestamp'], $file )
					) {
						// Reject cache and regenerate
						return time();
					}
					return null;
				}
			]
		);

		return $cachedValue['data'];
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
			return $file->getExtendedMetadata() ?: [];
		}

		$uploadDate = wfTimestamp( TS_ISO_8601, $file->getTimestamp() );

		$fileMetadata = [
			// This is modification time, which is close to "upload" time.
			'DateTime' => [
				'value' => $uploadDate,
				'source' => 'mediawiki-metadata',
			],
		];

		$title = $file->getTitle();
		if ( $title ) {
			$text = $title->getText();
			$pos = strrpos( $text, '.' );

			if ( $pos ) {
				$name = substr( $text, 0, $pos );
			} else {
				$name = $text;
			}

			$fileMetadata['ObjectName'] = [
				'value' => $name,
				'source' => 'mediawiki-metadata',
			];
		}

		return $fileMetadata;
	}

	/**
	 * Get additional metadata from hooks in standardized format.
	 *
	 * @param File $file File to use
	 * @param array $extendedMetadata
	 * @param int &$maxCacheTime Hook handlers might use this parameter to override cache time
	 *
	 * @return array [<property name> => ['value' => <value>]], or [] on error
	 * @since 1.23
	 */
	protected function getExtendedMetadataFromHook( File $file, array $extendedMetadata,
		&$maxCacheTime
	) {
		$this->getHookRunner()->onGetExtendedMetadata(
			$extendedMetadata,
			$file,
			$this->getContext(),
			$this->singleLang,
			$maxCacheTime
		);

		$visible = array_fill_keys( self::getVisibleFields(), true );
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
			|| $value['_type'] !== 'lang'
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
		if ( $value ) {
			return reset( $value );
		}

		// this should not happen; signal error
		return null;
	}

	/**
	 * Turns an XMP-style multivalue array into a single value by dropping all but the first
	 * value. If the value is not a multivalue array (or a multivalue array inside a multilang
	 * array), it is returned unchanged.
	 * See mediawiki.org/wiki/Manual:File_metadata_handling#Multi-language_array_format
	 * @param mixed $value
	 * @return mixed The value, or the first value if there were multiple ones
	 * @since 1.25
	 */
	protected function resolveMultivalueValue( $value ) {
		if ( !is_array( $value ) ) {
			return $value;
		}
		if ( isset( $value['_type'] ) && $value['_type'] === 'lang' ) {
			// if this is a multilang array, process fields separately
			$newValue = [];
			foreach ( $value as $k => $v ) {
				$newValue[$k] = $this->resolveMultivalueValue( $v );
			}
			return $newValue;
		}
		// _type is 'ul' or 'ol' or missing in which case it defaults to 'ul'
		$v = reset( $value );
		if ( key( $value ) === '_type' ) {
			$v = next( $value );
		}
		return $v;
	}

	/**
	 * Takes an array returned by the getExtendedMetadata* functions,
	 * and resolves multi-language values in it.
	 * @param array &$metadata
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
	 * @param array &$metadata
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
	 * @param array &$arr
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
		unset( $value );

		// Handle API metadata keys (particularly "_type")
		$keys = array_filter( array_keys( $arr ), [ ApiResult::class, 'isMetadataKey' ] );
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
		$key = preg_replace( '/[^a-zA-Z0-9_:.\-]/', '', $key );
		// drop characters which are invalid at the first position
		$key = preg_replace( '/^[\d\-.]+/', '', $key );

		if ( $key === '' ) {
			$key = '_';
		// special case for an internal keyword
		} elseif ( $key === '_element' ) {
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
		$priorityLanguages = MediaWikiServices::getInstance()
			->getLanguageFallback()
			->getAllIncludingSiteLanguage( $this->getLanguage()->getCode() );
		$priorityLanguages = array_merge(
			(array)$this->getLanguage()->getCode(),
			$priorityLanguages[0],
			$priorityLanguages[1]
		);

		return $priorityLanguages;
	}
}
