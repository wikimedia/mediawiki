<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

require_once 'UtfNormalUtil.php';
require_once 'UtfNormal.php';

if( php_sapi_name() != 'cli' ) {
	die( "Run me from the command line please.\n" );
}

$testfiles = array(
	'testdata/washington.txt' => 'English text',
	'testdata/berlin.txt' => 'German text',
	'testdata/tokyo.txt' => 'Japanese text',
	'testdata/byzantium.txt' => 'Korean text'
);
$normalizer = new UtfNormal;
foreach( $testfiles as $file => $desc ) {
	benchmarkTest( $normalizer, $file, $desc );
}

# -------

function benchmarkTest( &$u, $filename, $desc ) {
	print "Testing $filename ($desc)...\n";
	$data = file_get_contents( $filename );
	$forms = array( 'placebo',
		'fastDecompose', 'fastCombiningSort', 'fastCompose',
		'toNFD', 'toNFKD', 'toNFC', 'toNFKC',
		'NFD', 'NFKD', 'NFC', 'NFKC' );
	foreach( $forms as $form ) {
		benchmarkForm( $u, $data, $form );
	}
}

function benchTime(){
	$st = explode( ' ', microtime() );
	return (float)$st[0] + (float)$st[1];
}

function benchmarkForm( &$u, &$data, $form ) {
	global $utfCanonicalDecomp;
	$start = benchTime();
	$out = $u->$form( $data, $utfCanonicalDecomp );
	$delta = benchTime() - $start;
	$same = (0 == strcmp( $data, $out ) );
	
	printf( " %4s %1.4fs (%s)\n", $form, $delta, ($same ? 'no change' : 'changed' ) );
}

?>