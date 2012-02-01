<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>, 2008 Aaron Schulz
 * http://www.mediawiki.org/
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
 */

class LogEventsList {
	const NO_ACTION_LINK = 1;
	const NO_EXTRA_USER_LINKS = 2;

	/**
	 * @var Skin
	 */
	private $skin;

	/**
	 * @var OutputPage
	 */
	private $out;
	public $flags;

	/**
	 * @var Array
	 */
	protected $message;

	/**
	 * @var Array
	 */
	protected $mDefaultQuery;

	public function __construct( $skin, $out, $flags = 0 ) {
		$this->skin = $skin;
		$this->out = $out;
		$this->flags = $flags;
		$this->preCacheMessages();
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			$messages = array( 'revertmerge', 'protect_change', 'unblocklink', 'change-blocklink',
				'revertmove', 'undeletelink', 'undeleteviewlink', 'revdel-restore', 'hist', 'diff',
				'pipe-separator', 'revdel-restore-deleted', 'revdel-restore-visible' );
			foreach( $messages as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
			}
		}
	}

	/**
	 * Set page title and show header for this log type
	 * @param $type Array
	 * @deprecated in 1.19
	 */
	public function showHeader( $type ) {
		wfDeprecated( __METHOD__, '1.19' );
		// If only one log type is used, then show a special message...
		$headerType = (count($type) == 1) ? $type[0] : '';
		if( LogPage::isLogType( $headerType ) ) {
			$page = new LogPage( $headerType );
			$this->out->setPageTitle( $page->getName()->text() );
			$this->out->addHTML( $page->getDescription()->parseAsBlock() );
		} else {
			$this->out->addHTML( wfMsgExt('alllogstext',array('parseinline')) );
		}
	}

	/**
	 * Show options for the log list
	 *
	 * @param $types string or Array
	 * @param $user String
	 * @param $page String
	 * @param $pattern String
	 * @param $year Integer: year
	 * @param $month Integer: month
	 * @param $filter: array
	 * @param $tagFilter: array?
	 */
	public function showOptions( $types=array(), $user='', $page='', $pattern='', $year='',
		$month = '', $filter = null, $tagFilter='' ) {
		global $wgScript, $wgMiserMode;

		$action = $wgScript;
		$title = SpecialPage::getTitleFor( 'Log' );
		$special = $title->getPrefixedDBkey();

		// For B/C, we take strings, but make sure they are converted...
		$types = ($types === '') ? array() : (array)$types;

		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter );

		$html = Html::hidden( 'title', $special );

		// Basic selectors
		$html .= $this->getTypeMenu( $types ) . "\n";
		$html .= $this->getUserInput( $user ) . "\n";
		$html .= $this->getTitleInput( $page ) . "\n";
		$html .= $this->getExtraInputs( $types ) . "\n";

		// Title pattern, if allowed
		if (!$wgMiserMode) {
			$html .= $this->getTitlePattern( $pattern ) . "\n";
		}

		// date menu
		$html .= Xml::tags( 'p', null, Xml::dateMenu( $year, $month ) );

		// Tag filter
		if ($tagSelector) {
			$html .= Xml::tags( 'p', null, implode( '&#160;', $tagSelector ) );
		}

		// Filter links
		if ($filter) {
			$html .= Xml::tags( 'p', null, $this->getFilterLinks( $filter ) );
		}

		// Submit button
		$html .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );

		// Fieldset
		$html = Xml::fieldset( wfMsg( 'log' ), $html );

		// Form wrapping
		$html = Xml::tags( 'form', array( 'action' => $action, 'method' => 'get' ), $html );

		$this->out->addHTML( $html );
	}

	/**
	 * @param $filter Array
	 * @return String: Formatted HTML
	 */
	private function getFilterLinks( $filter ) {
		global $wgLang;
		// show/hide links
		$messages = array( wfMsgHtml( 'show' ), wfMsgHtml( 'hide' ) );
		// Option value -> message mapping
		$links = array();
		$hiddens = ''; // keep track for "go" button
		foreach( $filter as $type => $val ) {
			// Should the below assignment be outside the foreach?
			// Then it would have to be copied. Not certain what is more expensive.
			$query = $this->getDefaultQuery();
			$queryKey = "hide_{$type}_log";

			$hideVal = 1 - intval($val);
			$query[$queryKey] = $hideVal;

			$link = Linker::link(
				$this->getDisplayTitle(),
				$messages[$hideVal],
				array(),
				$query,
				array( 'known', 'noclasses' )
			);

			$links[$type] = wfMsgHtml( "log-show-hide-{$type}", $link );
			$hiddens .= Html::hidden( "hide_{$type}_log", $val ) . "\n";
		}
		// Build links
		return '<small>'.$wgLang->pipeList( $links ) . '</small>' . $hiddens;
	}

	private function getDefaultQuery() {
		global $wgRequest;

		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $wgRequest->getQueryValues();
			unset( $this->mDefaultQuery['title'] );
			unset( $this->mDefaultQuery['dir'] );
			unset( $this->mDefaultQuery['offset'] );
			unset( $this->mDefaultQuery['limit'] );
			unset( $this->mDefaultQuery['order'] );
			unset( $this->mDefaultQuery['month'] );
			unset( $this->mDefaultQuery['year'] );
		}
		return $this->mDefaultQuery;
	}

	/**
	 * Get the Title object of the page the links should point to.
	 * This is NOT the Title of the page the entries should be restricted to.
	 *
	 * @return Title object
	 */
	public function getDisplayTitle() {
		return $this->out->getTitle();
	}

	public function getContext() {
		return $this->out->getContext();
	}

	/**
	 * @param $queryTypes Array
	 * @return String: Formatted HTML
	 */
	private function getTypeMenu( $queryTypes ) {
		$queryType = count($queryTypes) == 1 ? $queryTypes[0] : '';
		$selector = $this->getTypeSelector();
		$selector->setDefault( $queryType );
		return $selector->getHtml();
	}

	/**
	 * Returns log page selector.
	 * @return XmlSelect
	 * @since 1.19
	 */
	public function getTypeSelector() {
		global $wgUser;

		$typesByName = array(); // Temporary array
		// First pass to load the log names
		foreach(  LogPage::validTypes() as $type ) {
			$page = new LogPage( $type );
			$restriction = $page->getRestriction();
			if ( $wgUser->isAllowed( $restriction ) ) {
				$typesByName[$type] = $page->getName()->text();
			}
		}

		// Second pass to sort by name
		asort($typesByName);

		// Always put "All public logs" on top
		$public = $typesByName[''];
		unset( $typesByName[''] );
		$typesByName = array( '' => $public ) + $typesByName;

		$select = new XmlSelect( 'type' );
		foreach( $typesByName as $type => $name ) {
			$select->addOption( $name, $type );
		}

		return $select;
	}

	/**
	 * @param $user String
	 * @return String: Formatted HTML
	 */
	private function getUserInput( $user ) {
		return '<span style="white-space: nowrap">' .
			Xml::inputLabel( wfMsg( 'specialloguserlabel' ), 'user', 'mw-log-user', 15, $user ) .
			'</span>';
	}

	/**
	 * @param $title String
	 * @return String: Formatted HTML
	 */
	private function getTitleInput( $title ) {
		return '<span style="white-space: nowrap">' .
			Xml::inputLabel( wfMsg( 'speciallogtitlelabel' ), 'page', 'mw-log-page', 20, $title ) .
			'</span>';
	}

	/**
	 * @param $pattern
	 * @return string Checkbox
	 */
	private function getTitlePattern( $pattern ) {
		return '<span style="white-space: nowrap">' .
			Xml::checkLabel( wfMsg( 'log-title-wildcard' ), 'pattern', 'pattern', $pattern ) .
			'</span>';
	}

	/**
	 * @param $types
	 * @return string
	 */
	private function getExtraInputs( $types ) {
		global $wgRequest;
		$offender = $wgRequest->getVal('offender');
		$user = User::newFromName( $offender, false );
		if( !$user || ($user->getId() == 0 && !IP::isIPAddress($offender) ) ) {
			$offender = ''; // Blank field if invalid
		}
		if( count($types) == 1 && $types[0] == 'suppress' ) {
			return Xml::inputLabel( wfMsg('revdelete-offender'), 'offender',
				'mw-log-offender', 20, $offender );
		}
		return '';
	}

	/**
	 * @return string
	 */
	public function beginLogEventsList() {
		return "<ul>\n";
	}

	/**
	 * @return string
	 */
	public function endLogEventsList() {
		return "</ul>\n";
	}

	/**
	 * @param $row Row: a single row from the result set
	 * @return String: Formatted HTML list item
	 */
	public function logLine( $row ) {
		$entry = DatabaseLogEntry::newFromRow( $row );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setShowUserToolLinks( !( $this->flags & self::NO_EXTRA_USER_LINKS ) );

		$action = $formatter->getActionText();
		$comment = $formatter->getComment();

		$classes = array( 'mw-logline-' . $entry->getType() );
		$title = $entry->getTarget();
		$time = $this->logTimestamp( $entry );

		// Extract extra parameters
		$paramArray = LogPage::extractParams( $row->log_params );
		// Add review/revert links and such...
		$revert = $this->logActionLinks( $row, $title, $paramArray, $comment );

		// Some user can hide log items and have review links
		$del = $this->getShowHideLinks( $row );
		if( $del != '' ) $del .= ' ';

		// Any tags...
		list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow( $row->ts_tags, 'logevent' );
		$classes = array_merge( $classes, $newClasses );

		return Xml::tags( 'li', array( "class" => implode( ' ', $classes ) ),
			$del . "$time $action $comment $revert $tagDisplay" ) . "\n";
	}

	private function logTimestamp( LogEntry $entry ) {
		global $wgLang;
		$time = $wgLang->timeanddate( wfTimestamp( TS_MW, $entry->getTimestamp() ), true );
		return htmlspecialchars( $time );
	}

	/**
	 * @TODO: split up!
	 *
	 * @param  $row
	 * @param Title $title
	 * @param Array $paramArray
	 * @param  $comment
	 * @return String
	 */
	private function logActionLinks( $row, $title, $paramArray, &$comment ) {
		global $wgUser;
		if( ( $this->flags & self::NO_ACTION_LINK ) // we don't want to see the action
			|| self::isDeleted( $row, LogPage::DELETED_ACTION ) ) // action is hidden
		{
			return '';
		}
		$revert = '';
		if( self::typeAction( $row, 'move', 'move', 'move' ) && !empty( $paramArray[0] ) ) {
			$destTitle = Title::newFromText( $paramArray[0] );
			if( $destTitle ) {
				$revert = '(' . Linker::link(
					SpecialPage::getTitleFor( 'Movepage' ),
					$this->message['revertmove'],
					array(),
					array(
						'wpOldTitle' => $destTitle->getPrefixedDBkey(),
						'wpNewTitle' => $title->getPrefixedDBkey(),
						'wpReason'   => wfMsgForContent( 'revertmove' ),
						'wpMovetalk' => 0
					),
					array( 'known', 'noclasses' )
				) . ')';
			}
		// Show undelete link
		} elseif( self::typeAction( $row, array( 'delete', 'suppress' ), 'delete', 'deletedhistory' ) ) {
			if( !$wgUser->isAllowed( 'undelete' ) ) {
				$viewdeleted = $this->message['undeleteviewlink'];
			} else {
				$viewdeleted = $this->message['undeletelink'];
			}
			$revert = '(' . Linker::link(
				SpecialPage::getTitleFor( 'Undelete' ),
				$viewdeleted,
				array(),
				array( 'target' => $title->getPrefixedDBkey() ),
				array( 'known', 'noclasses' )
			 ) . ')';
		// Show unblock/change block link
		} elseif( self::typeAction( $row, array( 'block', 'suppress' ), array( 'block', 'reblock' ), 'block' ) ) {
			$revert = '(' .
				Linker::link(
					SpecialPage::getTitleFor( 'Unblock', $row->log_title ),
					$this->message['unblocklink'],
					array(),
					array(),
					'known'
				) .
				$this->message['pipe-separator'] .
				Linker::link(
					SpecialPage::getTitleFor( 'Block', $row->log_title ),
					$this->message['change-blocklink'],
					array(),
					array(),
					'known'
				) .
				')';
		// Show change protection link
		} elseif( self::typeAction( $row, 'protect', array( 'modify', 'protect', 'unprotect' ) ) ) {
			$revert .= ' (' .
				Linker::link( $title,
					$this->message['hist'],
					array(),
					array(
						'action' => 'history',
						'offset' => $row->log_timestamp
					)
				);
			if( $wgUser->isAllowed( 'protect' ) ) {
				$revert .= $this->message['pipe-separator'] .
					Linker::link( $title,
						$this->message['protect_change'],
						array(),
						array( 'action' => 'protect' ),
						'known' );
			}
			$revert .= ')';
		// Show unmerge link
		} elseif( self::typeAction( $row, 'merge', 'merge', 'mergehistory' ) ) {
			$revert = '(' . Linker::link(
				SpecialPage::getTitleFor( 'MergeHistory' ),
				$this->message['revertmerge'],
				array(),
				array(
					'target' => $paramArray[0],
					'dest' => $title->getPrefixedDBkey(),
					'mergepoint' => $paramArray[1]
				),
				array( 'known', 'noclasses' )
			) . ')';
		// If an edit was hidden from a page give a review link to the history
		} elseif( self::typeAction( $row, array( 'delete', 'suppress' ), 'revision', 'deletedhistory' ) ) {
			$revert = RevisionDeleter::getLogLinks( $title, $paramArray,
								$this->message );
		// Hidden log items, give review link
		} elseif( self::typeAction( $row, array( 'delete', 'suppress' ), 'event', 'deletedhistory' ) ) {
			if( count($paramArray) >= 1 ) {
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				// $paramArray[1] is a CSV of the IDs
				$query = $paramArray[0];
				// Link to each hidden object ID, $paramArray[1] is the url param
				$revert = '(' . Linker::link(
					$revdel,
					$this->message['revdel-restore'],
					array(),
					array(
						'target' => $title->getPrefixedText(),
						'type' => 'logging',
						'ids' => $query
					),
					array( 'known', 'noclasses' )
				) . ')';
			}
		// Do nothing. The implementation is handled by the hook modifiying the passed-by-ref parameters.
		} else {
			wfRunHooks( 'LogLine', array( $row->log_type, $row->log_action, $title, $paramArray,
				&$comment, &$revert, $row->log_timestamp ) );
		}
		if( $revert != '' ) {
			$revert = '<span class="mw-logevent-actionlink">' . $revert . '</span>';
		}
		return $revert;
	}

	/**
	 * @param $row Row
	 * @return string
	 */
	private function getShowHideLinks( $row ) {
		global $wgUser;
		if( ( $this->flags & self::NO_ACTION_LINK ) // we don't want to see the links
			|| $row->log_type == 'suppress' ) { // no one can hide items from the suppress log
			return '';
		}
		$del = '';
		// Don't show useless link to people who cannot hide revisions
		if( $wgUser->isAllowed( 'deletedhistory' ) ) {
			if( $row->log_deleted || $wgUser->isAllowed( 'deleterevision' ) ) {
				$canHide = $wgUser->isAllowed( 'deleterevision' );
				// If event was hidden from sysops
				if( !self::userCan( $row, LogPage::DELETED_RESTRICTED ) ) {
					$del = Linker::revDeleteLinkDisabled( $canHide );
				} else {
					$target = SpecialPage::getTitleFor( 'Log', $row->log_type );
					$query = array(
						'target' => $target->getPrefixedDBkey(),
						'type'   => 'logging',
						'ids'    => $row->log_id,
					);
					$del = Linker::revDeleteLink( $query,
						self::isDeleted( $row, LogPage::DELETED_RESTRICTED ), $canHide );
				}
			}
		}
		return $del;
	}

	/**
	 * @param $row Row
	 * @param $type Mixed: string/array
	 * @param $action Mixed: string/array
	 * @param $right string
	 * @return Boolean
	 */
	public static function typeAction( $row, $type, $action, $right='' ) {
		$match = is_array($type) ?
			in_array( $row->log_type, $type ) : $row->log_type == $type;
		if( $match ) {
			$match = is_array( $action ) ?
				in_array( $row->log_action, $action ) : $row->log_action == $action;
			if( $match && $right ) {
				global $wgUser;
				$match = $wgUser->isAllowed( $right );
			}
		}
		return $match;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted.
	 *
	 * @param $row Row
	 * @param $field Integer
	 * @param $user User object to check, or null to use $wgUser
	 * @return Boolean
	 */
	public static function userCan( $row, $field, User $user = null ) {
		return self::userCanBitfield( $row->log_deleted, $field, $user );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted.
	 *
	 * @param $bitfield Integer (current field)
	 * @param $field Integer
	 * @param $user User object to check, or null to use $wgUser
	 * @return Boolean
	 */
	public static function userCanBitfield( $bitfield, $field, User $user = null ) {
		if( $bitfield & $field ) {
			if ( $bitfield & LogPage::DELETED_RESTRICTED ) {
				$permission = 'suppressrevision';
			} else {
				$permission = 'deletedhistory';
			}
			wfDebug( "Checking for $permission due to $field match on $bitfield\n" );
			if ( $user === null ) {
				global $wgUser;
				$user = $wgUser;
			}
			return $user->isAllowed( $permission );
		} else {
			return true;
		}
	}

	/**
	 * @param $row Row
	 * @param $field Integer: one of DELETED_* bitfield constants
	 * @return Boolean
	 */
	public static function isDeleted( $row, $field ) {
		return ( $row->log_deleted & $field ) == $field;
	}

	/**
	 * Show log extract. Either with text and a box (set $msgKey) or without (don't set $msgKey)
	 *
	 * @param $out OutputPage|String-by-reference
	 * @param $types String|Array Log types to show
	 * @param $page String|Title The page title to show log entries for
	 * @param $user String The user who made the log entries
	 * @param $param Associative Array with the following additional options:
	 * - lim Integer Limit of items to show, default is 50
	 * - conds Array Extra conditions for the query (e.g. "log_action != 'revision'")
	 * - showIfEmpty boolean Set to false if you don't want any output in case the loglist is empty
	 *   if set to true (default), "No matching items in log" is displayed if loglist is empty
	 * - msgKey Array If you want a nice box with a message, set this to the key of the message.
	 *   First element is the message key, additional optional elements are parameters for the key
	 *   that are processed with wfMsgExt and option 'parse'
	 * - offset Set to overwrite offset parameter in $wgRequest
	 *   set to '' to unset offset
	 * - wrap String Wrap the message in html (usually something like "<div ...>$1</div>").
	 * - flags Integer display flags (NO_ACTION_LINK,NO_EXTRA_USER_LINKS)
	 * @return Integer Number of total log items (not limited by $lim)
	 */
	public static function showLogExtract(
		&$out, $types=array(), $page='', $user='', $param = array()
	) {
		$defaultParameters = array(
			'lim' => 25,
			'conds' => array(),
			'showIfEmpty' => true,
			'msgKey' => array(''),
			'wrap' => "$1",
			'flags' => 0
		);
		# The + operator appends elements of remaining keys from the right
		# handed array to the left handed, whereas duplicated keys are NOT overwritten.
		$param += $defaultParameters;
		# Convert $param array to individual variables
		$lim = $param['lim'];
		$conds = $param['conds'];
		$showIfEmpty = $param['showIfEmpty'];
		$msgKey = $param['msgKey'];
		$wrap = $param['wrap'];
		$flags = $param['flags'];
		if ( !is_array( $msgKey ) ) {
			$msgKey = array( $msgKey );
		}

		if ( $out instanceof OutputPage ) {
			$context = $out->getContext();
		} else {
			$context = RequestContext::getMain();
		}

		# Insert list of top 50 (or top $lim) items
		$loglist = new LogEventsList( $context->getSkin(), $context->getOutput(), $flags );
		$pager = new LogPager( $loglist, $types, $user, $page, '', $conds );
		if ( isset( $param['offset'] ) ) { # Tell pager to ignore $wgRequest offset
			$pager->setOffset( $param['offset'] );
		}
		if( $lim > 0 ) $pager->mLimit = $lim;
		$logBody = $pager->getBody();
		$s = '';
		if( $logBody ) {
			if ( $msgKey[0] ) {
				$s = '<div class="mw-warning-with-logexcerpt">';

				if ( count( $msgKey ) == 1 ) {
					$s .= wfMsgExt( $msgKey[0], array( 'parse' ) );
				} else { // Process additional arguments
					$args = $msgKey;
					array_shift( $args );
					$s .= wfMsgExt( $msgKey[0], array( 'parse' ), $args );
				}
			}
			$s .= $loglist->beginLogEventsList() .
				 $logBody .
				 $loglist->endLogEventsList();
		} else {
			if ( $showIfEmpty ) {
				$s = Html::rawElement( 'div', array( 'class' => 'mw-warning-logempty' ),
					wfMsgExt( 'logempty', array( 'parseinline' ) ) );
			}
		}
		if( $pager->getNumRows() > $pager->mLimit ) { # Show "Full log" link
			$urlParam = array();
			if ( $page instanceof Title ) {
				$urlParam['page'] = $page->getPrefixedDBkey();
			} elseif ( $page != '' ) {
				$urlParam['page'] = $page;
			}
			if ( $user != '')
				$urlParam['user'] = $user;
			if ( !is_array( $types ) ) # Make it an array, if it isn't
				$types = array( $types );
			# If there is exactly one log type, we can link to Special:Log?type=foo
			if ( count( $types ) == 1 )
				$urlParam['type'] = $types[0];
			$s .= Linker::link(
				SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'log-fulllog' ),
				array(),
				$urlParam
			);
		}
		if ( $logBody && $msgKey[0] ) {
			$s .= '</div>';
		}

		if ( $wrap != '' ) { // Wrap message in html
			$s = str_replace( '$1', $s, $wrap );
		}

		/* hook can return false, if we don't want the message to be emitted (Wikia BugId:7093) */
		if ( wfRunHooks( 'LogEventsListShowLogExtract', array( &$s, $types, $page, $user, $param ) ) ) {
			// $out can be either an OutputPage object or a String-by-reference
			if ( $out instanceof OutputPage ){
				$out->addHTML( $s );
			} else {
				$out = $s;
			}
		}

		return $pager->getNumRows();
	}

	/**
	 * SQL clause to skip forbidden log types for this user
	 *
	 * @param $db DatabaseBase
	 * @param $audience string, public/user
	 * @return Mixed: string or false
	 */
	public static function getExcludeClause( $db, $audience = 'public' ) {
		global $wgLogRestrictions, $wgUser;
		// Reset the array, clears extra "where" clauses when $par is used
		$hiddenLogs = array();
		// Don't show private logs to unprivileged users
		foreach( $wgLogRestrictions as $logType => $right ) {
			if( $audience == 'public' || !$wgUser->isAllowed($right) ) {
				$safeType = $db->strencode( $logType );
				$hiddenLogs[] = $safeType;
			}
		}
		if( count($hiddenLogs) == 1 ) {
			return 'log_type != ' . $db->addQuotes( $hiddenLogs[0] );
		} elseif( $hiddenLogs ) {
			return 'log_type NOT IN (' . $db->makeList($hiddenLogs) . ')';
		}
		return false;
	}
 }
