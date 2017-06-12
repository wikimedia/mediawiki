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
 * Pager for Special:Watchlist
 * @ingroup Pager
 */
use MediaWiki\MediaWikiServices;

class WatchlistPager extends ChangesListPager {

	public function __construct( IContextSource $context, FormOptions $opts, array $filterGroups ) {
		parent::__construct( $context, $opts, $filterGroups );

		//$this->tagFilter = $opts['tagfilter'];

		// FIXME factor out the code below (also duplicated from RCPager)
		// Calculate cutoff
		$dbr = $this->getDatabase();
		$cutoff_unixtime = time() - ( $opts['days'] * 86400 );
		$cutoff_unixtime = $cutoff_unixtime - ( $cutoff_unixtime % 86400 );
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		// FIXME move the validity check and clear-on-invalid code somewhere better
		/*$fromValid = preg_match( '/^[0-9]{14}$/', $opts['from'] );
		if ( $fromValid && $opts['from'] > wfTimestamp( TS_MW, $cutoff ) ) {
			$cutoff = $dbr->timestamp( $opts['from'] );
		} else {
			$opts->reset( 'from' );
		}*/

		$this->getDateRangeCond( $cutoff, '' );

		$this->changesList->setWatchlistDivs();
	}

	public function getQueryInfo() {
		$queryInfo = parent::getQueryInfo();
		$dbr = $this->getDatabase();

		if ( $this->opts['extended'] ) {
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$nonRevisionTypes = [ RC_LOG ];
			Hooks::run( 'SpecialWatchlistGetNonRevisionTypes', [ &$nonRevisionTypes ] );
			if ( $nonRevisionTypes ) {
				$queryInfo['conds'][] = $dbr->makeList(
					[
						'rc_this_oldid=page_latest',
						'rc_type' => $nonRevisionTypes,
					],
					LIST_OR
				);
			}
			$usePage = true;
		}

		$user = $this->getUser();

		// JOIN on page
		if ( $usePage || $user->isAllowed( 'rollback' ) ) {
			$queryInfo['tables'][] = 'page';
			$queryInfo['fields'][] = 'page_latest';
			$queryInfo['join_conds']['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
		}

		// JOIN on watchlist
		$queryInfo['tables'][] = 'watchlist';
		$queryInfo['fields'][] = 'wl_notificationtimestamp';
		$queryInfo['join_conds']['watchlist'] = [ 'INNER JOIN', [
			'wl_user' => $user->getId(),
			'wl_namespace=rc_namespace',
			'wl_title=rc_title'
		] ];

		if ( $this->getConfig()->get( 'ShowUpdatedMarker' ) ) {
			$queryInfo['fields'][] = 'wl_notificationtimestamp';
		}

		// Log entries with DELETED_ACTION must not show up unless the user has
		// the necessary rights.
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$bitmask = LogPage::DELETED_ACTION;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		if ( $bitmask ) {
			$queryInfo['conds'][] = $dbr->makeList( [
				'rc_type != ' . RC_LOG,
				$dbr->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask",
			], LIST_OR );
		}

		return $queryInfo;
	}

	protected function runQueryInfoHook( &$queryInfo ) {
		// FIXME make hook aborts actually work (if needed?)
		// FIXME make hook run after subclass-specific query info is added (use buildQueryInfo?)
		$parentHook = parent::runQueryInfoHook( $queryInfo );
		return $parentHook && Hooks::run(
			'SpecialWatchlistQuery',
			[
				&$queryInfo['conds'],
				&$queryInfo['tables'],
				&$queryInfo['join_conds'],
				&$queryInfo['fields'],
				$this->opts
			],
			'1.23'
		);
	}

	public function isRowWatched( $row ) {
		return $this->getConfig()->get( 'ShowUpdatedMarker' ) &&
			!empty( $row->wl_notificationtimestamp );
	}
}
