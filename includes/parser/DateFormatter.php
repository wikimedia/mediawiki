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
 * Date formatter, recognises dates in plain text and formats them according to user preferences.
 * @todo preferences, OutputPage
 * @ingroup Parser
 */
class DateFormatter {
	private $mSource, $mTarget;
	private $monthNames = '';

	private $regexes;
	private $rules, $xMonths, $preferences;

	private $lang, $mLinked;

	/** @var string[] */
	private $keys;

	/** @var string[] */
	private $targets;

	const ALL = -1;
	const NONE = 0;
	const MDY = 1;
	const DMY = 2;
	const YMD = 3;
	const ISO1 = 4;
	const LASTPREF = 4;
	const ISO2 = 5;
	const YDM = 6;
	const DM = 7;
	const MD = 8;
	const LAST = 8;

	/**
	 * @param Language $lang In which language to format the date
	 */
	public function __construct( Language $lang ) {
		$this->lang = $lang;

		$this->monthNames = $this->getMonthRegex();
		for ( $i = 1; $i <= 12; $i++ ) {
			$this->xMonths[$this->lang->lc( $this->lang->getMonthName( $i ) )] = $i;
			$this->xMonths[$this->lang->lc( $this->lang->getMonthAbbreviation( $i ) )] = $i;
		}

		$this->regexTrail = '(?![a-z])/iu';

		# Partial regular expressions
		$this->prxDM = '\[\[(\d{1,2})[ _](' . $this->monthNames . ')\]\]';
		$this->prxMD = '\[\[(' . $this->monthNames . ')[ _](\d{1,2})\]\]';
		$this->prxY = '\[\[(\d{1,4}([ _]BC|))\]\]';
		$this->prxISO1 = '\[\[(-?\d{4})]]-\[\[(\d{2})-(\d{2})\]\]';
		$this->prxISO2 = '\[\[(-?\d{4})-(\d{2})-(\d{2})\]\]';

		# Real regular expressions
		$this->regexes[self::DMY] = "/{$this->prxDM}(?: *, *| +){$this->prxY}{$this->regexTrail}";
		$this->regexes[self::YDM] = "/{$this->prxY}(?: *, *| +){$this->prxDM}{$this->regexTrail}";
		$this->regexes[self::MDY] = "/{$this->prxMD}(?: *, *| +){$this->prxY}{$this->regexTrail}";
		$this->regexes[self::YMD] = "/{$this->prxY}(?: *, *| +){$this->prxMD}{$this->regexTrail}";
		$this->regexes[self::DM] = "/{$this->prxDM}{$this->regexTrail}";
		$this->regexes[self::MD] = "/{$this->prxMD}{$this->regexTrail}";
		$this->regexes[self::ISO1] = "/{$this->prxISO1}{$this->regexTrail}";
		$this->regexes[self::ISO2] = "/{$this->prxISO2}{$this->regexTrail}";

		# Extraction keys
		# See the comments in replace() for the meaning of the letters
		$this->keys[self::DMY] = 'jFY';
		$this->keys[self::YDM] = 'Y jF';
		$this->keys[self::MDY] = 'FjY';
		$this->keys[self::YMD] = 'Y Fj';
		$this->keys[self::DM] = 'jF';
		$this->keys[self::MD] = 'Fj';
		$this->keys[self::ISO1] = 'ymd'; # y means ISO year
		$this->keys[self::ISO2] = 'ymd';

		# Target date formats
		$this->targets[self::DMY] = '[[F j|j F]] [[Y]]';
		$this->targets[self::YDM] = '[[Y]], [[F j|j F]]';
		$this->targets[self::MDY] = '[[F j]], [[Y]]';
		$this->targets[self::YMD] = '[[Y]] [[F j]]';
		$this->targets[self::DM] = '[[F j|j F]]';
		$this->targets[self::MD] = '[[F j]]';
		$this->targets[self::ISO1] = '[[Y|y]]-[[F j|m-d]]';
		$this->targets[self::ISO2] = '[[y-m-d]]';

		# Rules
		#            pref       source      target
		$this->rules[self::DMY][self::MD] = self::DM;
		$this->rules[self::ALL][self::MD] = self::MD;
		$this->rules[self::MDY][self::DM] = self::MD;
		$this->rules[self::ALL][self::DM] = self::DM;
		$this->rules[self::NONE][self::ISO2] = self::ISO1;

		$this->preferences = [
			'default' => self::NONE,
			'dmy' => self::DMY,
			'mdy' => self::MDY,
			'ymd' => self::YMD,
			'ISO 8601' => self::ISO1,
		];
	}

	/**
	 * Get a DateFormatter object
	 *
	 * @param Language|null $lang In which language to format the date
	 *     Defaults to the site content language
	 * @return DateFormatter
	 */
	public static function getInstance( Language $lang = null ) {
		global $wgMainCacheType;

		$lang = $lang ?? MediaWikiServices::getInstance()->getContentLanguage();
		$cache = ObjectCache::getLocalServerInstance( $wgMainCacheType );

		static $dateFormatter = false;
		if ( !$dateFormatter ) {
			$dateFormatter = $cache->getWithSetCallback(
				$cache->makeKey( 'dateformatter', $lang->getCode() ),
				$cache::TTL_HOUR,
				function () use ( $lang ) {
					return new DateFormatter( $lang );
				}
			);
		}

		return $dateFormatter;
	}

	/**
	 * @param string $preference User preference
	 * @param string $text Text to reformat
	 * @param array $options Array can contain 'linked' and/or 'match-whole'
	 *
	 * @return string
	 */
	public function reformat( $preference, $text, $options = [ 'linked' ] ) {
		$linked = in_array( 'linked', $options );
		$match_whole = in_array( 'match-whole', $options );

		if ( isset( $this->preferences[$preference] ) ) {
			$preference = $this->preferences[$preference];
		} else {
			$preference = self::NONE;
		}
		for ( $i = 1; $i <= self::LAST; $i++ ) {
			$this->mSource = $i;
			if ( isset( $this->rules[$preference][$i] ) ) {
				# Specific rules
				$this->mTarget = $this->rules[$preference][$i];
			} elseif ( isset( $this->rules[self::ALL][$i] ) ) {
				# General rules
				$this->mTarget = $this->rules[self::ALL][$i];
			} elseif ( $preference ) {
				# User preference
				$this->mTarget = $preference;
			} else {
				# Default
				$this->mTarget = $i;
			}
			$regex = $this->regexes[$i];

			// Horrible hack
			if ( !$linked ) {
				$regex = str_replace( [ '\[\[', '\]\]' ], '', $regex );
			}

			if ( $match_whole ) {
				// Let's hope this works
				$regex = preg_replace( '!^/!', '/^', $regex );
				$regex = str_replace( $this->regexTrail,
					'$' . $this->regexTrail, $regex );
			}

			// Another horrible hack
			$this->mLinked = $linked;
			$text = preg_replace_callback( $regex, [ $this, 'replace' ], $text );
			unset( $this->mLinked );
		}
		return $text;
	}

	/**
	 * Regexp replacement callback
	 *
	 * @param array $matches
	 * @return string
	 */
	private function replace( $matches ) {
		# Extract information from $matches
		$linked = $this->mLinked ?? true;

		$bits = [];
		$key = $this->keys[$this->mSource];
		$keyLength = strlen( $key );
		for ( $p = 0; $p < $keyLength; $p++ ) {
			if ( $key[$p] != ' ' ) {
				$bits[$key[$p]] = $matches[$p + 1];
			}
		}

		return $this->formatDate( $bits, $matches[0], $linked );
	}

	/**
	 * @param array $bits
	 * @param string $orig Original input string, to be returned
	 *  on formatting failure.
	 * @param bool $link
	 * @return string
	 */
	private function formatDate( $bits, $orig, $link = true ) {
		$format = $this->targets[$this->mTarget];

		if ( !$link ) {
			// strip piped links
			$format = preg_replace( '/\[\[[^|]+\|([^\]]+)\]\]/', '$1', $format );
			// strip remaining links
			$format = str_replace( [ '[[', ']]' ], '', $format );
		}

		# Construct new date
		$text = '';
		$fail = false;

		// Pre-generate y/Y stuff because we need the year for the <span> title.
		if ( !isset( $bits['y'] ) && isset( $bits['Y'] ) ) {
			$bits['y'] = $this->makeIsoYear( $bits['Y'] );
		}
		if ( !isset( $bits['Y'] ) && isset( $bits['y'] ) ) {
			$bits['Y'] = $this->makeNormalYear( $bits['y'] );
		}

		if ( !isset( $bits['m'] ) ) {
			$m = $this->makeIsoMonth( $bits['F'] );
			if ( !$m || $m == '00' ) {
				$fail = true;
			} else {
				$bits['m'] = $m;
			}
		}

		if ( !isset( $bits['d'] ) ) {
			$bits['d'] = sprintf( '%02d', $bits['j'] );
		}

		$formatLength = strlen( $format );
		for ( $p = 0; $p < $formatLength; $p++ ) {
			$char = $format[$p];
			switch ( $char ) {
				case 'd': # ISO day of month
					$text .= $bits['d'];
					break;
				case 'm': # ISO month
					$text .= $bits['m'];
					break;
				case 'y': # ISO year
					$text .= $bits['y'];
					break;
				case 'j': # ordinary day of month
					if ( !isset( $bits['j'] ) ) {
						$text .= intval( $bits['d'] );
					} else {
						$text .= $bits['j'];
					}
					break;
				case 'F': # long month
					if ( !isset( $bits['F'] ) ) {
						$m = intval( $bits['m'] );
						if ( $m > 12 || $m < 1 ) {
							$fail = true;
						} else {
							$text .= $this->lang->getMonthName( $m );
						}
					} else {
						$text .= ucfirst( $bits['F'] );
					}
					break;
				case 'Y': # ordinary (optional BC) year
					$text .= $bits['Y'];
					break;
				default:
					$text .= $char;
			}
		}
		if ( $fail ) {
			// This occurs when parsing a date with day or month outside the bounds
			// of possibilities.
			$text = $orig;
		}

		$isoBits = [];
		if ( isset( $bits['y'] ) ) {
			$isoBits[] = $bits['y'];
		}
		$isoBits[] = $bits['m'];
		$isoBits[] = $bits['d'];
		$isoDate = implode( '-', $isoBits );

		// Output is not strictly HTML (it's wikitext), but <span> is whitelisted.
		$text = Html::rawElement( 'span',
					[ 'class' => 'mw-formatted-date', 'title' => $isoDate ], $text );

		return $text;
	}

	/**
	 * Return a regex that can be used to find month names in string
	 * @return string regex to find the months with
	 */
	private function getMonthRegex() {
		$names = [];
		for ( $i = 1; $i <= 12; $i++ ) {
			$names[] = $this->lang->getMonthName( $i );
			$names[] = $this->lang->getMonthAbbreviation( $i );
		}
		return implode( '|', $names );
	}

	/**
	 * Makes an ISO month, e.g. 02, from a month name
	 * @param string $monthName Month name
	 * @return string ISO month name
	 */
	private function makeIsoMonth( $monthName ) {
		$n = $this->xMonths[$this->lang->lc( $monthName )];
		return sprintf( '%02d', $n );
	}

	/**
	 * Make an ISO year from a year name, for instance: '-1199' from '1200 BC'
	 * @param string $year Year name
	 * @return string ISO year name
	 */
	private function makeIsoYear( $year ) {
		# Assumes the year is in a nice format, as enforced by the regex
		if ( substr( $year, -2 ) == 'BC' ) {
			$num = intval( substr( $year, 0, -3 ) ) - 1;
			# PHP bug note: sprintf( "%04d", -1 ) fails poorly
			$text = sprintf( '-%04d', $num );

		} else {
			$text = sprintf( '%04d', $year );
		}
		return $text;
	}

	/**
	 * Make a year one from an ISO year, for instance: '400 BC' from '-0399'.
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
