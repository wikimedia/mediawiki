<?php
# Copyright (C) 2004 Magnus Manske <magnus.manske@web.de>
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
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialGeo( $page = '' ) {
	global $wgOut, $wgLang, $wgRequest;
	$coordinates = htmlspecialchars( $wgRequest->getText( 'coordinates' ) );
	$coordinates = explode ( ":" , $coordinates ) ;
	$ns = array_shift ( $coordinates ) ;
	$ew = array_shift ( $coordinates ) ;
	if ( 0 < count ( $coordinates ) ) $zoom = length ( array_shift ( $coordinates ) ) ;
	else $zoom = 6 ;
	
	$ns = explode ( "." , $ns ) ;
	$ew = explode ( "." , $ew ) ;
	while ( count ( $ns ) < 3 ) $ns[] = "0" ;
	while ( count ( $ew ) < 3 ) $ew[] = "0" ;
	
	$mapquest = "http://www.mapquest.com/maps/map.adp?latlongtype=decimal&latitude={$ns[0]}.{$ns[1]}&longitude={$ew[0]}.{$ew[1]}&zoom={$zoom}" ;
	$mapquest = "<a href=\"{$mapquest}\">Mapquest</a>" ;
	
	
	$wgOut->addHTML( "{$mapquest}" ) ;
/*	
	if( $wgRequest->getVal( 'action' ) == 'submit') {
		$page = $wgRequest->getText( 'pages' );
		$curonly = $wgRequest->getCheck( 'curonly' );
	} else {
		# Pre-check the 'current version only' box in the UI
		$curonly = true;
	}
	
	if( $page != "" ) {
		header( "Content-type: application/xml; charset=utf-8" );
		$pages = explode( "\n", $page );
		$xml = pages2xml( $pages, $curonly );
		echo $xml;
		wfAbruptExit();
	}
	
	$wgOut->addWikiText( wfMsg( "exporttext" ) );
	$titleObj = Title::makeTitle( NS_SPECIAL, "Export" );
	$action = $titleObj->escapeLocalURL();
	$wgOut->addHTML( "
<form method='post' action=\"$action\">
<input type='hidden' name='action' value='submit' />
<textarea name='pages' cols='40' rows='10'></textarea><br />
<label><input type='checkbox' name='curonly' value='true' checked='checked' />
" . wfMsg( "exportcuronly" ) . "</label><br />
<input type='submit' />
</form>
" );
*/
}

?>
