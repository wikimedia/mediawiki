<?php
/**
 * Benchmark HTTP request vs HTTPS request.
 *
 * This come from r75429 message.
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
 * @author  Platonides
 */

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks HTTP request vs HTTPS request.
 *
 * @ingroup Benchmark
 */
class BenchHttpHttps extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark HTTP request vs HTTPS request.' );
	}

	public function execute() {
		$this->bench( [
			[ 'function' => [ $this, 'getHTTP' ] ],
			[ 'function' => [ $this, 'getHTTPS' ] ],
		] );
	}

	private function doRequest( $proto ) {
		Http::get( "$proto://localhost/", [], __METHOD__ );
	}

	// bench function 1
	protected function getHTTP() {
		$this->doRequest( 'http' );
	}

	// bench function 2
	protected function getHTTPS() {
		$this->doRequest( 'https' );
	}
}

$maintClass = BenchHttpHttps::class;
require_once RUN_MAINTENANCE_IF_MAIN;
