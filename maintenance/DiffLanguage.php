<?php
# MediaWiki web-based config/installation
# Copyright (C) 2004 Ashar Voultoiz <thoane@altern.org> and others
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

/**
 * Usage: php DiffLanguage.php [lang [file]]
 *
 * lang: Enter the language code following "Language" of the LanguageXX.php you
 * want to check. If using linux you might need to follow case aka Zh and not
 * zh.
 *
 * file: A php language file you want to include to compare mediawiki
 * Language{Lang}.php against (for example Special:Allmessages PHP output).
 *
 * The goal is to get a list of messages not yet localised in a languageXX.php
 * file using the language.php file as reference.
 * 
 * The script then print a list of wgAllMessagesXX keys that aren't localised, a
 * percentage of messages correctly localised and the number of messages to be
 * translated.
 * 
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** This script run from the commandline */
require_once( 'commandLine.inc' );

$wgLanguageCode = ucfirstlcrest($wgLanguageCode);

# FUNCTIONS
/** Return a given string with first letter upper case, the rest lowercase */
function ucfirstlcrest($string) {
	return strtoupper(substr($string,0,1)).strtolower(substr($string,1));
}

/** Ask user a language code */
function askLanguageCode() {
	global $wgLanguageCode;

	print "Enter the language you want to check [$wgLanguageCode]:";
	$input = ucfirstlcrest( readconsole() );
	if($input == '') $input = $wgLanguageCode;
	return $input;	
}


# MAIN ENTRY
if ( isset($args[0]) ) {
	$lang = ucfirstlcrest($args[0],1);
	// eventually against another language file
	if( isset($args[1])) include($args[1]) or die("File {$args[1]} not found.\n");
} else {
	// no lang given, prompt
	$lang = askLanguageCode();
}

if($lang != $wgLanguageCode) {
	$langFile = "$IP/languages/Language$lang.php";
	if (file_exists( $langFile ) ) {
		print "Including $langFile\n";
		include($langFile);
	} else die("ERROR: The file $langFile does not exist !\n");
}

/* ugly hack to load the correct array, if you have a better way
to achieve the same goal, recode it ! */
$foo = "wgAllMessages$lang";
$testme = &$$foo;
/* end of ugly hack */

# Get all references messages and check if they exist in the tested language
$i = 0;
print "\nChecking $lang localisation file against reference (en):\n----\n";
foreach($wgAllMessagesEn as $index => $localized)
{
	if(!(isset($testme[$index]))) {
		$i++;
		print "$lang: $index\n";
	}
}

echo "----\n";
echo "$lang language is complete at ".number_format((100 - $i/count($wgAllMessagesEn) * 100),2)."%\n";
echo "$i unlocalised messages of the ".count($wgAllMessagesEn)." messages available.\n";
print_r($time);