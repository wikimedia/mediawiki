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

function wfSpecialVersion()
{
	global $wgUser, $wgOut, $wgVersion;
	$fname = "wfSpecialVersion";

	$versions = array(
		"[http://wikipedia.sf.net/ MediaWiki]" => $wgVersion,
		"[http://www.php.net/ PHP]" => phpversion(),
		"[http://www.mysql.com/ MySQL]" => mysql_get_server_info()
	);
	
	$out = "";
	foreach( $versions as $module => $ver ) {
		$out .= ":$module: $ver\n";
	}
	$wgOut->addWikiText( $out );
	
	return;
}

?>
