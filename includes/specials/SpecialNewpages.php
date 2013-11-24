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
	protected $customFilters;

	// Some internal settings
	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'Newpages' );
	}

	protected function setup( $par ) {
		global $wgEnableNewpagesUserFilter;

		// Options
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', $this->getUser()->getBoolOption( 'newpageshidepatrolled' ) );
		$opts->add( 'hidebots', false );
		$opts->add( 'hideredirs', true );
		$opts->add( 'limit', $this->getUser()->getIntOption( 'rclimit' ) );
		$opts->add( 'offset', '' );
		$opts->add( 'namespace', '0' );
		$opts->add( 'username', '' );
		$opts->add( 'feed', '' );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'invert', false );

		$this->customFilters = array();
		wfRunHooks( 'SpecialNewPagesFilters', array( $this, &$this->customFilters ) );
		foreach ( $this->customFilters as $key => $params ) {
			$opts->add( $key, $params['default'] );
		}

		// Set values
		$opts->fetchValuesFromRequest( $this->getRequest() );
		if ( $par ) {
			$this->parseParams( $par );
		}

		// Validate
		$opts->validateIntBounds( 'limit', 0, 5000 );
		if ( !$wgEnableNewpagesUserFilter ) {
			$opts->setValue( 'username', '' );
		}
	}

	protected function parseParams( $par ) {
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
				$this->opts->setValue( 'offset', intval( $m[1] ) );
			}
			if ( preg_match( '/^username=(.*)$/', $bit, $m ) ) {
				$this->opts->setValue( 'username', $m[1] );
			}
			if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$ns = $this->getLanguage()->getNsIndex( $m[1] );
				if ( $ns !== false ) {
					$this->opts->setValue( 'namespace', $ns );
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
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$this->showNavigation = !$this->including(); // Maybe changed in setup
		$this->setup( $par );

		if ( !$this->including() ) {
			// Settings
			$this->form();

			$feedType = $this->opts->getValue( 'feed' );
			if ( $feedType ) {
				$this->feed( $feedType );

				return;
			}

			$allValues = $this->opts->getAllValues();
			unset( $allValues['feed'] );
			$out->setFeedAppendQuery( wfArrayToCgi( $allValues ) );
		}

		$pager = new NewPagesPager( $this, $this->opts );
		$pager->mLimit = $this->opts->getValue( 'limit' );
		$pager->mOffset = $this->opts->getValue( 'offset' );

		if ( $pager->getNumRows() ) {
			$navigation = '';
			if ( $this->showNavigation ) {
				$navigation = $pager->getNavigationBar();
			}
			$out->addHTML( $navigation . $pager->getBody() . $navigation );
		} else {
			$out->addWikiMsg( 'specialpage-empty' );
		}
	}

	protected function filterLinks() {
		// show/hide links
		$showhide = array( $this->msg( 'show' )->escaped(), $this->msg( 'hide' )->escaped() );

		// Option value -> message mapping
		$filters = array(
			'hideliu' => 'rcshowhideliu',
			'hidepatrolled' => 'rcshowhidepatr',
			'hidebots' => 'rcshowhidebots',
			'hideredirs' => 'whatlinkshere-hideredirs'
		);
		foreach ( $this->customFilters as $key => $params ) {
			$filters[$key] = $params['msg'];
		}

		// Disable some if needed
		if ( !User::groupHasPermission( '*', 'createpage' ) ) {
			unset( $filters['hideliu'] );
		}
		if ( !$this->getUser()->useNPPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = array();
		$changed = $this->opts->getChangedValues();
		unset( $changed['offset'] ); // Reset offset if query type changes

		$self = $this->getTitle();
		foreach ( $filters as $key => $msg ) {
			$onoff = 1 - $this->opts->getValue( $key );
			$link = Linker::link( $self, $showhide[$onoff], array(),
				array( $key => $onoff ) + $changed
			);
			$links[$key] = $this->msg( $msg )->rawParams( $link )->escaped();
		}

		return $this->getLanguage()->pipeList( $links );
	}

	protected function form() {
		global $wgEnableNewpagesUserFilter, $wgScript;

		// Consume values
		$this->opts->consumeValue( 'offset' ); // don't carry offset, DWIW
		$namespace = $this->opts->consumeValue( 'namespace' );
		$username = $this->opts->consumeValue( 'username' );
		$tagFilterVal = $this->opts->consumeValue( 'tagfilter' );
		$nsinvert = $this->opts->consumeValue( 'invert' );

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
			Xml::fieldset( $this->msg( 'newpages' )->text() ) .
			Xml::openElement( 'table', array( 'id' => 'mw-newpages-table' ) ) .
			'<tr>
				<td class="mw-label">' .
			Xml::label( $this->msg( 'namespace' )->text(), 'namespace' ) .
			'</td>
			<td class="mw-input">' .
			Html::namespaceSelector(
				array(
					'selected' => $namespace,
					'all' => 'all',
				), array(
					'name' => 'namespace',
					'id' => 'namespace',
					'class' => 'namespaceselector',
				)
			) . '&#160;' .
			Xml::checkLabel(
				$this->msg( 'invert' )->text(),
				'invert',
				'nsinvert',
				$nsinvert,
				array( 'title' => $this->msg( 'tooltip-invert' )->text() )
			) .
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
					Xml::label( $this->msg( 'newpages-username' )->text(), 'mw-np-username' ) .
					'</td>
				<td class="mw-input">' .
					Xml::input( 'username', 30, $userText, array( 'id' => 'mw-np-username' ) ) .
					'</td>
			</tr>' : '' ) .
			'<tr> <td></td>
				<td class="mw-submit">' .
			Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) .
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

		$this->getOutput()->addHTML( $form );
	}

	/**
	 * Format a row, providing the timestamp, links to the page/history,
	 * size, user links, and a comment
	 *
	 * @param object $result Result row
	 * @return String
	 */
	public function formatRow( $result ) {
		$title = Title::newFromRow( $result );

		# Revision deletion works on revisions, so we should cast one
		$row = array(
			'comment' => $result->rc_comment,
			'deleted' => $result->rc_deleted,
			'user_text' => $result->rc_user_text,
			'user' => $result->rc_user,
		);
		$rev = new Revision( $row );
		$rev->setTitle( $title );

		$classes = array();

		$lang = $this->getLanguage();
		$dm = $lang->getDirMark();

		$spanTime = Html::element( 'span', array( 'class' => 'mw-newpages-time' ),
			$lang->userTimeAndDate( $result->rc_timestamp, $this->getUser() )
		);
		$time = Linker::linkKnown(
			$title,
			$spanTime,
			array(),
			array( 'oldid' => $result->rc_this_oldid ),
			array()
		);

		$query = array( 'redirect' => 'no' );

		// Linker::linkKnown() uses 'known' and 'noclasses' options.
		// This breaks the colouration for stubs.
		$plink = Linker::link(
			$title,
			null,
			array( 'class' => 'mw-newpages-pagename' ),
			$query,
			array( 'known' )
		);
		$histLink = Linker::linkKnown(
			$title,
			$this->msg( 'hist' )->escaped(),
			array(),
			array( 'action' => 'history' )
		);
		$hist = Html::rawElement( 'span', array( 'class' => 'mw-newpages-history' ),
			$this->msg( 'parentheses' )->rawParams( $histLink )->escaped() );

		$length = Html::element(
			'span',
			array( 'class' => 'mw-newpages-length' ),
			$this->msg( 'brackets' )->params( $this->msg( 'nbytes' )
				->numParams( $result->length )->text()
			)
		);

		$ulink = Linker::revUserTools( $rev );
		$comment = Linker::revComment( $rev );

		if ( $this->patrollable( $result ) ) {
			$classes[] = 'not-patrolled';
		}

		# Add a class for zero byte pages
		if ( $result->length == 0 ) {
			$classes[] = 'mw-newpages-zero-byte-page';
		}

		# Tags, if any.
		if ( isset( $result->ts_tags ) ) {
			list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow(
				$result->ts_tags,
				'newpages'
			);
			$classes = array_merge( $classes, $newClasses );
		} else {
			$tagDisplay = '';
		}

		$css = count( $classes ) ? ' class="' . implode( ' ', $classes ) . '"' : '';

		# Display the old title if the namespace/title has been changed
		$oldTitleText = '';
		$oldTitle = Title::makeTitle( $result->rc_namespace, $result->rc_title );

		if ( !$title->equals( $oldTitle ) ) {
			$oldTitleText = $oldTitle->getPrefixedText();
			$oldTitleText = $this->msg( 'rc-old-title' )->params( $oldTitleText )->escaped();
		}

		return "<li{$css}>{$time} {$dm}{$plink} {$hist} {$dm}{$length} {$dm}{$ulink} {$comment} {$tagDisplay} {$oldTitleText}</li>\n";
	}

	/**
	 * Should a specific result row provide "patrollable" links?
	 *
	 * @param object $result Result row
	 * @return Boolean
	 */
	protected function patrollable( $result ) {
		return ( $this->getUser()->useNPPatrol() && !$result->rc_patrolled );
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 *
	 * @param $type String
	 */
	protected function feed( $type ) {
		global $wgFeed, $wgFeedClasses, $wgFeedLimit;

		if ( !$wgFeed ) {
			$this->getOutput()->addWikiMsg( 'feed-unavailable' );

			return;
		}

		if ( !isset( $wgFeedClasses[$type] ) ) {
			$this->getOutput()->addWikiMsg( 'feed-invalid' );

			return;
		}

		$feed = new $wgFeedClasses[$type](
			$this->feedTitle(),
			$this->msg( 'tagline' )->text(),
			$this->getTitle()->getFullURL()
		);

		$pager = new NewPagesPager( $this, $this->opts );
		$limit = $this->opts->getValue( 'limit' );
		$pager->mLimit = min( $limit, $wgFeedLimit );

		$feed->outHeader();
		if ( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		}
		$feed->outFooter();
	}

	protected function feedTitle() {
		global $wgLanguageCode, $wgSitename;
		$desc = $this->getDescription();

		return "$wgSitename - $desc [$wgLanguageCode]";
	}

	protected function feedItem( $row ) {
		$title = Title::makeTitle( intval( $row->rc_namespace ), $row->rc_title );
		if ( $title ) {
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
		if ( $revision ) {
			//XXX: include content model/type in feed item?
			return '<p>' . htmlspecialchars( $revision->getUserText() ) .
				$this->msg( 'colon-separator' )->inContentLanguage()->escaped() .
				htmlspecialchars( FeedItem::stripComment( $revision->getComment() ) ) .
				"</p>\n<hr />\n<div>" .
				nl2br( htmlspecialchars( $revision->getContent()->serialize() ) ) . "</div>";
		}

		return '';
	}

	protected function getGroupName() {
		return 'changes';
	}
}

/**
 * @ingroup SpecialPage Pager
 */
class NewPagesPager extends ReverseChronologicalPager {
	// Stored opts
	protected $opts;

	/**
	 * @var HtmlForm
	 */
	protected $mForm;

	function __construct( $form, FormOptions $opts ) {
		parent::__construct( $form->getContext() );
		$this->mForm = $form;
		$this->opts = $opts;
	}

	function getQueryInfo() {
		global $wgEnableNewpagesUserFilter;
		$conds = array();
		$conds['rc_new'] = 1;

		$namespace = $this->opts->getValue( 'namespace' );
		$namespace = ( $namespace === 'all' ) ? false : intval( $namespace );

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		if ( $namespace !== false ) {
			if ( $this->opts->getValue( 'invert' ) ) {
				$conds[] = 'rc_namespace != ' . $this->mDb->addQuotes( $namespace );
			} else {
				$conds['rc_namespace'] = $namespace;
			}
			$rcIndexes = array( 'new_name_timestamp' );
		} else {
			$rcIndexes = array( 'rc_timestamp' );
		}

		# $wgEnableNewpagesUserFilter - temp WMF hack
		if ( $wgEnableNewpagesUserFilter && $user ) {
			$conds['rc_user_text'] = $user->getText();
			$rcIndexes = 'rc_user_text';
		} elseif ( User::groupHasPermission( '*', 'createpage' ) &&
			$this->opts->getValue( 'hideliu' )
		) {
			# If anons cannot make new pages, don't "exclude logged in users"!
			$conds['rc_user'] = 0;
		}

		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if ( $this->opts->getValue( 'hidepatrolled' ) && $this->getUser()->useNPPatrol() ) {
			$conds['rc_patrolled'] = 0;
		}

		if ( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc_bot'] = 0;
		}

		if ( $this->opts->getValue( 'hideredirs' ) ) {
			$conds['page_is_redirect'] = 0;
		}

		// Allow changes to the New Pages query
		$tables = array( 'recentchanges', 'page' );
		$fields = array(
			'rc_namespace', 'rc_title', 'rc_cur_id', 'rc_user', 'rc_user_text',
			'rc_comment', 'rc_timestamp', 'rc_patrolled', 'rc_id', 'rc_deleted',
			'length' => 'page_len', 'rev_id' => 'page_latest', 'rc_this_oldid',
			'page_namespace', 'page_title'
		);
		$join_conds = array( 'page' => array( 'INNER JOIN', 'page_id=rc_cur_id' ) );

		wfRunHooks( 'SpecialNewpagesConditions',
			array( &$this, $this->opts, &$conds, &$tables, &$fields, &$join_conds ) );

		$info = array(
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $conds,
			'options' => array( 'USE INDEX' => array( 'recentchanges' => $rcIndexes ) ),
			'join_conds' => $join_conds
		);

		// Modify query for tags
		ChangeTags::modifyDisplayQuery(
			$info['tables'],
			$info['fields'],
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
