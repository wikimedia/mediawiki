<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Unicode normalization routines for working with UTF-8 strings.
 * Currently assumes that input strings are valid UTF-8!
 *
 * Not as fast as I'd like, but should be usable for most purposes.
 * UtfNormal::toNFC() will bail early if given ASCII text or text
 * it can quickly deterimine is already normalized.
 *
 * All functions can be called static.
 *
 * See description of forms at http://www.unicode.org/reports/tr15/
 *
 * @package UtfNormal
 */

/** */
require_once 'UtfNormalUtil.php';

global $utfCombiningClass, $utfCanonicalComp, $utfCanonicalDecomp;
$utfCombiningClass = NULL;
$utfCanonicalComp = NULL;
$utfCanonicalDecomp = NULL;

# Load compatibility decompositions on demand if they are needed.
global $utfCompatibilityDecomp;
$utfCompatibilityDecomp = NULL;

define( 'UNICODE_HANGUL_FIRST', 0xac00 );
define( 'UNICODE_HANGUL_LAST',  0xd7a3 );

define( 'UNICODE_HANGUL_LBASE', 0x1100 );
define( 'UNICODE_HANGUL_VBASE', 0x1161 );
define( 'UNICODE_HANGUL_TBASE', 0x11a7 );

define( 'UNICODE_HANGUL_LCOUNT', 19 );
define( 'UNICODE_HANGUL_VCOUNT', 21 );
define( 'UNICODE_HANGUL_TCOUNT', 28 );
define( 'UNICODE_HANGUL_NCOUNT', UNICODE_HANGUL_VCOUNT * UNICODE_HANGUL_TCOUNT );

define( 'UNICODE_HANGUL_LEND', UNICODE_HANGUL_LBASE + UNICODE_HANGUL_LCOUNT - 1 );
define( 'UNICODE_HANGUL_VEND', UNICODE_HANGUL_VBASE + UNICODE_HANGUL_VCOUNT - 1 );
define( 'UNICODE_HANGUL_TEND', UNICODE_HANGUL_TBASE + UNICODE_HANGUL_TCOUNT - 1 );

define( 'UNICODE_SURROGATE_FIRST', 0xd800 );
define( 'UNICODE_SURROGATE_LAST', 0xdfff );
define( 'UNICODE_MAX', 0x10ffff );
define( 'UNICODE_REPLACEMENT', 0xfffd );


define( 'UTF8_HANGUL_FIRST', codepointToUtf8( UNICODE_HANGUL_FIRST ) );
define( 'UTF8_HANGUL_LAST', codepointToUtf8( UNICODE_HANGUL_LAST ) );

define( 'UTF8_HANGUL_LBASE', codepointToUtf8( UNICODE_HANGUL_LBASE ) );
define( 'UTF8_HANGUL_VBASE', codepointToUtf8( UNICODE_HANGUL_VBASE ) );
define( 'UTF8_HANGUL_TBASE', codepointToUtf8( UNICODE_HANGUL_TBASE ) );

define( 'UTF8_HANGUL_LEND', codepointToUtf8( UNICODE_HANGUL_LEND ) );
define( 'UTF8_HANGUL_VEND', codepointToUtf8( UNICODE_HANGUL_VEND ) );
define( 'UTF8_HANGUL_TEND', codepointToUtf8( UNICODE_HANGUL_TEND ) );

define( 'UTF8_SURROGATE_FIRST', codepointToUtf8( UNICODE_SURROGATE_FIRST ) );
define( 'UTF8_SURROGATE_LAST', codepointToUtf8( UNICODE_SURROGATE_LAST ) );
define( 'UTF8_MAX', codepointToUtf8( UNICODE_MAX ) );
define( 'UTF8_REPLACEMENT', codepointToUtf8( UNICODE_REPLACEMENT ) );
#define( 'UTF8_REPLACEMENT', '!' );

define( 'UTF8_OVERLONG_A', "\xc1\xbf" );
define( 'UTF8_OVERLONG_B', "\xe0\x9f\xbf" );
define( 'UTF8_OVERLONG_C', "\xf0\x8f\xbf\xbf" );

# These two ranges are illegal
define( 'UTF8_FDD0', codepointToUtf8( 0xfdd0 ) );
define( 'UTF8_FDEF', codepointToUtf8( 0xfdef ) );
define( 'UTF8_FFFE', codepointToUtf8( 0xfffe ) );
define( 'UTF8_FFFF', codepointToUtf8( 0xffff ) );

define( 'UTF8_HEAD', false );
define( 'UTF8_TAIL', true );


/**
 * For using the ICU wrapper
 */
define( 'UNORM_NONE', 1 );
define( 'UNORM_NFD',  2 );
define( 'UNORM_NFKD', 3 );
define( 'UNORM_NFC',  4 );
define( 'UNORM_DEFAULT', UNORM_NFC );
define( 'UNORM_NFKC', 5 );
define( 'UNORM_FCD',  6 );

define( 'NORMALIZE_ICU', function_exists( 'utf8_normalize' ) );

/**
 *
 * @package MediaWiki
 */
class UtfNormal {
	/**
	 * The ultimate convenience function! Clean up invalid UTF-8 sequences,
	 * and convert to normal form C, canonical composition.
	 *
	 * Fast return for pure ASCII strings; some lesser optimizations for
	 * strings containing only known-good characters. Not as fast as toNFC().
	 *
	 * @param string $string a UTF-8 string
	 * @return string a clean, shiny, normalized UTF-8 string
	 */
	function cleanUp( $string ) {
		if( UtfNormal::quickIsNFCVerify( $string ) )
			return $string;
		else
			return UtfNormal::NFC( $string );
	}

	/**
	 * Convert a UTF-8 string to normal form C, canonical composition.
	 * Fast return for pure ASCII strings; some lesser optimizations for
	 * strings containing only known-good characters.
	 *
	 * @param string $string a valid UTF-8 string. Input is not validated.
	 * @return string a UTF-8 string in normal form C
	 */
	function toNFC( $string ) {
		if( NORMALIZE_ICU )
			return utf8_normalize( $string, UNORM_NFC );
		elseif( UtfNormal::quickIsNFC( $string ) )
			return $string;
		else
			return UtfNormal::NFC( $string );
	}
	
	/**
	 * Convert a UTF-8 string to normal form D, canonical decomposition.
	 * Fast return for pure ASCII strings.
	 *
	 * @param string $string a valid UTF-8 string. Input is not validated.
	 * @return string a UTF-8 string in normal form D
	 */
	function toNFD( $string ) {
		if( NORMALIZE_ICU )
			return utf8_normalize( $string, UNORM_NFD );
		elseif( preg_match( '/[\x80-\xff]/', $string ) )
			return UtfNormal::NFD( $string );
		else
			return $string;
	}
	
	/**
	 * Convert a UTF-8 string to normal form KC, compatibility composition.
	 * This may cause irreversible information loss, use judiciously.
	 * Fast return for pure ASCII strings.
	 *
	 * @param string $string a valid UTF-8 string. Input is not validated.
	 * @return string a UTF-8 string in normal form KC
	 */
	function toNFKC( $string ) {
		if( NORMALIZE_ICU )
			return utf8_normalize( $string, UNORM_NFKC );
		elseif( preg_match( '/[\x80-\xff]/', $string ) )
			return UtfNormal::NFKC( $string );
		else
			return $string;
	}
	
	/**
	 * Convert a UTF-8 string to normal form KD, compatibility decomposition.
	 * This may cause irreversible information loss, use judiciously.
	 * Fast return for pure ASCII strings.
	 *
	 * @param string $string a valid UTF-8 string. Input is not validated.
	 * @return string a UTF-8 string in normal form KD
	 */
	function toNFKD( $string ) {
		if( NORMALIZE_ICU )
			return utf8_normalize( $string, UNORM_NFKD );
		elseif( preg_match( '/[\x80-\xff]/', $string ) )
			return UtfNormal::NFKD( $string );
		else
			return $string;
	}
	
	/**
	 * Load the basic composition data if necessary
	 * @access private
	 */
	function loadData() {
		global $utfCombiningClass, $utfCanonicalComp, $utfCanonicalDecomp;
		if( !isset( $utfCombiningClass ) ) {
			require_once( 'UtfNormalData.inc' );
		}
	}
	
	/**
	 * Returns true if the string is _definitely_ in NFC.
	 * Returns false if not or uncertain.
	 * @param string $string a valid UTF-8 string. Input is not validated.
	 * @return bool
	 */
	function quickIsNFC( $string ) {
		# ASCII is always valid NFC!
		# If it's pure ASCII, let it through.
		if( !preg_match( '/[\x80-\xff]/', $string ) ) return true;
		
		UtfNormal::loadData();
		global $utfCheckNFC, $utfCombiningClass;
		$len = strlen( $string );
		for( $i = 0; $i < $len; $i++ ) {
			$c = $string{$i};
			$n = ord( $c );
			if( $n < 0x80 ) {
				continue;
			} elseif( $n >= 0xf0 ) {
				$c = substr( $string, $i, 4 );
				$i += 3;
			} elseif( $n >= 0xe0 ) {
				$c = substr( $string, $i, 3 );
				$i += 2;
			} elseif( $n >= 0xc0 ) {
				$c = substr( $string, $i, 2 );
				$i++;
			}
			if( isset( $utfCheckNFC[$c] ) ) {
				# If it's NO or MAYBE, bail and do the slow check.
				return false;
			}
			if( isset( $utfCombiningClass[$c] ) ) {
				# Combining character? We might have to do sorting, at least.
				return false;
			}
		}
		return true;
	}

	/**
	 * Returns true if the string is _definitely_ in NFC.
	 * Returns false if not or uncertain.
	 * @param string $string a UTF-8 string, altered on output to be valid UTF-8 safe for XML.
	 */
	function quickIsNFCVerify( &$string ) {
		# Screen out some characters that eg won't be allowed in XML
		preg_replace( '/[\x00-\x08\x0b\x0c\x0e-\x1f]/', UTF8_REPLACEMENT, $string );
		
		# ASCII is always valid NFC!
		# If we're only ever given plain ASCII, we can avoid the overhead
		# of initializing the decomposition tables by skipping out early.
		if( !preg_match( '/[\x80-\xff]/', $string ) ) return true;
		
		UtfNormal::loadData();
		global $utfCheckNFC, $utfCombiningClass;
		
		static $checkit = null, $tailBytes = null, $utfCheckOrCombining = null;
		if( !isset( $checkit ) ) {
			$utfCheckOrCombining = array_merge( $utfCheckNFC, $utfCombiningClass );

			# Head bytes for sequences which we should do further validity checks
			$checkit = array_flip( array_map( 'chr',
					array( 0xc0, 0xc1, 0xe0, 0xed, 0xef,
						   0xf0, 0xf1, 0xf2, 0xf3, 0xf4, 0xf5, 0xf6, 0xf7,
						   0xf8, 0xf9, 0xfa, 0xfb, 0xfc, 0xfd, 0xfe, 0xff ) ) );
			
			$tailBytes = array();
			for( $n = 0; $n < 256; $n++ ) {
				if( $n < 0xc0 ) {
					$remaining = 0;
				} elseif( $n < 0xe0 ) {
					$remaining = 1;
				} elseif( $n < 0xf0 ) {
					$remaining = 2;
				} elseif( $n < 0xf8 ) {
					$remaining = 3;
				} elseif( $n < 0xfc ) {
					$remaining = 4;
				} elseif( $n < 0xfe ) {
					$remaining = 5;
				} else {
					$remaining = 0;
				}
				$tailBytes[chr($n)] = $remaining;
			}
		}
		
		# Chop the text into pure-ASCII and non-ASCII areas;
		# large ASCII parts can be handled much more quickly.
		# Don't chop up Unicode areas for punctuation, though,
		# that wastes energy.
		preg_match_all(
			'/([\x00-\x7f]+|[\x80-\xff][\x00-\x40\x5b-\x5f\x7b-\xff]*)/',
			$string, $matches );
		
		ob_start();
		$looksNormal = true;
		foreach( $matches[1] as $str ) {
			if( $str{0} < "\x80" ) {
				# ASCII chunk: guaranteed to be valid UTF-8
				# and in normal form C, so output it quick.
				echo $str;
				continue;
			}
			
			# We'll have to examine the chunk byte by byte to ensure
			# that it consists of valid UTF-8 sequences, and to see
			# if any of them might not be normalized.
			#
			# Since PHP is not the fastest language on earth, some of
			# this code is a little ugly with inner loop optimizations.
			
			$len = strlen( $str ) + 1;
			$tail = 0;
			$head = '';
			for( $i = 0; --$len; ++$i ) {
				if( $tail ) {
					if( ( $c = $str{$i} ) >= "\x80" && $c < "\xc0" ) {
						$sequence .= $c;
						if( --$remaining ) {
							# Keep adding bytes...
							continue;
						}
						
						# We have come to the end of the sequence...
						$tail = 0;
						
						if( isset( $checkit[$head] ) ) {
							# Do some more detailed validity checks, for
							# invalid characters and illegal sequences.
							if( $head == "\xed" ) {
								# 0xed is relatively frequent in Korean, which
								# abuts the surrogate area, so we're doing
								# this check separately.
								if( $sequence >= UTF8_SURROGATE_FIRST ) {
									echo UTF8_REPLACEMENT;
									continue;
								}
							} else {
								$n = ord( $head );
								if(    ($n  < 0xc2 && $sequence <= UTF8_OVERLONG_A)
									|| ($n == 0xe0 && $sequence <= UTF8_OVERLONG_B)
									|| ($n == 0xef && 
										($sequence >= UTF8_FDD0 && $sequence <= UTF8_FDEF)
										|| ($sequence == UTF8_FFFE)
										|| ($sequence == UTF8_FFFF) )
									|| ($n == 0xf0 && $sequence <= UTF8_OVERLONG_C)
									|| ($n >= 0xf0 && $sequence > UTF8_MAX) ) {
									echo UTF8_REPLACEMENT;
									continue;
								}
							}
						}
						
						if( isset( $utfCheckOrCombining[$sequence] ) ) {
							# If it's NO or MAYBE, we'll have to rip
							# the string apart and put it back together.
							# That's going to be mighty slow.
							$looksNormal = false;
						}
						
						# The sequence is legal!
						echo $sequence;
						$head = '';
						continue;
					}
					# Not a valid tail byte! DIscard the char we've been building.
					$tail = false;
					echo UTF8_REPLACEMENT;
				}
				if( $remaining = $tailBytes[$c = $str{$i}] ) {
					$tail = 1;
					$sequence = $head = $c;
				} elseif( $c < "\x80" ) {
					echo $c;
				} elseif( $c < "\xc0" ) {
					# illegal tail bytes or head byte of overlong sequence
					if( $head == '' ) {
						# Don't add if we're continuing a too-long sequence
						echo UTF8_REPLACEMENT;
					}
				} else {
					echo UTF8_REPLACEMENT;
				}
			}
			if( $tail ) {
				# We ended the chunk in the middle of a sequence;
				# that's so not cool.
				echo UTF8_REPLACEMENT;
			}
		}
		$string = ob_get_contents();
		ob_end_clean();
		return $looksNormal;
	}
	
	# These take a string and run the normalization on them, without
	# checking for validity or any optimization etc. Input must be
	# VALID UTF-8!
	/**
	 * @param string $string
	 * @return string
	 * @access private
	 */
	function NFC( $string ) {
		return UtfNormal::fastCompose( UtfNormal::NFD( $string ) );
	}
	
	/**
	 * @param string $string
	 * @return string
	 * @access private
	 */
	function NFD( $string ) {
		UtfNormal::loadData();
		global $utfCanonicalDecomp;
		return UtfNormal::fastCombiningSort(
			UtfNormal::fastDecompose( $string, $utfCanonicalDecomp ) );
	}
	
	/**
	 * @param string $string
	 * @return string
	 * @access private
	 */
	function NFKC( $string ) {
		return UtfNormal::fastCompose( UtfNormal::NFKD( $string ) );
	}
	
	/**
	 * @param string $string
	 * @return string
	 * @access private
	 */
	function NFKD( $string ) {
		global $utfCompatibilityDecomp;
		if( !isset( $utfCompatibilityDecomp ) ) {
			require_once( 'UtfNormalDataK.inc' );
		}
		return UtfNormal::fastCombiningSort(
			UtfNormal::fastDecompose( $string, $utfCompatibilityDecomp ) );
	}
	
	
	/**
	 * Perform decomposition of a UTF-8 string into either D or KD form
	 * (depending on which decomposition map is passed to us).
	 * Input is assumed to be *valid* UTF-8. Invalid code will break.
	 * @access private
	 * @param string $string Valid UTF-8 string
	 * @param array $map hash of expanded decomposition map
	 * @return string a UTF-8 string decomposed, not yet normalized (needs sorting)
	 */
	function fastDecompose( $string, &$map ) {
		UtfNormal::loadData();
		$len = strlen( $string );
		$out = '';
		for( $i = 0; $i < $len; $i++ ) {
			$c = $string{$i};
			$n = ord( $c );
			if( $n < 0x80 ) {
				# ASCII chars never decompose
				# THEY ARE IMMORTAL
				$out .= $c;
				continue;
			} elseif( $n >= 0xf0 ) {
				$c = substr( $string, $i, 4 );
				$i += 3;
			} elseif( $n >= 0xe0 ) {
				$c = substr( $string, $i, 3 );
				$i += 2;
			} elseif( $n >= 0xc0 ) {
				$c = substr( $string, $i, 2 );
				$i++;
			}
			if( isset( $map[$c] ) ) {
				$out .= $map[$c];
				continue;
			} else {
				if( $c >= UTF8_HANGUL_FIRST && $c <= UTF8_HANGUL_LAST ) {
					# Decompose a hangul syllable into jamo;
					# hardcoded for three-byte UTF-8 sequence.
					# A lookup table would be slightly faster,
					# but adds a lot of memory & disk needs.
					#
					$index = ( (ord( $c{0} ) & 0x0f) << 12
					         | (ord( $c{1} ) & 0x3f) <<  6
					         | (ord( $c{2} ) & 0x3f) )
					       - UNICODE_HANGUL_FIRST;
					$l = IntVal( $index / UNICODE_HANGUL_NCOUNT );
					$v = IntVal( ($index % UNICODE_HANGUL_NCOUNT) / UNICODE_HANGUL_TCOUNT);
					$t = $index % UNICODE_HANGUL_TCOUNT;
					$out .= "\xe1\x84" . chr( 0x80 + $l ) . "\xe1\x85" . chr( 0xa1 + $v );
					if( $t >= 25 ) {
						$out .= "\xe1\x87" . chr( 0x80 + $t - 25 );
					} elseif( $t ) {
						$out .= "\xe1\x86" . chr( 0xa7 + $t );
					}
					continue;
				}
			}
			$out .= $c;
		}
		return $out;
	}

	/**
	 * Sorts combining characters into canonical order. This is the
	 * final step in creating decomposed normal forms D and KD.
	 * @access private
	 * @param string $string a valid, decomposed UTF-8 string. Input is not validated.
	 * @return string a UTF-8 string with combining characters sorted in canonical order
	 */
	function fastCombiningSort( $string ) {
		UtfNormal::loadData();
		global $utfCombiningClass;
		$len = strlen( $string );
		$out = '';
		$combiners = array();
		$lastClass = -1;
		for( $i = 0; $i < $len; $i++ ) {
			$c = $string{$i};
			$n = ord( $c );
			if( $n >= 0x80 ) {
				if( $n >= 0xf0 ) {
					$c = substr( $string, $i, 4 );
					$i += 3;
				} elseif( $n >= 0xe0 ) {
					$c = substr( $string, $i, 3 );
					$i += 2;
				} elseif( $n >= 0xc0 ) {
					$c = substr( $string, $i, 2 );
					$i++;
				}
				if( isset( $utfCombiningClass[$c] ) ) {
					$lastClass = $utfCombiningClass[$c];
					@$combiners[$lastClass] .= $c;
					continue;
				}
			}
			if( $lastClass ) {
				ksort( $combiners );
				$out .= implode( '', $combiners );
				$combiners = array();
			}
			$out .= $c;
			$lastClass = 0;
		}
		if( $lastClass ) {
			ksort( $combiners );
			$out .= implode( '', $combiners );
		}
		return $out;
	}

	/**
	 * Produces canonically composed sequences, i.e. normal form C or KC.
	 *
	 * @access private
	 * @param string $string a valid UTF-8 string in sorted normal form D or KD. Input is not validated.
	 * @return string a UTF-8 string with canonical precomposed characters used where possible
	 */
	function fastCompose( $string ) {
		UtfNormal::loadData();
		global $utfCanonicalComp, $utfCombiningClass;
		$len = strlen( $string );
		$out = '';
		$lastClass = -1;
		$startChar = '';
		$combining = '';
		$x1 = ord(substr(UTF8_HANGUL_VBASE,0,1));
		$x2 = ord(substr(UTF8_HANGUL_TEND,0,1));
		for( $i = 0; $i < $len; $i++ ) {
			$c = $string{$i};
			$n = ord( $c );
			if( $n < 0x80 ) {
				# No combining characters here...
				$out .= $startChar;
				$out .= $combining;
				$startChar = $c;
				$combining = '';
				$lastClass = 0;
				continue;
			} elseif( $n >= 0xf0 ) {
				$c = substr( $string, $i, 4 );
				$i += 3;
			} elseif( $n >= 0xe0 ) {
				$c = substr( $string, $i, 3 );
				$i += 2;
			} elseif( $n >= 0xc0 ) {
				$c = substr( $string, $i, 2 );
				$i++;
			}
			$pair = $startChar . $c;
			if( $n > 0x80 ) {
				if( isset( $utfCombiningClass[$c] ) ) {
					# A combining char; see what we can do with it
					$class = $utfCombiningClass[$c];
					if( !empty( $startChar ) &&
						$lastClass < $class &&
						$class > 0 &&
						isset( $utfCanonicalComp[$pair] ) ) {
						$startChar = $utfCanonicalComp[$pair];
						$class = 0;
					} else {
						$combining .= $c;
					}
					$lastClass = $class;
					continue;
				}
			}
			# New start char
			if( $lastClass == 0 ) {
				if( isset( $utfCanonicalComp[$pair] ) ) {
					$startChar = $utfCanonicalComp[$pair];
					continue;
				}
				if( $n >= $x1 && $n <= $x2 ) {
					# WARNING: Hangul code is painfully slow.
					# I apologize for this ugly, ugly code; however
					# performance is even more teh suck if we call
					# out to nice clean functions. Lookup tables are
					# marginally faster, but require a lot of space.
					#
					if( $c >= UTF8_HANGUL_VBASE &&
						$c <= UTF8_HANGUL_VEND &&
						$startChar >= UTF8_HANGUL_LBASE &&
						$startChar <= UTF8_HANGUL_LEND ) {
						#
						#$lIndex = utf8ToCodepoint( $startChar ) - UNICODE_HANGUL_LBASE;
						#$vIndex = utf8ToCodepoint( $c ) - UNICODE_HANGUL_VBASE;
						$lIndex = ord( $startChar{2} ) - 0x80;
						$vIndex = ord( $c{2}         ) - 0xa1;

						$hangulPoint = UNICODE_HANGUL_FIRST +
							UNICODE_HANGUL_TCOUNT *
							(UNICODE_HANGUL_VCOUNT * $lIndex + $vIndex);
						
						# Hardcode the limited-range UTF-8 conversion:
						$startChar = chr( $hangulPoint >> 12 & 0x0f | 0xe0 ) .
									 chr( $hangulPoint >>  6 & 0x3f | 0x80 ) .
									 chr( $hangulPoint       & 0x3f | 0x80 );
						continue;
					} elseif( $c >= UTF8_HANGUL_TBASE &&
							  $c <= UTF8_HANGUL_TEND &&
							  $startChar >= UTF8_HANGUL_FIRST &&
							  $startChar <= UTF8_HANGUL_LAST ) {
						# $tIndex = utf8ToCodepoint( $c ) - UNICODE_HANGUL_TBASE;
						$tIndex = ord( $c{2} ) - 0xa7;
						if( $tIndex < 0 ) $tIndex = ord( $c{2} ) - 0x80 + (0x11c0 - 0x11a7);
						
						# Increment the code point by $tIndex, without
						# the function overhead of decoding and recoding UTF-8
						#
						$tail = ord( $startChar{2} ) + $tIndex;
						if( $tail > 0xbf ) {
							$tail -= 0x40;
							$mid = ord( $startChar{1} ) + 1;
							if( $mid > 0xbf ) {
								$startChar{0} = chr( ord( $startChar{0} ) + 1 );
								$mid -= 0x40;
							}
							$startChar{1} = chr( $mid );
						}
						$startChar{2} = chr( $tail );
						continue;
					}
				}
			}
			$out .= $startChar;
			$out .= $combining;
			$startChar = $c;
			$combining = '';
			$lastClass = 0;
		}
		$out .= $startChar . $combining;
		return $out;
	}
	
	/**
	 * This is just used for the benchmark, comparing how long it takes to
	 * interate through a string without really doing anything of substance.
	 * @param string $string
	 * @return string
	 */
	function placebo( $string ) {
		$len = strlen( $string );
		$out = '';
		for( $i = 0; $i < $len; $i++ ) {
			$out .= $string{$i};
		}
		return $out;
	}
}

?>
