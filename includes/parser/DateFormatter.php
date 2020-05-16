<?php
/**
 * Date formatter
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
 * @ingroup Parser
 */

use MediaWiki\MediaWikiServices;

/**
 * Date formatter. Recognises dates and formats them according to a specified preference.
 *
 * This class was originally introduced to detect and transform dates in free text. It is now
 * only used by the {{#dateformat}} parser function. This is a very rudimentary date formatter;
 * Language::sprintfDate() has many more features and is the correct choice for most new code.
 * The main advantage of this date formatter is that it is able to format incomplete dates with an
 * unspecified year.
 *
 * @ingroup Parser
 */
class DateFormatter {
	/** @var string[] Date format regexes indexed the class constants */
	private $regexes;

	/**
	 * @var int[][] Array of special rules. The first key is the preference ID
	 * (one of the class constants), the second key is the detected source
	 * format, and the value is the ID of the target format that will be used
	 * in that case.
	 */
	private $rules = [];

	/**
	 * @var int[] Month numbers by lowercase name
	 */
	private $xMonths = [];

	/**
	 * @var string[] Month names by number
	 */
	private $monthNames = [];

	/**
	 * @var int[] A map of descriptive preference text to internal format ID
	 */
	private $preferenceIDs;

	/** @var string[] Format strings similar to those used by date(), indexed by ID */
	private $targetFormats;

	/** Used as a preference ID for rules that apply regardless of preference */
	private const ALL = -1;

	/** No preference: the date may be left in the same format as the input */
	private const NONE = 0;

	/** e.g. January 15, 2001 */
	private const MDY = 1;

	/** e.g. 15 January 2001 */
	private const DMY = 2;

	/** e.g. 2001 January 15 */
	private const YMD = 3;

	/** e.g. 2001-01-15 */
	private const ISO = 4;

	/** The highest ID that is a valid user preference */
	private const LASTPREF = 4;

	/** e.g. 2001, 15 January */
	private const YDM = 5;

	/** e.g. 15 January */
	private const DM = 6;

	/** e.g. January 15 */
	private const MD = 7;

	/** The highest ID that is a valid target format */
	private const LAST = 7;

	/**
	 * @param Language $lang In which language to format the date
	 */
	public function __construct( Language $lang ) {
		$monthRegexParts = [];
		for ( $i = 1; $i <= 12; $i++ ) {
			$monthName = $lang->getMonthName( $i );
			$monthAbbrev = $lang->getMonthAbbreviation( $i );
			$this->monthNames[$i] = $monthName;
			$monthRegexParts[] = preg_quote( $monthName, '/' );
			$monthRegexParts[] = preg_quote( $monthAbbrev, '/' );
			$this->xMonths[mb_strtolower( $monthName )] = $i;
			$this->xMonths[mb_strtolower( $monthAbbrev )] = $i;
		}

		// Partial regular expressions
		$monthNames = implode( '|', $monthRegexParts );
		$dm = "(?<day>\d{1,2})[ _](?<monthName>{$monthNames})";
		$md = "(?<monthName>{$monthNames})[ _](?<day>\d{1,2})";
		$y = '(?<year>\d{1,4}([ _]BC|))';
		$iso = '(?<isoYear>-?\d{4})-(?<isoMonth>\d{2})-(?<isoDay>\d{2})';

		$this->regexes = [
			self::DMY => "/^{$dm}(?: *, *| +){$y}$/iu",
			self::YDM => "/^{$y}(?: *, *| +){$dm}$/iu",
			self::MDY => "/^{$md}(?: *, *| +){$y}$/iu",
			self::YMD => "/^{$y}(?: *, *| +){$md}$/iu",
			self::DM => "/^{$dm}$/iu",
			self::MD => "/^{$md}$/iu",
			self::ISO => "/^{$iso}$/iu",
		];

		// Target date formats
		$this->targetFormats = [
			self::DMY => 'j F Y',
			self::YDM => 'Y, j F',
			self::MDY => 'F j, Y',
			self::YMD => 'Y F j',
			self::DM => 'j F',
			self::MD => 'F j',
			self::ISO => 'y-m-d',
		];

		// Rules
		//           pref       source      target
		$this->rules[self::DMY][self::MD] = self::DM;
		$this->rules[self::ALL][self::MD] = self::MD;
		$this->rules[self::MDY][self::DM] = self::MD;
		$this->rules[self::ALL][self::DM] = self::DM;
		$this->rules[self::NONE][self::ISO] = self::ISO;

		$this->preferenceIDs = [
			'default' => self::NONE,
			'dmy' => self::DMY,
			'mdy' => self::MDY,
			'ymd' => self::YMD,
			'ISO 8601' => self::ISO,
		];
	}

	/**
	 * Get a DateFormatter object
	 *
	 * @deprecated since 1.33 use MediaWikiServices::getDateFormatterFactory()
	 *
	 * @param Language|null $lang In which language to format the date
	 *     Defaults to the site content language
	 * @return DateFormatter
	 */
	public static function getInstance( Language $lang = null ) {
		$lang = $lang ?? MediaWikiServices::getInstance()->getContentLanguage();
		return MediaWikiServices::getInstance()->getDateFormatterFactory()->get( $lang );
	}

	/**
	 * @param string $preference User preference, must be one of "default",
	 *   "dmy", "mdy", "ymd" or "ISO 8601".
	 * @param string $text Text to reformat
	 * @param array $options Ignored. Since 1.33, 'match-whole' is implied, and
	 *  'linked' has been removed.
	 *
	 * @return string
	 */
	public function reformat( $preference, $text, $options = [] ) {
		if ( isset( $this->preferenceIDs[$preference] ) ) {
			$preference = $this->preferenceIDs[$preference];
		} else {
			$preference = self::NONE;
		}
		for ( $source = 1; $source <= self::LAST; $source++ ) {
			if ( isset( $this->rules[$preference][$source] ) ) {
				# Specific rules
				$target = $this->rules[$preference][$source];
			} elseif ( isset( $this->rules[self::ALL][$source] ) ) {
				# General rules
				$target = $this->rules[self::ALL][$source];
			} elseif ( $preference ) {
				# User preference
				$target = $preference;
			} else {
				# Default
				$target = $source;
			}
			$regex = $this->regexes[$source];

			$text = preg_replace_callback( $regex,
				function ( $match ) use ( $target ) {
					$format = $this->targetFormats[$target];

					$text = '';

					// Pre-generate y/Y stuff because we need the year for the <span> title.
					if ( !isset( $match['isoYear'] ) && isset( $match['year'] ) ) {
						$match['isoYear'] = $this->makeIsoYear( $match['year'] );
					}
					if ( !isset( $match['year'] ) && isset( $match['isoYear'] ) ) {
						$match['year'] = $this->makeNormalYear( $match['isoYear'] );
					}

					if ( !isset( $match['isoMonth'] ) ) {
						$m = $this->makeIsoMonth( $match['monthName'] );
						if ( $m === false ) {
							// Fail
							return $match[0];
						} else {
							$match['isoMonth'] = $m;
						}
					}

					if ( !isset( $match['isoDay'] ) ) {
						$match['isoDay'] = sprintf( '%02d', $match['day'] );
					}

					$formatLength = strlen( $format );
					for ( $p = 0; $p < $formatLength; $p++ ) {
						$char = $format[$p];
						switch ( $char ) {
							case 'd': // ISO day of month
								$text .= $match['isoDay'];
								break;
							case 'm': // ISO month
								$text .= $match['isoMonth'];
								break;
							case 'y': // ISO year
								$text .= $match['isoYear'];
								break;
							case 'j': // ordinary day of month
								if ( !isset( $match['day'] ) ) {
									$text .= intval( $match['isoDay'] );
								} else {
									$text .= $match['day'];
								}
								break;
							case 'F': // long month
								$m = intval( $match['isoMonth'] );
								if ( $m > 12 || $m < 1 ) {
									// Fail
									return $match[0];
								} else {
									$text .= $this->monthNames[$m];
								}
								break;
							case 'Y': // ordinary (optional BC) year
								$text .= $match['year'];
								break;
							default:
								$text .= $char;
						}
					}

					$isoBits = [];
					if ( isset( $match['isoYear'] ) ) {
						$isoBits[] = $match['isoYear'];
					}
					$isoBits[] = $match['isoMonth'];
					$isoBits[] = $match['isoDay'];
					$isoDate = implode( '-', $isoBits );

					// Output is not strictly HTML (it's wikitext), but <span> is whitelisted.
					$text = Html::rawElement( 'span',
						[ 'class' => 'mw-formatted-date', 'title' => $isoDate ], $text );

					return $text;
				}, $text
			);
		}
		return $text;
	}

	/**
	 * Makes an ISO month, e.g. 02, from a month name
	 * @param string $monthName Month name
	 * @return string|false ISO month name, or false if the input was invalid
	 */
	private function makeIsoMonth( $monthName ) {
		$isoMonth = $this->xMonths[mb_strtolower( $monthName )] ?? false;
		if ( $isoMonth === false ) {
			return false;
		}
		return sprintf( '%02d', $isoMonth );
	}

	/**
	 * Make an ISO year from a year name, for instance: '-1199' from '1200 BC'
	 * @param string $year Year name
	 * @return string ISO year name
	 */
	private function makeIsoYear( $year ) {
		// Assumes the year is in a nice format, as enforced by the regex
		if ( substr( $year, -2 ) == 'BC' ) {
			$num = intval( substr( $year, 0, -3 ) ) - 1;
			// PHP bug note: sprintf( "%04d", -1 ) fails poorly
			$text = sprintf( '-%04d', $num );
		} else {
			$text = sprintf( '%04d', $year );
		}
		return $text;
	}

	/**
	 * Make a year from an ISO year, for instance: '400 BC' from '-0399'.
	 * @param string $iso ISO year
	 * @return int|string int representing year number in case of AD dates, or string containing
	 *   year number and 'BC' at the end otherwise.
	 */
	private function makeNormalYear( $iso ) {
		if ( $iso[0] == '-' ) {
			$text = ( intval( substr( $iso, 1 ) ) + 1 ) . ' BC';
		} else {
			$text = intval( $iso );
		}
		return $text;
	}
}
