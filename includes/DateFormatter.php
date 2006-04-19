<?php
/**
 * Contain things
 * @todo document
 * @package MediaWiki
 * @subpackage Parser
 */

/** */
define('DF_ALL', -1);
define('DF_NONE', 0);
define('DF_MDY', 1);
define('DF_DMY', 2);
define('DF_YMD', 3);
define('DF_ISO1', 4);
define('DF_LASTPREF', 4);
define('DF_ISO2', 5);
define('DF_YDM', 6);
define('DF_DM', 7);
define('DF_MD', 8);
define('DF_LAST', 8);

/**
 * @todo preferences, OutputPage
 * @package MediaWiki
 * @subpackage Parser
 */
class DateFormatter
{
	var $mSource, $mTarget;
	var $monthNames = '', $rxDM, $rxMD, $rxDMY, $rxYDM, $rxMDY, $rxYMD;

	var $regexes, $pDays, $pMonths, $pYears;
	var $rules, $xMonths;

	/**
	 * @todo document
	 */
	function DateFormatter() {
		global $wgContLang;

		$this->monthNames = $this->getMonthRegex();
		for ( $i=1; $i<=12; $i++ ) {
			$this->xMonths[$wgContLang->lc( $wgContLang->getMonthName( $i ) )] = $i;
			$this->xMonths[$wgContLang->lc( $wgContLang->getMonthAbbreviation( $i ) )] = $i;
		}

		$this->regexTrail = '(?![a-z])/iu';

		# Partial regular expressions
		$this->prxDM = '\[\[(\d{1,2})[ _](' . $this->monthNames . ')]]';
		$this->prxMD = '\[\[(' . $this->monthNames . ')[ _](\d{1,2})]]';
		$this->prxY = '\[\[(\d{1,4}([ _]BC|))]]';
		$this->prxISO1 = '\[\[(-?\d{4})]]-\[\[(\d{2})-(\d{2})]]';
		$this->prxISO2 = '\[\[(-?\d{4})-(\d{2})-(\d{2})]]';

		# Real regular expressions
		$this->regexes[DF_DMY] = "/{$this->prxDM} *,? *{$this->prxY}{$this->regexTrail}";
		$this->regexes[DF_YDM] = "/{$this->prxY} *,? *{$this->prxDM}{$this->regexTrail}";
		$this->regexes[DF_MDY] = "/{$this->prxMD} *,? *{$this->prxY}{$this->regexTrail}";
		$this->regexes[DF_YMD] = "/{$this->prxY} *,? *{$this->prxMD}{$this->regexTrail}";
		$this->regexes[DF_DM] = "/{$this->prxDM}{$this->regexTrail}";
		$this->regexes[DF_MD] = "/{$this->prxMD}{$this->regexTrail}";
		$this->regexes[DF_ISO1] = "/{$this->prxISO1}{$this->regexTrail}";
		$this->regexes[DF_ISO2] = "/{$this->prxISO2}{$this->regexTrail}";

		# Extraction keys
		# See the comments in replace() for the meaning of the letters
		$this->keys[DF_DMY] = 'jFY';
		$this->keys[DF_YDM] = 'Y jF';
		$this->keys[DF_MDY] = 'FjY';
		$this->keys[DF_YMD] = 'Y Fj';
		$this->keys[DF_DM] = 'jF';
		$this->keys[DF_MD] = 'Fj';
		$this->keys[DF_ISO1] = 'ymd'; # y means ISO year
		$this->keys[DF_ISO2] = 'ymd';

		# Target date formats
		$this->targets[DF_DMY] = '[[F j|j F]] [[Y]]';
		$this->targets[DF_YDM] = '[[Y]], [[F j|j F]]';
		$this->targets[DF_MDY] = '[[F j]], [[Y]]';
		$this->targets[DF_YMD] = '[[Y]] [[F j]]';
		$this->targets[DF_DM] = '[[F j|j F]]';
		$this->targets[DF_MD] = '[[F j]]';
		$this->targets[DF_ISO1] = '[[Y|y]]-[[F j|m-d]]';
		$this->targets[DF_ISO2] = '[[y-m-d]]';

		# Rules
		#            pref    source 	  target
		$this->rules[DF_DMY][DF_MD] 	= DF_DM;
		$this->rules[DF_ALL][DF_MD] 	= DF_MD;
		$this->rules[DF_MDY][DF_DM] 	= DF_MD;
		$this->rules[DF_ALL][DF_DM] 	= DF_DM;
		$this->rules[DF_NONE][DF_ISO2] 	= DF_ISO1;
	}

	/**
	 * @static
	 */
	function &getInstance() {
		global $wgDBname, $wgMemc;
		static $dateFormatter = false;
		if ( !$dateFormatter ) {
			$dateFormatter = $wgMemc->get( "$wgDBname:dateformatter" );
			if ( !$dateFormatter ) {
				$dateFormatter = new DateFormatter;
				$wgMemc->set( "$wgDBname:dateformatter", $dateFormatter, 3600 );
			}
		}
		return $dateFormatter;
	}

	/**
	 * @param $preference
	 * @param $text
	 */
	function reformat( $preference, $text ) {
		if ($preference == 'ISO 8601') $preference = 4; # The ISO 8601 option used to be 4
		for ( $i=1; $i<=DF_LAST; $i++ ) {
			$this->mSource = $i;
			if ( @$this->rules[$preference][$i] ) {
				# Specific rules
				$this->mTarget = $this->rules[$preference][$i];
			} elseif ( @$this->rules[DF_ALL][$i] ) {
				# General rules
				$this->mTarget = $this->rules[DF_ALL][$i];
			} elseif ( $preference ) {
				# User preference
				$this->mTarget = $preference;
			} else {
				# Default
				$this->mTarget = $i;
			}
			$text = preg_replace_callback( $this->regexes[$i], 'wfMainDateReplace', $text );
		}
		return $text;
	}

	/**
	 * @param $matches
	 */
	function replace( $matches ) {
		# Extract information from $matches
		$bits = array();
		$key = $this->keys[$this->mSource];
		for ( $p=0; $p < strlen($key); $p++ ) {
			if ( $key{$p} != ' ' ) {
				$bits[$key{$p}] = $matches[$p+1];
			}
		}

		$format = $this->targets[$this->mTarget];

		# Construct new date
		$text = '';
		$fail = false;

		for ( $p=0; $p < strlen( $format ); $p++ ) {
			$char = $format{$p};
			switch ( $char ) {
				case 'd': # ISO day of month
					if ( !isset($bits['d']) ) {
						$text .= sprintf( '%02d', $bits['j'] );
					} else {
						$text .= $bits['d'];
					}
					break;
				case 'm': # ISO month
					if ( !isset($bits['m']) ) {
						$m = $this->makeIsoMonth( $bits['F'] );
						if ( !$m || $m == '00' ) {
							$fail = true;
						} else {
							$text .= $m;
						}
					} else {
						$text .= $bits['m'];
					}
					break;
				case 'y': # ISO year
					if ( !isset( $bits['y'] ) ) {
						$text .= $this->makeIsoYear( $bits['Y'] );
					} else {
						$text .= $bits['y'];
					}
					break;
				case 'j': # ordinary day of month
					if ( !isset($bits['j']) ) {
						$text .= intval( $bits['d'] );
					} else {
						$text .= $bits['j'];
					}
					break;
				case 'F': # long month
					if ( !isset( $bits['F'] ) ) {
						$m = intval($bits['m']);
						if ( $m > 12 || $m < 1 ) {
							$fail = true;
						} else {
							global $wgContLang;
							$text .= $wgContLang->getMonthName( $m );
						}
					} else {
						$text .= ucfirst( $bits['F'] );
					}
					break;
				case 'Y': # ordinary (optional BC) year
					if ( !isset( $bits['Y'] ) ) {
						$text .= $this->makeNormalYear( $bits['y'] );
					} else {
						$text .= $bits['Y'];
					}
					break;
				default:
					$text .= $char;
			}
		}
		if ( $fail ) {
			$text = $matches[0];
		}
		return $text;
	}

	/**
	 * @todo document
	 */
	function getMonthRegex() {
		global $wgContLang;
		$names = array();
		for( $i = 1; $i <= 12; $i++ ) {
			$names[] = $wgContLang->getMonthName( $i );
			$names[] = $wgContLang->getMonthAbbreviation( $i );
		}
		return implode( '|', $names );
	}

	/**
	 * Makes an ISO month, e.g. 02, from a month name
	 * @param $monthName String: month name
	 * @return string ISO month name
	 */
	function makeIsoMonth( $monthName ) {
		global $wgContLang;

		$n = $this->xMonths[$wgContLang->lc( $monthName )];
		return sprintf( '%02d', $n );
	}

	/**
	 * @todo document
	 * @param $year String: Year name
	 * @return string ISO year name
	 */
	function makeIsoYear( $year ) {
		# Assumes the year is in a nice format, as enforced by the regex
		if ( substr( $year, -2 ) == 'BC' ) {
			$num = intval(substr( $year, 0, -3 )) - 1;
			# PHP bug note: sprintf( "%04d", -1 ) fails poorly
			$text = sprintf( '-%04d', $num );

		} else {
			$text = sprintf( '%04d', $year );
		}
		return $text;
	}

	/**
	 * @todo document
	 */
	function makeNormalYear( $iso ) {
		if ( $iso{0} == '-' ) {
			$text = (intval( substr( $iso, 1 ) ) + 1) . ' BC';
		} else {
			$text = intval( $iso );
		}
		return $text;
	}
}

/**
 * @todo document
 */
function wfMainDateReplace( $matches ) {
	$df =& DateFormatter::getInstance();
	return $df->replace( $matches );
}

?>
