<?php
/**
 * Give information about the version MediaWiki, PHP, and the database
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * constructor
 */
function wfSpecialVersion() {
	global $wgOut, $wgVersion;
	
	$dbr =& wfGetDB( DB_SLAVE );
	
	$wgOut->addWikiText( "
<div dir='ltr'>
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

You should have received [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public License]
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
or [http://www.gnu.org/copyleft/gpl.html read it online]

* [http://www.mediawiki.org/ MediaWiki]: $wgVersion
* [http://www.php.net/ PHP]: " . phpversion() . " (" . php_sapi_name() . ")
* " . $dbr->getSoftwareLink() . ": " . $dbr->getServerVersion() . "
</div>" );
}
?>
