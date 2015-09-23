<?php
/**
 * This script generates UniNormalData.inc from the Unicode Character Database
 * and supplementary files.
 *
 * Copyright (C) 2004 Brion Vibber <brion@pobox.com>
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

use UtfNormal\Utils;

if ( PHP_SAPI != 'cli' ) {
	die( "Run me from the command line please.\n" );
}

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

function download( $file, $url ) {
	print "Downloading data from $url...\n";
	$fp = fopen( $file, 'w+' );
	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_FILE, $fp );
	curl_exec( $ch );
	curl_close( $ch );
	fclose( $fp );
}

function getFilePointer( $file, $url ) {
	if ( in_array( '--fetch', $_SERVER['argv'] ) ) {
		download( $file, $url );
	} elseif ( !file_exists( $file ) ) {
		print "Can't open $file for reading.\n";
		print "If necessary, fetch this file from the internet:\n";
		print "$url\n";
		print "Or re-run this script with --fetch\n";
		exit( -1 );
	}

	$fp = fopen( $file, "rt" );
	if ( !$fp ) {
		// Eh?
		print "Can't open $file for reading.\n";
		exit( -1 );
	}

	return $fp;
}


$in = getFilePointer(
	__DIR__ . "/data/DerivedNormalizationProps.txt",
	'http://www.unicode.org/Public/UNIDATA/DerivedNormalizationProps.txt'
);
print "Initializing normalization quick check tables...\n";
$checkNFC = array();
while ( false !== ( $line = fgets( $in ) ) ) {
	$matches = array();
	if ( preg_match(
		'/^([0-9A-F]+)(?:..([0-9A-F]+))?\s*;\s*(NFC_QC)\s*;\s*([MN])/',
		$line,
		$matches )
	) {
		list( $junk, $first, $last, $prop, $value ) = $matches;
		#print "$first $last $prop $value\n";
		if ( !$last ) {
			$last = $first;
		}

		$lastInDecimal = hexdec( $last );
		for ( $i = hexdec( $first ); $i <= $lastInDecimal; $i++ ) {
			$char = Utils::codepointToUtf8( $i );
			$checkNFC[$char] = $value;
		}
	}
}
fclose( $in );

$in = getFilePointer(
	__DIR__ . "/data/CompositionExclusions.txt",
	'http://www.unicode.org/Public/UNIDATA/CompositionExclusions.txt'
);
$exclude = array();
while ( false !== ( $line = fgets( $in ) ) ) {
	if ( preg_match( '/^([0-9A-F]+)/i', $line, $matches ) ) {
		$codepoint = $matches[1];
		$source = Utils::codepointToUtf8( hexdec( $codepoint ) );
		$exclude[$source] = true;
	}
}
fclose( $in );

$in = getFilePointer(
	__DIR__ . "/data/UnicodeData.txt",
	'http://www.unicode.org/Public/UNIDATA/UnicodeData.txt'
);
$compatibilityDecomp = array();
$canonicalDecomp = array();
$canonicalComp = array();
$combiningClass = array();
$total = 0;
$compat = 0;
$canon = 0;

print "Reading character definitions...\n";
while ( false !== ( $line = fgets( $in ) ) ) {
	$columns = explode( ';', $line );
	$codepoint = $columns[0];
	$name = $columns[1];
	$canonicalCombiningClass = $columns[3];
	$decompositionMapping = $columns[5];

	$source = Utils::codepointToUtf8( hexdec( $codepoint ) );

	if ( $canonicalCombiningClass != 0 ) {
		$combiningClass[$source] = intval( $canonicalCombiningClass );
	}

	if ( $decompositionMapping === '' ) continue;
	if ( preg_match( '/^<(.+)> (.*)$/', $decompositionMapping, $matches ) ) {
		# Compatibility decomposition
		$canonical = false;
		$decompositionMapping = $matches[2];
		$compat++;
	} else {
		$canonical = true;
		$canon++;
	}
	$total++;
	$dest = Utils::hexSequenceToUtf8( $decompositionMapping );

	$compatibilityDecomp[$source] = $dest;
	if ( $canonical ) {
		$canonicalDecomp[$source] = $dest;
		if ( empty( $exclude[$source] ) ) {
			$canonicalComp[$dest] = $source;
		}
	}
	#print "$codepoint | $canonicalCombiningClasses | $decompositionMapping\n";
}
fclose( $in );

print "Recursively expanding canonical mappings...\n";
$changed = 42;
$pass = 1;
while ( $changed > 0 ) {
	print "pass $pass\n";
	$changed = 0;
	foreach ( $canonicalDecomp as $source => $dest ) {
		$newDest = preg_replace_callback(
			'/([\xc0-\xff][\x80-\xbf]+)/',
			'callbackCanonical',
			$dest );
		if ( $newDest === $dest ) continue;
		$changed++;
		$canonicalDecomp[$source] = $newDest;
	}
	$pass++;
}

print "Recursively expanding compatibility mappings...\n";
$changed = 42;
$pass = 1;
while ( $changed > 0 ) {
	print "pass $pass\n";
	$changed = 0;
	foreach ( $compatibilityDecomp as $source => $dest ) {
		$newDest = preg_replace_callback(
			'/([\xc0-\xff][\x80-\xbf]+)/',
			'callbackCompat',
			$dest );
		if ( $newDest === $dest ) continue;
		$changed++;
		$compatibilityDecomp[$source] = $newDest;
	}
	$pass++;
}

print "$total decomposition mappings ($canon canonical, $compat compatibility)\n";

$out = fopen( dirname( __DIR__ ) . "/src/UtfNormalData.inc", "wt" );
if ( $out ) {
	$serCombining = Utils::escapeSingleString( serialize( $combiningClass ) );
	$serComp = Utils::escapeSingleString( serialize( $canonicalComp ) );
	$serCanon = Utils::escapeSingleString( serialize( $canonicalDecomp ) );
	$serCheckNFC = Utils::escapeSingleString( serialize( $checkNFC ) );
	$outdata = "<" . "?php
/**
 * This file was automatically generated -- do not edit!
 * Run UtfNormalGenerate.php to create this file again (make clean && make)
 *
 * @file
 */
// @codingStandardsIgnoreFile

UtfNormal\Validator::\$utfCombiningClass = unserialize( '$serCombining' );
UtfNormal\Validator::\$utfCanonicalComp = unserialize( '$serComp' );
UtfNormal\Validator::\$utfCanonicalDecomp = unserialize( '$serCanon' );
UtfNormal\Validator::\$utfCheckNFC = unserialize( '$serCheckNFC' );
\n";
	fputs( $out, $outdata );
	fclose( $out );
	print "Wrote out UtfNormalData.inc\n";
} else {
	print "Can't create file UtfNormalData.inc\n";
	exit( -1 );
}

$out = fopen( dirname( __DIR__ ) . "/src/UtfNormalDataK.inc", "wt" );
if ( $out ) {
	$serCompat = Utils::escapeSingleString( serialize( $compatibilityDecomp ) );
	$outdata = "<" . "?php
/**
 * This file was automatically generated -- do not edit!
 * Run UtfNormalGenerate.php to create this file again (make clean && make)
 *
 * @file
 */
// @codingStandardsIgnoreFile

UtfNormal\Validator::\$utfCompatibilityDecomp = unserialize( '$serCompat' );
\n";
	fputs( $out, $outdata );
	fclose( $out );
	print "Wrote out UtfNormalDataK.inc\n";
	exit( 0 );
} else {
	print "Can't create file UtfNormalDataK.inc\n";
	exit( -1 );
}

# ---------------

function callbackCanonical( $matches ) {
	// @codingStandardsIgnoreStart MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
	global $canonicalDecomp;
	// @codingStandardsIgnoreEnd

	if ( isset( $canonicalDecomp[$matches[1]] ) ) {
		return $canonicalDecomp[$matches[1]];
	}

	return $matches[1];
}

function callbackCompat( $matches ) {
	// @codingStandardsIgnoreStart MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
	global $compatibilityDecomp;
	// @codingStandardsIgnoreEnd

	if ( isset( $compatibilityDecomp[$matches[1]] ) ) {
		return $compatibilityDecomp[$matches[1]];
	}

	return $matches[1];
}
