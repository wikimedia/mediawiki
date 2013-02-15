<?php
/**
 * Maintenance script to generate first letter data files of Chinese
 * collations for Collation.php.
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
 * @ingroup MaintenanceLanguage
 */

require_once( __DIR__ .'/../Maintenance.php' );

/**
 * Generate first letter data files of Chinese for Collation.php
 *
 * @ingroup MaintenanceLanguage
 */
class GenerateCollationDataZh extends Maintenance {

	public function execute() {
		# Manually extracted array from ICU collation data:
		# http://source.icu-project.org/repos/icu/icu/trunk/source/data/coll/zh.txt
		# Only data in ICU 4.8rc1+ (r30101) has this $magic 'section header'.
		$magic = "\xef\xb7\x90";
		$this->generateFirstChars( 'zh@collation=pinyin', array(
			array( $magic . 'A', 'A' ),
			array( $magic . 'B', 'B' ),
			array( $magic . 'C', 'C' ),
			array( $magic . 'D', 'D' ),
			array( $magic . 'E', 'E' ),
			array( $magic . 'F', 'F' ),
			array( $magic . 'G', 'G' ),
			array( $magic . 'H', 'H' ),
			array( $magic . 'J', 'J' ),
			array( $magic . 'K', 'K' ),
			array( $magic . 'L', 'L' ),
			array( $magic . 'M', 'M' ),
			array( $magic . 'N', 'N' ),
			array( $magic . 'O', 'O' ),
			array( $magic . 'P', 'P' ),
			array( $magic . 'Q', 'Q' ),
			array( $magic . 'R', 'R' ),
			array( $magic . 'S', 'S' ),
			array( $magic . 'T', 'T' ),
			array( $magic . 'W', 'W' ),
			array( $magic . 'X', 'X' ),
			array( $magic . 'Y', 'Y' ),
			array( $magic . 'Z', 'Z' ),
		) );
		$this->generateFirstChars( 'zh@collation=stroke', array(
			array( $magic . codepointToUtf8( 0x2800 + 1 ), 1 ),
			array( $magic . codepointToUtf8( 0x2800 + 2 ), 2 ),
			array( $magic . codepointToUtf8( 0x2800 + 3 ), 3 ),
			array( $magic . codepointToUtf8( 0x2800 + 4 ), 4 ),
			array( $magic . codepointToUtf8( 0x2800 + 5 ), 5 ),
			array( $magic . codepointToUtf8( 0x2800 + 6 ), 6 ),
			array( $magic . codepointToUtf8( 0x2800 + 7 ), 7 ),
			array( $magic . codepointToUtf8( 0x2800 + 8 ), 8 ),
			array( $magic . codepointToUtf8( 0x2800 + 9 ), 9 ),
			array( $magic . codepointToUtf8( 0x2800 + 10 ), 10 ),
			array( $magic . codepointToUtf8( 0x2800 + 11 ), 11 ),
			array( $magic . codepointToUtf8( 0x2800 + 12 ), 12 ),
			array( $magic . codepointToUtf8( 0x2800 + 13 ), 13 ),
			array( $magic . codepointToUtf8( 0x2800 + 14 ), 14 ),
			array( $magic . codepointToUtf8( 0x2800 + 15 ), 15 ),
			array( $magic . codepointToUtf8( 0x2800 + 16 ), 16 ),
			array( $magic . codepointToUtf8( 0x2800 + 17 ), 17 ),
			array( $magic . codepointToUtf8( 0x2800 + 18 ), 18 ),
			array( $magic . codepointToUtf8( 0x2800 + 19 ), 19 ),
			array( $magic . codepointToUtf8( 0x2800 + 20 ), 20 ),
			array( $magic . codepointToUtf8( 0x2800 + 21 ), 21 ),
			array( $magic . codepointToUtf8( 0x2800 + 22 ), 22 ),
			array( $magic . codepointToUtf8( 0x2800 + 23 ), 23 ),
			array( $magic . codepointToUtf8( 0x2800 + 24 ), 24 ),
			array( $magic . codepointToUtf8( 0x2800 + 25 ), 25 ),
			array( $magic . codepointToUtf8( 0x2800 + 26 ), 26 ),
			array( $magic . codepointToUtf8( 0x2800 + 27 ), 27 ),
			array( $magic . codepointToUtf8( 0x2800 + 28 ), 28 ),
			array( $magic . codepointToUtf8( 0x2800 + 29 ), 29 ),
			array( $magic . codepointToUtf8( 0x2800 + 30 ), 30 ),
			array( $magic . codepointToUtf8( 0x2800 + 31 ), 31 ),
			array( $magic . codepointToUtf8( 0x2800 + 32 ), 32 ),
			array( $magic . codepointToUtf8( 0x2800 + 33 ), 33 ),
			array( $magic . codepointToUtf8( 0x2800 + 35 ), 35 ),
			array( $magic . codepointToUtf8( 0x2800 + 36 ), 36 ),
			array( $magic . codepointToUtf8( 0x2800 + 39 ), 39 ),
			array( $magic . codepointToUtf8( 0x2800 + 48 ), 48 ),
		) );
		$this->generateFirstChars( 'zh@collation=zhuyin', array(
			array( $magic . 'ㄅ', 'ㄅ' ),
			array( $magic . 'ㄆ', 'ㄆ' ),
			array( $magic . 'ㄇ', 'ㄇ' ),
			array( $magic . 'ㄈ', 'ㄈ' ),
			array( $magic . 'ㄉ', 'ㄉ' ),
			array( $magic . 'ㄊ', 'ㄊ' ),
			array( $magic . 'ㄋ', 'ㄋ' ),
			array( $magic . 'ㄌ', 'ㄌ' ),
			array( $magic . 'ㄍ', 'ㄍ' ),
			array( $magic . 'ㄎ', 'ㄎ' ),
			array( $magic . 'ㄏ', 'ㄏ' ),
			array( $magic . 'ㄐ', 'ㄐ' ),
			array( $magic . 'ㄑ', 'ㄑ' ),
			array( $magic . 'ㄒ', 'ㄒ' ),
			array( $magic . 'ㄓ', 'ㄓ' ),
			array( $magic . 'ㄔ', 'ㄔ' ),
			array( $magic . 'ㄕ', 'ㄕ' ),
			array( $magic . 'ㄖ', 'ㄖ' ),
			array( $magic . 'ㄗ', 'ㄗ' ),
			array( $magic . 'ㄘ', 'ㄘ' ),
			array( $magic . 'ㄙ', 'ㄙ' ),
			array( $magic . 'ㄚ', 'ㄚ' ),
			array( $magic . 'ㄛ', 'ㄛ' ),
			array( $magic . 'ㄜ', 'ㄜ' ),
			array( $magic . 'ㄞ', 'ㄞ' ),
			array( $magic . 'ㄟ', 'ㄟ' ),
			array( $magic . 'ㄠ', 'ㄠ' ),
			array( $magic . 'ㄡ', 'ㄡ' ),
			array( $magic . 'ㄢ', 'ㄢ' ),
			array( $magic . 'ㄣ', 'ㄣ' ),
			array( $magic . 'ㄤ', 'ㄤ' ),
			array( $magic . 'ㄥ', 'ㄥ' ),
			array( $magic . 'ㄦ', 'ㄦ' ),
			array( $magic . 'ㄧ', 'ㄧ' ),
			array( $magic . 'ㄨ', 'ㄨ' ),
			array( $magic . 'ㄩ', 'ㄩ' ),
		) );
	}

	function generateFirstChars( $locale, $firstChars ) {
		global $IP;
		$outFile = fopen( "$IP/serialized/first-letters-$locale.ser", 'w' );
		if ( !$outFile ) {
			$this->error( "Unable to open output file first-letters-$locale.ser" );
			exit( 1 );
		}

		fwrite( $outFile, serialize( $firstChars ) );
	}
}

$maintClass = 'GenerateCollationDataZh';
require_once( RUN_MAINTENANCE_IF_MAIN );
