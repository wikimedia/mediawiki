<?php
# Copyright (C) 2008 Aaron Schulz
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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 *
 * @addtogroup SpecialPage
 */

/**
 * constructor
 */
function wfSpecialLog( $par = '' ) {
	global $wgRequest, $wgOut, $wgUser;
	# Get parameters
	$type = $wgRequest->getVal( 'type', $par );
	$user = $wgRequest->getText( 'user' );
	$title = $wgRequest->getText( 'page' );
	$pattern = $wgRequest->getBool( 'pattern' );
	# Create a LogPager item to get the results and a LogEventList
	# item to format them...
	$loglist = new LogEventList( $wgUser->getSkin() );
	$pager = new LogPager( $loglist, $type, $user, $title, $pattern );
	# Set title and add header
	$loglist->showHeader( $wgOut, $pager->getType() );
	# Show form options
	$loglist->showOptions( $wgOut, $pager->getType(), $pager->getUser(), $pager->getPage(), $pager->getPattern() );
	# Insert list
	$logBody = $pager->getBody();
	if( $logBody ) {
		$wgOut->addHTML(
			$pager->getNavigationBar() . 
			$loglist->beginLogEventList() .
			$logBody .
			$loglist->endLogEventList() .
			$pager->getNavigationBar()
		);
	} else {
		$wgOut->addWikiMsg( 'logempty' );
	}
}

