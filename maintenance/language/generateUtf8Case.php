<?php
/**
 * Generates Utf8Case.ser from the Unicode Character Database and
 * supplementary files.
 *
 * Copyright © 2004, 2008 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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

require_once __DIR__ . '/../Maintenance.php';

/**
 * Generates Utf8Case.ser from the Unicode Character Database and
 * supplementary files.
 *
 * @ingroup MaintenanceLanguage
 */
class GenerateUtf8Case extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Generate Utf8Case.ser from the Unicode Character Database ' .
			'and supplementary files';
		$this->addOption( 'unicode-data-file', 'The local location of the data file ' .
			'from http://unicode.org/Public/UNIDATA/UnicodeData.txt', false, true );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		if ( !$this->hasOption( 'unicode-data-file' ) ) {
			$dataFile = 'UnicodeData.txt';
			if ( !file_exists( $dataFile ) ) {
				$this->error( "Unable to find UnicodeData.txt. Please specify " .
					"its location with --unicode-data-file=<FILE>" );
				exit( 1 );
			}
		} else {
			$dataFile = $this->getOption( 'unicode-data-file' );
			if ( !file_exists( $dataFile ) ) {
				$this->error( 'Unable to find the specified data file.' );
				exit( 1 );
			}
		}

		$file = fopen( $dataFile, 'r' );
		if ( !$file ) {
			$this->error( 'Unable to open the data file.' );
			exit( 1 );
		}

		// For the file format, see http://www.unicode.org/reports/tr44/
		$fieldNames = array(
			'Code',
			'Name',
			'General_Category',
			'Canonical_Combining_Class',
			'Bidi_Class',
			'Decomposition_Type_Mapping',
			'Numeric_Type_Value_6',
			'Numeric_Type_Value_7',
			'Numeric_Type_Value_8',
			'Bidi_Mirrored',
			'Unicode_1_Name',
			'ISO_Comment',
			'Simple_Uppercase_Mapping',
			'Simple_Lowercase_Mapping',
			'Simple_Titlecase_Mapping'
		);

		$upper = array();
		$lower = array();

		$lineNum = 0;
		while ( false !== ( $line = fgets( $file ) ) ) {
			++$lineNum;

			# Strip comments
			$line = trim( substr( $line, 0, strcspn( $line, '#' ) ) );
			if ( $line === '' ) {
				continue;
			}

			# Split fields
			$numberedData = explode( ';', $line );
			$data = array();
			foreach ( $fieldNames as $number => $name ) {
				$data[$name] = $numberedData[$number];
			}

			$source = hexSequenceToUtf8( $data['Code'] );
			if ( $data['Simple_Uppercase_Mapping'] ) {
				$upper[$source] = hexSequenceToUtf8( $data['Simple_Uppercase_Mapping'] );
			}
			if ( $data['Simple_Lowercase_Mapping'] ) {
				$lower[$source] = hexSequenceToUtf8( $data['Simple_Lowercase_Mapping'] );
			}
		}

		global $IP;
		file_put_contents( "$IP/serialized/Utf8Case.ser", serialize( array(
			'wikiUpperChars' => $upper,
			'wikiLowerChars' => $lower,
		) ) );
	}
}

$maintClass = 'GenerateUtf8Case';
require_once RUN_MAINTENANCE_IF_MAIN;
