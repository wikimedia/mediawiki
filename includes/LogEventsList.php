<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>, 2008 Aaron Schulz
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

class LogEventsList {
	const NO_ACTION_LINK = 1;

	private $skin;
	private $out;
	public $flags;

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
				'revertmove', 'undeletelink', 'undeleteviewlink', 'revdel-restore', 'rev-delundel', 'hist', 'diff',
				'pipe-separator' );
			foreach( $messages as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
			}
		}
	}

	/**
	 * Set page title and show header for this log type
	 * @param $type String
	 */
	public function showHeader( $type ) {
		if( LogPage::isLogType( $type ) ) {
			$this->out->setPageTitle( LogPage::logName( $type ) );
			$this->out->addHTML( LogPage::logHeader( $type ) );
		}
	}

	/**
	 * Show options for the log list
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
		$month = '', $filter = null, $tagFilter='' )
	{
		global $wgScript, $wgMiserMode;
		$action = htmlspecialchars( $wgScript );
		$title = SpecialPage::getTitleFor( 'Log' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		// For B/C, we take strings, but make sure they are converted...
		$types = ($types === '') ? array() : (array)$types;

		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter );

		$this->out->addHTML( "<form action=\"$action\" method=\"get\"><fieldset>" .
			Xml::element( 'legend', array(), wfMsg( 'log' ) ) .
			Xml::hidden( 'title', $special ) . "\n" .
			$this->getTypeMenu( $types ) . "\n" .
			$this->getUserInput( $user ) . "\n" .
			$this->getTitleInput( $page ) . "\n" .
			( !$wgMiserMode ? ($this->getTitlePattern( $pattern )."\n") : "" ) .
			"<p>" . Xml::dateMenu( $year, $month ) . "\n" .
			( $tagSelector ? Xml::tags( 'p', null, implode( '&nbsp;', $tagSelector ) ) :'' ). "\n" .
			( $filter ? "</p><p>".$this->getFilterLinks( $filter )."\n" : "" ) . "\n" .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "</p>\n" .
			"</fieldset></form>"
		);
	}
	
	/**
	 * @param $filter Array
	 * @return String: Formatted HTML
	 */
	private function getFilterLinks( $filter ) {
		global $wgTitle, $wgLang;
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
			$query[$queryKey] = $hideVal;

			$hideVal = 1 - intval($val);

			$link = $this->skin->link(
				$wgTitle,
				$messages[$hideVal],
				array(),
				$query,
				array( 'known', 'noclasses' )
			);

			$links[$type] = wfMsgHtml( "log-show-hide-{$type}", $link );
			$hiddens .= Xml::hidden( "hide_{$type}_log", $val ) . "\n";
		}
		// Build links
		return '<small>'.$wgLang->pipeList( $links ) . '</small>' . $hiddens;
	}
	
	private function getDefaultQuery() {
		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $_GET;
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
	 * @param $queryTypes Array
	 * @return String: Formatted HTML
	 */
	private function getTypeMenu( $queryTypes ) {
		global $wgLogRestrictions, $wgUser;

		$html = "<select name='type'>\n";

		$validTypes = LogPage::validTypes();
		$typesByName = array(); // Temporary array

		// First pass to load the log names
		foreach( $validTypes as $type ) {
			$text = LogPage::logName( $type );
			$typesByName[$text] = $type;
		}

		// Second pass to sort by name
		ksort($typesByName);

		// Note the query type
		$queryType = count($queryTypes) == 1 ? $queryTypes[0] : '';
		// Third pass generates sorted XHTML content
		foreach( $typesByName as $text => $type ) {
			$selected = ($type == $queryType);
			// Restricted types
			if ( isset($wgLogRestrictions[$type]) ) {
				if ( $wgUser->isAllowed( $wgLogRestrictions[$type] ) ) {
					$html .= Xml::option( $text, $type, $selected ) . "\n";
				}
			} else {
				$html .= Xml::option( $text, $type, $selected ) . "\n";
			}
		}

		$html .= '</select>';
		return $html;
	}

	/**
	 * @param $user String
	 * @return String: Formatted HTML
	 */
	private function getUserInput( $user ) {
		return Xml::inputLabel( wfMsg( 'specialloguserlabel' ), 'user', 'mw-log-user', 15, $user );
	}

	/**
	 * @param $title String
	 * @return String: Formatted HTML
	 */
	private function getTitleInput( $title ) {
		return Xml::inputLabel( wfMsg( 'speciallogtitlelabel' ), 'page', 'mw-log-page', 20, $title );
	}

	/**
	 * @return boolean Checkbox
	 */
	private function getTitlePattern( $pattern ) {
		return '<span style="white-space: nowrap">' .
			Xml::checkLabel( wfMsg( 'log-title-wildcard' ), 'pattern', 'pattern', $pattern ) .
			'</span>';
	}

	public function beginLogEventsList() {
		return "<ul>\n";
	}

	public function endLogEventsList() {
		return "</ul>\n";
	}

	/**
	 * @param $row Row: a single row from the result set
	 * @return String: Formatted HTML list item
	 */
	public function logLine( $row ) {
		global $wgLang, $wgUser, $wgContLang;

		$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		$classes = array( "mw-logline-{$row->log_type}" );
		$time = $wgLang->timeanddate( wfTimestamp(TS_MW, $row->log_timestamp), true );
		// User links
		if( self::isDeleted($row,LogPage::DELETED_USER) ) {
			$userLink = '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$userLink = $this->skin->userLink( $row->log_user, $row->user_name ) .
				$this->skin->userToolLinks( $row->log_user, $row->user_name, true, 0, $row->user_editcount );
		}
		// Comment
		if( self::isDeleted($row,LogPage::DELETED_COMMENT) ) {
			$comment = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-comment') . '</span>';
		} else {
			$comment = $wgContLang->getDirMark() . $this->skin->commentBlock( $row->log_comment );
		}
		// Extract extra parameters
		$paramArray = LogPage::extractParams( $row->log_params );
		$revert = $del = '';
		// Some user can hide log items and have review links
		if( !($this->flags & self::NO_ACTION_LINK) && $wgUser->isAllowed( 'deleterevision' ) ) {
			$del = $this->getShowHideLinks( $row ) . ' ';
		}
		// Add review links and such...
		if( ($this->flags & self::NO_ACTION_LINK) || ($row->log_deleted & LogPage::DELETED_ACTION) ) {
			// Action text is suppressed...
		} else if( self::typeAction($row,'move','move','move') && !empty($paramArray[0]) ) {
			$destTitle = Title::newFromText( $paramArray[0] );
			if( $destTitle ) {
				$revert = '(' . $this->skin->link(
					SpecialPage::getTitleFor( 'Movepage' ),
					$this->message['revertmove'],
					array(),
					array(
						'wpOldTitle' => $destTitle->getPrefixedDBkey(),
						'wpNewTitle' => $title->getPrefixedDBkey(),
						'wpReason' => wfMsgForContent( 'revertmove' ),
						'wpMovetalk' => 0
					),
					array( 'known', 'noclasses' )
				) . ')';
			}
		// Show undelete link
		} else if( self::typeAction($row,array('delete','suppress'),'delete','deletedhistory') ) {
			if( !$wgUser->isAllowed( 'undelete' ) ) {
				$viewdeleted = $this->message['undeleteviewlink'];
			} else {
				$viewdeleted = $this->message['undeletelink'];
			}

			$revert = '(' . $this->skin->link(
				SpecialPage::getTitleFor( 'Undelete' ),
				$viewdeleted,
				array(),
				array( 'target' => $title->getPrefixedDBkey() ),
				array( 'known', 'noclasses' )
			 ) . ')';
		// Show unblock/change block link
		} else if( self::typeAction($row,array('block','suppress'),array('block','reblock'),'block') ) {
			$revert = '(' .
				$this->skin->link(
					SpecialPage::getTitleFor( 'Ipblocklist' ),
					$this->message['unblocklink'],
					array(),
					array(
						'action' => 'unblock',
						'ip' => $row->log_title
					),
					'known'
				) .
				$this->message['pipe-separator'] .
				$this->skin->link(
					SpecialPage::getTitleFor( 'Blockip', $row->log_title ),
					$this->message['change-blocklink'],
					array(),
					array(),
					'known'
				) .
				')';
		// Show change protection link
		} else if( self::typeAction( $row, 'protect', array( 'modify', 'protect', 'unprotect' ) ) ) {
			$revert .= ' (' . 
				$this->skin->link( $title,
					$this->message['hist'],
					array(),
					array(
						'action' => 'history',
						'offset' => $row->log_timestamp
					)
				);
			if( $wgUser->isAllowed( 'protect' ) ) {
				$revert .= $this->message['pipe-separator'] .
					$this->skin->link( $title,
						$this->message['protect_change'],
						array(),
						array( 'action' => 'protect' ),
						'known' );
			}
			$revert .= ')';
		// Show unmerge link
		} else if( self::typeAction($row,'merge','merge','mergehistory') ) {
			$merge = SpecialPage::getTitleFor( 'Mergehistory' );
			$revert = '(' .  $this->skin->link(
				$merge,
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
		} else if( self::typeAction($row,array('delete','suppress'),'revision','deleterevision') ) {
			if( count($paramArray) >= 2 ) {
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				// Different revision types use different URL params...
				$key = $paramArray[0];
				// $paramArray[1] is a CSV of the IDs
				$Ids = explode( ',', $paramArray[1] );
				$query = $paramArray[1];
				$revert = array();
				// Diff link for single rev deletions
				if( ( $key === 'oldid' || $key == 'revision' ) && count($Ids) == 1 ) {
					$revert[] = $this->skin->link(
						$title,
						$this->message['diff'], 
						array(),
						array(
							'diff' => intval( $Ids[0] ),
							'unhide' => 1
						),
						array( 'known', 'noclasses' )
					);
				}
				// View/modify link...
				$revert[] = $this->skin->link(
					$revdel,
					$this->message['revdel-restore'],
					array(),
					array(
						'target' => $title->getPrefixedUrl(),
						'type' => $key,
						'ids' => $query
					),
					array( 'known', 'noclasses' )
				);
				// Pipe links
				$revert = '(' . implode(' | ',$revert) . ')';
			}
		// Hidden log items, give review link
		} else if( self::typeAction($row,array('delete','suppress'),'event','deleterevision') ) {
			if( count($paramArray) >= 1 ) {
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				// $paramArray[1] is a CSV of the IDs
				$Ids = explode( ',', $paramArray[0] );
				$query = $paramArray[0];
				// Link to each hidden object ID, $paramArray[1] is the url param
				$revert = '(' . $this->skin->link(
					$revdel,
					$this->message['revdel-restore'], 
					array(),
					array(
						'target' => $title->getPrefixedUrl(),
						'type' => 'logging',
						'ids' => $query
					),
					array( 'known', 'noclasses' )
				) . ')';
			}
		// Self-created users
		} else if( self::typeAction($row,'newusers','create2') ) {
			if( isset( $paramArray[0] ) ) {
				$revert = $this->skin->userToolLinks( $paramArray[0], $title->getDBkey(), true );
			} else {
				# Fall back to a blue contributions link
				$revert = $this->skin->userToolLinks( 1, $title->getDBkey() );
			}
			if( $time < '20080129000000' ) {
				# Suppress $comment from old entries (before 2008-01-29),
				# not needed and can contain incorrect links
				$comment = '';
			}
		// Do nothing. The implementation is handled by the hook modifiying the passed-by-ref parameters.
		} else {
			wfRunHooks( 'LogLine', array( $row->log_type, $row->log_action, $title, $paramArray,
				&$comment, &$revert, $row->log_timestamp ) );
		}
		// Event description
		if( self::isDeleted($row,LogPage::DELETED_ACTION) ) {
			$action = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';
		} else {
			$action = LogPage::actionText( $row->log_type, $row->log_action, $title,
				$this->skin, $paramArray, true );
		}

		// Any tags...
		list($tagDisplay, $newClasses) = ChangeTags::formatSummaryRow( $row->ts_tags, 'logevent' );
		$classes = array_merge( $classes, $newClasses );

		if( $revert != '' ) {
			$revert = '<span class="mw-logevent-actionlink">' . $revert . '</span>';
		}

		$time = htmlspecialchars( $time );

		return Xml::tags( 'li', array( "class" => implode( ' ', $classes ) ),
			$del . $time . ' ' . $userLink . ' ' . $action . ' ' . $comment . ' ' . $revert . " $tagDisplay" ) . "\n";
	}

	/**
	 * @param $row Row
	 * @return string
	 */
	private function getShowHideLinks( $row ) {
		// If event was hidden from sysops
		if( !self::userCan( $row, LogPage::DELETED_RESTRICTED ) ) {
			$del = Xml::tags( 'span', array( 'class'=>'mw-revdelundel-link' ),
				'('.$this->message['rev-delundel'].')' );
		} else if( $row->log_type == 'suppress' ) {
			$del = ''; // No one should be hiding from the oversight log
		} else {
			$target = SpecialPage::getTitleFor( 'Log', $row->log_type );
			$page = Title::makeTitle( $row->log_namespace, $row->log_title );
			$query = array( 
				'target' => $target->getPrefixedDBkey(),
				'type' => 'logging',
				'ids' => $row->log_id, 
			);
			$del = $this->skin->revDeleteLink( $query,
				self::isDeleted( $row, LogPage::DELETED_RESTRICTED ) );
		}
		return $del;
	}

	/**
	 * @param $row Row
	 * @param $type Mixed: string/array
	 * @param $action Mixed: string/array
	 * @param $right string
	 * @return bool
	 */
	public static function typeAction( $row, $type, $action, $right='' ) {
		$match = is_array($type) ?
			in_array($row->log_type,$type) : $row->log_type == $type;
		if( $match ) {
			$match = is_array($action) ?
				in_array($row->log_action,$action) : $row->log_action == $action;
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
	 * @param $row Row
	 * @param $field Integer
	 * @return Boolean
	 */
	public static function userCan( $row, $field ) {
		if( ( $row->log_deleted & $field ) == $field ) {
			global $wgUser;
			$permission = ( $row->log_deleted & LogPage::DELETED_RESTRICTED ) == LogPage::DELETED_RESTRICTED
				? 'suppressrevision'
				: 'deleterevision';
			wfDebug( "Checking for $permission due to $field match on $row->log_deleted\n" );
			return $wgUser->isAllowed( $permission );
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
		return ($row->log_deleted & $field) == $field;
	}

	/**
	 * Quick function to show a short log extract
	 * @param $out OutputPage
	 * @param $types String or Array
	 * @param $page String
	 * @param $user String
	 * @param $lim Integer
	 * @param $conds Array
	 */
	public static function showLogExtract( $out, $types=array(), $page='', $user='', $lim=0, $conds=array() ) {
		global $wgUser;
		# Insert list of top 50 or so items
		$loglist = new LogEventsList( $wgUser->getSkin(), $out, 0 );
		$pager = new LogPager( $loglist, $types, $user, $page, '', $conds );
		if( $lim > 0 ) $pager->mLimit = $lim;
		$logBody = $pager->getBody();
		if( $logBody ) {
			$out->addHTML(
				$loglist->beginLogEventsList() .
				$logBody .
				$loglist->endLogEventsList()
			);
		} else {
			$out->addWikiMsg( 'logempty' );
		}
		return $pager->getNumRows();
	}

	/**
	 * SQL clause to skip forbidden log types for this user
	 * @param $db Database
	 * @param $audience string, public/user
	 * @return mixed (string or false)
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

/**
 * @ingroup Pager
 */
class LogPager extends ReverseChronologicalPager {
	private $types = array(), $user = '', $title = '', $pattern = '';
	private $typeCGI = '';
	public $mLogEventsList;

	/**
	 * constructor
	 * @param $list LogEventsList
	 * @param $types String or Array
	 * @param $user String
	 * @param $title String
	 * @param $pattern String
	 * @param $conds Array
	 * @param $year Integer
	 * @param $month Integer
	 */
	public function __construct( $list, $types = array(), $user = '', $title = '', $pattern = '',
		$conds = array(), $year = false, $month = false, $tagFilter = '' ) 
	{
		parent::__construct();
		$this->mConds = $conds;

		$this->mLogEventsList = $list;

		$this->limitType( $types ); // also excludes hidden types
		$this->limitUser( $user );
		$this->limitTitle( $title, $pattern );
		$this->getDateCond( $year, $month );
		$this->mTagFilter = $tagFilter;
	}

	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['type'] = $this->typeCGI; // arrays won't work here
		$query['user'] = $this->user;
		$query['month'] = $this->mMonth;
		$query['year'] = $this->mYear;
		return $query;
	}

	// Call ONLY after calling $this->limitType() already!
	public function getFilterParams() {
		global $wgFilterLogTypes, $wgUser, $wgRequest;
		$filters = array();
		if( count($this->types) ) {
			return $filters;
		}
		foreach( $wgFilterLogTypes as $type => $default ) {
			// Avoid silly filtering
			if( $type !== 'patrol' || $wgUser->useNPPatrol() ) {
				$hide = $wgRequest->getInt( "hide_{$type}_log", $default );
				$filters[$type] = $hide;
				if( $hide )
					$this->mConds[] = 'log_type != ' . $this->mDb->addQuotes( $type );
			}
		}
		return $filters;
	}

	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 * @param $types String or array: Log types ('upload', 'delete', etc);
	 *   empty string means no restriction
	 */
	private function limitType( $types ) {
		global $wgLogRestrictions, $wgUser;
		// If $types is not an array, make it an array
		$types = ($types === '') ? array() : (array)$types;
		// Don't even show header for private logs; don't recognize it...
		foreach ( $types as $type ) {
			if( isset( $wgLogRestrictions[$type] ) && !$wgUser->isAllowed($wgLogRestrictions[$type]) ) {
				$types = array_diff( $types, array( $type ) );
			}
		}
		// Don't show private logs to unprivileged users.
		// Also, only show them upon specific request to avoid suprises.
		$audience = $types ? 'user' : 'public';
		$hideLogs = LogEventsList::getExcludeClause( $this->mDb, $audience );
		if( $hideLogs !== false ) {
			$this->mConds[] = $hideLogs;
		}
		if( count($types) ) {
			$this->types = $types;
			$this->mConds['log_type'] = $types;
			// Set typeCGI; used in url param for paging
			if( count($types) == 1 ) $this->typeCGI = $types[0];
		}
	}

	/**
	 * Set the log reader to return only entries by the given user.
	 * @param $name String: (In)valid user name
	 */
	private function limitUser( $name ) {
		if( $name == '' ) {
			return false;
		}
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if( is_null($usertitle) ) {
			return false;
		}
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = User::idFromName( $name );
		if( !$userid ) {
			/* It should be nicer to abort query at all,
			   but for now it won't pass anywhere behind the optimizer */
			$this->mConds[] = "NULL";
		} else {
			global $wgUser;
			$this->mConds['log_user'] = $userid;
			// Paranoia: avoid brute force searches (bug 17342)
			if( !$wgUser->isAllowed( 'suppressrevision' ) ) {
				$this->mConds[] = 'log_deleted & ' . LogPage::DELETED_USER . ' = 0';
			}
			$this->user = $usertitle->getText();
		}
	}

	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 * @param $page String: Title name as text
	 * @param $pattern String
	 */
	private function limitTitle( $page, $pattern ) {
		global $wgMiserMode, $wgUser;

		$title = Title::newFromText( $page );
		if( strlen($page) == 0 || !$title instanceof Title )
			return false;

		$this->title = $title->getPrefixedText();
		$ns = $title->getNamespace();
		# Using the (log_namespace, log_title, log_timestamp) index with a
		# range scan (LIKE) on the first two parts, instead of simple equality,
		# makes it unusable for sorting.  Sorted retrieval using another index
		# would be possible, but then we might have to scan arbitrarily many
		# nodes of that index. Therefore, we need to avoid this if $wgMiserMode
		# is on.
		#
		# This is not a problem with simple title matches, because then we can
		# use the page_time index.  That should have no more than a few hundred
		# log entries for even the busiest pages, so it can be safely scanned
		# in full to satisfy an impossible condition on user or similar.
		if( $pattern && !$wgMiserMode ) {
			# use escapeLike to avoid expensive search patterns like 't%st%'
			$safetitle = $this->mDb->escapeLike( $title->getDBkey() );
			$this->mConds['log_namespace'] = $ns;
			$this->mConds[] = "log_title LIKE '$safetitle%'";
			$this->pattern = $pattern;
		} else {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds['log_title'] = $title->getDBkey();
		}
		// Paranoia: avoid brute force searches (bug 17342)
		if( !$wgUser->isAllowed( 'suppressrevision' ) ) {
			$this->mConds[] = 'log_deleted & ' . LogPage::DELETED_ACTION . ' = 0';
		}
	}

	public function getQueryInfo() {
		$tables = array( 'logging', 'user' );
		$this->mConds[] = 'user_id = log_user';
		$groupBy = false;
		# Add log_search table if there are conditions on it
		if( array_key_exists('ls_field',$this->mConds) ) {
			$tables[] = 'log_search';
			$index = array( 'log_search' => 'ls_field_val', 'logging' => 'PRIMARY' );
			$groupBy = 'ls_log_id';
		# Don't use the wrong logging index
		} else if( $this->title || $this->pattern || $this->user ) {
			$index = array( 'logging' => array('page_time','user_time') );
		} else if( $this->types ) {
			$index = array( 'logging' => 'type_time' );
		} else {
			$index = array( 'logging' => 'times' );
		}
		$options = array( 'USE INDEX' => $index );
		# Don't show duplicate rows when using log_search
		if( $groupBy ) $options['GROUP BY'] = $groupBy;
		$info = array(
			'tables'     => $tables,
			'fields'     => array( 'log_type', 'log_action', 'log_user', 'log_namespace',
				'log_title', 'log_params', 'log_comment', 'log_id', 'log_deleted',
				'log_timestamp', 'user_name', 'user_editcount' ),
			'conds'      => $this->mConds,
			'options'    => $options,
			'join_conds' => array( 
				'user' => array( 'INNER JOIN', 'user_id=log_user' ),
				'log_search' => array( 'INNER JOIN', 'ls_log_id=log_id' )
			)
		);
		# Add ChangeTags filter query
		ChangeTags::modifyDisplayQuery( $info['tables'], $info['fields'], $info['conds'],
			$info['join_conds'], $info['options'], $this->mTagFilter );

		return $info;
	}

	function getIndexField() {
		return 'log_timestamp';
	}

	public function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		if( $this->getNumRows() > 0 ) {
			$lb = new LinkBatch;
			while( $row = $this->mResult->fetchObject() ) {
				$lb->add( $row->log_namespace, $row->log_title );
				$lb->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
				$lb->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_name ) );
			}
			$lb->execute();
			$this->mResult->seek( 0 );
		}
		wfProfileOut( __METHOD__ );
		return '';
	}

	public function formatRow( $row ) {
		return $this->mLogEventsList->logLine( $row );
	}

	public function getType() {
		return $this->types;
	}

	public function getUser() {
		return $this->user;
	}

	public function getPage() {
		return $this->title;
	}

	public function getPattern() {
		return $this->pattern;
	}

	public function getYear() {
		return $this->mYear;
	}

	public function getMonth() {
		return $this->mMonth;
	}

	public function getTagFilter() {
		return $this->mTagFilter;
	}

	public function doQuery() {
		// Workaround MySQL optimizer bug
		$this->mDb->setBigSelects();
		parent::doQuery();
		$this->mDb->setBigSelects( 'default' );
	}
}

/**
 * @deprecated
 * @ingroup SpecialPage
 */
class LogReader {
	var $pager;
	/**
	 * @param $request WebRequest: for internal use use a FauxRequest object to pass arbitrary parameters.
	 */
	function __construct( $request ) {
		global $wgUser, $wgOut;
		wfDeprecated(__METHOD__);
		# Get parameters
		$type = $request->getVal( 'type' );
		$user = $request->getText( 'user' );
		$title = $request->getText( 'page' );
		$pattern = $request->getBool( 'pattern' );
		$year = $request->getIntOrNull( 'year' );
		$month = $request->getIntOrNull( 'month' );
		$tagFilter = $request->getVal( 'tagfilter' );
		# Don't let the user get stuck with a certain date
		$skip = $request->getText( 'offset' ) || $request->getText( 'dir' ) == 'prev';
		if( $skip ) {
			$year = '';
			$month = '';
		}
		# Use new list class to output results
		$loglist = new LogEventsList( $wgUser->getSkin(), $wgOut, 0 );
		$this->pager = new LogPager( $loglist, $type, $user, $title, $pattern, $year, $month, $tagFilter );
	}

	/**
	* Is there at least one row?
	* @return bool
	*/
	public function hasRows() {
		return isset($this->pager) ? ($this->pager->getNumRows() > 0) : false;
	}
}

/**
 * @deprecated
 * @ingroup SpecialPage
 */
class LogViewer {
	const NO_ACTION_LINK = 1;

	/**
	 * LogReader object
	 */
	var $reader;

	/**
	 * @param &$reader LogReader: where to get our data from
	 * @param $flags Integer: Bitwise combination of flags:
	 *     LogEventsList::NO_ACTION_LINK   Don't show restore/unblock/block links
	 */
	function __construct( &$reader, $flags = 0 ) {
		wfDeprecated(__METHOD__);
		$this->reader =& $reader;
		$this->reader->pager->mLogEventsList->flags = $flags;
		# Aliases for shorter code...
		$this->pager =& $this->reader->pager;
		$this->list =& $this->reader->pager->mLogEventsList;
	}

	/**
	 * Take over the whole output page in $wgOut with the log display.
	 */
	public function show() {
		# Set title and add header
		$this->list->showHeader( $pager->getType() );
		# Show form options
		$this->list->showOptions( $this->pager->getType(), $this->pager->getUser(), $this->pager->getPage(),
			$this->pager->getPattern(), $this->pager->getYear(), $this->pager->getMonth() );
		# Insert list
		$logBody = $this->pager->getBody();
		if( $logBody ) {
			$wgOut->addHTML(
				$this->pager->getNavigationBar() .
				$this->list->beginLogEventsList() .
				$logBody .
				$this->list->endLogEventsList() .
				$this->pager->getNavigationBar()
			);
		} else {
			$wgOut->addWikiMsg( 'logempty' );
		}
	}

	/**
	 * Output just the list of entries given by the linked LogReader,
	 * with extraneous UI elements. Use for displaying log fragments in
	 * another page (eg at Special:Undelete)
	 * @param $out OutputPage: where to send output
	 */
	public function showList( &$out ) {
		$logBody = $this->pager->getBody();
		if( $logBody ) {
			$out->addHTML(
				$this->list->beginLogEventsList() .
				$logBody .
				$this->list->endLogEventsList()
			);
		} else {
			$out->addWikiMsg( 'logempty' );
		}
	}
}
