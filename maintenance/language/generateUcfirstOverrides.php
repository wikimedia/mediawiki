<?php
/**
 * Generate a php file containing an array of
 *   utf8_lowercase => utf8_uppercase
 * overrides. Takes as input two json files generated with generateUpperCharTable.php
 * as input.
 *
 * Example: Prepare Language::ucfirst on newer PHP 7.4 to work like the current PHP 7.2
 *
 * $ php7.2 maintenance/language/generateUpperCharTable.php --outfile php72.json
 * $ php7.4 maintenance/language/generateUpperCharTable.php --outfile php74.json
 * $ php7.2 maintenance/language/generateUcfirstOverrides.php \
 *       --override php74.json --with php72.json --outfile test.php
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

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\StaticArrayWriter;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class GenerateUcfirstOverrides extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Generates a php source file containing a definition for mb_strtoupper overrides' );
		$this->addOption( 'outfile', 'Output file', true, true, 'o' );
		$this->addOption( 'override', 'Char table we want to avoid (e.g. future PHP)',
			true, true, false, true );
		$this->addOption( 'with', 'Char table we want to obtain or preserve (e.g. current PHP)', true, true );
	}

	public function execute() {
		$outfile = $this->getOption( 'outfile' );
		$fromTables = [];
		foreach ( $this->getOption( 'override' ) as $fileName ) {
			$fromTables[] = $this->loadJson( $fileName );
		}
		$to = $this->loadJson( $this->getOption( 'with' ) );
		$overrides = [];

		foreach ( $fromTables as $from ) {
			foreach ( $from as $lc => $uc ) {
				$ref = $to[$lc] ?? null;
				if ( $ref !== null && $ref !== $uc ) {
					$overrides[$lc] = $ref;
				}
			}
		}
		ksort( $overrides );
		$writer = new StaticArrayWriter();
		file_put_contents(
			$outfile,
			$writer->create( $overrides, 'File created by generateUcfirstOverrides.php' )
		);
	}

	/** @return mixed */
	private function loadJson( string $filename ) {
		$data = file_get_contents( $filename );
		if ( $data === false ) {
			$msg = sprintf( "Could not load data from file '%s'\n", $filename );
			$this->fatalError( $msg );
		}
		$json = json_decode( $data, true );
		if ( $json === null ) {
			$msg = sprintf( "Invalid json in the data file %s\n", $filename );
			$this->fatalError( $msg, 2 );
		}
		return $json;
	}
}

// @codeCoverageIgnoreStart
$maintClass = GenerateUcfirstOverrides::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
