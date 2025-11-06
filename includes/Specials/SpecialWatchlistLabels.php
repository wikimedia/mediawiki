<?php
/**
 * @license GPL-3.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Watchlist\WatchlistSpecialPage;

/**
 * A special page for viewing a user's watchlist labels and performing CRUD operations on them.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 */
class SpecialWatchlistLabels extends SpecialPage {

	use WatchlistSpecialPage;

	/** @inheritDoc */
	public function __construct( $name = 'WatchlistLabels', $restriction = 'viewmywatchlist' ) {
		parent::__construct( $name, $restriction, false );
	}

	/** @inheritDoc */
	public function execute( $subPage ) {
		$output = $this->getOutput();
		$output->setPageTitleMsg( $this->msg( 'watchlistlabels-title' ) );
		$this->addHelpLink( 'Help:Watchlist labels' );
		if ( !$this->getConfig()->get( MainConfigNames::EnableWatchlistLabels ) ) {
			$output->addHTML( Html::errorBox( $this->msg( 'watchlistlabels-not-enabled' )->escaped() ) );
			return;
		}
		$output->addSubtitle( $this->getWatchlistOwnerHtml() );
	}
}
