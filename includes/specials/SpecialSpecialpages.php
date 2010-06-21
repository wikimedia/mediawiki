<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */
 
/**
 * @file
 * @ingroup SpecialPage
 */

function wfSpecialSpecialpages() {
	global $wgOut, $wgUser, $wgMessageCache, $wgSortSpecialPages;

	$wgMessageCache->loadAllMessages();

	$wgOut->setRobotPolicy( 'noindex,nofollow' );  # Is this really needed?
	$sk = $wgUser->getSkin();

	$pages = SpecialPage::getUsablePages();

	if( count( $pages ) == 0 ) {
		# Yeah, that was pointless. Thanks for coming.
		return;
	}

	/** Put them into a sortable array */
	$groups = array();
	foreach ( $pages as $page ) {
		if ( $page->isListed() ) {
			$group = SpecialPage::getGroup( $page );
			if( !isset($groups[$group]) ) {
				$groups[$group] = array();
			}
			$groups[$group][$page->getDescription()] = array( $page->getTitle(), $page->isRestricted() );
		}
	}

	/** Sort */
	if ( $wgSortSpecialPages ) {
		foreach( $groups as $group => $sortedPages ) {
			ksort( $groups[$group] );
		}
	}

	/** Always move "other" to end */
	if( array_key_exists('other',$groups) ) {
		$other = $groups['other'];
		unset( $groups['other'] );
		$groups['other'] = $other;
	}

	$includesRestrictedPages = false;
	/** Now output the HTML */
	foreach ( $groups as $group => $sortedPages ) {
		$middle = ceil( count($sortedPages)/2 );
		$total = count($sortedPages);
		$count = 0;

		$wgOut->wrapWikiMsg( "<h4 class=\"mw-specialpagesgroup\" id=\"mw-specialpagesgroup-$group\">$1</h4>\n", "specialpages-group-$group" );
		$wgOut->addHTML( "<table style='width: 100%;' class='mw-specialpages-table'><tr>" );
		$wgOut->addHTML( "<td width='30%' valign='top'><ul>\n" );
		foreach( $sortedPages as $desc => $specialpage ) {
			list( $title, $restricted ) = $specialpage;
			$link = $sk->linkKnown( $title , htmlspecialchars( $desc ) );
			if( $restricted ) {
				$includesRestrictedPages = true;
				$wgOut->addHTML( "<li class='mw-specialpages-page mw-specialpagerestricted'><strong>{$link}</strong></li>\n" );
			} else {
				$wgOut->addHTML( "<li>{$link}</li>\n" );
			}

			# Split up the larger groups
			$count++;
			if( $total > 3 && $count == $middle ) {
				$wgOut->addHTML( "</ul></td><td width='10%'></td><td width='30%' valign='top'><ul>" );
			}
		}
		$wgOut->addHTML( "</ul></td><td width='30%' valign='top'></td></tr></table>\n" );
	}

	if ( $includesRestrictedPages ) {
		$wgOut->wrapWikiMsg( "<div class=\"mw-specialpages-notes\">\n$1\n</div>", 'specialpages-note' );
	}
}
