<?php
/**
 * Class for some IPTC functions.
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
 * Class for some IPTC functions.
 *
 * @ingroup Media
 */
class IPTC {
	/**
	 * This takes the results of iptcparse() and puts it into a
	 * form that can be handled by mediawiki. Generally called from
	 * BitmapMetadataHandler::doApp13.
	 *
	 * @see http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf
	 *
	 * @param string $rawData The app13 block from jpeg containing iptc/iim data
	 * @return array IPTC metadata array
	 */
	static function parse( $rawData ) {
		$parsed = iptcparse( $rawData );
		$data = [];
		if ( !is_array( $parsed ) ) {
			return $data;
		}

		$c = '';
		// charset info contained in tag 1:90.
		if ( isset( $parsed['1#090'] ) && isset( $parsed['1#090'][0] ) ) {
			$c = self::getCharset( $parsed['1#090'][0] );
			if ( $c === false ) {
				// Unknown charset. refuse to parse.
				// note: There is a different between
				// unknown and no charset specified.
				return [];
			}
			unset( $parsed['1#090'] );
		}

		foreach ( $parsed as $tag => $val ) {
			if ( isset( $val[0] ) && trim( $val[0] ) == '' ) {
				wfDebugLog( 'iptc', "IPTC tag $tag had only whitespace as its value." );
				continue;
			}
			switch ( $tag ) {
				case '2#120': /*IPTC caption. mapped with exif ImageDescription*/
					$data['ImageDescription'] = self::convIPTC( $val, $c );
					break;
				case '2#116': /* copyright. Mapped with exif copyright */
					$data['Copyright'] = self::convIPTC( $val, $c );
					break;
				case '2#080': /* byline. Mapped with exif Artist */
					/* merge with byline title (2:85)
					 * like how exif does it with
					 * Title, person. Not sure if this is best
					 * approach since we no longer have the two fields
					 * separate. each byline title entry corresponds to a
					 * specific byline.                          */

					$bylines = self::convIPTC( $val, $c );
					if ( isset( $parsed['2#085'] ) ) {
						$titles = self::convIPTC( $parsed['2#085'], $c );
					} else {
						$titles = [];
					}

					$titleCount = count( $titles );
					for ( $i = 0; $i < $titleCount; $i++ ) {
						if ( isset( $bylines[$i] ) ) {
							// theoretically this should always be set
							// but doesn't hurt to be careful.
							$bylines[$i] = $titles[$i] . ', ' . $bylines[$i];
						}
					}
					$data['Artist'] = $bylines;
					break;
				case '2#025': /* keywords */
					$data['Keywords'] = self::convIPTC( $val, $c );
					break;
				case '2#101': /* Country (shown) */
					$data['CountryDest'] = self::convIPTC( $val, $c );
					break;
				case '2#095': /* state/province (shown) */
					$data['ProvinceOrStateDest'] = self::convIPTC( $val, $c );
					break;
				case '2#090': /* city (Shown) */
					$data['CityDest'] = self::convIPTC( $val, $c );
					break;
				case '2#092': /* sublocation (shown) */
					$data['SublocationDest'] = self::convIPTC( $val, $c );
					break;
				case '2#005': /* object name/title */
					$data['ObjectName'] = self::convIPTC( $val, $c );
					break;
				case '2#040': /* special instructions */
					$data['SpecialInstructions'] = self::convIPTC( $val, $c );
					break;
				case '2#105': /* headline */
					$data['Headline'] = self::convIPTC( $val, $c );
					break;
				case '2#110': /* credit */
					/*"Identifies the provider of the objectdata,
					 * not necessarily the owner/creator". */
					$data['Credit'] = self::convIPTC( $val, $c );
					break;
				case '2#115': /* source */
					/* "Identifies the original owner of the intellectual content of the
					 *objectdata. This could be an agency, a member of an agency or
					 *an individual." */
					$data['Source'] = self::convIPTC( $val, $c );
					break;

				case '2#007': /* edit status (lead, correction, etc) */
					$data['EditStatus'] = self::convIPTC( $val, $c );
					break;
				case '2#015': /* category. deprecated. max 3 letters in theory, often more */
					$data['iimCategory'] = self::convIPTC( $val, $c );
					break;
				case '2#020': /* category. deprecated. */
					$data['iimSupplementalCategory'] = self::convIPTC( $val, $c );
					break;
				case '2#010': /*urgency (1-8. 1 most, 5 normal, 8 low priority)*/
					$data['Urgency'] = self::convIPTC( $val, $c );
					break;
				case '2#022':
					/* "Identifies objectdata that recurs often and predictably...
					 * Example: Euroweather" */
					$data['FixtureIdentifier'] = self::convIPTC( $val, $c );
					break;
				case '2#026':
					/* Content location code (iso 3166 + some custom things)
					 * ex: TUR (for turkey), XUN (for UN), XSP (outer space)
					 * See wikipedia article on iso 3166 and appendix D of iim std. */
					$data['LocationDestCode'] = self::convIPTC( $val, $c );
					break;
				case '2#027':
					/* Content location name. Full printable name
					 * of location of photo. */
					$data['LocationDest'] = self::convIPTC( $val, $c );
					break;
				case '2#065':
					/* Originating Program.
					 * Combine with Program version (2:70) if present.
					 */
					$software = self::convIPTC( $val, $c );

					if ( count( $software ) !== 1 ) {
						// according to iim standard this cannot have multiple values
						// so if there is more than one, something weird is happening,
						// and we skip it.
						wfDebugLog( 'iptc', 'IPTC: Wrong count on 2:65 Software field' );
						break;
					}

					if ( isset( $parsed['2#070'] ) ) {
						// if a version is set for the software.
						$softwareVersion = self::convIPTC( $parsed['2#070'], $c );
						unset( $parsed['2#070'] );
						$data['Software'] = [ [ $software[0], $softwareVersion[0] ] ];
					} else {
						$data['Software'] = $software;
					}
					break;
				case '2#075':
					/* Object cycle.
					 * a for morning (am), p for evening, b for both */
					$data['ObjectCycle'] = self::convIPTC( $val, $c );
					break;
				case '2#100':
					/* Country/Primary location code.
					 * "Indicates the code of the country/primary location where the
					 * intellectual property of the objectdata was created"
					 * unclear how this differs from 2#026
					 */
					$data['CountryCodeDest'] = self::convIPTC( $val, $c );
					break;
				case '2#103':
					/* original transmission ref.
					 * "A code representing the location of original transmission ac-
					 * cording to practises of the provider."
					 */
					$data['OriginalTransmissionRef'] = self::convIPTC( $val, $c );
					break;
				case '2#118': /*contact*/
					$data['Contact'] = self::convIPTC( $val, $c );
					break;
				case '2#122':
					/* Writer/Editor
					 * "Identification of the name of the person involved in the writing,
					 * editing or correcting the objectdata or caption/abstract."
					 */
					$data['Writer'] = self::convIPTC( $val, $c );
					break;
				case '2#135': /* lang code */
					$data['LanguageCode'] = self::convIPTC( $val, $c );
					break;

				// Start date stuff.
				// It doesn't accept incomplete dates even though they are valid
				// according to spec.
				// Should potentially store timezone as well.
				case '2#055':
					// Date created (not date digitized).
					// Maps to exif DateTimeOriginal
					if ( isset( $parsed['2#060'] ) ) {
						$time = $parsed['2#060'];
					} else {
						$time = [];
					}
					$timestamp = self::timeHelper( $val, $time, $c );
					if ( $timestamp ) {
						$data['DateTimeOriginal'] = $timestamp;
					}
					break;

				case '2#062':
					// Date converted to digital representation.
					// Maps to exif DateTimeDigitized
					if ( isset( $parsed['2#063'] ) ) {
						$time = $parsed['2#063'];
					} else {
						$time = [];
					}
					$timestamp = self::timeHelper( $val, $time, $c );
					if ( $timestamp ) {
						$data['DateTimeDigitized'] = $timestamp;
					}
					break;

				case '2#030':
					// Date released.
					if ( isset( $parsed['2#035'] ) ) {
						$time = $parsed['2#035'];
					} else {
						$time = [];
					}
					$timestamp = self::timeHelper( $val, $time, $c );
					if ( $timestamp ) {
						$data['DateTimeReleased'] = $timestamp;
					}
					break;

				case '2#037':
					// Date expires.
					if ( isset( $parsed['2#038'] ) ) {
						$time = $parsed['2#038'];
					} else {
						$time = [];
					}
					$timestamp = self::timeHelper( $val, $time, $c );
					if ( $timestamp ) {
						$data['DateTimeExpires'] = $timestamp;
					}
					break;

				case '2#000': /* iim version */
					// unlike other tags, this is a 2-byte binary number.
					// technically this is required if there is iptc data
					// but in practise it isn't always there.
					if ( strlen( $val[0] ) == 2 ) {
						// if is just to be paranoid.
						$versionValue = ord( substr( $val[0], 0, 1 ) ) * 256;
						$versionValue += ord( substr( $val[0], 1, 1 ) );
						$data['iimVersion'] = $versionValue;
					}
					break;

				case '2#004':
					// IntellectualGenere.
					// first 4 characters are an id code
					// That we're not really interested in.

					// This prop is weird, since it's
					// allowed to have multiple values
					// in iim 4.1, but not in the XMP
					// stuff. We're going to just
					// extract the first value.
					$con = self::convIPTC( $val, $c );
					if ( strlen( $con[0] ) < 5 ) {
						wfDebugLog( 'iptc', 'IPTC: '
							. '2:04 too short. '
							. 'Ignoring.' );
						break;
					}
					$extracted = substr( $con[0], 4 );
					$data['IntellectualGenre'] = $extracted;
					break;

				case '2#012':
					// Subject News code - this is a compound field
					// at the moment we only extract the subject news
					// code, which is an 8 digit (ascii) number
					// describing the subject matter of the content.
					$codes = self::convIPTC( $val, $c );
					foreach ( $codes as $ic ) {
						$fields = explode( ':', $ic, 3 );

						if ( count( $fields ) < 2 || $fields[0] !== 'IPTC' ) {
							wfDebugLog( 'IPTC', 'IPTC: '
								. 'Invalid 2:12 - ' . $ic );
							break;
						}
						$data['SubjectNewsCode'] = $fields[1];
					}
					break;

				// purposely does not do 2:125, 2:130, 2:131,
				// 2:47, 2:50, 2:45, 2:42, 2:8, 2:3
				// 2:200, 2:201, 2:202
				// or the audio stuff (2:150 to 2:154)

				case '2#070':
				case '2#060':
				case '2#063':
				case '2#085':
				case '2#038':
				case '2#035':
					// ignore. Handled elsewhere.
					break;

				default:
					wfDebugLog( 'iptc', "Unsupported iptc tag: $tag. Value: " . implode( ',', $val ) );
					break;
			}
		}

		return $data;
	}

	/**
	 * Convert an iptc date and time tags into the exif format
	 *
	 * @todo Potentially this should also capture the timezone offset.
	 * @param array $date The date tag
	 * @param array $time The time tag
	 * @param string $c The charset
	 * @return string Date in EXIF format.
	 */
	private static function timeHelper( $date, $time, $c ) {
		if ( count( $date ) === 1 ) {
			// the standard says this should always be 1
			// just double checking.
			list( $date ) = self::convIPTC( $date, $c );
		} else {
			return null;
		}

		if ( count( $time ) === 1 ) {
			list( $time ) = self::convIPTC( $time, $c );
			$dateOnly = false;
		} else {
			$time = '000000+0000'; // placeholder
			$dateOnly = true;
		}

		if ( !( preg_match( '/\d\d\d\d\d\d[-+]\d\d\d\d/', $time )
			&& preg_match( '/\d\d\d\d\d\d\d\d/', $date )
			&& substr( $date, 0, 4 ) !== '0000'
			&& substr( $date, 4, 2 ) !== '00'
			&& substr( $date, 6, 2 ) !== '00'
		) ) {
			// something wrong.
			// Note, this rejects some valid dates according to iptc spec
			// for example: the date 00000400 means the photo was taken in
			// April, but the year and day is unknown. We don't process these
			// types of incomplete dates atm.
			wfDebugLog( 'iptc', "IPTC: invalid time ( $time ) or date ( $date )" );

			return null;
		}

		$unixTS = wfTimestamp( TS_UNIX, $date . substr( $time, 0, 6 ) );
		if ( $unixTS === false ) {
			wfDebugLog( 'iptc', "IPTC: can't convert date to TS_UNIX: $date $time." );

			return null;
		}

		$tz = ( intval( substr( $time, 7, 2 ) ) * 60 * 60 )
			+ ( intval( substr( $time, 9, 2 ) ) * 60 );

		if ( substr( $time, 6, 1 ) === '-' ) {
			$tz = -$tz;
		}

		$finalTimestamp = wfTimestamp( TS_EXIF, $unixTS + $tz );
		if ( $finalTimestamp === false ) {
			wfDebugLog( 'iptc', "IPTC: can't make final timestamp. Date: " . ( $unixTS + $tz ) );

			return null;
		}
		if ( $dateOnly ) {
			// return the date only
			return substr( $finalTimestamp, 0, 10 );
		} else {
			return $finalTimestamp;
		}
	}

	/**
	 * Helper function to convert charset for iptc values.
	 * @param string|array $data The iptc string
	 * @param string $charset The charset
	 *
	 * @return string|array
	 */
	private static function convIPTC( $data, $charset ) {
		if ( is_array( $data ) ) {
			foreach ( $data as &$val ) {
				$val = self::convIPTCHelper( $val, $charset );
			}
		} else {
			$data = self::convIPTCHelper( $data, $charset );
		}

		return $data;
	}

	/**
	 * Helper function of a helper function to convert charset for iptc values.
	 * @param string|array $data The IPTC string
	 * @param string $charset The charset
	 *
	 * @return string
	 */
	private static function convIPTCHelper( $data, $charset ) {
		if ( $charset ) {
			MediaWiki\suppressWarnings();
			$data = iconv( $charset, "UTF-8//IGNORE", $data );
			MediaWiki\restoreWarnings();
			if ( $data === false ) {
				$data = "";
				wfDebugLog( 'iptc', __METHOD__ . " Error converting iptc data charset $charset to utf-8" );
			}
		} else {
			// treat as utf-8 if is valid utf-8. otherwise pretend its windows-1252
			// most of the time if there is no 1:90 tag, it is either ascii, latin1, or utf-8
			$oldData = $data;
			UtfNormal\Validator::quickIsNFCVerify( $data ); // make $data valid utf-8
			if ( $data === $oldData ) {
				return $data; // if validation didn't change $data
			} else {
				return self::convIPTCHelper( $oldData, 'Windows-1252' );
			}
		}

		return trim( $data );
	}

	/**
	 * take the value of 1:90 tag and returns a charset
	 * @param string $tag 1:90 tag.
	 * @return string Charset name or "?"
	 * Warning, this function does not (and is not intended to) detect
	 * all iso 2022 escape codes. In practise, the code for utf-8 is the
	 * only code that seems to have wide use. It does detect that code.
	 */
	static function getCharset( $tag ) {

		// According to iim standard, charset is defined by the tag 1:90.
		// in which there are iso 2022 escape sequences to specify the character set.
		// the iim standard seems to encourage that all necessary escape sequences are
		// in the 1:90 tag, but says it doesn't have to be.

		// This is in need of more testing probably. This is definitely not complete.
		// however reading the docs of some other iptc software, it appears that most iptc software
		// only recognizes utf-8. If 1:90 tag is not present content is
		// usually ascii or iso-8859-1 (and sometimes utf-8), but no guarantee.

		// This also won't work if there are more than one escape sequence in the 1:90 tag
		// or if something is put in the G2, or G3 charsets, etc. It will only reliably recognize utf-8.

		// This is just going through the charsets mentioned in appendix C of the iim standard.

		//  \x1b = ESC.
		switch ( $tag ) {
			case "\x1b%G": // utf-8
			// Also call things that are compatible with utf-8, utf-8 (e.g. ascii)
			case "\x1b(B": // ascii
			case "\x1b(@": // iso-646-IRV (ascii in latest version, $ different in older version)
				$c = 'UTF-8';
				break;
			case "\x1b(A": // like ascii, but british.
				$c = 'ISO646-GB';
				break;
			case "\x1b(C": // some obscure sweedish/finland encoding
				$c = 'ISO-IR-8-1';
				break;
			case "\x1b(D":
				$c = 'ISO-IR-8-2';
				break;
			case "\x1b(E": // some obscure danish/norway encoding
				$c = 'ISO-IR-9-1';
				break;
			case "\x1b(F":
				$c = 'ISO-IR-9-2';
				break;
			case "\x1b(G":
				$c = 'SEN_850200_B'; // aka iso 646-SE; ascii-like
				break;
			case "\x1b(I":
				$c = "ISO646-IT";
				break;
			case "\x1b(L":
				$c = "ISO646-PT";
				break;
			case "\x1b(Z":
				$c = "ISO646-ES";
				break;
			case "\x1b([":
				$c = "GREEK7-OLD";
				break;
			case "\x1b(K":
				$c = "ISO646-DE";
				break;
			case "\x1b(N": // crylic
				$c = "ISO_5427";
				break;
			case "\x1b(`": // iso646-NO
				$c = "NS_4551-1";
				break;
			case "\x1b(f": // iso646-FR
				$c = "NF_Z_62-010";
				break;
			case "\x1b(g":
				$c = "PT2"; // iso646-PT2
				break;
			case "\x1b(h":
				$c = "ES2";
				break;
			case "\x1b(i": // iso646-HU
				$c = "MSZ_7795.3";
				break;
			case "\x1b(w":
				$c = "CSA_Z243.4-1985-1";
				break;
			case "\x1b(x":
				$c = "CSA_Z243.4-1985-2";
				break;
			case "\x1b\$(B":
			case "\x1b\$B":
			case "\x1b&@\x1b\$B":
			case "\x1b&@\x1b\$(B":
				$c = "JIS_C6226-1983";
				break;
			case "\x1b-A": // iso-8859-1. at least for the high code characters.
			case "\x1b(@\x1b-A":
			case "\x1b(B\x1b-A":
				$c = 'ISO-8859-1';
				break;
			case "\x1b-B": // iso-8859-2. at least for the high code characters.
				$c = 'ISO-8859-2';
				break;
			case "\x1b-C": // iso-8859-3. at least for the high code characters.
				$c = 'ISO-8859-3';
				break;
			case "\x1b-D": // iso-8859-4. at least for the high code characters.
				$c = 'ISO-8859-4';
				break;
			case "\x1b-E": // iso-8859-5. at least for the high code characters.
				$c = 'ISO-8859-5';
				break;
			case "\x1b-F": // iso-8859-6. at least for the high code characters.
				$c = 'ISO-8859-6';
				break;
			case "\x1b-G": // iso-8859-7. at least for the high code characters.
				$c = 'ISO-8859-7';
				break;
			case "\x1b-H": // iso-8859-8. at least for the high code characters.
				$c = 'ISO-8859-8';
				break;
			case "\x1b-I": // CSN_369103. at least for the high code characters.
				$c = 'CSN_369103';
				break;
			default:
				wfDebugLog( 'iptc', __METHOD__ . 'Unknown charset in iptc 1:90: ' . bin2hex( $tag ) );
				// at this point just give up and refuse to parse iptc?
				$c = false;
		}
		return $c;
	}
}
