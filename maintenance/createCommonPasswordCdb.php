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
 * Meant to take a file like
 * https://github.com/danielmiessler/SecLists/blob/master/Passwords/10_million_password_list_top_1000000.txt?raw=true
 * as input.
 * @see serialized/commonpasswords.cdb and PasswordPolicyChecks::checkPopularPasswordBlacklist
 * @ingroup Maintenance
 */
class GenerateCommonPassword extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Generate CDB file of common passwords";
		$this->addOption( 'output', "Alternative location to write CDB file to", false, true, 'o' );
		$this->addOption( 'limit', "Max number of passwords to write", false, true, 'l' );
		$this->addArg( 'inputfile', 'List of passwords (one per line) to use or - for stdin', true );
	}

	public function execute() {
		global $IP;
		$outfile = $this->getOption( 'output', "$IP/serialized/commonpasswords.cdb" );
		$limit = $this->getOption( 'limit', PHP_INT_MAX );
		$langEn = Language::Factory( 'en' );

		$infile = $this->getArg();
		if ( $infile === '-' ) {
			$infile = 'php://stdin';
		}

		if ( !is_readable( $infile ) && $infile !== 'php://stdin' ) {
			$this->error( "Cannot open input file $infile for reading", 1 );
		}

		$file = file_get_contents( $infile );
		if ( $file === false ) {
			$this->error( "Cannot read input file $infile", 1 );
		}

		$lines = explode( "\n", $file );
		if ( $lines[count( $lines ) - 1] === '' ) {
			unset( $lines[count( $lines ) - 1] );
		}
		try {
			$db = \Cdb\Writer::open( $outfile );

			$alreadyWritten = array();
			$skipped = 0;
			// Apparently, coding conventions prohibit putting count() in loop test.
			$totalLines = count( $lines );
			for ( $i = 0; $i < $totalLines && ( $i - $skipped ) < $limit; $i++ ) {
				$line = $langEn->lc( trim( $lines[$i] ) );
				if ( $line === '' ) {
					$this->error( "Line number " . ( $i + 1 ) . " is blank?\n" );
					$skipped++;
					continue;
				}
				if ( isset( $alreadyWritten[$line] ) ) {
					$this->error( "Password '$line' already written (line " . ( $i + 1 ) .")\n" );
					$skipped++;
					continue;
				}
				$alreadyWritten[$line] = true;
				$db->set( $line, $i+1-$skipped );
			}
			// All caps, so cannot conflict with potential password
			$db->set( '_TOTALENTRIES', $i - $skipped );
			$db->close();

			$this->output( "Successfully wrote " . ( $i - $skipped ) . " (out of $i) passwords to $outfile\n" );
		} catch ( \Cdb\Exception $e ) {
			$this->error( "Error writing cdb file: " . $e->getMessage(), 2 );
		}

	}
}

$maintClass = "GenerateCommonPassword";
require_once RUN_MAINTENANCE_IF_MAIN;
