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
			return ''; // No one should be hiding from the oversight log
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
		$this->db = wfGetDB( DB_SLAVE );
		$this->setupQuery( $request );
	}

	/**
	 * Basic setup and applies the limiting factors from the WebRequest object.
	 * @param WebRequest $request
	 * @private
	 */
	function setupQuery( $request ) {
		$page = $this->db->tableName( 'page' );
		$user = $this->db->tableName( 'user' );
		$this->joinClauses = array( 
			"LEFT OUTER JOIN $page ON log_namespace=page_namespace AND log_title=page_title",
			"INNER JOIN $user ON user_id=log_user" );
		$this->whereClauses = array();

		$this->limitType( $request->getVal( 'type' ) );
		$this->limitUser( $request->getText( 'user' ) );
		$this->limitTitle( $request->getText( 'page' ) , $request->getBool( 'pattern' ) );
		$this->limitTime( $request->getVal( 'from' ), '>=' );
		$this->limitTime( $request->getVal( 'until' ), '<=' );

		list( $this->limit, $this->offset ) = $request->getLimitOffset();
		
		// XXX This all needs to use Pager, ugly hack for now.
		global $wgMiserMode;
		if( $wgMiserMode )
			$this->offset = min( $this->offset, 10000 );
	}

	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 * @param string $type A log type ('upload', 'delete', etc)
	 * @private
	 */
	function limitType( $type ) {
		global $wgLogRestrictions, $wgUser;
		// Reset the array, clears extra "where" clauses when $par is used
		$this->whereClauses = $hiddenLogs = array();
		// Nothing to show the user requested a log they can't see
		if( isset($wgLogRestrictions[$type]) && !$wgUser->isAllowed($wgLogRestrictions[$type]) ) {
			$this->whereClauses[] = "1 = 0";
			return false;
		}
		// Don't show private logs to unpriviledged users
		foreach( $wgLogRestrictions as $logtype => $right ) {
			if( !$wgUser->isAllowed($right) || empty($type) ) {
				$safetype = $this->db->strencode( $logtype );
				$hiddenLogs[] = "'$safetype'";
			}
		}
		if( !empty($hiddenLogs) ) {
			$this->whereClauses[] = 'log_type NOT IN('.implode(',',$hiddenLogs).')';
		}
		
		if( empty($type) ) {
			return false;
		}
		$this->type = $type;
		$safetype = $this->db->strencode( $type );
		$this->whereClauses[] = "log_type='$safetype'";
	}

	/**
	 * Set the log reader to return only entries by the given user.
	 * @param string $name (In)valid user name
	 * @private
	 */
	function limitUser( $name ) {
		if ( $name == '' )
			return false;
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if ( is_null( $usertitle ) )
			return false;
		$this->user = $usertitle->getText();
		
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = $this->db->selectField('user','user_id',array('user_name'=>$this->user));
		if (!$userid)
			/* It should be nicer to abort query at all, 
			   but for now it won't pass anywhere behind the optimizer */
			$this->whereClauses[] = "NULL";
		else
			$this->whereClauses[] = "log_user=$userid";
	}

	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 * @param string $page Title name as text
	 * @private
	 */
	function limitTitle( $page , $pattern ) {
		global $wgMiserMode;
		
		$title = Title::newFromText( $page );
		
		if( strlen( $page ) == 0 || !$title instanceof Title )
			return false;

		$this->title =& $title;
		$this->pattern = $pattern;
		$ns = $title->getNamespace();
		if ( $pattern && !$wgMiserMode ) {
			$safetitle = $this->db->escapeLike( $title->getDBkey() ); // use escapeLike to avoid expensive search patterns like 't%st%'
			$this->whereClauses[] = "log_namespace=$ns AND log_title LIKE '$safetitle%'";
		} else {
			$safetitle = $this->db->strencode( $title->getDBkey() );
			$this->whereClauses[] = "log_namespace=$ns AND log_title = '$safetitle'";
		}
	}

	/**
	 * Set the log reader to return only entries in a given time range.
	 * @param string $time Timestamp of one endpoint
	 * @param string $direction either ">=" or "<=" operators
	 * @private
	 */
	function limitTime( $time, $direction ) {
		# Direction should be a comparison operator
		if( empty( $time ) ) {
			return false;
		}
		$safetime = $this->db->strencode( wfTimestamp( TS_MW, $time ) );
		$this->whereClauses[] = "log_timestamp $direction '$safetime'";
	}

	/**
	 * Build an SQL query from all the set parameters.
	 * @return string the SQL query
	 * @private
	 */
	function getQuery() {
		global $wgAllowLogDeletion;
	
		$logging = $this->db->tableName( "logging" );
		$sql = "SELECT /*! STRAIGHT_JOIN */ log_type, log_action, log_timestamp,
			log_user, user_name, log_namespace, log_title, page_id,
			log_comment, log_params, log_deleted ";
		if( $wgAllowLogDeletion )	
			$sql .= ", log_id ";
		
		$sql .= "FROM $logging ";
		if( !empty( $this->joinClauses ) ) {
			$sql .= implode( ' ', $this->joinClauses );
		}
		if( !empty( $this->whereClauses ) ) {
			$sql .= " WHERE " . implode( ' AND ', $this->whereClauses );
		}
		$sql .= " ORDER BY log_timestamp DESC ";
		$sql = $this->db->limitResult($sql, $this->limit, $this->offset );
		return $sql;
	}

	/**
	 * Execute the query and start returning results.
	 * @return ResultWrapper result object to return the relevant rows
	 */
	function getRows() {
		$res = $this->db->query( $this->getQuery(), __METHOD__ );
		return $this->db->resultObject( $res );
	}

	/**
	 * @return string The query type that this LogReader has been limited to.
	 */
	function queryType() {
		return $this->type;
	}

	/**
	 * @return string The username type that this LogReader has been limited to, if any.
	 */
	function queryUser() {
		return $this->user;
	}

	/**
	 * @return boolean The checkbox, if titles should be searched by a pattern too
	 */
	function queryPattern() {
		return $this->pattern;
	}

	/**
	 * @return string The text of the title that this LogReader has been limited to.
	 */
	function queryTitle() {
		if( is_null( $this->title ) ) {
			return '';
		} else {
			return $this->title->getPrefixedText();
		}
	}
	
	/**
	 * Is there at least one row?
	 *
	 * @return bool
	 */
	public function hasRows() {
		# Little hack...
		$limit = $this->limit;
		$this->limit = 1;
		$res = $this->db->query( $this->getQuery() );
		$this->limit = $limit;
		$ret = $this->db->numRows( $res ) > 0;
		$this->db->freeResult( $res );
		return $ret;
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
		$this->flags = $flags;
		$this->logList = new LogEventList( $wgUser->getSkin(), $flags );
	}

	/**
	 * Take over the whole output page in $wgOut with the log display.
	 */
	function show() {
		global $wgOut;
		$this->logList->showHeader( $wgOut, $this->reader->queryType() );
		$this->logList->showOptions( $wgOut, $this->reader->queryType(), $this->reader->queryUser(), 
			$this->reader->queryTitle(), $this->reader->queryPattern() ); 
		$result = $this->getLogRows();
		if ( $this->numResults > 0 ) {
			$this->showPrevNext( $wgOut );
			$this->doShowList( $wgOut, $result );
			$this->showPrevNext( $wgOut );
		} else {
			$this->showError( $wgOut );
		}
	}

	/**
	 * Load the data from the linked LogReader
	 * Preload the link cache
	 * Initialise numResults
	 *
	 * Must be called before calling showPrevNext
	 *
	 * @return object database result set
	 */
	function getLogRows() {
		$result = $this->reader->getRows();
		$this->numResults = 0;

		// Fetch results and form a batch link existence query
		$batch = new LinkBatch;
		while ( $s = $result->fetchObject() ) {
			// User link
			$batch->addObj( Title::makeTitleSafe( NS_USER, $s->user_name ) );
			$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $s->user_name ) );

			// Move destination link
			if ( $s->log_type == 'move' ) {
				$paramArray = LogPage::extractParams( $s->log_params );
				$title = Title::newFromText( $paramArray[0] );
				$batch->addObj( $title );
			}
			++$this->numResults;
		}
		$batch->execute();

		return $result;
	}


	/**
	 * Output just the list of entries given by the linked LogReader,
	 * with extraneous UI elements. Use for displaying log fragments in
	 * another page (eg at Special:Undelete)
	 * @param OutputPage $out where to send output
	 */
	function showList( &$out ) {
		$result = $this->getLogRows();
		if ( $this->numResults > 0 ) {
			$this->doShowList( $out, $result );
		} else {
			$this->showError( $out );
		}
	}

	function doShowList( &$out, $result ) {
		// Rewind result pointer and go through it again, making the HTML
		$html = "\n<ul>\n";
		$result->seek( 0 );
		while( $s = $result->fetchObject() ) {
			$html .= $this->logList->logLine( $s );
		}
		$html .= "\n</ul>\n";
		$out->addHTML( $html );
		$result->free();
	}

	function showError( &$out ) {
		$out->addWikiMsg( 'logempty' );
	}

	/**
	 * @param OutputPage &$out where to send output
	 * @private
	 */
	function showPrevNext( &$out ) {
		global $wgContLang,$wgRequest;
		$pieces = array();
		$pieces[] = 'type=' . urlencode( $this->reader->queryType() );
		$pieces[] = 'user=' . urlencode( $this->reader->queryUser() );
		$pieces[] = 'page=' . urlencode( $this->reader->queryTitle() );
		$pieces[] = 'pattern=' . urlencode( $this->reader->queryPattern() );
		$bits = implode( '&', $pieces );
		list( $limit, $offset ) = $wgRequest->getLimitOffset();

		# TODO: use timestamps instead of offsets to make it more natural
		# to go huge distances in time
		$html = wfViewPrevNext( $offset, $limit,
			$wgContLang->specialpage( 'Log' ),
			$bits,
			$this->numResults < $limit);
		$out->addHTML( '<p>' . $html . '</p>' );
	}
}

