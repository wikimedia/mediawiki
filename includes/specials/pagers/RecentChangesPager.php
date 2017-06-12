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
 * @ingroup Pager
 */

/**
 * Pager for Special:RecentChanges
 * @ingroup Pager
 */
use MediaWiki\MediaWikiServices;

class RecentChangesPager extends ChangesListPager {

	protected $legend;

	public function __construct( ChangesListSpecialPage $clsp, FormOptions $opts, array $filterGroups ) {
		parent::__construct( $clsp, $opts, $filterGroups );

		$this->joinOnWatchlist = $this->getUser()->isLoggedIn() &&
			$this->getUser()->isAllowed( 'viewmywatchlist' );

		// FIXME factor out the code below
		// Calculate cutoff
		$dbr = $this->getDatabase();
		$cutoff_unixtime = time() - $opts['days'] * 3600 * 24;
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		// FIXME move the validity check and clear-on-invalid code somewhere better
		$fromValid = preg_match( '/^[0-9]{14}$/', $opts['from'] );
		if ( $fromValid && $opts['from'] > wfTimestamp( TS_MW, $cutoff ) ) {
			$cutoff = $dbr->timestamp( $opts['from'] );
		} else {
			$opts->reset( 'from' );
		}

		$this->getDateRangeCond( $cutoff, '' );
	}

	public function getQueryInfo() {
		$queryInfo = parent::getQueryInfo();

		// rc_new is not an ENUM, but adding a redundant rc_new IN (0,1) gives mysql enough
		// knowledge to use an index merge if it wants (it may use some other index though).
		$queryInfo['conds']['rc_new'] = [ 0, 1 ];

		// JOIN on page, used for 'last revision' filter highlight
		$queryInfo['tables'][] = 'page';
		$queryInfo['fields'][] = 'page_latest';
		$queryInfo['join_conds']['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];

		// JOIN on watchlist for logged-in users
		if ( $this->joinOnWatchlist ) {
			$queryInfo['tables'][] = 'watchlist';
			$queryInfo['fields'][] = 'wl_user';
			$queryInfo['join_conds']['watchlist'] = [ 'LEFT JOIN', [
				'wl_user' => $this->getUser()->getId(),
				'wl_namespace=rc_namespace',
				'wl_title=rc_title'
			] ];
		}

		// TODO filterByCategories stuff

		return $queryInfo;
	}

	protected function runQueryInfoHook( &$queryInfo ) {
		// FIXME make hook aborts actually work (if needed?)
		$parentHook = parent::runQueryInfoHook( $queryInfo );
		return $parentHook && Hooks::run(
			'SpecialRecentChangesQuery',
			[
				&$queryInfo['conds'],
				&$queryInfo['tables'],
				&$queryInfo['join_conds'],
				$this->opts,
				&$queryInfo['query_options'],
				&$queryInfo['fields']
			],
			'1.23'
		);
	}

	public function shouldHighlightAsWatched( $row ) {
		return $this->getConfig()->get( 'ShowUpdatedMarker' ) &&
			isset( $row->wl_user ) && $row->wl_user !== null;
	}

	public function setLegend( $legend ) {
		$this->legend = $legend;
	}

	public function getStartBody() {
		return parent::getStartBody() . $this->legend;
	}
}
