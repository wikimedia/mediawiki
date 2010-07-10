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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */
 
/**
 * @file
 * @ingroup SpecialPage
 */
class SpecialSpecialpages extends UnlistedSpecialPage {

	function __construct() {
		parent::__construct( 'Specialpages' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$groups = $this->getPageGroups();

		if ( $groups === false ) {
			return;
		}

		$this->outputPageList( $groups );
	}

	private function getPageGroups() {
		global $wgSortSpecialPages;

		$pages = SpecialPage::getUsablePages();

		if( !count( $pages ) ) {
			# Yeah, that was pointless. Thanks for coming.
			return false;
		}

		/** Put them into a sortable array */
		$groups = array();
		foreach ( $pages as $page ) {
			if ( $page->isListed() ) {
				$group = SpecialPage::getGroup( $page );
				if( !isset( $groups[$group] ) ) {
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
		if( array_key_exists( 'other', $groups ) ) {
			$other = $groups['other'];
			unset( $groups['other'] );
			$groups['other'] = $other;
		}

		return $groups;
	}

	private function outputPageList( $groups ) {
		global $wgUser, $wgOut;

		$sk = $wgUser->getSkin();
		$includesRestrictedPages = false;

		foreach ( $groups as $group => $sortedPages ) {
			$middle = ceil( count( $sortedPages )/2 );
			$total = count( $sortedPages );
			$count = 0;

			$wgOut->wrapWikiMsg( "<h4 class=\"mw-specialpagesgroup\" id=\"mw-specialpagesgroup-$group\">$1</h4>\n", "specialpages-group-$group" );
			$wgOut->addHTML(
				Html::openElement( 'table', array( 'style' => 'width:100%;', 'class' => 'mw-specialpages-table' ) ) ."\n" .
				Html::openElement( 'tr' ) . "\n" .
				Html::openElement( 'td', array( 'style' => 'width:30%;vertical-align:top' ) ) . "\n" .
				Html::openElement( 'ul' ) . "\n"
			);
			foreach( $sortedPages as $desc => $specialpage ) {
				list( $title, $restricted ) = $specialpage;
				$link = $sk->linkKnown( $title , htmlspecialchars( $desc ) );
				if( $restricted ) {
					$includesRestrictedPages = true;
					$wgOut->addHTML( Html::rawElement( 'li', array( 'class' => 'mw-specialpages-page mw-specialpagerestricted' ), Html::rawElement( 'strong', array(), $link ) ) . "\n" );
				} else {
					$wgOut->addHTML( Html::rawElement( 'li', array(), $link ) . "\n" );
				}

				# Split up the larger groups
				$count++;
				if( $total > 3 && $count == $middle ) {
					$wgOut->addHTML(
						Html::closeElement( 'ul' ) . Html::closeElement( 'td' ) .
						Html::element( 'td', array( 'style' => 'width:10%' ), '' ) .
						Html::openElement( 'td', array( 'style' => 'width:30%' ) ) . Html::openElement( 'ul' ) . "\n"
					);
				}
			}
			$wgOut->addHTML(
				Html::closeElement( 'ul' ) . Html::closeElement( 'td' ) .
				Html::element( 'td', array( 'style' => 'width:30%' ), '' ) .
				Html::closeElement( 'tr' ) . Html::closeElement( 'table' ) . "\n"
			);
		}

		if ( $includesRestrictedPages ) {
			$wgOut->wrapWikiMsg( "<div class=\"mw-specialpages-notes\">\n$1\n</div>", 'specialpages-note' );
		}
	}
}
