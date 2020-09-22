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

use MediaWiki\MediaWikiServices;

/**
 * A special page that lists special pages
 *
 * @ingroup SpecialPage
 */
class SpecialSpecialpages extends UnlistedSpecialPage {

	public function __construct() {
		parent::__construct( 'Specialpages' );
	}

	public function execute( $par ) {
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
		$pages = MediaWikiServices::getInstance()->getSpecialPageFactory()->
			getUsablePages( $this->getUser() );

		if ( $pages === [] ) {
			# Yeah, that was pointless. Thanks for coming.
			return false;
		}

		/** Put them into a sortable array */
		$groups = [];
		/** @var SpecialPage $page */
		foreach ( $pages as $page ) {
			if ( $page->isListed() ) {
				$group = $page->getFinalGroupName();
				if ( !isset( $groups[$group] ) ) {
					$groups[$group] = [];
				}
				$groups[$group][$page->getDescription()] = [
					$page->getPageTitle(),
					$page->isRestricted(),
					$page->isCached()
				];
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
			if ( strpos( $group, '/' ) !== false ) {
				list( $group, $subGroup ) = explode( '/', $group, 2 );
				$out->wrapWikiMsg(
					"<h3 class=\"mw-specialpagessubgroup\">$1</h3>\n",
					"specialpages-group-$group-$subGroup"
				);
			} else {
				$out->wrapWikiMsg(
					"<h2 class=\"mw-specialpagesgroup\" id=\"mw-specialpagesgroup-$group\">$1</h2>\n",
					"specialpages-group-$group"
				);
			}
			$out->addHTML(
				Html::openElement( 'div', [ 'class' => 'mw-specialpages-list' ] )
				. '<ul>'
			);
			foreach ( $sortedPages as $desc => $specialpage ) {
				list( $title, $restricted, $cached ) = $specialpage;

				$pageClasses = [];
				if ( $cached ) {
					$includesCachedPages = true;
					$pageClasses[] = 'mw-specialpagecached';
				}
				if ( $restricted ) {
					$includesRestrictedPages = true;
					$pageClasses[] = 'mw-specialpagerestricted';
				}

				$link = $this->getLinkRenderer()->makeKnownLink( $title, $desc );
				$out->addHTML( Html::rawElement(
						'li',
						[ 'class' => $pageClasses ],
						$link
					) . "\n" );
			}
			$out->addHTML(
				Html::closeElement( 'ul' ) .
				Html::closeElement( 'div' )
			);
		}

		// add legend
		$notes = [];
		if ( $includesRestrictedPages ) {
			$restricedMsg = $this->msg( 'specialpages-note-restricted' );
			if ( !$restricedMsg->isDisabled() ) {
				$notes[] = $restricedMsg->plain();
			}
		}
		if ( $includesCachedPages ) {
			$cachedMsg = $this->msg( 'specialpages-note-cached' );
			if ( !$cachedMsg->isDisabled() ) {
				$notes[] = $cachedMsg->plain();
			}
		}
		if ( $notes !== [] ) {
			$out->wrapWikiMsg(
				"<h2 class=\"mw-specialpages-note-top\">$1</h2>", 'specialpages-note-top'
			);
			$out->wrapWikiTextAsInterface(
				'mw-specialpages-notes',
				implode( "\n", $notes )
			);
		}
	}
}
