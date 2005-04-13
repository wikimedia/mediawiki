<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * constructor
 */
function wfSpecialVersion() {
	global $wgUser, $wgOut, $wgVersion, $wgScriptPath;
	$fname = 'wfSpecialVersion';

	$prefix = wfMsg( 'special_version_prefix' );
	$postfix = wfMsg( 'special_version_postfix' );
	if( $prefix != '&nbsp;' ) {
		$wgOut->addWikiText( $prefix );
	}
	$wgOut->addWikiText( "
This wiki is powered by '''[http://www.mediawiki.org/ MediaWiki]''',  
copyright (C) 2001-2005 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
Tim Starling, Erik Möller, and others.
 
MediaWiki is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
 
MediaWiki is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received [{{SERVER}}$wgScriptPath/COPYING a copy of the GNU General Public License]
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
or [http://www.gnu.org/copyleft/gpl.html read it online]");
	if( $postfix != '&nbsp;' ) {
		$wgOut->addWikiText( $postfix );
	}
	$versions = array(
		"[http://wikipedia.sf.net/ MediaWiki]" => $wgVersion,
		"[http://www.php.net/ PHP]" => phpversion() . " (" . php_sapi_name() . ")"
 	);
 	
 	$dbr =& wfGetDB( DB_SLAVE );
 	$dblink = $dbr->getSoftwareLink();
 	$versions[$dblink] = $dbr->getServerVersion();
	
	$out = '';
	foreach( $versions as $module => $ver ) {
		$out .= "*$module: $ver\n";
	}
	$wgOut->addWikiText( $out );
}
?>
