<?php

namespace MediaWiki\Watchlist;

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialEditWatchlist;
use MediaWiki\Specials\SpecialWatchlist;

/**
 * Used by the three watchlist special pages (Watchlist, EditWatchlist, and WatchlistLabels) to
 * handle their common navigation.
 */
trait WatchlistSpecialPage {

	/** @var int|false where the value is one of the SpecialEditWatchlist::EDIT_* constants (e.g. EDIT_RAW) */
	protected $currentMode;

	/** @inheritDoc */
	abstract public function getConfig();

	/** @inheritDoc */
	abstract public function getLinkRenderer();

	/** @inheritDoc */
	abstract public function getOutput();

	/** @inheritDoc */
	abstract public function getUser();

	/** @inheritDoc */
	abstract public function getSkin();

	/** @inheritDoc */
	abstract public function msg( $key, ...$params );

	/** @inheritDoc */
	public function getAssociatedNavigationLinks() {
		$links = SpecialWatchlist::WATCHLIST_TAB_PATHS;
		if ( !$this->getConfig()->get( MainConfigNames::EnableWatchlistLabels ) ) {
			unset( $links[2] );
		}
		return $links;
	}

	/** @inheritDoc */
	public function getShortDescription( string $path = '' ): string {
		switch ( $path ) {
			case 'Watchlist':
				return $this->msg( 'watchlisttools-view' )->text();
			case 'EditWatchlist':
				return $this->msg( 'watchlisttools-edit' )->text();
			case 'WatchlistLabels':
				return $this->msg( 'watchlisttools-labels' )->text();
			case 'EditWatchlist/raw':
				return $this->msg( 'watchlisttools-raw' )->text();
			case 'EditWatchlist/clear':
				return $this->msg( 'watchlisttools-clear' )->text();
			default:
				return $path;
		}
	}

	/**
	 * Get the "For <user>" HTML to add as the special page subtitle.
	 *
	 * @return string HTML span element.
	 */
	protected function getWatchlistOwnerHtml(): string {
		return Html::element(
			'span',
			[
				'class' => 'mw-watchlist-owner'
			],
			// Previously the watchlistfor2 message took 2 parameters.
			// It now only takes 1 so empty string is passed.
			// Empty string parameter can be removed when all messages
			// are updated to not use $2
			$this->msg( 'watchlistfor2', $this->getUser()->getName(), '' )->text()
		);
	}

	/**
	 * For legacy skins, render the watchlist navigation link in the subtitle.
	 */
	protected function outputSubtitle(): void {
		if ( !$this->getSkin()->supportsMenu( 'associated-pages' ) ) {
			$subtitle = $this->getWatchlistOwnerHtml()
				. ' ' . $this->buildTools( $this->currentMode );
			$this->getOutput()->addSubtitle( $subtitle );
		}
	}

	/**
	 * Build the HTML for links for navigation between watchlist viewing and editing modes.
	 * This is only used for skins that don't support the `associated-pages` menu.
	 *
	 * @param ?int $selectedMode The mode of the current page, to be shown as active.
	 * @return string
	 */
	public function buildTools( ?int $selectedMode = null ): string {
		$linkRenderer = $this->getLinkRenderer() ?? MediaWikiServices::getInstance()->getLinkRenderer();

		$tools = [];
		// These should be kept in sync with SpecialWatchlist::WATCHLIST_TAB_PATHS.
		$modes = array_filter( [
			'view' => [ 'Watchlist', false, false ],
			'edit' => [ 'EditWatchlist', false, SpecialEditWatchlist::EDIT ],
			'labels' => $this->getConfig()->get( MainConfigNames::EnableWatchlistLabels )
				? [ 'WatchlistLabels', false, false ]
				: null,
			'raw' => [ 'EditWatchlist', 'raw', SpecialEditWatchlist::EDIT_RAW ],
			'clear' => [ 'EditWatchlist', 'clear', SpecialEditWatchlist::EDIT_CLEAR ],
		] );

		foreach ( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$link = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( $arr[0], $arr[1] ),
				wfMessage( "watchlisttools-{$mode}" )->text()
			);
			$isSelected = $selectedMode === $arr[2];
			$classes = [
				'mw-watchlist-toollink',
				'mw-watchlist-toollink-' . $mode,
				$isSelected ? 'mw-watchlist-toollink-active' :
					'mw-watchlist-toollink-inactive'
			];
			$tools[] = Html::rawElement( 'span', [
				'class' => $classes,
			], $link );
		}

		$this->getOutput()->addModuleStyles( 'mediawiki.interface.helpers.styles' );
		return Html::rawElement(
			'span',
			[ 'class' => [ 'mw-watchlist-toollinks', 'mw-changeslist-links' ] ],
			implode( '', $tools )
		);
	}
}
