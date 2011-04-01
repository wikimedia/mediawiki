<?php
/**
 * Approximate benchmark for some basic operations.
 * Runs large chunks of text through cleanup with a lowish memory limit,
 * to test regression on mem usage (bug 28146)
 *
 * Copyright © 2004-2011 Brion Vibber <brion@wikimedia.org>
 * http://www.mediawiki.org/
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

if( isset( $_SERVER['argv'] ) && in_array( '--icu', $_SERVER['argv'] ) ) {
	dl( 'php_utfnormal.so' );
}

require_once 'UtfNormalUtil.php';
require_once 'UtfNormal.php';

define( 'BENCH_CYCLES', 1 );
define( 'BIGSIZE', 1024 * 1024 * 10); // 10m
ini_set('memory_limit', BIGSIZE + 120 * 1024 * 1024);

if( php_sapi_name() != 'cli' ) {
	die( "Run me from the command line please.\n" );
}

$testfiles = array(
	'testdata/washington.txt' => 'English text',
	'testdata/berlin.txt' => 'German text',
	'testdata/bulgakov.txt' => 'Russian text',
	'testdata/tokyo.txt' => 'Japanese text',
	'testdata/young.txt' => 'Korean text'
);
$normalizer = new UtfNormal;
UtfNormal::loadData();
foreach( $testfiles as $file => $desc ) {
	benchmarkTest( $normalizer, $file, $desc );
}

# -------

function benchmarkTest( &$u, $filename, $desc ) {
	print "Testing $filename ($desc)...\n";
	$data = file_get_contents( $filename );
	$all = $data;
	while (strlen($all) < BIGSIZE) {
		$all .= $all;
	}
	$data = $all;
	echo "Data is " . strlen($data) . " bytes.\n";
	$forms = array(
        'quickIsNFCVerify',
		'cleanUp',
		);
	foreach( $forms as $form ) {
		if( is_array( $form ) ) {
			$str = $data;
			foreach( $form as $step ) {
				$str = benchmarkForm( $u, $str, $step );
			}
		} else {
			benchmarkForm( $u, $data, $form );
		}
	}
}

function benchTime(){
	$st = explode( ' ', microtime() );
	return (float)$st[0] + (float)$st[1];
}

function benchmarkForm( &$u, &$data, $form ) {
	#$start = benchTime();
	for( $i = 0; $i < BENCH_CYCLES; $i++ ) {
		$start = benchTime();
		$out = $u->$form( $data, UtfNormal::$utfCanonicalDecomp );
		$deltas[] = (benchTime() - $start);
	}
	#$delta = (benchTime() - $start) / BENCH_CYCLES;
	sort( $deltas );
	$delta = $deltas[0]; # Take shortest time

	$rate = intval( strlen( $data ) / $delta );
	$same = (0 == strcmp( $data, $out ) );

	printf( " %20s %6.1fms %12s bytes/s (%s)\n",
		$form,
		$delta*1000.0,
		number_format( $rate ),
		($same ? 'no change' : 'changed' ) );
	return $out;
}
