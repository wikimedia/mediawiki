<?php
/**
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
 */

/**
 * This is to display changes made to all articles linked in an article.
 * @ingroup SpecialPage
 */
class SpecialRecentchangeslinked extends SpecialRecentchanges {
	var $rclTargetTitle;

	function __construct(){
		parent::__construct( 'Recentchangeslinked' );
	}

	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$opts->add( 'target', '' );
		$opts->add( 'showlinkedto', false );
		$opts->add( 'tagfilter', '' );
		return $opts;
	}

	public function parseParameters( $par, FormOptions $opts ) {
		$opts['target'] = $par;
	}

	public function feedSetup() {
		global $wgRequest;
		$opts = parent::feedSetup();
		$opts['target'] = $wgRequest->getVal( 'target' );
		return $opts;
	}

	public function getFeedObject( $feedFormat ){
		$feed = new ChangesFeed( $feedFormat, false );
		$feedObj = $feed->getFeedObject(
			wfMsgForContent( 'recentchangeslinked-title', $this->getTargetTitle()->getPrefixedText() ),
			wfMsgForContent( 'recentchangeslinked-feed' )
		);
		return array( $feed, $feedObj );
	}

	public function doMainQuery( $conds, $opts ) {
		global $wgUser, $wgOut;

		$target = $opts['target'];
		$showlinkedto = $opts['showlinkedto'];
		$limit = $opts['limit'];

		if ( $target === '' ) {
			return false;
		}
		$title = Title::newFromURL( $target );
		if( !$title || $title->getInterwiki() != '' ){
			$wgOut->wrapWikiMsg( "<div class=\"errorbox\">\n$1\n</div><br style=\"clear: both\" />", 'allpagesbadtitle' );
			return false;
		}

		$wgOut->setPageTitle( wfMsg( 'recentchangeslinked-title', $title->getPrefixedText() ) );

		/*
		 * Ordinary links are in the pagelinks table, while transclusions are
		 * in the templatelinks table, categorizations in categorylinks and
		 * image use in imagelinks.  We need to somehow combine all these.
		 * Special:Whatlinkshere does this by firing multiple queries and
		 * merging the results, but the code we inherit from our parent class
		 * expects only one result set so we use UNION instead.
		 */

		$dbr = wfGetDB( DB_SLAVE, 'recentchangeslinked' );
		$id = $title->getArticleId();
		$ns = $title->getNamespace();
		$dbkey = $title->getDBkey();

		$tables = array( 'recentchanges' );
		$select = array( $dbr->tableName( 'recentchanges' ) . '.*' );
		$join_conds = array();
		$query_options = array();

		// left join with watchlist table to highlight watched rows
		if( $uid = $wgUser->getId() ) {
			$tables[] = 'watchlist';
			$select[] = 'wl_user';
			$join_conds['watchlist'] = array( 'LEFT JOIN', "wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace" );
		}
		if ( $wgUser->isAllowed( 'rollback' ) ) {
			$tables[] = 'page';
			$join_conds['page'] = array('LEFT JOIN', 'rc_cur_id=page_id');
			$select[] = 'page_latest';
		}
		if ( !$this->including() ) { // bug 23293
			ChangeTags::modifyDisplayQuery( $tables, $select, $conds, $join_conds,
				$query_options, $opts['tagfilter'] );
		}

		// XXX: parent class does this, should we too?
		// wfRunHooks('SpecialRecentChangesQuery', array( &$conds, &$tables, &$join_conds, $opts ) );

		if( $ns == NS_CATEGORY && !$showlinkedto ) {
			// special handling for categories
			// XXX: should try to make this less klugy
			$link_tables = array( 'categorylinks' );
			$showlinkedto = true;
		} else {
			// for now, always join on these tables; really should be configurable as in whatlinkshere
			$link_tables = array( 'pagelinks', 'templatelinks' );
			// imagelinks only contains links to pages in NS_FILE
			if( $ns == NS_FILE || !$showlinkedto ) $link_tables[] = 'imagelinks';
		}

		if( $id == 0 && !$showlinkedto )
			return false; // nonexistent pages can't link to any pages

		// field name prefixes for all the various tables we might want to join with
		$prefix = array( 'pagelinks' => 'pl', 'templatelinks' => 'tl', 'categorylinks' => 'cl', 'imagelinks' => 'il' );

		$subsql = array(); // SELECT statements to combine with UNION

		foreach( $link_tables as $link_table ) {
			$pfx = $prefix[$link_table];

			// imagelinks and categorylinks tables have no xx_namespace field, and have xx_to instead of xx_title
			if( $link_table == 'imagelinks' ) $link_ns = NS_FILE;
			else if( $link_table == 'categorylinks' ) $link_ns = NS_CATEGORY;
			else $link_ns = 0;

			if( $showlinkedto ) {
				// find changes to pages linking to this page
				if( $link_ns ) {
					if( $ns != $link_ns ) continue; // should never happen, but check anyway
					$subconds = array( "{$pfx}_to" => $dbkey );
				} else {
					$subconds = array( "{$pfx}_namespace" => $ns, "{$pfx}_title" => $dbkey );
				}
				$subjoin = "rc_cur_id = {$pfx}_from";
			} else {
				// find changes to pages linked from this page
				$subconds = array( "{$pfx}_from" => $id );
				if( $link_table == 'imagelinks' || $link_table == 'categorylinks' ) {
					$subconds["rc_namespace"] = $link_ns;
					$subjoin = "rc_title = {$pfx}_to";
				} else {
					$subjoin = "rc_namespace = {$pfx}_namespace AND rc_title = {$pfx}_title";
				}
			}

			if( $dbr->unionSupportsOrderAndLimit())
				$order = array( 'ORDER BY' => 'rc_timestamp DESC' );
			else
				$order = array();

			
			$query = $dbr->selectSQLText( 
				array_merge( $tables, array( $link_table ) ), 
				$select, 
				$conds + $subconds,
				__METHOD__, 
				$order + $query_options,
				$join_conds + array( $link_table => array( 'INNER JOIN', $subjoin ) )
			);
			
			if( $dbr->unionSupportsOrderAndLimit())
				$query = $dbr->limitResult( $query, $limit );

			$subsql[] = $query;
		}

		if( count($subsql) == 0 )
			return false; // should never happen
		if( count($subsql) == 1 && $dbr->unionSupportsOrderAndLimit() )
			$sql = $subsql[0];
		else {
			// need to resort and relimit after union
			$sql = $dbr->unionQueries($subsql, false).' ORDER BY rc_timestamp DESC';
			$sql = $dbr->limitResult($sql, $limit, false);
		}
		
		$res = $dbr->query( $sql, __METHOD__ );

		if( $res->numRows() == 0 )
			$this->mResultEmpty = true;

		return $res;
	}
	
	function getExtraOptions( $opts ){
		$opts->consumeValues( array( 'showlinkedto', 'target', 'tagfilter' ) );
		$extraOpts = array();
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );
		$extraOpts['target'] = array( wfMsgHtml( 'recentchangeslinked-page' ),
			Xml::input( 'target', 40, str_replace('_',' ',$opts['target']) ) .
			Xml::check( 'showlinkedto', $opts['showlinkedto'], array('id' => 'showlinkedto') ) . ' ' .
			Xml::label( wfMsg("recentchangeslinked-to"), 'showlinkedto' ) );
		$tagFilter = ChangeTags::buildTagFilterSelector( $opts['tagfilter'] );
		if ($tagFilter)
			$extraOpts['tagfilter'] = $tagFilter;
		return $extraOpts;
	}

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

	function setTopText( OutputPage $out, FormOptions $opts ) {
		global $wgUser;
		$skin = $wgUser->getSkin();
		$target = $this->getTargetTitle();
		if( $target )
			$out->setSubtitle( wfMsg( 'recentchangeslinked-backlink', $skin->link( $target,
				$target->getPrefixedText(), array(), array( 'redirect' => 'no'  ) ) ) );
	}

	public function getFeedQuery() {
		$target = $this->getTargetTitle();
		if( $target ) {
			return "target=" . urlencode( $target->getPrefixedDBkey() );
		} else {
			return false;
		}
	}

	function setBottomText( OutputPage $out, FormOptions $opts ) {
		if( isset( $this->mResultEmpty ) && $this->mResultEmpty ){
			$out->addWikiMsg( 'recentchangeslinked-noresult' );	
		}
	}
}
