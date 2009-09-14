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
 * @file
 * @ingroup SpecialPage
 */

/**
 * constructor
 */
function wfSpecialLog( $par = '' ) {
	global $wgRequest, $wgOut, $wgUser, $wgLogTypes;

	# Get parameters
	$parms = explode( '/', ($par = ( $par !== null ) ? $par : '' ) );
	$symsForAll = array( '*', 'all' );
	if ( $parms[0] != '' && ( in_array( $par, $wgLogTypes ) || in_array( $par, $symsForAll ) ) ) {
		$type = $par;
		$user = $wgRequest->getText( 'user' );
	} else if ( count( $parms ) == 2 ) {
		$type = $parms[0];
		$user = $parms[1];
	} else {
		$type = $wgRequest->getVal( 'type' );
		$user = ( $par != '' ) ? $par : $wgRequest->getText( 'user' );
	}
	$title = $wgRequest->getText( 'page' );
	$pattern = $wgRequest->getBool( 'pattern' );
	$y = $wgRequest->getIntOrNull( 'year' );
	$m = $wgRequest->getIntOrNull( 'month' );
	$tagFilter = $wgRequest->getVal( 'tagfilter' );
	# Don't let the user get stuck with a certain date
	$skip = $wgRequest->getText( 'offset' ) || $wgRequest->getText( 'dir' ) == 'prev';
	if( $skip ) {
		$y = '';
		$m = '';
	}
	# Handle type-specific inputs
	$qc = array();
	if( $type == 'suppress' ) {
		$offender = User::newFromName( $wgRequest->getVal('offender'), false );
		if( $offender && $offender->getId() > 0 ) {
			$qc = array( 'ls_field' => 'target_author_id', 'ls_value' => $offender->getId() );
		} else if( $offender && IP::isIPAddress( $offender->getName() ) ) {
			$qc = array( 'ls_field' => 'target_author_ip', 'ls_value' => $offender->getName() );
		}
	}
	# Create a LogPager item to get the results and a LogEventsList item to format them...
	$loglist = new LogEventsList( $wgUser->getSkin(), $wgOut, 0 );
	$pager = new LogPager( $loglist, $type, $user, $title, $pattern, $qc, $y, $m, $tagFilter );
	# Set title and add header
	$loglist->showHeader( $pager->getType() );
	# Show form options
	$loglist->showOptions( $pager->getType(), $pager->getUser(), $pager->getPage(), $pager->getPattern(),
		$pager->getYear(), $pager->getMonth(), $pager->getFilterParams(), $tagFilter );
	# Insert list
	$logBody = $pager->getBody();
	if( $logBody ) {
		$wgOut->addHTML(
			$pager->getNavigationBar() .
			$loglist->beginLogEventsList() .
			$logBody .
			$loglist->endLogEventsList() .
			$pager->getNavigationBar()
		);
	} else {
		$wgOut->addWikiMsg( 'logempty' );
	}
}
