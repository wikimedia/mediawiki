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

# Unicode normalization routines for working with UTF-8 strings.
# Currently assumes that input strings are valid UTF-8!
#
# Not as fast as I'd like, but should be usable for most purposes.
# UtfNormal::toNFC() will bail early if given ASCII text or text
# it can quickly deterimine is already normalized.
#
# All functions can be called static.
#
# See description of forms at http://www.unicode.org/reports/tr15/

require_once 'UtfNormalUtil.php';
require_once 'UtfNormalData.inc';

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

define( 'UTF8_HANGUL_FIRST', codepointToUtf8( UNICODE_HANGUL_FIRST ) );
define( 'UTF8_HANGUL_LAST', codepointToUtf8( UNICODE_HANGUL_LAST ) );

define( 'UTF8_HANGUL_LBASE', codepointToUtf8( UNICODE_HANGUL_LBASE ) );
define( 'UTF8_HANGUL_VBASE', codepointToUtf8( UNICODE_HANGUL_VBASE ) );
define( 'UTF8_HANGUL_TBASE', codepointToUtf8( UNICODE_HANGUL_TBASE ) );

define( 'UTF8_HANGUL_LEND', codepointToUtf8( UNICODE_HANGUL_LEND ) );
define( 'UTF8_HANGUL_VEND', codepointToUtf8( UNICODE_HANGUL_VEND ) );
define( 'UTF8_HANGUL_TEND', codepointToUtf8( UNICODE_HANGUL_TEND ) );

class UtfNormal {
	# These functions try to skip the conversion if it won't be necessary.
	# An all ASCII string for instance doesn't need conversion.
	function toNFC( $string ) {
		if( UtfNormal::quickIsNFC( $string ) )
			return $string;
		else
			return UtfNormal::NFC( $string );
	}
	
	function toNFD( $string ) {
		if( preg_match( '/[\x80-\xff]/', $string ) )
			return UtfNormal::NFD( $string );
		else
			return $string;
	}
	
	function toNFKC( $string ) {
		if( preg_match( '/[\x80-\xff]/', $string ) )
			return UtfNormal::NFKC( $string );
		else
			return $string;
	}
	
	function toNFKD( $string ) {
		if( preg_match( '/[\x80-\xff]/', $string ) )
			return UtfNormal::NFKD( $string );
		else
			return $string;
	}
	
	
	function quickIsNFC( $string ) {
		# ASCII is always valid NFC!
		if( !preg_match( '/[\x80-\xff]/', $string ) ) return true;
		
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
	
	# These take a string and run the normalization on them, without
	# checking for validity or any optimization etc. Input must be
	# VALID UTF-8!
	function NFC( $string ) {
		return $out = UtfNormal::fastCompose( UtfNormal::NFD( $string ) );
	}
	
	function NFD( $string ) {
		global $utfCanonicalDecomp;
		return UtfNormal::fastCombiningSort(
			UtfNormal::fastDecompose( $string, $utfCanonicalDecomp ) );
	}
	
	function NFKC( $string ) {
		return UtfNormal::fastCompose( UtfNormal::NFKD( $string ) );
	}
	
	function NFKD( $string ) {
		global $utfCompatibilityDecomp;
		if( !isset( $utfCompatibilityDecomp ) ) {
			require_once( 'UtfNormalDataK.inc' );
		}
		return UtfNormal::fastCombiningSort(
			UtfNormal::fastDecompose( $string, $utfCompatibilityDecomp ) );
	}
	
	
	/* Private */
	# Perform decomposition of a UTF-8 string into either D or KD form
	# (depending on which decomposition map is passed to us).
	# Input is assumed to be *valid* UTF-8. Invalid code will break.
	function fastDecompose( &$string, &$map ) {
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
			} else {
				if( $c >= UTF8_HANGUL_FIRST && $c <= UTF8_HANGUL_LAST ) {
					$out .= UtfNormal::decomposeHangul( $c );
				} else {
					$out .= $c;
				}
			}
		}
		return $out;
	}

	function decomposeHangul( $c ) {
		$codepoint = utf8ToCodepoint( $c );
		$index = $codepoint - UNICODE_HANGUL_FIRST;
		$l = IntVal( $index / UNICODE_HANGUL_NCOUNT );
		$v = IntVal( ($index % UNICODE_HANGUL_NCOUNT) / UNICODE_HANGUL_TCOUNT);
		$t = $index % UNICODE_HANGUL_TCOUNT;
		$out = codepointToUtf8( $l + UNICODE_HANGUL_LBASE );
		$out .= codepointToUtf8( $v + UNICODE_HANGUL_VBASE );
		if( $t ) $out .= codepointToUtf8( $t + UNICODE_HANGUL_TBASE );
		return $out;
	}
	
	# Sorts combining characters into canonical order. This is the
	# final step in creating decomposed normal forms D and KD.
	function fastCombiningSort( $string ) {
		global $utfCombiningClass;
		$replacedCount = 1;
		while( $replacedCount > 0 ) {
			$replacedCount = 0;
			$len = strlen( $string );
			$out = '';
			$lastClass = -1;
			$lastChar = '';
			for( $i = 0; $i < $len; $i++ ) {
				$c = $string{$i};
				$n = ord( $c );
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
				$class = isset( $utfCombiningClass[$c] ) ? $utfCombiningClass[$c] : 0;
				if( $lastClass == -1 ) {
					# First one
					$lastChar = $c;
					$lastClass = $class;
				} elseif( $lastClass > $class && $class > 0 ) {
					# Swap -- put this one on the stack
					$out .= $c;
					$replacedCount++;
				} else {
					$out .= $lastChar;
					$lastChar = $c;
					$lastClass = $class;
				}
			}
			$out .= $lastChar;
			$string = $out;
		}
		return $string;
	}

	# Produces canonically composed sequences, i.e. normal form C or KC.
	# Input must be valid UTF-8 in sorted normal form D or KD.
	function fastCompose( $string ) {
		global $utfCanonicalComp, $utfCombiningClass;
		$len = strlen( $string );
		$out = '';
		$lastClass = -1;
		$startChar = '';
		$combining = '';
		for( $i = 0; $i < $len; $i++ ) {
			$c = $string{$i};
			$n = ord( $c );
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
			$class = isset( $utfCombiningClass[$c] ) ? $utfCombiningClass[$c] : 0;
			$pair = $startChar . $c;
			if( empty( $utfCombiningClass[$c] ) ) {
				# New start char
				if( $lastClass == 0 && isset( $utfCanonicalComp[$pair] ) ) {
					$startChar = $utfCanonicalComp[$pair];
				} elseif( $lastClass == 0 &&
				          $c >= UTF8_HANGUL_VBASE &&
				          $c <= UTF8_HANGUL_VEND &&
				          $startChar >= UTF8_HANGUL_LBASE &&
				          $startChar <= UTF8_HANGUL_LEND ) {
					$lIndex = utf8ToCodepoint( $startChar ) - UNICODE_HANGUL_LBASE;
					$vIndex = utf8ToCodepoint( $c ) - UNICODE_HANGUL_VBASE;
					$hangulPoint = UNICODE_HANGUL_FIRST +
						UNICODE_HANGUL_TCOUNT *
						(UNICODE_HANGUL_VCOUNT * $lIndex + $vIndex);
					$startChar = codepointToUtf8( $hangulPoint );
				} elseif( $lastClass == 0 &&
				          $c >= UTF8_HANGUL_TBASE &&
				          $c <= UTF8_HANGUL_TEND &&
				          $startChar >= UTF8_HANGUL_FIRST &&
				          $startChar <= UTF8_HANGUL_LAST ) {
					$tIndex = utf8ToCodepoint( $c ) - UNICODE_HANGUL_TBASE;
					$hangulPoint = utf8ToCodepoint( $startChar ) + $tIndex;
					$startChar = codepointToUtf8( $hangulPoint );
				} else {
					$out .= $startChar;
					$out .= $combining;
					$startChar = $c;
					$combining = '';
				}
			} else {
				# A combining char; see what we can do with it
				if( !empty( $startChar ) &&
					$lastClass < $class &&
					$class > 0 &&
					isset( $utfCanonicalComp[$pair] ) ) {
					$startChar = $utfCanonicalComp[$pair];
					$class = 0;
				} else {
					$combining .= $c;
				}
			}
			$lastClass = $class;
		}
		$out .= $startChar . $combining;
		return $out;
	}

	# This is just used for the benchmark, comparing how long it takes to
	# interate through a string without really doing anything of substance.
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