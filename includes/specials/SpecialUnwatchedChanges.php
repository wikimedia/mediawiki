<?php
/**
 * Implements Special:UnwatchedChanges
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
 * This is to display changes made to all unwatched pages.
 *
 * @ingroup SpecialPage
 * @since 1.24
 */
class SpecialUnwatchedChanges extends SpecialRecentChanges {

	public function __construct( $name = 'UnwatchedChanges', $restriction = 'unwatchedpages' ) {
		parent::__construct( $name, $restriction );
	}

	/**
	 * Special:RecentChanges is includable, however we do not
	 * want to be, since only certain users allowed to view this page.
	 */
	public function isIncludable() {
		return false;
	}

	public function getFeedObject( $feedFormat ) {
		# This would really only work for in-browser RSS readers, since
		# you would need to be logged in. If users complain, we could
		# set up something similar to watchlist rss with the token,
		# but for now, lets wait to see if the limitation actually
		# bothers anybody.
		$feed = new ChangesFeed( $feedFormat, false );
		$feedObj = $feed->getFeedObject(
			$this->msg( 'unwatchedchanges' )
				->inContentLanguage()->text(),
			$this->msg( 'unwatchedchanges-feed' )->inContentLanguage()->text(),
			$this->getTitle()->getFullURL()
		);
		return array( $feed, $feedObj );
	}

	public function doMainQuery( $conds, $opts ) {
		// XXX: Should this be in the 'watchlist' query group? Its own group? No group?
		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

		$tables = array( 'recentchanges', 'watchlist' );
		$select = RecentChange::selectFields();
		$queryOptions = array(
			'LIMIT' => $opts['limit'],
			'ORDER BY' => 'rc_timestamp DESC'
		);

		$joinConds = array(
			'watchlist' => array(
				'LEFT JOIN', array(
					'wl_title=rc_title',
					'wl_namespace=rc_namespace'
				)
			)
		);

		// We only want unwatched pages.
		$conds['wl_title'] = null;

		if ( $this->getUser()->isAllowed( 'rollback' ) ) {
			$tables[] = 'page';
			$joinConds['page'] = array( 'LEFT JOIN', 'rc_cur_id=page_id' );
			$select[] = 'page_latest';
		}
		ChangeTags::modifyDisplayQuery(
			$tables,
			$select,
			$conds,
			$joinConds,
			$queryOptions,
			$opts['tagfilter']
		);

		$hookArgs = array(
			&$conds,
			&$tables,
			&$joinConds,
			$opts,
			&$queryOptions,
			&$select
		);
		if ( !wfRunHooks( 'SpecialRecentChangesQuery', $hookArgs ) ) {
			return false;
		}

		$res = $dbr->select(
			$tables,
			$select,
			$conds,
			__METHOD__,
			$queryOptions,
			$joinConds
		);

		if ( $res->numRows() == 0 ) {
			$this->mResultEmpty = true;
		}

		return $res;
	}

}
