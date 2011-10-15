<?php
/**
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

require_once( dirname( __FILE__ ) . '/Benchmarker.php' );

class BenchmarkHooks extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmark MediaWiki Hooks.";
	}

	public function execute() {
		$time = $this->benchEmptyHooks();
		$this->output( 'Empty hook: ' . $time . "\n" );
		$time = $this->benchLoadedHooks();
		$this->output( 'Loaded hook: ' . $time . "\n" );
		$this->output( "\n" );
	}

	private function benchEmptyHooks( $trials = 1 ) {
		global $wgHooks;
		$wgHooks['Test'] = array();

		$start = wfTime();
		for ( $i = 0; $i < $trials; $i++ ) {
			wfRunHooks( 'Test' );
		}
		$delta = wfTime() - $start;
		$pertrial = $delta / $trials;
		return sprintf( "Took %6.2fms",
			$pertrial );
	}

	private function benchLoadedHooks( $trials = 1 ) {
		global $wgHooks;
		$wgHooks['Test'][] = array( $this, 'test' );

		$start = wfTime();
		for ( $i = 0; $i < $trials; $i++ ) {
			wfRunHooks( 'Test' );
		}
		$delta = wfTime() - $start;
		$pertrial = $delta / $trials;
		return sprintf( "Took %6.2fms",
			$pertrial );
	}

	public function test() {
		return true;
	}
}

$maintClass = "BenchmarkHooks";
require_once( RUN_MAINTENANCE_IF_MAIN );
