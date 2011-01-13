<?php
/**
 * This come from r75429 message
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
 * @author Platonides 
 */

require_once( dirname( __FILE__ ) . '/Benchmarker.php' );
class bench_HTTP_HTTPS extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmark HTTP request vs HTTPS request.";
	}

	public function execute() {
		$this->bench( array(
			array( 'function' => array( $this, 'getHTTP' ) ),
			array( 'function' => array( $this, 'getHTTPS' ) ),
		));
		print $this->getFormattedResults();
	}

	static function doRequest( $proto ) {
		Http::get( "$proto://localhost/" );
	}

	// bench function 1
	function getHTTP() {
		$this->doRequest( 'http' );
	}

	// bench function 2
	function getHTTPS() {
		$this->doRequest( 'https' );
	}
}

$maintClass = 'bench_HTTP_HTTPS';
require_once( RUN_MAINTENANCE_IF_MAIN );
