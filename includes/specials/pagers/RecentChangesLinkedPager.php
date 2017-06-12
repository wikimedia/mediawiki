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
 * Pager for Special:RecentChangesLinked
 * @ingroup Pager
 */
use MediaWiki\MediaWikiServices;

class RecentChangesLinkedPager extends RecentChangesPager {

	protected $targetTitle;

	public function __construct( IContextSource $context, FormOptions $opts, array $filterGroups, Title $target ) {
		$this->mDb = wfGetDB( DB_REPLICA, 'recentchangeslinked' );
		$this->targetTitle = $target;

		parent::__construct( $context, $opts, $filterGroups );
	}

	// HACK we need a UNION here and getQueryInfo doesn't allow us to express that,
	// so instead we hijack the query execution and build our UNION queries here
	public function reallyDoQuery( $offset, $limit, $descending ) {
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) =
			$this->buildQueryInfo( $offset, $limit, $descending );

		$dbr = $this->getDatabase();
		$showLinkedTo = $this->opts['showlinkedto'];
		$ns = $this->targetTitle->getNamespace();
		if ( $ns === NS_CATEGORY && !$showLinkedTo ) {
			// special handling for categories
			// XXX: should try to make this less kludgy
			$linkTables = [ 'categorylinks' ];
			$showLinkedTo = true;
		} else {
			// for now, always join on these tables; really should be configurable as in whatlinkshere
			$linkTables = [ 'pagelinks', 'templatelinks' ];
			// imagelinks only contains links to pages in NS_FILE
			if ( $ns == NS_FILE || !$showLinkedTo ) {
				$linkTables[] = 'imagelinks';
			}
		}

		if ( !$dbr->unionSupportsOrderAndLimit() ) {
			unset( $options['ORDER BY'] );
			unset( $options['LIMIT'] );
		}

		// field name prefixes for all the various tables we might want to join with
		$prefixes = [
			'pagelinks' => 'pl',
			'templatelinks' => 'tl',
			'categorylinks' => 'cl',
			'imagelinks' => 'il'
		];

		$subqueries = []; // SELECT statements to combine with UNION

		foreach ( $linkTables as $linkTable ) {
			$prefix = $prefixes[$linkTable];

			// imagelinks and categorylinks tables have no xx_namespace field,
			// and have xx_to instead of xx_title
			if ( $linkTable == 'imagelinks' ) {
				$linkNs = NS_FILE;
			} elseif ( $linkTable == 'categorylinks' ) {
				$linkNs = NS_CATEGORY;
			} else {
				$linkNs = 0;
			}

			if ( $showLinkedTo ) {
				// find changes to pages linking to this page
				if ( $linkNs ) {
					if ( $ns != $linkNs ) {
						// should never happen, but check anyway
						continue;
					}
					$subconds = [ "{$prefix}_to" => $this->targetTitle->getDBkey() ];
				} else {
					$subconds = [ "{$prefix}_namespace" => $ns, "{$prefix}_title" => $this->targetTitle->getDBkey() ];
				}
				$subjoin = "rc_cur_id = {$prefix}_from";
			} else {
				// find changes to pages linked from this page
				$subconds = [ "{$prefix}_from" => $this->targetTitle->getArticleID() ];
				if ( $linkTable == 'imagelinks' || $linkTable == 'categorylinks' ) {
					$subconds["rc_namespace"] = $linkNs;
					$subjoin = "rc_title = {$prefix}_to";
				} else {
					$subjoin = [ "rc_namespace = {$prefix}_namespace", "rc_title = {$prefix}_title" ];
				}
			}

			// Create a subquery based on the base query, adding a join to $linkTable
			// and the appropriate conditions
			$subquery = $dbr->selectSQLText(
				array_merge( $tables, [ $linkTable ] ),
				$fields,
				$conds + $subconds,
				__METHOD__,
				$options,
				$join_conds + [ $linkTable => [ 'INNER JOIN', $subjoin ] ]
			);

			$subqueries[] = $subquery;
		}

		if ( count( $subqueries ) === 1 && $dbr->unionSupportsOrderAndLimit() ) {
			$query = $subqueries[0];
		} else {
			// need to resort and relimit after union
			$query = $dbr->unionQueries( $subqueries, false ) . ' ORDER BY rc_timestamp DESC';
			$query = $dbr->limitResult( $query, $limit, false );
		}

		return $dbr->query( $query, $fname );
	}
}
