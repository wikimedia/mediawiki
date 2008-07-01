<?php

/**
 * This is to display changes made to all articles linked in an article.
 * @ingroup SpecialPage
 */
class SpecialRecentchangeslinked extends SpecialRecentchanges {

	function __construct(){
		SpecialPage::SpecialPage( 'Recentchangeslinked' );	
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

	public function feedSetup(){
		global $wgRequest;
		$opts = parent::feedSetup();
		$opts['target'] = $wgRequest->getVal( 'target' );
		return $opts;
	}

	public function getFeedObject( $feedFormat ){
		$feed = new ChangesFeed( $feedFormat, false );
		$feedObj = $feed->getFeedObject(
			wfMsgForContent( 'recentchangeslinked-title', $this->mTargetTitle->getPrefixedText() ),
			wfMsgForContent( 'recentchangeslinked' )
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
			global $wgOut;
			$wgOut->wrapWikiMsg( '<div class="errorbox">$1</div><br clear="both" />', 'allpagesbadtitle' );
			return false;
		}
		$this->mTargetTitle = $title;

		$wgOut->setPageTitle( wfMsg( 'recentchangeslinked-title', $title->getPrefixedText() ) );

		$dbr = wfGetDB( DB_SLAVE, 'recentchangeslinked' );
		$id = $title->getArticleId();

		$tables = array( 'recentchanges' );
		$select = array( $dbr->tableName( 'recentchanges' ) . '.*' );
		$join_conds = array();

		if( $title->getNamespace() == NS_CATEGORY ) {
			$tables[] = 'categorylinks';
			$conds['cl_to'] = $title->getDBkey();
			$join_conds['categorylinks'] = array( 'LEFT JOIN', 'cl_from=rc_cur_id' );
		} else {
			if( $showlinkedto ) {
				if( $title->getNamespace() == NS_TEMPLATE ){
					$tables[] = 'templatelinks';
					$conds['tl_namespace'] = $title->getNamespace();
					$conds['tl_title'] = $title->getDBkey();
					$join_conds['templatelinks'] = array( 'LEFT JOIN', 'tl_from=rc_cur_id' );
				} else {
					$tables[] = 'pagelinks';
					$conds['pl_namespace'] = $title->getNamespace();
					$conds['pl_title'] = $title->getDBkey();
					$join_conds['pagelinks'] = array( 'LEFT JOIN', 'pl_from=rc_cur_id' );
				}
			} else {
				$tables[] = 'pagelinks';
				$conds['pl_from'] = $id;
				$join_conds['pagelinks'] = array( 'LEFT JOIN', 'pl_namespace = rc_namespace AND pl_title = rc_title' );
			}
		}

		if( $uid = $wgUser->getId() ) {
			$tables[] = 'watchlist';
			$join_conds['watchlist'] = array( 'LEFT JOIN', "wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace" );
			$select[] = 'wl_user';
		}

		$res = $dbr->select( $tables, $select, $conds, __METHOD__,
			array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit ), $join_conds );

		if( $dbr->numRows( $res ) == 0 )
			$this->mResultEmpty = true;

		return $res;
	}
	
	function getExtraOptions( $opts ){
		$opts->consumeValues( array( 'showlinkedto', 'target' ) );
		$extraOpts = array();
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );
		$extraOpts['target'] = array( wfMsg( 'recentchangeslinked-page' ),
			Xml::input( 'target', 40, str_replace('_',' ',$opts['target']) ) .
			Xml::check( 'showlinkedto', $opts['showlinkedto'], array('id' => 'showlinkedto') ) . ' ' .
			Xml::label( wfMsg("recentchangeslinked-to"), 'showlinkedto' ) );
		$extraOpts['submit'] = Xml::submitbutton( wfMsg('allpagessubmit') );
		return $extraOpts;
	}
	
	function setTopText( &$out, $opts ){}
	
	function setBottomText( &$out, $opts ){
		if( isset( $this->mTargetTitle ) && is_object( $this->mTargetTitle ) ){
			global $wgUser;
			$out->setFeedAppendQuery( "target=" . urlencode( $this->mTargetTitle->getPrefixedDBkey() ) );
			$out->addHTML("&lt; ".$wgUser->getSkin()->makeLinkObj( $this->mTargetTitle, "", "redirect=no" )."<hr />\n");
		}
		if( isset( $this->mResultEmpty ) && $this->mResultEmpty ){
			$out->addWikiMsg( 'recentchangeslinked-noresult' );	
		}
	}
}
