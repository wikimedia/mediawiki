<?php
/**
 * Implements Special:Newpages
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
 * A special page that list newly created pages
 *
 * @ingroup SpecialPage
 */
class SpecialNewpages extends IncludableSpecialPage {

	// Stored objects

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @var Skin
	 */
	protected $skin;

	// Some internal settings
	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'Newpages' );
	}

	protected function setup( $par ) {
		global $wgRequest, $wgUser, $wgEnableNewpagesUserFilter;

		// Options
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', $wgUser->getBoolOption( 'newpageshidepatrolled' ) );
		$opts->add( 'hidebots', false );
		$opts->add( 'hideredirs', true );
		$opts->add( 'limit', (int)$wgUser->getOption( 'rclimit' ) );
		$opts->add( 'offset', '' );
		$opts->add( 'namespace', '0' );
		$opts->add( 'username', '' );
		$opts->add( 'feed', '' );
		$opts->add( 'tagfilter', '' );

		// Set values
		$opts->fetchValuesFromRequest( $wgRequest );
		if ( $par ) $this->parseParams( $par );

		// Validate
		$opts->validateIntBounds( 'limit', 0, 5000 );
		if( !$wgEnableNewpagesUserFilter ) {
			$opts->setValue( 'username', '' );
		}

		// Store some objects
		$this->skin = $wgUser->getSkin();
	}

	protected function parseParams( $par ) {
		global $wgLang;
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'shownav' == $bit ) {
				$this->showNavigation = true;
			}
			if ( 'hideliu' === $bit ) {
				$this->opts->setValue( 'hideliu', true );
			}
			if ( 'hidepatrolled' == $bit ) {
				$this->opts->setValue( 'hidepatrolled', true );
			}
			if ( 'hidebots' == $bit ) {
				$this->opts->setValue( 'hidebots', true );
			}
			if ( 'showredirs' == $bit ) {
				$this->opts->setValue( 'hideredirs', false );
			}
			if ( is_numeric( $bit ) ) {
				$this->opts->setValue( 'limit', intval( $bit ) );
			}

			$m = array();
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$this->opts->setValue( 'limit', intval( $m[1] ) );
			}
			// PG offsets not just digits!
			if ( preg_match( '/^offset=([^=]+)$/', $bit, $m ) ) {
				$this->opts->setValue( 'offset',  intval( $m[1] ) );
			}
			if ( preg_match( '/^username=(.*)$/', $bit, $m ) ) {
				$this->opts->setValue( 'username', $m[1] );
			}
			if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$ns = $wgLang->getNsIndex( $m[1] );
				if( $ns !== false ) {
					$this->opts->setValue( 'namespace',  $ns );
				}
			}
		}
	}

	/**
	 * Show a form for filtering namespace and username
	 *
	 * @param $par String
	 * @return String
	 */
	public function execute( $par ) {
		global $wgOut;

		$this->setHeaders();
		$this->outputHeader();

		$this->showNavigation = !$this->including(); // Maybe changed in setup
		$this->setup( $par );

		if( !$this->including() ) {
			// Settings
			$this->form();

			$this->setSyndicated();
			$feedType = $this->opts->getValue( 'feed' );
			if( $feedType ) {
				return $this->feed( $feedType );
			}
		}

		$pager = new NewPagesPager( $this, $this->opts );
		$pager->mLimit = $this->opts->getValue( 'limit' );
		$pager->mOffset = $this->opts->getValue( 'offset' );

		if( $pager->getNumRows() ) {
			$navigation = '';
			if ( $this->showNavigation ) {
				$navigation = $pager->getNavigationBar();
			}
			$wgOut->addHTML( $navigation . $pager->getBody() . $navigation );
		} else {
			$wgOut->addWikiMsg( 'specialpage-empty' );
		}
	}

	protected function filterLinks() {
		global $wgGroupPermissions, $wgUser, $wgLang;

		// show/hide links
		$showhide = array( wfMsgHtml( 'show' ), wfMsgHtml( 'hide' ) );

		// Option value -> message mapping
		$filters = array(
			'hideliu' => 'rcshowhideliu',
			'hidepatrolled' => 'rcshowhidepatr',
			'hidebots' => 'rcshowhidebots',
			'hideredirs' => 'whatlinkshere-hideredirs'
		);

		// Disable some if needed
		# FIXME: throws E_NOTICEs if not set; and doesn't obey hooks etc.
		if ( $wgGroupPermissions['*']['createpage'] !== true ) {
			unset( $filters['hideliu'] );
		}

		if ( !$wgUser->useNPPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = array();
		$changed = $this->opts->getChangedValues();
		unset( $changed['offset'] ); // Reset offset if query type changes

		$self = $this->getTitle();
		foreach ( $filters as $key => $msg ) {
			$onoff = 1 - $this->opts->getValue( $key );
			$link = $this->skin->link( $self, $showhide[$onoff], array(),
					array( $key => $onoff ) + $changed
			);
			$links[$key] = wfMsgHtml( $msg, $link );
		}

		return $wgLang->pipeList( $links );
	}

	protected function form() {
		global $wgOut, $wgEnableNewpagesUserFilter, $wgScript;

		// Consume values
		$this->opts->consumeValue( 'offset' ); // don't carry offset, DWIW
		$namespace = $this->opts->consumeValue( 'namespace' );
		$username = $this->opts->consumeValue( 'username' );
		$tagFilterVal = $this->opts->consumeValue( 'tagfilter' );

		// Check username input validity
		$ut = Title::makeTitleSafe( NS_USER, $username );
		$userText = $ut ? $ut->getText() : '';

		// Store query values in hidden fields so that form submission doesn't lose them
		$hidden = array();
		foreach ( $this->opts->getUnconsumedValues() as $key => $value ) {
			$hidden[] = Html::hidden( $key, $value );
		}
		$hidden = implode( "\n", $hidden );

		$tagFilter = ChangeTags::buildTagFilterSelector( $tagFilterVal );
		if ( $tagFilter ) {
			list( $tagFilterLabel, $tagFilterSelector ) = $tagFilter;
		}

		$form = Xml::openElement( 'form', array( 'action' => $wgScript ) ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) .
			Xml::fieldset( wfMsg( 'newpages' ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-newpages-table' ) ) .
			'<tr>
				<td class="mw-label">' .
					Xml::label( wfMsg( 'namespace' ), 'namespace' ) .
				'</td>
				<td class="mw-input">' .
					Xml::namespaceSelector( $namespace, 'all' ) .
				'</td>
			</tr>' . ( $tagFilter ? (
			'<tr>
				<td class="mw-label">' .
					$tagFilterLabel .
				'</td>
				<td class="mw-input">' .
					$tagFilterSelector .
				'</td>
			</tr>' ) : '' ) .
			( $wgEnableNewpagesUserFilter ?
			'<tr>
				<td class="mw-label">' .
					Xml::label( wfMsg( 'newpages-username' ), 'mw-np-username' ) .
				'</td>
				<td class="mw-input">' .
					Xml::input( 'username', 30, $userText, array( 'id' => 'mw-np-username' ) ) .
				'</td>
			</tr>' : '' ) .
			'<tr> <td></td>
				<td class="mw-submit">' .
					Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
				'</td>
			</tr>' .
			'<tr>
				<td></td>
				<td class="mw-input">' .
					$this->filterLinks() .
				'</td>
			</tr>' .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			$hidden .
			Xml::closeElement( 'form' );

		$wgOut->addHTML( $form );
	}

	protected function setSyndicated() {
		global $wgOut;
		$wgOut->setSyndicated( true );
		$wgOut->setFeedAppendQuery( wfArrayToCGI( $this->opts->getAllValues() ) );
	}

	/**
	 * Format a row, providing the timestamp, links to the page/history, size, user links, and a comment
	 *
	 * @param $result Result row
	 * @return String
	 */
	public function formatRow( $result ) {
		global $wgLang, $wgContLang;

		$classes = array();
		
		$dm = $wgContLang->getDirMark();

		$title = Title::makeTitleSafe( $result->rc_namespace, $result->rc_title );
		$time = Html::element( 'span', array( 'class' => 'mw-newpages-time' ),
			$wgLang->timeAndDate( $result->rc_timestamp, true )
		);

		$query = array( 'redirect' => 'no' );

		if( $this->patrollable( $result ) ) {
			$query['rcid'] = $result->rc_id;
		}

		$plink = $this->skin->linkKnown(
			$title,
			null,
			array( 'class' => 'mw-newpages-pagename' ),
			$query,
			array( 'known' ) // Set explicitly to avoid the default of 'known','noclasses'. This breaks the colouration for stubs
		);
		$histLink = $this->skin->linkKnown(
			$title,
			wfMsgHtml( 'hist' ),
			array(),
			array( 'action' => 'history' )
		);
		$hist = Html::rawElement( 'span', array( 'class' => 'mw-newpages-history' ), wfMsg( 'parentheses', $histLink ) );

		$length = Html::rawElement( 'span', array( 'class' => 'mw-newpages-length' ),
				'[' . wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ), $wgLang->formatNum( $result->length ) ) .
				']'
		);
		$ulink = $this->skin->userLink( $result->rc_user, $result->rc_user_text ) . ' ' .
			$this->skin->userToolLinks( $result->rc_user, $result->rc_user_text );
		$comment = $this->skin->commentBlock( $result->rc_comment );
		
		if ( $this->patrollable( $result ) ) {
			$classes[] = 'not-patrolled';
		}

		# Add a class for zero byte pages
		if ( $result->length == 0 ) {
			$classes[] = 'mw-newpages-zero-byte-page';
		}

		# Tags, if any. check for including due to bug 23293
		if ( !$this->including() ) {
			list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow( $result->ts_tags, 'newpages' );
			$classes = array_merge( $classes, $newClasses );
		} else {
			$tagDisplay = '';
		}

		$css = count( $classes ) ? ' class="' . implode( ' ', $classes ) . '"' : '';

		return "<li{$css}>{$time} {$dm}{$plink} {$hist} {$dm}{$length} {$dm}{$ulink} {$comment} {$tagDisplay}</li>\n";
	}

	/**
	 * Should a specific result row provide "patrollable" links?
	 *
	 * @param $result Result row
	 * @return Boolean
	 */
	protected function patrollable( $result ) {
		global $wgUser;
		return ( $wgUser->useNPPatrol() && !$result->rc_patrolled );
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 *
	 * @param $type String
	 */
	protected function feed( $type ) {
		global $wgFeed, $wgFeedClasses, $wgFeedLimit, $wgOut;

		if ( !$wgFeed ) {
			$wgOut->addWikiMsg( 'feed-unavailable' );
			return;
		}

		if( !isset( $wgFeedClasses[$type] ) ) {
			$wgOut->addWikiMsg( 'feed-invalid' );
			return;
		}

		$feed = new $wgFeedClasses[$type](
			$this->feedTitle(),
			wfMsgExt( 'tagline', 'parsemag' ),
			$this->getTitle()->getFullUrl()
		);

		$pager = new NewPagesPager( $this, $this->opts );
		$limit = $this->opts->getValue( 'limit' );
		$pager->mLimit = min( $limit, $wgFeedLimit );

		$feed->outHeader();
		if( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		}
		$feed->outFooter();
	}

	protected function feedTitle() {
		global $wgLanguageCode, $wgSitename;
		$page = SpecialPage::getPage( 'Newpages' );
		$desc = $page->getDescription();
		return "$wgSitename - $desc [$wgLanguageCode]";
	}

	protected function feedItem( $row ) {
		$title = Title::MakeTitle( intval( $row->rc_namespace ), $row->rc_title );
		if( $title ) {
			$date = $row->rc_timestamp;
			$comments = $title->getTalkPage()->getFullURL();

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ),
				$comments
			);
		} else {
			return null;
		}
	}

	protected function feedItemAuthor( $row ) {
		return isset( $row->rc_user_text ) ? $row->rc_user_text : '';
	}

	protected function feedItemDesc( $row ) {
		$revision = Revision::newFromId( $row->rev_id );
		if( $revision ) {
			return '<p>' . htmlspecialchars( $revision->getUserText() ) . wfMsgForContent( 'colon-separator' ) .
				htmlspecialchars( FeedItem::stripComment( $revision->getComment() ) ) . 
				"</p>\n<hr />\n<div>" .
				nl2br( htmlspecialchars( $revision->getText() ) ) . "</div>";
		}
		return '';
	}
}

/**
 * @ingroup SpecialPage Pager
 */
class NewPagesPager extends ReverseChronologicalPager {
	// Stored opts
	protected $opts, $mForm;

	function __construct( $form, FormOptions $opts ) {
		parent::__construct();
		$this->mForm = $form;
		$this->opts = $opts;
	}

	function getTitle() {
		static $title = null;
		if ( $title === null ) {
			$title = $this->mForm->getTitle();
		}
		return $title;
	}

	function getQueryInfo() {
		global $wgEnableNewpagesUserFilter, $wgGroupPermissions, $wgUser;
		$conds = array();
		$conds['rc_new'] = 1;

		$namespace = $this->opts->getValue( 'namespace' );
		$namespace = ( $namespace === 'all' ) ? false : intval( $namespace );

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		if( $namespace !== false ) {
			$conds['rc_namespace'] = $namespace;
			$rcIndexes = array( 'new_name_timestamp' );
		} else {
			$rcIndexes = array( 'rc_timestamp' );
		}

		# $wgEnableNewpagesUserFilter - temp WMF hack
		if( $wgEnableNewpagesUserFilter && $user ) {
			$conds['rc_user_text'] = $user->getText();
			$rcIndexes = 'rc_user_text';
		# If anons cannot make new pages, don't "exclude logged in users"!
		} elseif( $wgGroupPermissions['*']['createpage'] && $this->opts->getValue( 'hideliu' ) ) {
			$conds['rc_user'] = 0;
		}
		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if( $this->opts->getValue( 'hidepatrolled' ) && $wgUser->useNPPatrol() ) {
			$conds['rc_patrolled'] = 0;
		}
		if( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc_bot'] = 0;
		}

		if ( $this->opts->getValue( 'hideredirs' ) ) {
			$conds['page_is_redirect'] = 0;
		}
  
		// Allow changes to the New Pages query
		wfRunHooks( 'SpecialNewpagesConditions', array( &$this, $this->opts, &$conds ) );

		$info = array(
			'tables' => array( 'recentchanges', 'page' ),
			'fields' => array(
				'rc_namespace', 'rc_title', 'rc_cur_id', 'rc_user',
				'rc_user_text', 'rc_comment', 'rc_timestamp', 'rc_patrolled',
				'rc_id', 'page_len AS length', 'page_latest AS rev_id',
				'ts_tags'
			),
			'conds' => $conds,
			'options' => array( 'USE INDEX' => array( 'recentchanges' => $rcIndexes ) ),
			'join_conds' => array(
				'page' => array( 'INNER JOIN', 'page_id=rc_cur_id' ),
			),
		);

		// Empty array for fields, it'll be set by us anyway.
		$fields = array();

		// Modify query for tags
		ChangeTags::modifyDisplayQuery(
			$info['tables'],
			$fields,
			$info['conds'],
			$info['join_conds'],
			$info['options'],
			$this->opts['tagfilter']
		);

		return $info;
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
		foreach ( $this->mResult as $row ) {
			$linkBatch->add( NS_USER, $row->rc_user_text );
			$linkBatch->add( NS_USER_TALK, $row->rc_user_text );
			$linkBatch->add( $row->rc_namespace, $row->rc_title );
		}
		$linkBatch->execute();
		return '<ul>';
	}

	function getEndBody() {
		return '</ul>';
	}
}
