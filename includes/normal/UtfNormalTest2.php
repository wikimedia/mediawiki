#!/usr/bin/php
<?php

if( php_sapi_name() != 'cli' ) {
	die( "Run me from the command line please.\n" );
}

// From http://unicode.org/Public/UNIDATA/NormalizationTest.txt
$file = "NormalizationTest.txt";
$sep = ';';
$comment = "#";
$f = fopen($file, "r");

/**
 * The following section will be used for testing different normalization methods.
 * - Pure PHP
     ~ no assertion errors
     ~ 6.25 minutes

 * - php_utfnormal.so or intl extension: both are wrappers around
     libicu so we list the version of libicu when making the
     comparison

 * - libicu Ubuntu 3.8.1-3ubuntu1.1 php 5.2.6-3ubuntu4.5
     ~ 2200 assertion errors
     ~ 5 seconds
	 ~ output: http://paste2.org/p/921566

 * - libicu Ubuntu 4.2.1-3 php 5.3.2-1ubuntu4.2
     ~ 1384 assertion errors
	 ~ 15 seconds
	 ~ output: http://paste2.org/p/921435

 * - libicu Debian 4.4.1-5 php 5.3.2-1ubuntu4.2
     ~ no assertion errors
	 ~ 13 seconds

 * - Tests comparing pure PHP output with libicu output were added
     later and slow down the runtime.
 */

require_once("./UtfNormal.php");
function normalize_form_c($c)      { return UtfNormal::toNFC($c);  }
function normalize_form_c_php($c)  { return UtfNormal::toNFC($c, "php");  }
function normalize_form_d($c)      { return UtfNormal::toNFD($c);  }
function normalize_form_d_php($c)  { return UtfNormal::toNFD($c, "php");  }
function normalize_form_kc($c)     { return UtfNormal::toNFKC($c); }
function normalize_form_kc_php($c) { return UtfNormal::toNFKC($c, "php"); }
function normalize_form_kd($c)     { return UtfNormal::toNFKD($c); }
function normalize_form_kd_php($c) { return UtfNormal::toNFKD($c, "php"); }

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);
assert_options(ASSERT_CALLBACK, 'my_assert');

function my_assert( $file, $line, $code ) {
	global $col, $count, $lineNo;
	echo "Assertion that '$code' failed on line $lineNo ($col[5])\n";
}

$count = 0;
$lineNo = 0;
if( $f !== false ) {
	while( ( $col = getRow( $f ) ) !== false ) {
		$lineNo++;

		if(count($col) == 6) {
			$count++;
			if( $count % 100 === 0 ) echo "Count: $count\n";
		} else {
			continue;
		}

		# verify that the pure PHP version is correct
		$NFCc1  = normalize_form_c($col[0]);
		$NFCc1p = normalize_form_c_php($col[0]);
		assert('$NFCc1 === $NFCc1p');
		$NFCc2  = normalize_form_c($col[1]);
		$NFCc2p = normalize_form_c_php($col[1]);
		assert('$NFCc2 === $NFCc2p');
		$NFCc3  = normalize_form_c($col[2]);
		$NFCc3p = normalize_form_c_php($col[2]);
		assert('$NFCc3 === $NFCc3p');
		$NFCc4  = normalize_form_c($col[3]);
		$NFCc4p = normalize_form_c_php($col[3]);
		assert('$NFCc4 === $NFCc4p');
		$NFCc5  = normalize_form_c($col[4]);
		$NFCc5p = normalize_form_c_php($col[4]);
		assert('$NFCc5 === $NFCc5p');

		$NFDc1  = normalize_form_d($col[0]);
		$NFDc1p = normalize_form_d_php($col[0]);
		assert('$NFDc1 === $NFDc1p');
		$NFDc2  = normalize_form_d($col[1]);
		$NFDc2p = normalize_form_d_php($col[1]);
		assert('$NFDc2 === $NFDc2p');
		$NFDc3  = normalize_form_d($col[2]);
		$NFDc3p = normalize_form_d_php($col[2]);
		assert('$NFDc3 === $NFDc3p');
		$NFDc4  = normalize_form_d($col[3]);
		$NFDc4p = normalize_form_d_php($col[3]);
		assert('$NFDc4 === $NFDc4p');
		$NFDc5  = normalize_form_d($col[4]);
		$NFDc5p = normalize_form_d_php($col[4]);
		assert('$NFDc5 === $NFDc5p');

		$NFKDc1  = normalize_form_kd($col[0]);
		$NFKDc1p = normalize_form_kd_php($col[0]);
		assert('$NFKDc1 === $NFKDc1p');
		$NFKDc2  = normalize_form_kd($col[1]);
		$NFKDc2p = normalize_form_kd_php($col[1]);
		assert('$NFKDc2 === $NFKDc2p');
		$NFKDc3  = normalize_form_kd($col[2]);
		$NFKDc3p = normalize_form_kd_php($col[2]);
		assert('$NFKDc3 === $NFKDc3p');
		$NFKDc4  = normalize_form_kd($col[3]);
		$NFKDc4p = normalize_form_kd_php($col[3]);
		assert('$NFKDc4 === $NFKDc4p');
		$NFKDc5  = normalize_form_kd($col[4]);
		$NFKDc5p = normalize_form_kd_php($col[4]);
		assert('$NFKDc5 === $NFKDc5p');

		$NFKCc1  = normalize_form_kc($col[0]);
		$NFKCc1p = normalize_form_kc_php($col[0]);
		assert('$NFKCc1 === $NFKCc1p');
		$NFKCc2  = normalize_form_kc($col[1]);
		$NFKCc2p = normalize_form_kc_php($col[1]);
		assert('$NFKCc2 === $NFKCc2p');
		$NFKCc3  = normalize_form_kc($col[2]);
		$NFKCc3p = normalize_form_kc_php($col[2]);
		assert('$NFKCc3 === $NFKCc3p');
		$NFKCc4  = normalize_form_kc($col[3]);
		$NFKCc4p = normalize_form_kc_php($col[3]);
		assert('$NFKCc4 === $NFKCc4p');
		$NFKCc5  = normalize_form_kc($col[4]);
		$NFKCc5p = normalize_form_kc_php($col[4]);
		assert('$NFKCc5 === $NFKCc5p');

		# c2 ==	 NFC(c1) ==	 NFC(c2) ==	 NFC(c3)
		assert('$col[1] === $NFCc1');
		assert('$col[1] === $NFCc2');
		assert('$col[1] === $NFCc3');

		# c4 ==	 NFC(c4) ==	 NFC(c5)
		assert('$col[3] === $NFCc4');
		assert('$col[3] === $NFCc5');

		# c3 ==	 NFD(c1) ==	 NFD(c2) ==	 NFD(c3)
		assert('$col[2] === $NFDc1');
		assert('$col[2] === $NFDc2');
		assert('$col[2] === $NFDc3');

		# c5 ==	 NFD(c4) ==	 NFD(c5)
		assert('$col[4] === $NFDc4');
		assert('$col[4] === $NFDc5');

		# c4 == NFKC(c1) == NFKC(c2) == NFKC(c3) == NFKC(c4) == NFKC(c5)
		assert('$col[3] === $NFKCc1');
		assert('$col[3] === $NFKCc2');
		assert('$col[3] === $NFKCc3');
		assert('$col[3] === $NFKCc4');
		assert('$col[3] === $NFKCc5');

		# c5 == NFKD(c1) == NFKD(c2) == NFKD(c3) == NFKD(c4) == NFKD(c5)
		assert('$col[4] === $NFKDc1');
		assert('$col[4] === $NFKDc2');
		assert('$col[4] === $NFKDc3');
		assert('$col[4] === $NFKDc4');
		assert('$col[4] === $NFKDc5');
	}
}
echo "done.\n";

// Compare against http://en.wikipedia.org/wiki/UTF-8#Description
function unichr($c) {
	if ($c <= 0x7F) {
		return chr($c);
	} else if ($c <= 0x7FF) {
		return chr(0xC0 | $c >> 6) . chr(0x80 | $c & 0x3F);
	} else if ($c <= 0xFFFF) {
		return chr(0xE0 | $c >> 12) . chr(0x80 | $c >> 6 & 0x3F)
			. chr(0x80 | $c & 0x3F);
	} else if ($c <= 0x10FFFF) {
		return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F)
			. chr(0x80 | $c >> 6 & 0x3F)
			. chr(0x80 | $c & 0x3F);
	} else {
		return false;
	}
}

function unistr($c) {
	return implode("", array_map("unichr", array_map("hexdec", explode(" ", $c))));
}

function getRow( $f ) {
	global $comment, $sep;

	$row = fgets( $f );
	if( $row === false ) return false;
	$row = rtrim($row);
	$pos = strpos( $row, $comment );
	$pos2 = strpos( $row, ")" );
	if( $pos === 0 ) return array($row);
	$c = "";

	if( $pos ) {
		if($pos2) $c = substr( $row, $pos2 + 2 );
		else	  $c = substr( $row, $pos );
		$row = substr( $row, 0, $pos );
	}

	$ret = array();
	foreach(explode( $sep, $row ) as $ent) {
		if(trim($ent) !== "") {
			$ret[] = unistr($ent);
		}
	}
	$ret[] = $c;

	return $ret;
}