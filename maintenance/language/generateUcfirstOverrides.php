<?php
/**
 * Generate a php file containg an array of
 *   utf8_lowercase => utf8_uppercase
 * overrides. Takes as input two json files generated with generateUpperCharTable.php
 * as input.
 *
 * Example run:
 * # this will prepare a file to use to make hhvm's Language::ucfirst work like php7's
 *
 * $ php7.2 maintenance/language/generateUpperCharTable.php --outfile php7.2.json
 * $ hhvm --php maintenance/language/generateUpperCharTable.php --outfile hhvm.json
 * $ hhvm maintenance/language/generateUcfirstOverrides.php \
 *       --override hhvm.json --with php7.2.json --outfile test.php
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

class GenerateUcfirstOverrides extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Generates a php source file containing a definition for mb_strtoupper overrides' );
		$this->addOption( 'outfile', 'Output file', true, true, 'o' );
		$this->addOption( 'override', 'Char table we want to override', true, true );
		$this->addOption( 'with', 'Char table we want to obtain', true, true );
	}

	public function execute() {
		$outfile = $this->getOption( 'outfile' );
		$from = $this->loadJson( $this->getOption( 'override' ) );
		$to = $this->loadJson( $this->getOption( 'with' ) );
		$overrides = [];

		foreach ( $from as $lc => $uc ) {
			$ref = $to[$lc] ?? null;
			if ( $ref !== null && $ref !== $uc ) {
				$overrides[$lc] = $ref;
			}
		}
		$writer = new StaticArrayWriter();
		file_put_contents(
			$outfile,
			$writer->create( $overrides, 'File created by generateUcfirstOverrides.php' )
		);
	}

	private function loadJson( $filename ) {
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

$maintClass = GenerateUcfirstOverrides::class;
require_once RUN_MAINTENANCE_IF_MAIN;
