<?php
die("This script is not being maintained.");

# Checks translation of all language files
 
function wfLocalUrl() { return "";}
function wfLocalUrle() { return "";}

function check($arrayname, $lang, $text)
{
	$arraynameprinted = 0;
	
	global $count, $total;

	$msgarray = $arrayname . ucfirst( $lang );
	$msgarrayen = $arrayname . "En";

	eval( $text );
	if ( !is_array( $$msgarrayen ) ) {
		print "\nArray '$msgarrayen' not present\n";
		return;
	} elseif ( !is_array( $$msgarray ) ) {
		print "\nArray '$msgarray' not present\n";
		return;
	}

	foreach ( $$msgarrayen as $code => $msg ) {
		++$total;

		if ( ! array_key_exists( $code, $$msgarray ) ) {
			if (!$arraynameprinted) {
				print("\nIn array '$msgarray':\n");
				$arraynameprinted = 1;
			}
			
			if ( is_numeric( $code ) ) {
				print "$code ($msg)\n";
			} else {
				print "{$code}\n";
			}
			++$count;
		}
	}
}

function getLanguage( $lang )
{
	$fileName = "../languages/Language" . ucfirst( $lang ) . ".php";
	$file = fopen( $fileName, "r" );
	$text = fread( $file, filesize( $fileName ) );
	$clipPos = strpos( $text, "class Language" );
	$text = substr( $text, 0, $clipPos );
	$text = preg_replace( "/^<\?(php|)/", "", $text );
	$text = preg_replace( "/^include.*$/m", "", $text );
	
	return $text;
}
	
function checkLanguage( $lang, $enText ) 
{
	$text = $enText . getLanguage( $lang );
	check("wgLanguageNames", $lang, $text);
	check("wgNamespaceNames", $lang, $text);
	check("wgDefaultUserOptions", $lang, $text);
	check("wgQuickbarSettings", $lang, $text);
	check("wgSkinNames", $lang, $text);
	check("wgMathNames", $lang, $text);
	check("wgUserToggles", $lang, $text);
	check("wgWeekdayNames", $lang, $text);
	check("wgMonthNames", $lang, $text);
	check("wgMonthAbbreviations", $lang, $text);
	check("wgValidSpecialPages", $lang, $text);
	check("wgSysopSpecialPages", $lang, $text);
	check("wgDeveloperSpecialPages", $lang, $text);
	check("wgAllMessages", $lang, $text);
	check("wgMagicWords", $lang, $text);
}

if ( $argc > 1 ) {
	array_shift( $argv );
	$glob = implode( " ", $argv );
} else {
	$glob = "../languages/Language?*.php";
}

umask( 000 );
set_time_limit( 0 );
$count = $total = 0;
$enText = getLanguage( "" );
$filenames = glob( $glob );
$width = 80;
foreach ( $filenames as $filename ) {
	if ( preg_match( "/languages\/Language(.*)\.php/", $filename, $m ) ) {
		$lang = strtolower( $m[1] );
		if ( $lang != "utf8" ) {
			print "\n" . str_repeat( "-", $width );
			print "\n$lang\n";
			print str_repeat( "-", $width ) . "\n";
			checkLanguage( $lang, $enText );
		}
	}
}

print "\n" . str_repeat( "-", $width ) . "\n";
print "{$count} messages of {$total} not translated.\n";

