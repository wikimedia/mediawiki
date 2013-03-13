<?php
/**
 * Utility class for creating and accessing recent change entries.
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

/**
 * Utility class for creating new RC entries
 *
 * mAttribs:
 *  rc_id           id of the row in the recentchanges table
 *  rc_timestamp    time the entry was made
 *  rc_cur_time     timestamp on the cur row
 *  rc_namespace    namespace #
 *  rc_title        non-prefixed db key
 *  rc_type         is new entry, used to determine whether updating is necessary
 *  rc_minor        is minor
 *  rc_cur_id       page_id of associated page entry
 *  rc_user         user id who made the entry
 *  rc_user_text    user name who made the entry
 *  rc_comment      edit summary
 *  rc_this_oldid   rev_id associated with this entry (or zero)
 *  rc_last_oldid   rev_id associated with the entry before this one (or zero)
 *  rc_bot          is bot, hidden
 *  rc_ip           IP address of the user in dotted quad notation
 *  rc_new          obsolete, use rc_type==RC_NEW
 *  rc_patrolled    boolean whether or not someone has marked this edit as patrolled
 *  rc_old_len      integer byte length of the text before the edit
 *  rc_new_len      the same after the edit
 *  rc_deleted      partial deletion
 *  rc_logid        the log_id value for this log entry (or zero)
 *  rc_log_type     the log type (or null)
 *  rc_log_action   the log action (or null)
 *  rc_params       log params
 *
 * mExtra:
 *  prefixedDBkey   prefixed db key, used by external app via msg queue
 *  lastTimestamp   timestamp of previous entry, used in WHERE clause during update
 *  lang            the interwiki prefix, automatically set in save()
 *  oldSize         text size before the change
 *  newSize         text size after the change
 *  pageStatus      status of the page: created, deleted, moved, restored, changed
 *
 * temporary:       not stored in the database
 *      notificationtimestamp
 *      numberofWatchingusers
 *
 * @todo document functions and variables
 */
class RecentChange {
	var $mAttribs = array(), $mExtra = array();

	/**
	 * @var Title
	 */
	var $mTitle = false;

	/**
	 * @var User
	 */
	private $mPerformer = false;

	/**
	 * @var Title
	 */
	var $mMovedToTitle = false;
	var $numberofWatchingusers = 0; # Dummy to prevent error message in SpecialRecentchangeslinked
	var $notificationtimestamp;

	# Factory methods

	/**
	 * @param $row
	 * @return RecentChange
	 */
	public static function newFromRow( $row ) {
		$rc = new RecentChange;
		$rc->loadFromRow( $row );
		return $rc;
	}

	/**
	 * @param $row
	 * @return RecentChange
	 */
	public static function newFromCurRow( $row ) {
		$rc = new RecentChange;
		$rc->loadFromCurRow( $row );
		$rc->notificationtimestamp = false;
		$rc->numberofWatchingusers = false;
		return $rc;
	}

	/**
	 * Obtain the recent change with a given rc_id value
	 *
	 * @param int $rcid rc_id value to retrieve
	 * @return RecentChange
	 */
	public static function newFromId( $rcid ) {
		return self::newFromConds( array( 'rc_id' => $rcid ), __METHOD__ );
	}

	/**
	 * Find the first recent change matching some specific conditions
	 *
	 * @param array $conds of conditions
	 * @param $fname Mixed: override the method name in profiling/logs
	 * @return RecentChange
	 */
	public static function newFromConds( $conds, $fname = __METHOD__ ) {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'recentchanges', self::selectFields(), $conds, $fname );
		if ( $row !== false ) {
			return self::newFromRow( $row );
		} else {
			return null;
		}
	}

	/**
	 * Return the list of recentchanges fields that should be selected to create
	 * a new recentchanges object.
	 * @return array
	 */
	public static function selectFields() {
		return array(
			'rc_id',
			'rc_timestamp',
			'rc_cur_time',
			'rc_user',
			'rc_user_text',
			'rc_namespace',
			'rc_title',
			'rc_comment',
			'rc_minor',
			'rc_bot',
			'rc_new',
			'rc_cur_id',
			'rc_this_oldid',
			'rc_last_oldid',
			'rc_type',
			'rc_patrolled',
			'rc_ip',
			'rc_old_len',
			'rc_new_len',
			'rc_deleted',
			'rc_logid',
			'rc_log_type',
			'rc_log_action',
			'rc_params',
		);
	}

	# Accessors

	/**
	 * @param $attribs array
	 */
	public function setAttribs( $attribs ) {
		$this->mAttribs = $attribs;
	}

	/**
	 * @param $extra array
	 */
	public function setExtra( $extra ) {
		$this->mExtra = $extra;
	}

	/**
	 *
	 * @return Title
	 */
	public function &getTitle() {
		if ( $this->mTitle === false ) {
			$this->mTitle = Title::makeTitle( $this->mAttribs['rc_namespace'], $this->mAttribs['rc_title'] );
		}
		return $this->mTitle;
	}

	/**
	 * Get the User object of the person who performed this change.
	 *
	 * @return User
	 */
	public function getPerformer() {
		if ( $this->mPerformer === false ) {
			if ( $this->mAttribs['rc_user'] ) {
				$this->mPerformer = User::newFromID( $this->mAttribs['rc_user'] );
			} else {
				$this->mPerformer = User::newFromName( $this->mAttribs['rc_user_text'], false );
			}
		}
		return $this->mPerformer;
	}

	/**
	 * Writes the data in this object to the database
	 * @param $noudp bool
	 */
	public function save( $noudp = false ) {
		global $wgLocalInterwiki, $wgPutIPinRC, $wgUseEnotif, $wgShowUpdatedMarker, $wgContLang;

		$dbw = wfGetDB( DB_MASTER );
		if ( !is_array( $this->mExtra ) ) {
			$this->mExtra = array();
		}
		$this->mExtra['lang'] = $wgLocalInterwiki;

		if ( !$wgPutIPinRC ) {
			$this->mAttribs['rc_ip'] = '';
		}

		# If our database is strict about IP addresses, use NULL instead of an empty string
		if ( $dbw->strictIPs() and $this->mAttribs['rc_ip'] == '' ) {
			unset( $this->mAttribs['rc_ip'] );
		}

		# Trim spaces on user supplied text
		$this->mAttribs['rc_comment'] = trim( $this->mAttribs['rc_comment'] );

		# Make sure summary is truncated (whole multibyte characters)
		$this->mAttribs['rc_comment'] = $wgContLang->truncate( $this->mAttribs['rc_comment'], 255 );

		# Fixup database timestamps
		$this->mAttribs['rc_timestamp'] = $dbw->timestamp( $this->mAttribs['rc_timestamp'] );
		$this->mAttribs['rc_cur_time'] = $dbw->timestamp( $this->mAttribs['rc_cur_time'] );
		$this->mAttribs['rc_id'] = $dbw->nextSequenceValue( 'recentchanges_rc_id_seq' );

		## If we are using foreign keys, an entry of 0 for the page_id will fail, so use NULL
		if ( $dbw->cascadingDeletes() and $this->mAttribs['rc_cur_id'] == 0 ) {
			unset( $this->mAttribs['rc_cur_id'] );
		}

		# Insert new row
		$dbw->insert( 'recentchanges', $this->mAttribs, __METHOD__ );

		# Set the ID
		$this->mAttribs['rc_id'] = $dbw->insertId();

		# Notify extensions
		wfRunHooks( 'RecentChange_save', array( &$this ) );

		# Notify external application via UDP
		if ( !$noudp ) {
			$this->notifyRC2UDP();
		}

		# E-mail notifications
		if ( $wgUseEnotif || $wgShowUpdatedMarker ) {
			$editor = $this->getPerformer();
			$title = $this->getTitle();

			if ( wfRunHooks( 'AbortEmailNotification', array( $editor, $title ) ) ) {
				# @todo FIXME: This would be better as an extension hook
				$enotif = new EmailNotification();
				$enotif->notifyOnPageChange( $editor, $title,
					$this->mAttribs['rc_timestamp'],
					$this->mAttribs['rc_comment'],
					$this->mAttribs['rc_minor'],
					$this->mAttribs['rc_last_oldid'],
					$this->mExtra['pageStatus'] );
			}
		}
	}

	public function notifyRC2UDP() {
		global $wgRC2UDPAddress, $wgRC2UDPOmitBots;
		# Notify external application via UDP
		if ( $wgRC2UDPAddress && ( !$this->mAttribs['rc_bot'] || !$wgRC2UDPOmitBots ) ) {
			self::sendToUDP( $this->getIRCLine() );
		}
	}

	/**
	 * Send some text to UDP.
	 * @see RecentChange::cleanupForIRC
	 * @param string $line text to send
	 * @param string $address defaults to $wgRC2UDPAddress.
	 * @param string $prefix defaults to $wgRC2UDPPrefix.
	 * @param int $port defaults to $wgRC2UDPPort. (Since 1.17)
	 * @return Boolean: success
	 */
	public static function sendToUDP( $line, $address = '', $prefix = '', $port = '' ) {
		global $wgRC2UDPAddress, $wgRC2UDPPrefix, $wgRC2UDPPort;
		# Assume default for standard RC case
		$address = $address ? $address : $wgRC2UDPAddress;
		$prefix = $prefix ? $prefix : $wgRC2UDPPrefix;
		$port = $port ? $port : $wgRC2UDPPort;
		# Notify external application via UDP
		if ( $address ) {
			$conn = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
			if ( $conn ) {
				$line = $prefix . $line;
				wfDebug( __METHOD__ . ": sending UDP line: $line\n" );
				socket_sendto( $conn, $line, strlen( $line ), 0, $address, $port );
				socket_close( $conn );
				return true;
			} else {
				wfDebug( __METHOD__ . ": failed to create UDP socket\n" );
			}
		}
		return false;
	}

	/**
	 * Remove newlines, carriage returns and decode html entities
	 * @param $text String
	 * @return String
	 */
	public static function cleanupForIRC( $text ) {
		return Sanitizer::decodeCharReferences( str_replace( array( "\n", "\r" ), array( "", "" ), $text ) );
	}

	/**
	 * Mark a given change as patrolled
	 *
	 * @param $change Mixed: RecentChange or corresponding rc_id
	 * @param $auto Boolean: for automatic patrol
	 * @return Array See doMarkPatrolled(), or null if $change is not an existing rc_id
	 */
	public static function markPatrolled( $change, $auto = false ) {
		global $wgUser;

		$change = $change instanceof RecentChange
			? $change
			: RecentChange::newFromId( $change );

		if ( !$change instanceof RecentChange ) {
			return null;
		}
		return $change->doMarkPatrolled( $wgUser, $auto );
	}

	/**
	 * Mark this RecentChange as patrolled
	 *
	 * NOTE: Can also return 'rcpatroldisabled', 'hookaborted' and 'markedaspatrollederror-noautopatrol' as errors
	 * @param $user User object doing the action
	 * @param $auto Boolean: for automatic patrol
	 * @return array of permissions errors, see Title::getUserPermissionsErrors()
	 */
	public function doMarkPatrolled( User $user, $auto = false ) {
		global $wgUseRCPatrol, $wgUseNPPatrol;
		$errors = array();
		// If recentchanges patrol is disabled, only new pages
		// can be patrolled
		if ( !$wgUseRCPatrol && ( !$wgUseNPPatrol || $this->getAttribute( 'rc_type' ) != RC_NEW ) ) {
			$errors[] = array( 'rcpatroldisabled' );
		}
		// Automatic patrol needs "autopatrol", ordinary patrol needs "patrol"
		$right = $auto ? 'autopatrol' : 'patrol';
		$errors = array_merge( $errors, $this->getTitle()->getUserPermissionsErrors( $right, $user ) );
		if ( !wfRunHooks( 'MarkPatrolled', array( $this->getAttribute( 'rc_id' ), &$user, false ) ) ) {
			$errors[] = array( 'hookaborted' );
		}
		// Users without the 'autopatrol' right can't patrol their
		// own revisions
		if ( $user->getName() == $this->getAttribute( 'rc_user_text' ) && !$user->isAllowed( 'autopatrol' ) ) {
			$errors[] = array( 'markedaspatrollederror-noautopatrol' );
		}
		if ( $errors ) {
			return $errors;
		}
		// If the change was patrolled already, do nothing
		if ( $this->getAttribute( 'rc_patrolled' ) ) {
			return array();
		}
		// Actually set the 'patrolled' flag in RC
		$this->reallyMarkPatrolled();
		// Log this patrol event
		PatrolLog::record( $this, $auto, $user );
		wfRunHooks( 'MarkPatrolledComplete', array( $this->getAttribute( 'rc_id' ), &$user, false ) );
		return array();
	}

	/**
	 * Mark this RecentChange patrolled, without error checking
	 * @return Integer: number of affected rows
	 */
	public function reallyMarkPatrolled() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'recentchanges',
			array(
				'rc_patrolled' => 1
			),
			array(
				'rc_id' => $this->getAttribute( 'rc_id' )
			),
			__METHOD__
		);
		return $dbw->affectedRows();
	}

	/**
	 * Makes an entry in the database corresponding to an edit
	 *
	 * @param $timestamp
	 * @param $title Title
	 * @param $minor
	 * @param $user User
	 * @param $comment
	 * @param $oldId
	 * @param $lastTimestamp
	 * @param $bot
	 * @param $ip string
	 * @param $oldSize int
	 * @param $newSize int
	 * @param $newId int
	 * @param $patrol int
	 * @return RecentChange
	 */
	public static function notifyEdit( $timestamp, &$title, $minor, &$user, $comment, $oldId,
		$lastTimestamp, $bot, $ip = '', $oldSize = 0, $newSize = 0, $newId = 0, $patrol = 0 ) {
		$rc = new RecentChange;
		$rc->mTitle = $title;
		$rc->mPerformer = $user;
		$rc->mAttribs = array(
			'rc_timestamp'  => $timestamp,
			'rc_cur_time'   => $timestamp,
			'rc_namespace'  => $title->getNamespace(),
			'rc_title'      => $title->getDBkey(),
			'rc_type'       => RC_EDIT,
			'rc_minor'      => $minor ? 1 : 0,
			'rc_cur_id'     => $title->getArticleID(),
			'rc_user'       => $user->getId(),
			'rc_user_text'  => $user->getName(),
			'rc_comment'    => $comment,
			'rc_this_oldid' => $newId,
			'rc_last_oldid' => $oldId,
			'rc_bot'        => $bot ? 1 : 0,
			'rc_ip'         => self::checkIPAddress( $ip ),
			'rc_patrolled'  => intval( $patrol ),
			'rc_new'        => 0,  # obsolete
			'rc_old_len'    => $oldSize,
			'rc_new_len'    => $newSize,
			'rc_deleted'    => 0,
			'rc_logid'      => 0,
			'rc_log_type'   => null,
			'rc_log_action' => '',
			'rc_params'     => ''
		);

		$rc->mExtra = array(
			'prefixedDBkey' => $title->getPrefixedDBkey(),
			'lastTimestamp' => $lastTimestamp,
			'oldSize'       => $oldSize,
			'newSize'       => $newSize,
			'pageStatus'   => 'changed'
		);
		$rc->save();
		return $rc;
	}

	/**
	 * Makes an entry in the database corresponding to page creation
	 * Note: the title object must be loaded with the new id using resetArticleID()
	 * @todo Document parameters and return
	 *
	 * @param $timestamp
	 * @param $title Title
	 * @param $minor
	 * @param $user User
	 * @param $comment
	 * @param $bot
	 * @param $ip string
	 * @param $size int
	 * @param $newId int
	 * @param $patrol int
	 * @return RecentChange
	 */
	public static function notifyNew( $timestamp, &$title, $minor, &$user, $comment, $bot,
		$ip = '', $size = 0, $newId = 0, $patrol = 0 ) {
		$rc = new RecentChange;
		$rc->mTitle = $title;
		$rc->mPerformer = $user;
		$rc->mAttribs = array(
			'rc_timestamp'      => $timestamp,
			'rc_cur_time'       => $timestamp,
			'rc_namespace'      => $title->getNamespace(),
			'rc_title'          => $title->getDBkey(),
			'rc_type'           => RC_NEW,
			'rc_minor'          => $minor ? 1 : 0,
			'rc_cur_id'         => $title->getArticleID(),
			'rc_user'           => $user->getId(),
			'rc_user_text'      => $user->getName(),
			'rc_comment'        => $comment,
			'rc_this_oldid'     => $newId,
			'rc_last_oldid'     => 0,
			'rc_bot'            => $bot ? 1 : 0,
			'rc_ip'             => self::checkIPAddress( $ip ),
			'rc_patrolled'      => intval( $patrol ),
			'rc_new'            => 1, # obsolete
			'rc_old_len'        => 0,
			'rc_new_len'        => $size,
			'rc_deleted'        => 0,
			'rc_logid'          => 0,
			'rc_log_type'       => null,
			'rc_log_action'     => '',
			'rc_params'         => ''
		);

		$rc->mExtra = array(
			'prefixedDBkey' => $title->getPrefixedDBkey(),
			'lastTimestamp' => 0,
			'oldSize' => 0,
			'newSize' => $size,
			'pageStatus' => 'created'
		);
		$rc->save();
		return $rc;
	}

	/**
	 * @param $timestamp
	 * @param $title
	 * @param $user
	 * @param $actionComment
	 * @param $ip string
	 * @param $type
	 * @param $action
	 * @param $target
	 * @param $logComment
	 * @param $params
	 * @param $newId int
	 * @param $actionCommentIRC string
	 * @return bool
	 */
	public static function notifyLog( $timestamp, &$title, &$user, $actionComment, $ip, $type,
		$action, $target, $logComment, $params, $newId = 0, $actionCommentIRC = '' )
	{
		global $wgLogRestrictions;
		# Don't add private logs to RC!
		if ( isset( $wgLogRestrictions[$type] ) && $wgLogRestrictions[$type] != '*' ) {
			return false;
		}
		$rc = self::newLogEntry( $timestamp, $title, $user, $actionComment, $ip, $type, $action,
			$target, $logComment, $params, $newId, $actionCommentIRC );
		$rc->save();
		return true;
	}

	/**
	 * @param $timestamp
	 * @param $title Title
	 * @param $user User
	 * @param $actionComment
	 * @param $ip string
	 * @param $type
	 * @param $action
	 * @param $target Title
	 * @param $logComment
	 * @param $params
	 * @param $newId int
	 * @param $actionCommentIRC string
	 * @return RecentChange
	 */
	public static function newLogEntry( $timestamp, &$title, &$user, $actionComment, $ip,
		$type, $action, $target, $logComment, $params, $newId = 0, $actionCommentIRC = '' ) {
		global $wgRequest;

		## Get pageStatus for email notification
		switch ( $type . '-' . $action ) {
			case 'delete-delete':
				$pageStatus = 'deleted';
				break;
			case 'move-move':
			case 'move-move_redir':
				$pageStatus = 'moved';
				break;
			case 'delete-restore':
				$pageStatus = 'restored';
				break;
			case 'upload-upload':
				$pageStatus = 'created';
				break;
			case 'upload-overwrite':
			default:
				$pageStatus = 'changed';
				break;
		}

		$rc = new RecentChange;
		$rc->mTitle = $target;
		$rc->mPerformer = $user;
		$rc->mAttribs = array(
			'rc_timestamp'  => $timestamp,
			'rc_cur_time'   => $timestamp,
			'rc_namespace'  => $target->getNamespace(),
			'rc_title'      => $target->getDBkey(),
			'rc_type'       => RC_LOG,
			'rc_minor'      => 0,
			'rc_cur_id'     => $target->getArticleID(),
			'rc_user'       => $user->getId(),
			'rc_user_text'  => $user->getName(),
			'rc_comment'    => $logComment,
			'rc_this_oldid' => 0,
			'rc_last_oldid' => 0,
			'rc_bot'        => $user->isAllowed( 'bot' ) ? $wgRequest->getBool( 'bot', true ) : 0,
			'rc_ip'         => self::checkIPAddress( $ip ),
			'rc_patrolled'  => 1,
			'rc_new'        => 0, # obsolete
			'rc_old_len'    => null,
			'rc_new_len'    => null,
			'rc_deleted'    => 0,
			'rc_logid'      => $newId,
			'rc_log_type'   => $type,
			'rc_log_action' => $action,
			'rc_params'     => $params
		);

		$rc->mExtra = array(
			'prefixedDBkey' => $title->getPrefixedDBkey(),
			'lastTimestamp' => 0,
			'actionComment' => $actionComment, // the comment appended to the action, passed from LogPage
			'pageStatus'    => $pageStatus,
			'actionCommentIRC' => $actionCommentIRC
		);
		return $rc;
	}

	/**
	 * Initialises the members of this object from a mysql row object
	 *
	 * @param $row
	 */
	public function loadFromRow( $row ) {
		$this->mAttribs = get_object_vars( $row );
		$this->mAttribs['rc_timestamp'] = wfTimestamp( TS_MW, $this->mAttribs['rc_timestamp'] );
		$this->mAttribs['rc_deleted'] = $row->rc_deleted; // MUST be set
	}

	/**
	 * Makes a pseudo-RC entry from a cur row
	 *
	 * @param $row
	 */
	public function loadFromCurRow( $row ) {
		$this->mAttribs = array(
			'rc_timestamp' => wfTimestamp( TS_MW, $row->rev_timestamp ),
			'rc_cur_time' => $row->rev_timestamp,
			'rc_user' => $row->rev_user,
			'rc_user_text' => $row->rev_user_text,
			'rc_namespace' => $row->page_namespace,
			'rc_title' => $row->page_title,
			'rc_comment' => $row->rev_comment,
			'rc_minor' => $row->rev_minor_edit ? 1 : 0,
			'rc_type' => $row->page_is_new ? RC_NEW : RC_EDIT,
			'rc_cur_id' => $row->page_id,
			'rc_this_oldid' => $row->rev_id,
			'rc_last_oldid' => isset( $row->rc_last_oldid ) ? $row->rc_last_oldid : 0,
			'rc_bot' => 0,
			'rc_ip' => '',
			'rc_id' => $row->rc_id,
			'rc_patrolled' => $row->rc_patrolled,
			'rc_new' => $row->page_is_new, # obsolete
			'rc_old_len' => $row->rc_old_len,
			'rc_new_len' => $row->rc_new_len,
			'rc_params' => isset( $row->rc_params ) ? $row->rc_params : '',
			'rc_log_type' => isset( $row->rc_log_type ) ? $row->rc_log_type : null,
			'rc_log_action' => isset( $row->rc_log_action ) ? $row->rc_log_action : null,
			'rc_logid' => isset( $row->rc_logid ) ? $row->rc_logid : 0,
			'rc_deleted' => $row->rc_deleted // MUST be set
		);
	}

	/**
	 * Get an attribute value
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute( $name ) {
		return isset( $this->mAttribs[$name] ) ? $this->mAttribs[$name] : null;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->mAttribs;
	}

	/**
	 * Gets the end part of the diff URL associated with this object
	 * Blank if no diff link should be displayed
	 * @param $forceCur
	 * @return string
	 */
	public function diffLinkTrail( $forceCur ) {
		if ( $this->mAttribs['rc_type'] == RC_EDIT ) {
			$trail = "curid=" . (int)( $this->mAttribs['rc_cur_id'] ) .
				"&oldid=" . (int)( $this->mAttribs['rc_last_oldid'] );
			if ( $forceCur ) {
				$trail .= '&diff=0';
			} else {
				$trail .= '&diff=' . (int)( $this->mAttribs['rc_this_oldid'] );
			}
		} else {
			$trail = '';
		}
		return $trail;
	}

	/**
	 * @return string
	 */
	public function getIRCLine() {
		global $wgUseRCPatrol, $wgUseNPPatrol, $wgRC2UDPInterwikiPrefix, $wgLocalInterwiki,
			$wgCanonicalServer, $wgScript;

		if ( $this->mAttribs['rc_type'] == RC_LOG ) {
			// Don't use SpecialPage::getTitleFor, backwards compatibility with
			// IRC API which expects "Log".
			$titleObj = Title::newFromText( 'Log/' . $this->mAttribs['rc_log_type'], NS_SPECIAL );
		} else {
			$titleObj =& $this->getTitle();
		}
		$title = $titleObj->getPrefixedText();
		$title = self::cleanupForIRC( $title );

		if ( $this->mAttribs['rc_type'] == RC_LOG ) {
			$url = '';
		} else {
			$url = $wgCanonicalServer . $wgScript;
			if ( $this->mAttribs['rc_type'] == RC_NEW ) {
				$query = '?oldid=' . $this->mAttribs['rc_this_oldid'];
			} else {
				$query = '?diff=' . $this->mAttribs['rc_this_oldid'] . '&oldid=' . $this->mAttribs['rc_last_oldid'];
			}
			if ( $wgUseRCPatrol || ( $this->mAttribs['rc_type'] == RC_NEW && $wgUseNPPatrol ) ) {
				$query .= '&rcid=' . $this->mAttribs['rc_id'];
			}
			// HACK: We need this hook for WMF's secure server setup
			wfRunHooks( 'IRCLineURL', array( &$url, &$query ) );
			$url .= $query;
		}

		if ( $this->mAttribs['rc_old_len'] !== null && $this->mAttribs['rc_new_len'] !== null ) {
			$szdiff = $this->mAttribs['rc_new_len'] - $this->mAttribs['rc_old_len'];
			if ( $szdiff < -500 ) {
				$szdiff = "\002$szdiff\002";
			} elseif ( $szdiff >= 0 ) {
				$szdiff = '+' . $szdiff;
			}
			// @todo i18n with parentheses in content language?
			$szdiff = '(' . $szdiff . ')';
		} else {
			$szdiff = '';
		}

		$user = self::cleanupForIRC( $this->mAttribs['rc_user_text'] );

		if ( $this->mAttribs['rc_type'] == RC_LOG ) {
			$targetText = $this->getTitle()->getPrefixedText();
			$comment = self::cleanupForIRC( str_replace( "[[$targetText]]", "[[\00302$targetText\00310]]", $this->mExtra['actionCommentIRC'] ) );
			$flag = $this->mAttribs['rc_log_action'];
		} else {
			$comment = self::cleanupForIRC( $this->mAttribs['rc_comment'] );
			$flag = '';
			if ( !$this->mAttribs['rc_patrolled'] && ( $wgUseRCPatrol || $this->mAttribs['rc_type'] == RC_NEW && $wgUseNPPatrol ) ) {
				$flag .= '!';
			}
			$flag .= ( $this->mAttribs['rc_type'] == RC_NEW ? "N" : "" ) . ( $this->mAttribs['rc_minor'] ? "M" : "" ) . ( $this->mAttribs['rc_bot'] ? "B" : "" );
		}

		if ( $wgRC2UDPInterwikiPrefix === true && $wgLocalInterwiki !== false ) {
			$prefix = $wgLocalInterwiki;
		} elseif ( $wgRC2UDPInterwikiPrefix ) {
			$prefix = $wgRC2UDPInterwikiPrefix;
		} else {
			$prefix = false;
		}
		if ( $prefix !== false ) {
			$titleString = "\00314[[\00303$prefix:\00307$title\00314]]";
		} else {
			$titleString = "\00314[[\00307$title\00314]]";
		}

		# see http://www.irssi.org/documentation/formats for some colour codes. prefix is \003,
		# no colour (\003) switches back to the term default
		$fullString = "$titleString\0034 $flag\00310 " .
			"\00302$url\003 \0035*\003 \00303$user\003 \0035*\003 $szdiff \00310$comment\003\n";

		return $fullString;
	}

	/**
	 * Returns the change size (HTML).
	 * The lengths can be given optionally.
	 * @param $old int
	 * @param $new int
	 * @return string
	 */
	public function getCharacterDifference( $old = 0, $new = 0 ) {
		if ( $old === 0 ) {
			$old = $this->mAttribs['rc_old_len'];
		}
		if ( $new === 0 ) {
			$new = $this->mAttribs['rc_new_len'];
		}
		if ( $old === null || $new === null ) {
			return '';
		}
		return ChangesList::showCharacterDifference( $old, $new );
	}

	private static function checkIPAddress( $ip ) {
		global $wgRequest;
		if ( $ip ) {
			if ( !IP::isIPAddress( $ip ) ) {
				throw new MWException( "Attempt to write \"" . $ip . "\" as an IP address into recent changes" );
			}
		} else {
			$ip = $wgRequest->getIP();
			if ( !$ip ) {
				$ip = '';
			}
		}
		return $ip;
	}
}
