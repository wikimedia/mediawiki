<?php
/**
 * Implements Special:Recentchangeslinked
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
 * This is to display changes made to all articles linked in an article.
 *
 * @ingroup SpecialPage
 */
class SpecialRecentChangesLinked extends SpecialRecentChanges {
	/** @var bool|Title */
	protected $rclTargetTitle;

	function __construct() {
		parent::__construct( 'Recentchangeslinked' );
	}

	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$opts->add( 'target', '' );
		$opts->add( 'showlinkedto', false );

		return $opts;
	}

	public function parseParameters( $par, FormOptions $opts ) {
		$opts['target'] = $par;
	}

	/**
	 * @inheritdoc
	 */
	protected function doMainQuery( $tables, $select, $conds, $query_options,
		$join_conds, FormOptions $opts ) {

		$target = $opts['target'];
		$showlinkedto = $opts['showlinkedto'];
		$limit = $opts['limit'];

		if ( $target === '' ) {
			return false;
		}
		$outputPage = $this->getOutput();
		$title = Title::newFromText( $target );
		if ( !$title || $title->isExternal() ) {
			$outputPage->addHTML( '<div class="errorbox">' . $this->msg( 'allpagesbadtitle' )
					->parse() . '</div>' );

			return false;
		}

		$outputPage->setPageTitle( $this->msg( 'recentchangeslinked-title', $title->getPrefixedText() ) );

		/*
		 * Ordinary links are in the pagelinks table, while transclusions are
		 * in the templatelinks table, categorizations in categorylinks and
		 * image use in imagelinks.  We need to somehow combine all these.
		 * Special:Whatlinkshere does this by firing multiple queries and
		 * merging the results, but the code we inherit from our parent class
		 * expects only one result set so we use UNION instead.
		 */

		$dbr = wfGetDB( DB_REPLICA, 'recentchangeslinked' );
		$id = $title->getArticleID();
		$ns = $title->getNamespace();
		$dbkey = $title->getDBkey();

		$tables[] = 'recentchanges';
		$select = array_merge( RecentChange::selectFields(), $select );

		// left join with watchlist table to highlight watched rows
		$uid = $this->getUser()->getId();
		if ( $uid && $this->getUser()->isAllowed( 'viewmywatchlist' ) ) {
			$tables[] = 'watchlist';
			$select[] = 'wl_user';
			$join_conds['watchlist'] = [ 'LEFT JOIN', [
				'wl_user' => $uid,
				'wl_title=rc_title',
				'wl_namespace=rc_namespace'
			] ];
		}
		if ( $this->getUser()->isAllowed( 'rollback' ) ) {
			$tables[] = 'page';
			$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
			$select[] = 'page_latest';
		}
		ChangeTags::modifyDisplayQuery(
			$tables,
			$select,
			$conds,
			$join_conds,
			$query_options,
			$opts['tagfilter']
		);

		if ( !$this->runMainQueryHook( $tables, $select, $conds, $query_options, $join_conds,
			$opts )
		) {
			return false;
		}

		if ( $ns == NS_CATEGORY && !$showlinkedto ) {
			// special handling for categories
			// XXX: should try to make this less kludgy
			$link_tables = [ 'categorylinks' ];
			$showlinkedto = true;
		} else {
			// for now, always join on these tables; really should be configurable as in whatlinkshere
			$link_tables = [ 'pagelinks', 'templatelinks' ];
			// imagelinks only contains links to pages in NS_FILE
			if ( $ns == NS_FILE || !$showlinkedto ) {
				$link_tables[] = 'imagelinks';
			}
		}

		if ( $id == 0 && !$showlinkedto ) {
			return false; // nonexistent pages can't link to any pages
		}

		// field name prefixes for all the various tables we might want to join with
		$prefix = [
			'pagelinks' => 'pl',
			'templatelinks' => 'tl',
			'categorylinks' => 'cl',
			'imagelinks' => 'il'
		];

		$subsql = []; // SELECT statements to combine with UNION

		foreach ( $link_tables as $link_table ) {
			$pfx = $prefix[$link_table];

			// imagelinks and categorylinks tables have no xx_namespace field,
			// and have xx_to instead of xx_title
			if ( $link_table == 'imagelinks' ) {
				$link_ns = NS_FILE;
			} elseif ( $link_table == 'categorylinks' ) {
				$link_ns = NS_CATEGORY;
			} else {
				$link_ns = 0;
			}

			if ( $showlinkedto ) {
				// find changes to pages linking to this page
				if ( $link_ns ) {
					if ( $ns != $link_ns ) {
						continue;
					} // should never happen, but check anyway
					$subconds = [ "{$pfx}_to" => $dbkey ];
				} else {
					$subconds = [ "{$pfx}_namespace" => $ns, "{$pfx}_title" => $dbkey ];
				}
				$subjoin = "rc_cur_id = {$pfx}_from";
			} else {
				// find changes to pages linked from this page
				$subconds = [ "{$pfx}_from" => $id ];
				if ( $link_table == 'imagelinks' || $link_table == 'categorylinks' ) {
					$subconds["rc_namespace"] = $link_ns;
					$subjoin = "rc_title = {$pfx}_to";
				} else {
					$subjoin = [ "rc_namespace = {$pfx}_namespace", "rc_title = {$pfx}_title" ];
				}
			}

			if ( $dbr->unionSupportsOrderAndLimit() ) {
				$order = [ 'ORDER BY' => 'rc_timestamp DESC' ];
			} else {
				$order = [];
			}

			$query = $dbr->selectSQLText(
				array_merge( $tables, [ $link_table ] ),
				$select,
				$conds + $subconds,
				__METHOD__,
				$order + $query_options,
				$join_conds + [ $link_table => [ 'INNER JOIN', $subjoin ] ]
			);

			if ( $dbr->unionSupportsOrderAndLimit() ) {
				$query = $dbr->limitResult( $query, $limit );
			}

			$subsql[] = $query;
		}

		if ( count( $subsql ) == 0 ) {
			return false; // should never happen
		}
		if ( count( $subsql ) == 1 && $dbr->unionSupportsOrderAndLimit() ) {
			$sql = $subsql[0];
		} else {
			// need to resort and relimit after union
			$sql = $dbr->unionQueries( $subsql, false ) . ' ORDER BY rc_timestamp DESC';
			$sql = $dbr->limitResult( $sql, $limit, false );
		}

		$res = $dbr->query( $sql, __METHOD__ );

		if ( $res->numRows() == 0 ) {
			$this->mResultEmpty = true;
		}

		return $res;
	}

	function setTopText( FormOptions $opts ) {
		$target = $this->getTargetTitle();
		if ( $target ) {
			$this->getOutput()->addBacklinkSubtitle( $target );
			$this->getSkin()->setRelevantTitle( $target );
		}
	}

	/**
	 * Get options to be displayed in a form
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	function getExtraOptions( $opts ) {
		$extraOpts = parent::getExtraOptions( $opts );

		$opts->consumeValues( [ 'showlinkedto', 'target' ] );

		$extraOpts['target'] = [ $this->msg( 'recentchangeslinked-page' )->escaped(),
			Xml::input( 'target', 40, str_replace( '_', ' ', $opts['target'] ) ) .
			Xml::check( 'showlinkedto', $opts['showlinkedto'], [ 'id' => 'showlinkedto' ] ) . ' ' .
			Xml::label( $this->msg( 'recentchangeslinked-to' )->text(), 'showlinkedto' ) ];

		$this->addHelpLink( 'Help:Related changes' );
		return $extraOpts;
	}

	/**
	 * @return Title
	 */
	function getTargetTitle() {
		if ( $this->rclTargetTitle === null ) {
			$opts = $this->getOptions();
			if ( isset( $opts['target'] ) && $opts['target'] !== '' ) {
				$this->rclTargetTitle = Title::newFromText( $opts['target'] );
			} else {
				$this->rclTargetTitle = false;
			}
		}

		return $this->rclTargetTitle;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset );
	}
}
