<?php
/**
 * Maintenance script to generate first letter data files of Chinese
 * collationsfor Collation.php.
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

	public function __construct() {
		parent::__construct();
	}

	public function execute() {
		# Manually extracted array from ICU collation data:
		# http://source.icu-project.org/repos/icu/icu/trunk/source/data/coll/zh.txt
		# Ideally this may be automatically extracted from there or UCD data,
		# but I don't bother to write another parser for its own format,
		# and there're some differences between different UCD data versions and ICU data.
		$this->generateFirstChars( 'zh@collation=pinyin', array(
			array( '阿', 'A' ),
			array( '八', 'B' ),
			array( '嚓', 'C' ),
			array( '咑', 'D' ),
			array( '妸', 'E' ),
			array( '发', 'F' ),
			array( '旮', 'G' ),
			array( '哈', 'H' ),
			array( '丌', 'J' ),
			array( '咔', 'K' ),
			array( '垃', 'L' ),
			array( '呣', 'M' ),
			array( '嗯', 'N' ),
			array( '喔', 'O' ),
			array( '妑', 'P' ),
			array( '七', 'Q' ),
			array( '呥', 'R' ),
			array( '仨', 'S' ),
			array( '他', 'T' ),
			array( '穵', 'W' ),
			array( '夕', 'X' ),
			array( '丫', 'Y' ),
			array( '帀', 'Z' ),
		) );
		$this->generateFirstChars( 'zh@collation=stroke', array(
			array( '一', 1 ),
			array( '丁', 2 ),
			array( '万', 3 ),
			array( '不', 4 ),
			array( '丗', 5 ),
			array( '㐁', 6 ),
			array( '丣', 7 ),
			array( '並', 8 ),
			array( '临', 9 ),
			array( '𠀾', 10 ),
			array( '㐢', 11 ),
			array( '𠁆', 12 ),
			array( '亂', 13 ),
			array( '𠁎', 14 ),
			array( '㒓', 15 ),
			array( '亸', 16 ),
			array( '償', 17 ),
			array( '儭', 18 ),
			array( '㐦', 19 ),
			array( '㒥', 20 ),
			array( '㒧', 21 ),
			array( '亹', 22 ),
			array( '儽', 23 ),
			array( '儾', 24 ),
			array( '囔', 25 ),
			array( '㔶', 26 ),
			array( '灥', 27 ),
			array( '囖', 28 ),
			array( '爨', 29 ),
			array( '厵', 30 ),
			array( '灩', 31 ),
			array( '灪', 32 ),
			array( '𡤻', 33 ),
			array( '齾', 35 ),
			array( '齉', 36 ),
			array( '靐', 39 ),
			array( '龘', 48 ),
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
