<?php
/**
 * Benchmark if elseif... versus switch case.
 *
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
 * @ingroup Benchmark
 * @author  Platonides
 */

require_once( __DIR__ . '/Benchmarker.php' );

/**
 * Maintenance script that benchmark if elseif... versus switch case.
 *
 * @ingroup Maintenance
 */
class bench_if_switch extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmark if elseif... versus switch case.";
	}

	public function execute() {
		$this->bench( array(
			array( 'function' => array( $this, 'doElseIf' ) ),
			array( 'function' => array( $this, 'doSwitch' ) ),
		));
		print $this->getFormattedResults();
	}

	// bench function 1
	function doElseIf() {
		$a = 'z';
		if( $a == 'a') {}
		elseif( $a == 'b') {}
		elseif( $a == 'c') {}
		elseif( $a == 'd') {}
		elseif( $a == 'e') {}
		elseif( $a == 'f') {}
		elseif( $a == 'g') {}
		elseif( $a == 'h') {}
		elseif( $a == 'i') {}
		elseif( $a == 'j') {}
		elseif( $a == 'k') {}
		elseif( $a == 'l') {}
		elseif( $a == 'm') {}
		elseif( $a == 'n') {}
		elseif( $a == 'o') {}
		elseif( $a == 'p') {}
		else {}
	}

	// bench function 2
	function doSwitch() {
		$a = 'z';
		switch( $a ) {
			case 'b': break;
			case 'c': break;
			case 'd': break;
			case 'e': break;
			case 'f': break;
			case 'g': break;
			case 'h': break;
			case 'i': break;
			case 'j': break;
			case 'k': break;
			case 'l': break;
			case 'm': break;
			case 'n': break;
			case 'o': break;
			case 'p': break;
			default:
		}
	}
}

$maintClass = 'bench_if_switch';
require_once( RUN_MAINTENANCE_IF_MAIN );
