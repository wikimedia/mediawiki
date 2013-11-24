<?php
/**
 * Benchmark for wfIsWindows().
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
 * Maintenance script that benchmarks wfIsWindows().
 *
 * @ingroup Benchmark
 */
class bench_wfIsWindows extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmark for wfIsWindows.";
	}

	public function execute() {
		$this->bench( array(
			array( 'function' => array( $this, 'wfIsWindows' ) ),
			array( 'function' => array( $this, 'wfIsWindowsCached' ) ),
		));
		print $this->getFormattedResults();
	}

	static function is_win() {
		return substr( php_uname(), 0, 7 ) == 'Windows';
	}

	// bench function 1
	function wfIsWindows() {
		return self::is_win();
	}

	// bench function 2
	function wfIsWindowsCached() {
		static $isWindows = null;
		if( $isWindows == null ) {
			$isWindows = self::is_win();
		}
		return $isWindows;
	}
}

$maintClass = 'bench_wfIsWindows';
require_once RUN_MAINTENANCE_IF_MAIN;
