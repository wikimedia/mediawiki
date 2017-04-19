<?php
/**
 * Benchmark for strtr() vs str_replace().
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
 */

require_once __DIR__ . '/Benchmarker.php';

function bfNormalizeTitleStrTr( $str ) {
	return strtr( $str, '_', ' ' );
}

function bfNormalizeTitleStrReplace( $str ) {
	return str_replace( '_', ' ', $str );
}

/**
 * Maintenance script that benchmarks for strtr() vs str_replace().
 *
 * @ingroup Benchmark
 */
class BenchStrtrStrReplace extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark for strtr() vs str_replace().' );
	}

	public function execute() {
		$this->bench( [
			[ 'function' => [ $this, 'benchstrtr' ] ],
			[ 'function' => [ $this, 'benchstr_replace' ] ],
			[ 'function' => [ $this, 'benchstrtr_indirect' ] ],
			[ 'function' => [ $this, 'benchstr_replace_indirect' ] ],
		] );
	}

	protected function benchstrtr() {
		strtr( "[[MediaWiki:Some_random_test_page]]", "_", " " );
	}

	protected function benchstr_replace() {
		str_replace( "_", " ", "[[MediaWiki:Some_random_test_page]]" );
	}

	protected function benchstrtr_indirect() {
		bfNormalizeTitleStrTr( "[[MediaWiki:Some_random_test_page]]" );
	}

	protected function benchstr_replace_indirect() {
		bfNormalizeTitleStrReplace( "[[MediaWiki:Some_random_test_page]]" );
	}
}

$maintClass = 'BenchStrtrStrReplace';
require_once RUN_MAINTENANCE_IF_MAIN;
