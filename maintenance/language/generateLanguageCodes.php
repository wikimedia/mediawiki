<?php
/**
 * This script generates a CDB of all ISO 639 languages,
 * based on the file that can be found from the link here:
 * http://sil.org/iso639-3/download.asp
 * The file is updated usually once a year and its name includes a date,
 * so it cannot be hardcoded
 * (e.g. http://sil.org/iso639-3/iso-639-3_20110525.tab)
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
 * @author Robin Pepermans
 * @file
 * @ingroup MaintenanceLanguage
 * @defgroup MaintenanceLanguage MaintenanceLanguage
 */

require_once( dirname( __FILE__ ) .'/../Maintenance.php' );

class GenerateLanguageCodes extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Generates a file with all ISO 639 languages";
		$this->addOption( 'codelist', 'The file with codes, from SIL.org ' .
			'e.g. http://sil.org/iso639-3/iso-639-3_20120228.tab',
			true, true );
	}

	public function execute() {

		$url = $this->getOption( 'codelist' );
		$file = file( $url );

		if( !$file ) {
			$this->error( 'Invalid file: ' . $url );
			die();
		}

		$dataNames = explode( "\t", $file[0] );
		$dataNames[0] = 'Id'; # hack to avoid BOM

		unset( $file[0] );

		$codeList = array();
		$all1codes = array();
		$all2codes = array();
		$all3codes = array();

		$cdb = CdbWriter::open( dirname( __FILE__ ) . '/LanguageCodes.cdb' );

		foreach( $file as $line ) {
			$exploded = explode( "\t", trim( $line ) );
			foreach( $exploded as $i => $value ) {
				$name = trim( $dataNames[$i] );
				$codeList['tmp'][$name] = $value;
			}
			$index = $codeList['tmp']['Id']; # the ISO 639-3 language code
			$all3codes[$index] = true;

			$part1 = isset( $codeList['tmp']['Part1'] ) ? $codeList['tmp']['Part1'] : null;
			if( $part1 && $part1 !== $index ) {
				# If there is an ISO 639-1, make the ISO 639-1 refer to the ISO 639-3
				$cdb->set( $part1, $index );
				$all1codes[$part1] = true;
			}

			$part2B = isset( $codeList['tmp']['Part2B'] ) ? $codeList['tmp']['Part2B'] : null;
			if( $part2B && $part2B !== $index ) {
				# If there is an ISO 639-2B, make the ISO 639-1 refer to the ISO 639-3
				$cdb->set( $part2B, $index );
				$all2codes[$part2B] = true;
			}

			$part2T = isset( $codeList['tmp']['Part2T'] ) ? $codeList['tmp']['Part2T'] : null;
			if( $part2T && $part2T !== $index ) {
				# If there is an ISO 639-2T, make the ISO 639-2T refer to the ISO 639-3
				$cdb->set( $part2T, $index );
				$all2codes[$part2T] = true;
			}

			unset( $codeList['tmp']['Id'] ); # is added as key ($index), so no need to set in value
			# Part2B|Part2T|Part1|Scope|Language_Type|Ref_Name|Comment
			$implode = implode( '|', $codeList['tmp'] );
			$cdb->set( $index, $implode );

			unset( $codeList['tmp'] );
		}

		$cdb->set( 'ALL1CODES', implode( '|', array_keys( $all1codes ) ) );
		$cdb->set( 'ALL2CODES', implode( '|', array_keys( $all2codes ) ) );
		$cdb->set( 'ALL3CODES', implode( '|', array_keys( $all3codes ) ) );
	}
}

$maintClass = "GenerateLanguageCodes";
require_once( RUN_MAINTENANCE_IF_MAIN );
