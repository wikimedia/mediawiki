<?php
/**
 * Create serialized/commonpasswords.cdb
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to create common password cdb database.
 *
 * Meant to take a file like those from
 * https://github.com/danielmiessler/SecLists
 * For example:
 * https://github.com/danielmiessler/SecLists/blob/fe2b40dd84/Passwords/rockyou.txt?raw=true
 *
 * @see serialized/commonpasswords.cdb and PasswordPolicyChecks::checkPopularPasswordBlacklist
 * @since 1.27
 * @ingroup Maintenance
 */
class GenerateCommonPassword extends Maintenance {
	public function __construct() {
		global $IP;
		parent::__construct();
		$this->addDescription( 'Generate CDB file of common passwords' );
		$this->addOption( 'limit', "Max number of passwords to write", false, true, 'l' );
		$this->addArg( 'inputfile', 'List of passwords (one per line) to use or - for stdin', true );
		$this->addArg(
			'output',
			"Location to write CDB file to (Try $IP/serialized/commonpasswords.cdb)",
			true
		);
	}

	public function execute() {
		$limit = (int)$this->getOption( 'limit', PHP_INT_MAX );
		$langEn = Language::factory( 'en' );

		$infile = $this->getArg( 0 );
		if ( $infile === '-' ) {
			$infile = 'php://stdin';
		}
		$outfile = $this->getArg( 1 );

		if ( !is_readable( $infile ) && $infile !== 'php://stdin' ) {
			$this->error( "Cannot open input file $infile for reading", 1 );
		}

		$file = fopen( $infile, 'r' );
		if ( $file === false ) {
			$this->error( "Cannot read input file $infile", 1 );
		}

		try {
			$db = \Cdb\Writer::open( $outfile );

			$alreadyWritten = [];
			$skipped = 0;
			for ( $i = 0; ( $i - $skipped ) < $limit; $i++ ) {
				if ( feof( $file ) ) {
					break;
				}
				$rawLine = fgets( $file );

				if ( $rawLine === false ) {
					$this->error( "Error reading input file" );
					break;
				}
				if ( substr( $rawLine, -1 ) !== "\n" && !feof( $file ) ) {
					// We're assuming that this just won't happen.
					$this->error( "fgets did not return whole line at $i??" );
				}
				$line = $langEn->lc( trim( $rawLine ) );
				if ( $line === '' ) {
					$this->error( "Line number " . ( $i + 1 ) . " is blank?" );
					$skipped++;
					continue;
				}
				if ( isset( $alreadyWritten[$line] ) ) {
					$this->output( "Password '$line' already written (line " . ( $i + 1 ) .")\n" );
					$skipped++;
					continue;
				}
				$alreadyWritten[$line] = true;
				$db->set( $line, $i + 1 - $skipped );
			}
			// All caps, so cannot conflict with potential password
			$db->set( '_TOTALENTRIES', $i - $skipped );
			$db->close();

			$this->output( "Successfully wrote " . ( $i - $skipped ) .
				" (out of $i) passwords to $outfile\n"
			);
		} catch ( \Cdb\Exception $e ) {
			$this->error( "Error writing cdb file: " . $e->getMessage(), 2 );
		}
	}
}

$maintClass = "GenerateCommonPassword";
require_once RUN_MAINTENANCE_IF_MAIN;
