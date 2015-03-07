<?php
/**
 * Generates the normalizer data file for Arabic.
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
 * Generates the normalizer data file for Arabic.
 * For NFC see includes/libs/normal.
 *
 * @ingroup MaintenanceLanguage
 */
class GenerateNormalizerDataAr extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Generate the normalizer data file for Arabic';
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

		$pairs = array();

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

			$code = base_convert( $data['Code'], 16, 10 );
			if ( ( $code >= 0xFB50 && $code <= 0xFDFF ) # Arabic presentation forms A
				|| ( $code >= 0xFE70 && $code <= 0xFEFF ) # Arabic presentation forms B
			) {
				if ( $data['Decomposition_Type_Mapping'] === '' ) {
					// No decomposition
					continue;
				}
				if ( !preg_match( '/^ *(<\w*>) +([0-9A-F ]*)$/',
					$data['Decomposition_Type_Mapping'], $m )
				) {
					$this->error( "Can't parse Decomposition_Type/Mapping on line $lineNum" );
					$this->error( $line );
					continue;
				}

				$source = UtfNormal\Utils::hexSequenceToUtf8( $data['Code'] );
				$dest = UtfNormal\Utils::hexSequenceToUtf8( $m[2] );
				$pairs[$source] = $dest;
			}
		}

		global $IP;
		file_put_contents( "$IP/serialized/normalize-ar.ser", serialize( $pairs ) );
		echo "ar: " . count( $pairs ) . " pairs written.\n";
	}
}

$maintClass = 'GenerateNormalizerDataAr';
require_once RUN_MAINTENANCE_IF_MAIN;
