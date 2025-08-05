<?php
/**
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Title\Title;
use OOUI\FieldLayout;
use OOUI\SearchInputWidget;

/**
 * A special page that lists special pages
 *
 * @ingroup SpecialPage
 */
class SpecialSpecialPages extends UnlistedSpecialPage {

	public function __construct() {
		parent::__construct( 'Specialpages' );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$out = $this->getOutput();
		$this->setHeaders();
		$this->outputHeader();
		$out->getMetadata()->setPreventClickjacking( false );
		$out->addModuleStyles( 'mediawiki.special' );

		$groups = $this->getSpecialPages();

		if ( $groups === false ) {
			return;
		}

		$this->addHelpLink( 'Help:Special pages' );
		$this->outputPageList( $groups );
	}

	/** @return array[]|false */
	private function getSpecialPages() {
		$pages = $this->getSpecialPageFactory()->getUsablePages( $this->getUser(), $this->getContext() );

		if ( $pages === [] ) {
			// Yeah, that was pointless. Thanks for coming.
			return false;
		}

		// Put them into a sortable array
		$specialPages = [];
		foreach ( $pages as $page ) {
			$group = $page->getFinalGroupName();
			$desc = $page->getDescription();
			// (T360723) Only show an entry if the message isn't blanked, to allow on-wiki unlisting
			if ( !$desc->isDisabled() ) {
				$specialPages[$desc->text()] = [
					$page->getPageTitle(),
					$page->isRestricted(),
					$page->isCached(),
					$group
				];
			}
		}

		// Sort by group
		uasort( $specialPages, static fn ( $a, $b ) => $a[3] <=> $b[3] );

		return $specialPages;
	}

	private function outputPageList( array $specialPages ) {
		$out = $this->getOutput();
		$aliases = $this->getSpecialPageFactory()->getAliasList();
		$out->addModuleStyles( [ 'codex-styles' ] );
		$out->addModules( 'mediawiki.special.specialpages' );
		$out->enableOOUI();

		$includesRestrictedPages = false;
		foreach ( $specialPages as $desc => [ $title, $restricted, $cached, $group ] ) {
			if ( $restricted ) {
				$includesRestrictedPages = true;
				break;
			}
		}

		if ( $includesRestrictedPages ) {
			$legend = Html::rawElement(
				'div',
				[ 'class' => [ 'cdx-card', 'mw-special-pages-legend' ] ],
				Html::element(
					'div',
					[ 'class' => [ 'cdx-card__text' ] ],
					$this->msg( 'specialpages-access-restricted-note' )->text()
				)
			);
			$out->addHTML( $legend );
		}

		$out->addHTML(
			Html::openElement( 'div', [ 'class' => 'cdx-table' ] ) .
			Html::openElement( 'div', [ 'class' => 'cdx-table__header' ] )
		);
		// Headers
		$out->addHTML( new FieldLayout(
			new SearchInputWidget( [
				'placeholder' => $this->msg( 'specialpages-header-search' )->text(),
			] ),
			[
				'classes' => [ 'mw-special-pages-search' ],
				'label' => $this->msg( 'specialpages-header-search' )->text(),
				'invisibleLabel' => true,
				'infusable' => true,
			]
		) );
		// Open table elements
		$out->addHTML(
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', [ 'class' => 'cdx-table__table-wrapper' ] ) .
			Html::openElement( 'table', [ 'class' => 'cdx-table__table sortable' ] )
		);
		// Add table header
		$accessHeader = $includesRestrictedPages ?
			Html::element( 'th', [], $this->msg( 'specialpages-header-access' )->text() ) : '';
		$out->addHTML(
			Html::openElement( 'thead' ) .
			Html::openElement( 'tr' ) .
			Html::element( 'th', [], $this->msg( 'specialpages-header-name' )->text() ) .
			Html::element( 'th', [], $this->msg( 'specialpages-header-category' )->text() ) .
			$accessHeader .
			Html::closeElement( 'tr' ) .
			Html::closeElement( 'thead' ) .
			Html::openElement( 'tbody' )
		);
		// Format contents
		$language = $this->getLanguage();
		foreach ( $specialPages as $desc => [ $title, $restricted, $cached, $group ] ) {
			$indexAttr = [ 'data-search-index-0' => $language->lc( $title->getText() ) ];
			$c = 1;
			foreach ( $aliases as $alias => $target ) {
				/** @var Title $title */
				if ( $target == $title->getText() && $language->lc( $alias ) !== $language->lc( $title->getText() ) ) {
					$indexAttr['data-search-index-' . $c ] = $language->lc( $alias );
					++$c;
				}
			}
			if ( str_contains( $group, '/' ) ) {
				[ $group, $subGroup ] = explode( '/', $group, 2 );
				$groupName = $this->msg( "specialpages-group-$group-$subGroup" )->text();
			} else {
				$groupName = $this->msg( "specialpages-group-$group" )->text();
			}
			$rowClasses = [ 'mw-special-pages-search-highlight', 'mw-special-pages-row' ];
			if ( $includesRestrictedPages ) {
				if ( $restricted === true ) {
					$rowClasses[] = 'mw-special-pages-row-restricted';
					$accessMessageKey = 'specialpages-access-restricted';
				} else {
					$accessMessageKey = 'specialpages-access-public';
				}
				$accessCell = Html::element( 'td', [], $this->msg( $accessMessageKey )->text() );
			} else {
				$accessCell = '';
			}
			$out->addHTML(
				Html::openElement( 'tr', $indexAttr + [ 'class' => $rowClasses ] ) .
				Html::rawElement(
					'td',
					[ 'class' => 'mw-special-pages-name' ],
					$this->getLinkRenderer()->makeKnownLink( $title, $desc )
				) .
				Html::element( 'td', [], $groupName ) .
				$accessCell .
				Html::closeElement( 'tr' )
			);
		}

		$out->addHTML(
			Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' ) .
			Html::closeElement( 'div' ) .
			Html::closeElement( 'div' )
		);
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialSpecialPages::class, 'SpecialSpecialpages' );
