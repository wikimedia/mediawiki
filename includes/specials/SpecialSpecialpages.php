<?php
/**
 * Implements Special:Specialpages
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
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * A special page that lists special pages
 *
 * @ingroup SpecialPage
 */
class SpecialSpecialpages extends UnlistedSpecialPage {

	function __construct() {
		parent::__construct( 'Specialpages' );
	}

	function execute( $par ) {
		$out = $this->getOutput();
		$this->setHeaders();
		$this->outputHeader();
		$out->allowClickjacking();
		$out->addModuleStyles( 'mediawiki.special' );

		$groups = $this->getPageGroups();

		if ( $groups === false ) {
			return;
		}

		$this->addHelpLink( 'Help:Special pages' );
		$this->outputPageList( $groups );
	}

	private function getPageGroups() {
		$pages = SpecialPageFactory::getUsablePages( $this->getUser() );

		if ( !count( $pages ) ) {
			# Yeah, that was pointless. Thanks for coming.
			return false;
		}

		/** Put them into a sortable array */
		$groups = array();
		/** @var SpecialPage $page */
		foreach ( $pages as $page ) {
			if ( $page->isListed() ) {
				$group = $page->getFinalGroupName();
				if ( !isset( $groups[$group] ) ) {
					$groups[$group] = array();
				}
				$groups[$group][$page->getDescription()] = array(
					$page->getPageTitle(),
					$page->isRestricted(),
					$page->isCached()
				);
			}
		}

		/** Sort */
		foreach ( $groups as $group => $sortedPages ) {
			ksort( $groups[$group] );
		}

		/** Always move "other" to end */
		if ( array_key_exists( 'other', $groups ) ) {
			$other = $groups['other'];
			unset( $groups['other'] );
			$groups['other'] = $other;
		}

		return $groups;
	}

	private function outputPageList( $groups ) {
		$out = $this->getOutput();

		$includesRestrictedPages = false;
		$includesCachedPages = false;

		foreach ( $groups as $group => $sortedPages ) {

			$out->wrapWikiMsg(
				"<h2 class=\"mw-specialpagesgroup\" id=\"mw-specialpagesgroup-$group\">$1</h2>\n",
				"specialpages-group-$group"
			);
			$out->addHTML(
				Html::openElement( 'div', array( 'class' => 'mw-specialpages-list' ) )
				. '<ul>'
			);
			foreach ( $sortedPages as $desc => $specialpage ) {
				list( $title, $restricted, $cached ) = $specialpage;

				$pageClasses = array();
				if ( $cached ) {
					$includesCachedPages = true;
					$pageClasses[] = 'mw-specialpagecached';
				}
				if ( $restricted ) {
					$includesRestrictedPages = true;
					$pageClasses[] = 'mw-specialpagerestricted';
				}

				$link = Linker::linkKnown( $title, htmlspecialchars( $desc ) );
				$out->addHTML( Html::rawElement(
						'li',
						array( 'class' => implode( ' ', $pageClasses ) ),
						$link
					) . "\n" );
			}
			$out->addHTML(
				Html::closeElement( 'ul' ) .
				Html::closeElement( 'div' )
			);
		}

		if ( $includesRestrictedPages || $includesCachedPages ) {
			$out->wrapWikiMsg( "<h2 class=\"mw-specialpages-note-top\">$1</h2>", 'specialpages-note-top' );
			$out->wrapWikiMsg( "<div class=\"mw-specialpages-notes\">\n$1\n</div>", 'specialpages-note' );
		}
	}
}
