<?php
/**
 * Approximate benchmark for some basic operations.
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @ingroup UtfNormal
 */

use UtfNormal\Validator;

if ( PHP_SAPI != 'cli' ) {
	die( "Run me from the command line please.\n" );
}

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

define( 'BENCH_CYCLES', 5 );

$testfiles = array(
	__DIR__ . '/testdata/washington.txt' => 'English text',
	__DIR__ . '/testdata/berlin.txt' => 'German text',
	__DIR__ . '/testdata/bulgakov.txt' => 'Russian text',
	__DIR__ . '/testdata/tokyo.txt' => 'Japanese text',
	__DIR__ . '/testdata/young.txt' => 'Korean text'
);
$normalizer = new Validator;
Validator::loadData();
foreach ( $testfiles as $file => $desc ) {
	benchmarkTest( $normalizer, $file, $desc );
}

# -------

function benchmarkTest( &$u, $filename, $desc ) {
	print "Testing $filename ($desc)...\n";
	$data = file_get_contents( $filename );
	$forms = array(
#		'placebo',
		'cleanUp',
		'toNFC',
#		'toNFKC',
#		'toNFD', 'toNFKD',
		'NFC',
#		'NFKC',
#		'NFD', 'NFKD',
		array( 'fastDecompose', 'fastCombiningSort', 'fastCompose' ),
#		'quickIsNFC', 'quickIsNFCVerify',
	);

	foreach ( $forms as $form ) {
		if ( is_array( $form ) ) {
			$str = $data;
			foreach ( $form as $step ) {
				$str = benchmarkForm( $u, $str, $step );
			}
		} else {
			benchmarkForm( $u, $data, $form );
		}
	}
}

function benchmarkForm( &$u, &$data, $form ) {
	#$start = microtime( true );
	for ( $i = 0; $i < BENCH_CYCLES; $i++ ) {
		$start = microtime( true );
		$out = $u->$form( $data, Validator::$utfCanonicalDecomp );
		$deltas[] = ( microtime( true ) - $start );
	}
	#$delta = (microtime( true ) - $start) / BENCH_CYCLES;
	sort( $deltas );
	$delta = $deltas[0]; # Take shortest time

	$rate = intval( strlen( $data ) / $delta );
	$same = ( 0 == strcmp( $data, $out ) );

	printf( " %20s %6.1fms %12s bytes/s (%s)\n",
		$form,
		$delta * 1000.0,
		number_format( $rate ),
		( $same ? 'no change' : 'changed' ) );

	return $out;
}
