<?php

namespace MediaWiki\Watchlist;

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialWatchlist;

/**
 * Used by the three watchlist special pages (Watchlist, EditWatchlist, and WatchlistLabels) to
 * handle their common navigation.
 */
trait WatchlistSpecialPage {

	/** @inheritDoc */
	abstract public function getConfig();

	/** @inheritDoc */
	abstract public function getUser();

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
}
