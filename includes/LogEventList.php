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

class LogEventList {
	const NO_ACTION_LINK = 1;
	private $skin;
	public $flags;

	function __construct( &$skin, $flags = 0 ) {
		$this->skin =& $skin;
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
			foreach( explode(' ', 'viewpagelogs revhistory filehist rev-delundel' ) as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escape') );
			}
		}
	}
	
	/**
	 * Set page title and show header for this log type
	 * @param OutputPage $out where to send output
	 * @param strin $type
	 */
	public function showHeader( $out, $type ) {
		if( LogPage::isLogType( $type ) ) {
			$out->setPageTitle( LogPage::logName( $type ) );
			$out->addWikiText( LogPage::logHeader( $type ) );
		}
	}

	/**
	 * Show options for the log list
	 * @param OutputPage $out where to send output
	 * @param string $type,
	 * @param string $user,
	 * @param string $page,
	 * @param string $pattern
	 */
	public function showOptions( $out, $type, $user, $page, $pattern ) {
		global $wgScript, $wgMiserMode;
		$action = htmlspecialchars( $wgScript );
		$title = SpecialPage::getTitleFor( 'Log' );
		$special = htmlspecialchars( $title->getPrefixedDBkey() );
		$out->addHTML( "<form action=\"$action\" method=\"get\"><fieldset>" .
			Xml::element( 'legend', array(), wfMsg( 'log' ) ) .
			Xml::hidden( 'title', $special ) . "\n" .
			$this->getTypeMenu( $type ) . "\n" .
			$this->getUserInput( $user ) . "\n" .
			$this->getTitleInput( $page ) . "\n" .
			( !$wgMiserMode ? ($this->getTitlePattern( $pattern )."\n") : "" ) .
			Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . "\n" .
			"</fieldset></form>" );
	}

	/**
	 * @return string Formatted HTML
	 */
	private function getTypeMenu( $queryType ) {
		global $wgLogRestrictions, $wgUser;
	
		$out = "<select name='type'>\n";

		$validTypes = LogPage::validTypes();
		$m = array(); // Temporary array

		// First pass to load the log names
		foreach( $validTypes as $type ) {
			$text = LogPage::logName( $type );
			$m[$text] = $type;
		}

		// Second pass to sort by name
		ksort($m);

		// Third pass generates sorted XHTML content
		foreach( $m as $text => $type ) {
			$selected = ($type == $queryType);
			// Restricted types
			if ( isset($wgLogRestrictions[$type]) ) {
				if ( $wgUser->isAllowed( $wgLogRestrictions[$type] ) ) {
					$out .= Xml::option( $text, $type, $selected ) . "\n";
				}
			} else {
				$out .= Xml::option( $text, $type, $selected ) . "\n";
			}
		}

		$out .= '</select>';
		return $out;
	}

	/**
	 * @return string Formatted HTML
	 */
	private function getUserInput( $user ) {
		return Xml::inputLabel( wfMsg( 'specialloguserlabel' ), 'user', 'user', 12, $user );
	}

	/**
	 * @return string Formatted HTML
	 */
	private function getTitleInput( $title ) {
		return Xml::inputLabel( wfMsg( 'speciallogtitlelabel' ), 'page', 'page', 20, $title );
	}

	/**
	 * @return boolean Checkbox
	 */
	private function getTitlePattern( $pattern ) {
		return Xml::checkLabel( wfMsg( 'log-title-wildcard' ), 'pattern', 'pattern', $pattern );
	}
	
	public function beginLogEventList() {
		return "<ul>\n";
	}
	
	public function endLogEventList() {
		return "</ul>\n";
	}
	
		/**
	 * @param Row $row a single row from the result set
	 * @return string Formatted HTML list item
	 * @private
	 */
	public function logLine( $row ) {
		global $wgLang, $wgUser, $wgContLang;
		$skin = $wgUser->getSkin();
		$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		$time = $wgLang->timeanddate( wfTimestamp(TS_MW, $row->log_timestamp), true );
		// Enter the existence or non-existence of this page into the link cache,
		// for faster makeLinkObj() in LogPage::actionText()
		$linkCache =& LinkCache::singleton();
		$linkCache->addLinkObj( $title );
		// User links
		if( LogPage::isDeleted($row,LogPage::DELETED_USER) ) {
			$userLink = '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$userLink = $this->skin->userLink( $row->log_user, $row->user_name ) . 
				$this->skin->userToolLinksRedContribs( $row->log_user, $row->user_name );
		}
		// Comment
		if( $row->log_action == 'create2' ) {
			$comment = ''; // Suppress from old account creations, useless and can contain incorrect links
		} else if( LogPage::isDeleted($row,LogPage::DELETED_COMMENT) ) {
			$comment = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-comment') . '</span>';
		} else {
			$comment = $wgContLang->getDirMark() . $this->skin->commentBlock( $row->log_comment );
		}
		// Extract extra parameters
		$paramArray = LogPage::extractParams( $row->log_params );
		$revert = $del = '';
		// Some user can hide log items and have review links
		if( $wgUser->isAllowed( 'deleterevision' ) ) {
			$del = $this->showhideLinks( $row ) . ' ';
		}
		// Add review links and such...
		if( !($this->flags & self::NO_ACTION_LINK) && !($row->log_deleted & LogPage::DELETED_ACTION) ) {
			if( $row->log_type == 'move' && isset( $paramArray[0] ) && $wgUser->isAllowed( 'move' ) ) {
				$destTitle = Title::newFromText( $paramArray[0] );
				if( $destTitle ) {
					$revert = '(' . $this->skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Movepage' ),
						wfMsg( 'revertmove' ),
						'wpOldTitle=' . urlencode( $destTitle->getPrefixedDBkey() ) .
						'&wpNewTitle=' . urlencode( $title->getPrefixedDBkey() ) .
						'&wpReason=' . urlencode( wfMsgForContent( 'revertmove' ) ) .
						'&wpMovetalk=0' ) . ')';
				}
			// Show undelete link
			} else if( $row->log_action == 'delete' && $wgUser->isAllowed( 'delete' ) ) {
				$revert = '(' . $this->skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Undelete' ),
					wfMsg( 'undeletelink' ) ,
					'target='. urlencode( $title->getPrefixedDBkey() ) ) . ')';
			// Show unblock link
			} else if( $row->log_action == 'block' && $wgUser->isAllowed( 'block' ) ) {
				$revert = '(' .  $this->skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Ipblocklist' ),
					wfMsg( 'unblocklink' ),
					'action=unblock&ip=' . urlencode( $row->log_title ) ) . ')';
			// Show change protection link
			} else if( ( $row->log_action == 'protect' || $row->log_action == 'modify' ) && $wgUser->isAllowed( 'protect' ) ) {
				$revert = '(' .  $this->skin->makeKnownLinkObj( $title, wfMsg( 'protect_change' ), 'action=unprotect' ) . ')';
			// Show unmerge link
			} else if ( $row->log_action == 'merge' ) {
				$merge = SpecialPage::getTitleFor( 'Mergehistory' );
				$revert = '(' .  $this->skin->makeKnownLinkObj( $merge, wfMsg('revertmerge'),
					wfArrayToCGI( 
						array('target' => $paramArray[0], 'dest' => $title->getPrefixedText(), 'mergepoint' => $paramArray[1] ) 
					) 
				) . ')';
			// If an edit was hidden from a page give a review link to the history
			} else if( $row->log_action == 'revision' && $wgUser->isAllowed( 'deleterevision' ) ) {
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				// Different revision types use different URL params...
				$subtype = isset($paramArray[2]) ? $paramArray[1] : '';
				// Link to each hidden object ID, $paramArray[1] is the url param. List if several...
				$Ids = explode( ',', $paramArray[2] );
				if( count($Ids) == 1 ) {
					$revert = $this->skin->makeKnownLinkObj( $revdel, wfMsgHtml('revdel-restore'),
						wfArrayToCGI( array('target' => $paramArray[0], $paramArray[1] => $Ids[0] ) ) );
				} else {
					$revert .= wfMsgHtml('revdel-restore').':';
					foreach( $Ids as $n => $id ) {
						$revert .= ' '.$this->skin->makeKnownLinkObj( $revdel, '#'.($n+1),
							wfArrayToCGI( array('target' => $paramArray[0], $paramArray[1] => $id ) ) );
					}
				}
				$revert = "($revert)";
			// Hidden log items, give review link
			} else if( $row->log_action == 'event' && $wgUser->isAllowed( 'deleterevision' ) ) {
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				$revert .= wfMsgHtml('revdel-restore');
				$Ids = explode( ',', $paramArray[0] );
				// Link to each hidden object ID, $paramArray[1] is the url param. List if several...
				if( count($Ids) == 1 ) {
					$revert = $this->skin->makeKnownLinkObj( $revdel, wfMsgHtml('revdel-restore'),
						wfArrayToCGI( array('logid' => $Ids[0] ) ) );
				} else {
					foreach( $Ids as $n => $id ) {
						$revert .= $this->skin->makeKnownLinkObj( $revdel, '#'.($n+1),
							wfArrayToCGI( array('logid' => $id ) ) );
					}
				}
				$revert = "($revert)";
			} else {
				wfRunHooks( 'LogLine', array( $row->log_type, $row->log_action, $title, $paramArray, 
					&$comment, &$revert, $row->log_timestamp ) );
				// wfDebug( "Invoked LogLine hook for " $row->log_type . ", " . $row->log_action . "\n" );
				// Do nothing. The implementation is handled by the hook modifiying the passed-by-ref parameters.
			}
		}
		// Event description
		if( $row->log_deleted & LogPage::DELETED_ACTION ) {
			$action = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';
		} else {
			$action = LogPage::actionText( $row->log_type, $row->log_action, $title, $this->skin, $paramArray, true );
		}
		
		return "<li>$del$time $userLink $action $comment $revert</li>\n";
	}
	
	/**
	 * @param Row $row
	 * @private
	 */
	private function showhideLinks( $row ) {
		global $wgAllowLogDeletion;
		
		if( !$wgAllowLogDeletion )
			return "";
	
		$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
		// If event was hidden from sysops
		if( !LogPage::userCan( $row, Revision::DELETED_RESTRICTED ) ) {
			$del = $this->message['rev-delundel'];
		} else if( $row->log_type == 'suppress' ) {
			// No one should be hiding from the oversight log
			$del = $this->message['rev-delundel'];
		} else {
			$del = $this->skin->makeKnownLinkObj( $revdel, $this->message['rev-delundel'], 'logid='.$row->log_id );
			// Bolden oversighted content
			if( LogPage::isDeleted( $row, Revision::DELETED_RESTRICTED ) )
				$del = "<strong>$del</strong>";
		}
		return "<tt>(<small>$del</small>)</tt>";
	}
	
	/**
	 * SQL clause to skip forbidden log types for this user
	 * @param Database $db
	 * @returns mixed (string or false)
	 */
	public static function getExcludeClause( $db ) {
		global $wgLogRestrictions, $wgUser;
		// Reset the array, clears extra "where" clauses when $par is used
		$hiddenLogs = array();
		// Don't show private logs to unpriviledged users
		foreach( $wgLogRestrictions as $logtype => $right ) {
			if( !$wgUser->isAllowed($right) ) {
				$safetype = $db->strencode( $logtype );
				$hiddenLogs[] = "'$safetype'";
			}
		}
		if( !empty($hiddenLogs) ) {
			return 'log_type NOT IN(' . implode(',',$hiddenLogs) . ')';
		}
		return false;
	}
}

/**
 * @addtogroup Pager
 */
class LogPager extends ReverseChronologicalPager {
	private $type = '', $user = '', $title = '', $pattern = '';
	public $mLogEventList;
	/**
	* constructor
	* @param LogEventList $loglist,
	* @param string $type,
	* @param string $user,
	* @param string $page,
	* @param string $pattern
	* @param array $conds
	*/
	function __construct( $loglist, $type, $user, $title, $pattern, $conds = array() ) {
		parent::__construct();
		$this->mConds = $conds;
		
		$this->mLogEventList = $loglist;
		
		$this->limitType( $type );
		$this->limitUser( $user );
		$this->limitTitle( $title, $pattern );
	}
	
	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 * @param string $type A log type ('upload', 'delete', etc)
	 * @private
	 */
	private function limitType( $type ) {
		// Don't show private logs to unpriviledged users
		$hideLogs = LogEventList::getExcludeClause( $this->mDb );
		if( $hideLogs !== false ) {
			$this->mConds[] = $hideLogs;
		}
		if( empty($type) ) {
			return false;
		}
		$this->type = $type;
		$this->mConds['log_type'] = $type;
	}
	
	/**
	 * Set the log reader to return only entries by the given user.
	 * @param string $name (In)valid user name
	 * @private
	 */
	function limitUser( $name ) {
		if( $name == '' ) {
			return false;
		}
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if( is_null($usertitle) ) {
			return false;
		}
		$this->user = $usertitle->getText();
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = User::idFromName( $this->user );
		if( !$userid ) {
			/* It should be nicer to abort query at all, 
			   but for now it won't pass anywhere behind the optimizer */
			$this->mConds[] = "NULL";
		} else {
			$this->mConds['log_user'] = $userid;
		}
	}

	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 * @param string $page Title name as text
	 * @private
	 */
	function limitTitle( $page, $pattern ) {
		global $wgMiserMode;
		
		$title = Title::newFromText( $page );
		if( strlen($page) == 0 || !$title instanceof Title )
			return false;
		
		$this->title = $title->getPrefixedText();
		$this->pattern = $pattern;
		$ns = $title->getNamespace();
		if( $pattern && !$wgMiserMode ) {
			# use escapeLike to avoid expensive search patterns like 't%st%'
			$safetitle = $this->mDb->escapeLike( $title->getDBkey() );
			$this->mConds['log_namespace'] = $ns;
			$this->mConds[] = "log_title LIKE '$safetitle%'";
		} else {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds['log_title'] = $title->getDBkey();
		}
	}

	function getQueryInfo() {
		$this->mConds[] = 'user_id = log_user';
		# Hack this until live
		global $wgAllowLogDeletion;
		$log_id = $wgAllowLogDeletion ? 'log_id' : '0 AS log_id';
		return array(
			'tables' => array( 'logging', 'user' ),
			'fields' => array( 'log_type', 'log_action', 'log_user', 'log_namespace', 'log_title', 
				'log_params', 'log_comment', $log_id, 'log_deleted', 'log_timestamp', 'user_name' ),
			'conds' => $this->mConds,
			'options' => array()
		);
	}

	function getIndexField() {
		return 'log_timestamp';
	}

	function formatRow( $row ) {
		return $this->mLogEventList->logLine( $row );
	}
	
	public function getType() {
		return $this->type;
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
}

/**
 *
 * @addtogroup SpecialPage
 */
class LogReader {
	var $db, $joinClauses, $whereClauses;
	var $type = '', $user = '', $title = null, $pattern = false;
	/**
	 * @param WebRequest $request For internal use use a FauxRequest object to pass arbitrary parameters.
	 */
	function LogReader( $request ) {
		global $wgUser;
		# Get parameters
		$type = $request->getVal( 'type' );
		$user = $request->getText( 'user' );
		$title = $request->getText( 'page' );
		$pattern = $request->getBool( 'pattern' );
		
		$loglist = new LogEventList( $wgUser->getSkin() );
		$this->pager = new LogPager( $loglist, $type, $user, $title, $pattern );
	}
}

/**
 *
 * @addtogroup SpecialPage
 */
class LogViewer {
	const NO_ACTION_LINK = 1;
	/**
	 * @var LogReader $reader
	 */
	var $reader;
	var $numResults = 0;
	var $flags = 0;

	/**
	 * @param LogReader &$reader where to get our data from
	 * @param integer $flags Bitwise combination of flags:
	 *     LogEventList::NO_ACTION_LINK   Don't show restore/unblock/block links
	 */
	function LogViewer( &$reader, $flags = 0 ) {
		global $wgUser;
		$this->skin = $wgUser->getSkin();
		$this->reader =& $reader;
		$this->reader->pager->mLogEventList->flags = $flags;
		# Aliases for shorter code...
		$this->pager =& $this->reader->pager;
		$this->logEventList =& $this->reader->pager->mLogEventList;
	}

	/**
	 * Take over the whole output page in $wgOut with the log display.
	 */
	function show() {
		global $wgOut;
		# Set title and add header
		$this->logEventList->showHeader( $wgOut, $pager->getType() );
		# Show form options
		$this->logEventList->showOptions( $wgOut, $this->pager->getType(), $this->pager->getUser(), 
			$this->pager->getPage(), $this->pager->getPattern() );
		# Insert list
		$logBody = $this->pager->getBody();
		if( $logBody ) {
			$wgOut->addHTML(
				$this->pager->getNavigationBar() . 
				$this->logEventList->beginLogEventList() .
				$logBody .
				$this->logEventList->endLogEventList() .
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
	 * @param OutputPage $out where to send output
	 */
	function showList( &$out ) {
		$logBody = $this->pager->getBody();
		if( $logBody ) {
			$out->addHTML(
				$this->pager->getNavigationBar() . 
				$this->logEventList->beginLogEventList() .
				$logBody .
				$this->logEventList->endLogEventList() .
				$this->pager->getNavigationBar()
			);
		} else {
			$out->addWikiMsg( 'logempty' );
		}
	}
}

