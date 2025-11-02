<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Title\Title;
use OOUI\FieldLayout;
use OOUI\SearchInputWidget;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;

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

		$groups = $this->getPageGroups();

		if ( $groups === false ) {
			return;
		}

		$this->addHelpLink( 'Help:Special pages' );
		$this->outputPageList( $groups );
	}

	/** @return array[][]|false */
	private function getPageGroups() {
		$pages = $this->getSpecialPageFactory()->getUsablePages( $this->getUser(), $this->getContext() );

		if ( $pages === [] ) {
			// Yeah, that was pointless. Thanks for coming.
			return false;
		}

		// Put them into a sortable array
		$groups = [];
		foreach ( $pages as $page ) {
			$group = $page->getFinalGroupName();
			$desc = $page->getDescription();
			// (T360723) Only show an entry if the message isn't blanked, to allow on-wiki unlisting
			if ( !$desc->isDisabled() ) {
				$groups[$group][$desc->text()] = [
					$page->getPageTitle(),
					$page->isRestricted(),
					$page->isCached()
				];
			}
		}

		// Sort
		foreach ( $groups as $group => $sortedPages ) {
			ksort( $groups[$group] );
		}

		// Always move "other" to end
		if ( array_key_exists( 'other', $groups ) ) {
			$other = $groups['other'];
			unset( $groups['other'] );
			$groups['other'] = $other;
		}

		return $groups;
	}

	private function outputPageList( array $groups ) {
		$out = $this->getOutput();
		$aliases = $this->getSpecialPageFactory()->getAliasList();
		$out->addModules( 'mediawiki.special.specialpages' );
		$out->enableOOUI();

		// Legend
		$includesRestrictedPages = false;
		$includesCachedPages = false;
		foreach ( $groups as $group => $sortedPages ) {
			foreach ( $sortedPages as $desc => [ $title, $restricted, $cached ] ) {
				if ( $cached ) {
					$includesCachedPages = true;
				}
				if ( $restricted ) {
					$includesRestrictedPages = true;
				}
			}
		}

		$notes = [];
		if ( $includesRestrictedPages ) {
			$restricedMsg = $this->msg( 'specialpages-note-restricted' );
			if ( !$restricedMsg->isDisabled() ) {
				$notes[] = $restricedMsg->parse();
			}
		}
		if ( $includesCachedPages ) {
			$cachedMsg = $this->msg( 'specialpages-note-cached' );
			if ( !$cachedMsg->isDisabled() ) {
				$notes[] = $cachedMsg->parse();
			}
		}
		if ( $notes !== [] ) {
			$legendHeading = $this->msg( 'specialpages-note-top' )->parse();

			$legend = Html::rawElement(
				'div',
				[ 'class' => [ 'mw-changeslist-legend', 'mw-specialpages-notes' ] ],
				$legendHeading . implode( "\n", $notes )
			);

			$out->addHTML( $legend );
			$out->addModuleStyles( 'mediawiki.special.changeslist.legend' );
		}

		$out->addHTML( ( new FieldLayout(
			new SearchInputWidget( [
				'placeholder' => $this->msg( 'specialpages-header-search' )->text(),
			] ),
			[
				'classes' => [ 'mw-special-pages-search' ],
				'label' => $this->msg( 'specialpages-header-search' )->text(),
				'invisibleLabel' => true,
				'infusable' => true,
			]
		) )->toString() );

		// Format table of contents
		$tocData = new TOCData();
		$tocLength = 0;
		foreach ( $groups as $group => $sortedPages ) {
			if ( !str_contains( $group, '/' ) ) {
				++$tocLength;
				$tocData->addSection( new SectionMetadata(
					1,
					2,
					$this->msg( "specialpages-group-$group" )->escaped(),
					$this->getLanguage()->formatNum( $tocLength ),
					(string)$tocLength,
					null,
					null,
					"mw-specialpagesgroup-$group",
					"mw-specialpagesgroup-$group"
				) );
			}
		}

		$out->addTOCPlaceholder( $tocData );

		// Format contents
		$language = $this->getLanguage();
		foreach ( $groups as $group => $sortedPages ) {
			if ( str_contains( $group, '/' ) ) {
				[ $group, $subGroup ] = explode( '/', $group, 2 );
				$out->addHTML( Html::element(
					'h3',
					[ 'class' => "mw-specialpagessubgroup" ],
					$this->msg( "specialpages-group-$group-$subGroup" )->text()
				) . "\n" );
			} else {
				$out->addHTML( Html::element(
					'h2',
					[ 'class' => "mw-specialpagesgroup", 'id' => "mw-specialpagesgroup-$group" ],
					$this->msg( "specialpages-group-$group" )->text()
				) . "\n" );
			}
			$out->addHTML(
				Html::openElement( 'div', [ 'class' => 'mw-specialpages-list' ] )
				. '<ul>'
			);
			foreach ( $sortedPages as $desc => [ $title, $restricted, $cached ] ) {
				$indexAttr = [ 'data-search-index-0' => $language->lc( $title->getText() ) ];
				$c = 1;
				foreach ( $aliases as $alias => $target ) {
					/** @var Title $title */
					if (
						$target == $title->getText() &&
						$language->lc( $alias ) !== $language->lc( $title->getText() )
					) {
						$indexAttr['data-search-index-' . $c ] = $language->lc( $alias );
						++$c;
					}
				}
				$pageClasses = [];
				if ( $cached ) {
					$pageClasses[] = 'mw-specialpagecached';
				}
				if ( $restricted ) {
					$pageClasses[] = 'mw-specialpagerestricted';
				}

				$link = $this->getLinkRenderer()->makeKnownLink( $title, $desc );
				$out->addHTML( Html::rawElement(
						'li',
						$indexAttr + [ 'class' => $pageClasses ],
						$link
					) . "\n" );
			}
			$out->addHTML(
				Html::closeElement( 'ul' ) .
				Html::closeElement( 'div' )
			);
		}
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialSpecialPages::class, 'SpecialSpecialpages' );
