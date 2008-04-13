<?php
/**
 *
 * @addtogroup SpecialPage
 */


/**
 * Start point
 */
function wfSpecialNewPages( $par ) {
	$page = new NewPagesForm();
	
	$page->showList( $par );
}

/**
 * implements Special:Newpages
 * @addtogroup SpecialPage
 */
class NewPagesForm {
	/**
	 * Show a form for filtering namespace and username
	 *
	 * @return string
	 */
	public function showList() {
		global $wgScript, $wgContLang, $wgGroupPermissions, $wgRequest, $wgUser, $wgOut;
		$sk = $wgUser->getSkin();
		$align = $wgContLang->isRTL() ? 'left' : 'right';
		$self = SpecialPage::getTitleFor( 'NewPages' );

		// show/hide links
		$showhide = array( wfMsgHtml( 'show' ), wfMsgHtml( 'hide' ));

		$hidelinks = array();

		if ( $wgGroupPermissions['*']['createpage'] === true ) {
			$hidelinks['hideliu'] = 'rcshowhideliu';
		}
		if ( $wgUser->useNPPatrol() ) {
			$hidelinks['hidepatrolled'] = 'rcshowhidepatr';
		}
		$hidelinks['hidebots'] = 'rcshowhidebots';

		$defaults = array(
            /* bool */ 'hideliu' => false,
            /* bool */ 'hidepatrolled' => false,
            /* bool */ 'hidebots' => false,
            /* text */ 'namespace' => "0",
            /* text */ 'username' => '',
            /* int  */ 'offset' => 0,
            /* int  */ 'limit' => 50,
        );
		$options = $defaults;
		
		// Override all values from requests, if specified
        foreach ( $defaults as $v => $t ) {
            if ( is_bool($t) ) {
                $options[$v] = $wgRequest->getBool( $v, $options[$v] );
            } elseif( is_int($t) ) {
                $options[$v] = $wgRequest->getInt( $v, $options[$v] );
            } elseif( is_string($t) ) {
                $options[$v] = $wgRequest->getText( $v, $options[$v] );
            }
        }
		
		$wgOut->setSyndicated( true );
		$wgOut->setFeedAppendQuery( "namespace={$options['namespace']}&username={$options['username']}" );

		$feedType = $wgRequest->getVal( 'feed' );
		if( $feedType ) {
			wfProfileOut( __METHOD__ );
			return $this->feed( $feedType, $options );
		}

		$nondefaults = array();
        foreach ( $options as $v => $t ) {
            if ( $v === 'offset' ) continue; # Reset offset if parameters change
            wfAppendToArrayIfNotDefault( $v, $t, $defaults, $nondefaults );
        }

		$links = array();
		foreach ( $hidelinks as $key => $msg ) {
			$reversed = 1 - $options[$key];
			$link = $sk->makeKnownLinkObj( $self, $showhide[$reversed],
				wfArrayToCGI( array( $key => $reversed ), $nondefaults )
			);
			$links[$key] = wfMsgHtml( $msg, $link );
		}

		$hl = implode( ' | ', $links );

		// Store query values in hidden fields so that form submission doesn't lose them
		$hidden = array();
		foreach ( $nondefaults as $key => $value ) {
			if ( $key === 'namespace' ) continue;
			if ( $key === 'username' ) continue;
			$hidden[] = Xml::hidden( $key, $value );
		}
		$hidden = implode( "\n", $hidden );

		$form = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::hidden( 'title', $self->getPrefixedDBkey() ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'newpages' ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-newpages-table' ) ) .
			"<tr>
				<td align=\"$align\">" .
					Xml::label( wfMsg( 'namespace' ), 'namespace' ) .
				"</td>
				<td>" .
					Xml::namespaceSelector( $options['namespace'], 'all' ) .
				"</td>
			</tr>
			<tr>
				<td align=\"$align\">" .
					Xml::label( wfMsg( 'newpages-username' ), 'mw-np-username' ) .
				"</td>
				<td>" .
					Xml::input( 'username', 30, $options['username'], array( 'id' => 'mw-np-username' ) ) .
				"</td>
			</tr>
			<tr> <td></td>
				<td>" .
					Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
				"</td>
			</tr>" .
			"<tr>
				<td></td>
				<td>" .
					$hl .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			$hidden .
			Xml::closeElement( 'form' );

		$wgOut->addHTML( $form );

		$pager = new NewPagesPager( $this, array(), $options['namespace'], $options['hideliu'], 
			$options['hidepatrolled'], $options['hidebots'], $options['username'] );	

		if( $pager->getNumRows() ) {
			$wgOut->addHTML( $pager->getNavigationBar() .
				$pager->getStartBody() .
				$pager->getBody() .
				$pager->getEndBody() .
				$pager->getNavigationBar() );
		} else {
			$wgOut->addHTML( '<p>' . wfMsgHtml( 'specialpage-empty' ) . '</p>' );
		}
	}
	
	/**
	 * Format a row, providing the timestamp, links to the page/history, size, user links, and a comment
	 *
	 * @param $skin Skin to use
	 * @param $result Result row
	 * @return string
	 */
	public function formatRow( $result ) {
		global $wgLang, $wgContLang, $wgUser;
		$dm = $wgContLang->getDirMark();
		
		static $skin=null;

		if( is_null( $skin ) )
			$skin = $wgUser->getSkin();

		$title = Title::makeTitleSafe( $result->rc_namespace, $result->rc_title );
		$time = $wgLang->timeAndDate( $result->rc_timestamp, true );
		$plink = $skin->makeKnownLinkObj( $title, '', $this->patrollable( $result ) ? 'rcid=' . $result->rc_id : '' );
		$hist = $skin->makeKnownLinkObj( $title, wfMsgHtml( 'hist' ), 'action=history' );
		$length = wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ), 
			$wgLang->formatNum( htmlspecialchars( $result->length ) ) );
		$ulink = $skin->userLink( $result->rc_user, $result->rc_user_text ) . ' ' . 
			$skin->userToolLinks( $result->rc_user, $result->rc_user_text );
		$comment = $skin->commentBlock( $result->rc_comment );
		$css = $this->patrollable( $result ) ? 'not-patrolled' : '';

		return "<li class='$css'>{$time} {$dm}{$plink} ({$hist}) {$dm}[{$length}] {$dm}{$ulink} {$comment}</li>";
	}

	/**
	 * Should a specific result row provide "patrollable" links?
	 *
	 * @param $result Result row
	 * @return bool
	 */
	protected function patrollable( $result ) {
		global $wgUser;
		return ( $wgUser->useNPPatrol() && !$result->rc_patrolled );
	}
	
	/**
	 * Output a subscription feed listing recent edits to this page.
	 * @param string $type
	 */
	protected function feed( $type, $options ) {
		require_once 'SpecialRecentchanges.php';
		
		global $wgFeed, $wgFeedClasses;
		
		if ( !$wgFeed ) {
			global $wgOut;
			$wgOut->addWikiMsg( 'feed-unavailable' );
			return;
		}
		
		if( !isset( $wgFeedClasses[$type] ) ) {
			global $wgOut;
			$wgOut->addWikiMsg( 'feed-invalid' );
			return;
		}
		
		$self = SpecialPage::getTitleFor( 'NewPages' );
		$feed = new $wgFeedClasses[$type](
			$this->feedTitle(),
			wfMsg( 'tagline' ),
			$self->getFullUrl() );

		$pager = new NewPagesPager( $this, array(), $options['namespace'], $options['hideliu'], 
			$options['hidepatrolled'], $options['hidebots'], $options['username'] );	
		
		$feed->outHeader();
		if( $pager->getNumRows() > 0 ) {
			while( $row = $pager->mResult->fetchObject() ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		}
		$feed->outFooter();
	}
	
	protected function feedTitle() {
		global $wgContLanguageCode, $wgSitename;
		$page = SpecialPage::getPage( 'Newpages' );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgContLanguageCode]";
	}

	protected function feedItem( $row ) {
		$title = Title::MakeTitle( intval( $row->rc_namespace ), $row->rc_title );
		if( $title ) {
			$date = $row->rc_timestamp;
			$comments = $this->stripComment( $row->rc_comment );

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ),
				$comments);
		} else {
			return NULL;
		}
	}
	
	/**
	 * Quickie hack... strip out wikilinks to more legible form from the comment.
	 */
	function stripComment( $text ) {
		return preg_replace( '/\[\[([^]]*\|)?([^]]+)\]\]/', '\2', $text );
	}
	
	function feedItemAuthor( $row ) {
		return isset( $row->rc_user_text ) ? $row->rc_user_text : '';
	}
	
	protected function feedItemDesc( $row ) {
		$revision = Revision::newFromId( $row->rev_id );
		if( $revision ) {
			return '<p>' . htmlspecialchars( wfMsg( 'summary' ) ) . ': ' .
				htmlspecialchars( $revision->getComment() ) . "</p>\n<hr />\n<div>" .
				nl2br( htmlspecialchars( $revision->getText() ) ) . "</div>";
		}
		return '';
	}
}

/**
 * @addtogroup Pager
 */
class NewPagesPager extends ReverseChronologicalPager {
	private $hideliu, $hidepatrolled, $hidebots, $namespace, $user;
	
	function __construct( $form, $conds=array(), $namespace, $hliu=false, $hpatrolled=false, $hbots=1, $user='' ) {
		parent::__construct();
		$this->mForm = $form;
		$this->mConds = $conds;
		
		$this->namespace = ($namespace === "all") ? false : intval($namespace);
		$this->user = $user;
		
		$this->hideliu = (bool)$hliu;
		$this->hidepatrolled = (bool)$hpatrolled;
		$this->hidebots = (bool)$hbots;
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds['rc_new'] = 1;
		if( $this->namespace !== false ) {
			$conds['rc_namespace'] = $this->namespace;
			$rcIndexes = array( 'new_name_timestamp', 'rc_timestamp', 'rc_user_text' );
		} else {
			$rcIndexes = array( 'rc_timestamp', 'rc_user_text' );
		}
		$conds[] = 'page_id = rc_cur_id';
		$conds['page_is_redirect'] = 0;
		
		global $wgGroupPermissions, $wgUser;
		# If anons cannot make new pages, don't query for it!
		if( $wgGroupPermissions['*']['createpage'] && $this->hideliu ) {
			$conds['rc_user'] = 0;
		} else {
			$title = Title::makeTitleSafe( NS_USER, $this->user );
			if( $title ) {
				$conds['rc_user_text'] = $title->getText();
			}
		}
		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if( $this->hidepatrolled && $wgUser->useNPPatrol() ) {
			$conds['rc_patrolled'] = 0;
		}
		if( $this->hidebots ) {
			$conds['rc_bot'] = 0;
		}

		if( $this->user ) {
			$conds['rc_user_text'] = $this->user;
		}

		return array(
			'tables' => array( 'recentchanges', 'page' ),
			'fields' => 'rc_namespace,rc_title, rc_cur_id, rc_user,rc_user_text,rc_comment,
				rc_timestamp,rc_patrolled,rc_id,page_len as length, page_latest as rev_id',
			'conds' => $conds,
			'options' => array( 'USE INDEX' => array('recentchanges' => $rcIndexes) )
		);
	}

	function getIndexField() {
		return 'rc_timestamp';
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}
	
	function getStartBody() {
		# Do a batch existence check on pages
		$linkBatch = new LinkBatch();
		while( $row = $this->mResult->fetchObject() ) {
			$linkBatch->add( NS_USER, $row->rc_user_text );
			$linkBatch->add( NS_USER_TALK, $row->rc_user_text );
			$linkBatch->add( $row->rc_namespace, $row->rc_title );
		}
		$linkBatch->execute();
		return "<ul>";
	}

	function getEndBody() {
		return "</ul>";
	}
}
