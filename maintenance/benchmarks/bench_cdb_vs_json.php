<?php
/**
 * Benchmark for writing and reading lots of values to CDB and JSON
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
 * @ingroup Benchmark
 * @author Chad
 */

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks the 
 *
 * @ingroup Benchmark
 */
class BenchCdbVsJson extends Benchmarker {
	private $values, $cdbFiles, $jsonFiles = array();

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmark for cdb vs json.";
	}

	public function execute() {
		print "Building data...";
		$this->buildDummyData();
		print "done\n";

		$this->bench( array(
			array( 'function' => array( $this, 'readCdb' ) ),
			array( 'function' => array( $this, 'readJson' ) ),
		) );

		print $this->getFormattedResults();
	}

	function buildDummyData() {
		for ( $i = 0; $i < 1e5; $i++ ) {
			$this->values[wfRandomString(10)] = wfRandomString();
		}

		// Just write json and CDB here. We know json will be faster
		// The interesting part is which is faster on a random read
		$writer = CdbWriter::open( 'speedtest.cdb' );
		array_walk( $this->values, function( $value, $key ) use ( $writer ) {
			$writer->set( $key, $value );
		} );
		$writer->close();
		file_put_contents( 'speedtest.json', json_encode( $this->values ) );
	}

	function readCdb() {
		$reader = CdbReader::open( 'speedtest.cdb' );
		$val = $reader->get( array_rand( $this->values ) );
	}

	function readJson() {
		$struct = FormatJson::decode( file_get_contents( 'speedtest.json' ), true );
		$val = $struct[ array_rand( $this->values ) ];
	}
}

$maintClass = 'BenchCdbVsJson';
require_once RUN_MAINTENANCE_IF_MAIN;
