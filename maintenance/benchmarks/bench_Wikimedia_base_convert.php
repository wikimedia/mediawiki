<?php
/**
 * Benchmark for Wikimedia\base_convert()
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
 * @author Tyler Romeo
 */

require_once __DIR__ . '/../includes/Benchmarker.php';

/**
 * Maintenance script that benchmarks Wikimedia\base_convert().
 *
 * Code exists in vendor repository brought in via composer.
 *
 * @ingroup Benchmark
 */
class BenchWikimediaBaseConvert extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark for Wikimedia\base_convert.' );
		$this->addOption( "inbase", "Input base", false, true );
		$this->addOption( "outbase", "Output base", false, true );
		$this->addOption( "length", "Size in digits to generate for input", false, true );
	}

	public function execute() {
		$inbase = $this->getOption( "inbase", 36 );
		$outbase = $this->getOption( "outbase", 16 );
		$length = $this->getOption( "length", 128 );
		$number = self::makeRandomNumber( $inbase, $length );

		$this->bench( [
			[
				'function' => 'Wikimedia\base_convert',
				'args' => [ $number, $inbase, $outbase, 0, true, 'php' ]
			],
			[
				'function' => 'Wikimedia\base_convert',
				'args' => [ $number, $inbase, $outbase, 0, true, 'bcmath' ]
			],
			[
				'function' => 'Wikimedia\base_convert',
				'args' => [ $number, $inbase, $outbase, 0, true, 'gmp' ]
			],
		] );
	}

	protected static function makeRandomNumber( $base, $length ) {
		$baseChars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$res = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$res .= $baseChars[mt_rand( 0, $base - 1 )];
		}

		return $res;
	}
}

$maintClass = BenchWikimediaBaseConvert::class;
require_once RUN_MAINTENANCE_IF_MAIN;
