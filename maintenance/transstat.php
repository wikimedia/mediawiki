<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @bug 2499
 *
 * Output is posted from time to time on:
 * http://meta.wikimedia.org/wiki/Localization_statistics
 */

/** */
require_once('commandLine.inc');

$langs = array();
$dir = opendir("$IP/languages");
while ($file = readdir($dir)) {
	if (preg_match("/Language(.*?)\.php$/", $file, $m)) {
		$langs[] = $m[1];
	}
}

sort($langs);

// Cleanup
foreach($langs as $key => $lang) {
	if ($lang == 'Utf8' || $lang == '' || $lang == 'Converter')
		unset($langs[$key]);
}


$msgs = array();
foreach($langs as $lang) {
	// Since they aren't loaded by default..
	require_once( 'languages/Language' . $lang . '.php' );
	$arr = 'wgAllMessages' . $lang;
	if (@is_array($$arr)) { // Some of them don't have a message array 
		$msgs[$wgContLang->lcfirst($lang)] = array(
			'total' => count($$arr),
			'redundant' => redundant($$arr),
		);
	} else {
		$msgs[$wgContLang->lcfirst($lang)] = array(
			'total' => 0,
			'redundant' => 0,
		);
	}
}

// wiki syntax header
$out = "{| border=2 cellpadding=4 cellspacing=0 style=\"background: #f9f9f9; border: 1px #aaa solid; border-collapse: collapse;\" width=100%\n";
$out .= beginul();
$out .= li('Language', true);
$out .= li('Translated', true);
$out .= li('%', true);
$out .= li('Untranslated', true);
$out .= li('%', true);
$out .= li('Redundant', true);
$out .= li('%', true);
$out .= endul();

// generate table rows using wikisyntax
foreach($msgs as $lang => $stats) {
	$out .= beginul();
	$out .= li($wgContLang->getLanguageName(strtr($lang, '_', '-')) . " ($lang)"); // Language
	$out .= li($stats['total'] . '/' . $msgs['en']['total']); // Translated
	$out .= li(percent($stats['total'], $msgs['en']['total'])); // % Translated
	$out .= li($msgs['en']['total'] - $stats['total']); // Untranslated
	$out .= li(percent($msgs['en']['total'] - $stats['total'], $msgs['en']['total'])); // % Untranslated
	$out .= li($stats['redundant'] . '/' . $stats['total']); // Redundant
	$out .= li(percent($stats['redundant'],  $stats['total'])); // % Redundant
	$out .= endul();
}
$out = substr($out, 0, -3) . "|}\n";
echo $out;

function beginul() { return ''; }
function endul() { return "|-\n"; }
function li($in, $heading = false) { return ($heading ? '!' : '|') . " $in\n"; }
function percent($subset, $total, $accuracy = 2) { return @sprintf( '%.' . $accuracy . 'f%%', 100 * $subset / $total ); }

// FIXME: This takes an obscene amount of time
function redundant(&$arr) {
	global $wgAllMessagesEn;
	
	$redundant = 0;
	foreach(array_keys($arr) as $key) {
		if ( ! array_key_exists( $key, $wgAllMessagesEn) )
			++$redundant;
	}
	return $redundant;
}
