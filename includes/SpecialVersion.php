<?php

function wfSpecialVersion()
{
	global $wgUser, $wgOut, $wgVersion;
	$fname = "wfSpecialVersion";

	$prefix = wfMsg( 'special_version_prefix' );
	$postfix = wfMsg( 'special_version_postfix' );
	if( $prefix != '&nbsp;' ) {
		$wgOut->addWikiText( $prefix );
	}
	$wgOut->addHTML( '
 <p>This wiki is powered by <b><a href="http://www.mediawiki.org/">MediaWiki</a></b>,  
 copyright (C) 2001-2004 by Magnus Manske, Brion Vibber, Lee Daniel Crocker,
 Tim Starling, Erik M&ouml;ller, and others.</p>
 
 <p>MediaWiki is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.</p>
 
 <p>MediaWiki is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.</p>
 
 <p>You should have received <a href="../COPYING">a copy of the GNU General Public License</a>
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 or <a href="http://www.gnu.org/copyleft/gpl.html">read it online</a></p>
	');
	if( $postfix != '&nbsp;' ) {
		$wgOut->addWikiText( $postfix );
	}
	$versions = array(
		"[http://wikipedia.sf.net/ MediaWiki]" => $wgVersion,
		"[http://www.php.net/ PHP]" => phpversion() . " (" . php_sapi_name() . ")",
		"[http://www.mysql.com/ MySQL]" => mysql_get_server_info()
 	);
	
	$out = "";
	foreach( $versions as $module => $ver ) {
		$out .= ":$module: $ver\n";
	}
	$wgOut->addWikiText( $out );
}
?>
