<?php
/**
 * Check to see if all messages have been translated into
 * the selected language. To run this script, you must have
 * a working installation, and it checks the selected language
 * of that installation.
 *
 * The enhanced version will check more arrays than just
 * wgAllMessages
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
function check($arrayname) {
	$arraynameprinted = 0;
	
	global $count, $total, $wgLanguageCode;

	$msgarray = $arrayname . ucfirst( $wgLanguageCode );
	$msgarrayen = $arrayname . "En";

	global $$msgarray;
	global $$msgarrayen;

	foreach ( $$msgarrayen as $code => $msg ) {
		++$total;

		if ( ! array_key_exists( $code, $$msgarray ) ) {
			if (!$arraynameprinted) {
				print("\nIn array '$msgarray':\n");
				$arraynameprinted = 1;
			}

			print "{$code}\n";
			++$count;
		}
	}
}

if ( ! is_readable( "../LocalSettings.php" ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

$DP = "../includes";
require_once( "../LocalSettings.php" );

if ( "en" == $wgLanguageCode ) {
	print "Current selected language is English. Cannot check translations.\n";
	exit();
}
$include = "Language" . ucfirst( $wgLanguageCode ) . ".php";
if ( ! is_readable( "{$IP}/{$include}" ) ) {
	print "Translation file \"{$include}\" not found in installation directory.\n" .
	  "You must have the software installed to run this script.\n";
	exit();
}

umask( 000 );
set_time_limit( 0 );

require_once( "{$IP}/Setup.php" );
$wgTitle = Title::newFromText( "Translation checking script" );
$wgCommandLineMode = true;

$count = $total = 0;


check("wgLanguageNames");
check("wgNamespaceNames");
check("wgDefaultUserOptions");
check("wgQuickbarSettings");
check("wgSkinNames");
check("wgMathNames");
check("wgUserToggles");
check("wgWeekdayNames");
check("wgMonthNames");
check("wgMonthAbbreviations");
check("wgValidSpecialPages");
check("wgSysopSpecialPages");
check("wgDeveloperSpecialPages");
check("wgAllMessages");

print "{$count} messages of {$total} not translated.\n";
