<?php
/**
 * Contain classes for dealing with individual log entries
 *
 * This is how I see the log system history:
 * - appending to plain wiki pages
 * - formatting log entries based on database fields
 * - user is now part of the action message
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
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.19
 */

/**
 * Interface for log entries. Every log entry has these methods.
 * @since 1.19
 */
interface LogEntry {
	/**
	 * The main log type.
	 * @return string
	 */
	public function getType();

	/**
	 * The log subtype.
	 * @return string
	 */
	public function getSubtype();

	/**
	 * The full logtype in format maintype/subtype.
	 * @return string
	 */
	public function getFullType();

	/**
	 * Get the extra parameters stored for this message.
	 * @return array
	 */
	public function getParameters();

	/**
	 * Get the user for performed this action.
	 * @return User
	 */
	public function getPerformer();

	/**
	 * Get the target page of this action.
	 * @return Title
	 */
	public function getTarget();

	/**
	 * Get the timestamp when the action was executed.
	 * @return string
	 */
	public function getTimestamp();

	/**
	 * Get the user provided comment.
	 * @return string
	 */
	public function getComment();

	/**
	 * Get the access restriction.
	 * @return string
	 */
	public function getDeleted();

	/**
	 * @param int $field One of LogPage::DELETED_* bitfield constants
	 * @return bool
	 */
	public function isDeleted( $field );
}

/**
 * Extends the LogEntryInterface with some basic functionality
 * @since 1.19
 */
abstract class LogEntryBase implements LogEntry {
	public function getFullType() {
		return $this->getType() . '/' . $this->getSubtype();
	}

	public function isDeleted( $field ) {
		return ( $this->getDeleted() & $field ) === $field;
	}

	/**
	 * Whether the parameters for this log are stored in new or
	 * old format.
	 * @return bool
	 */
	public function isLegacy() {
		return false;
	}

	/**
	 * Create a blob from a parameter array
	 *
	 * @param array $params
	 * @return string
	 * @since 1.26
	 */
	public static function makeParamBlob( $params ) {
		return serialize( (array)$params );
	}

	/**
	 * Extract a parameter array from a blob
	 *
	 * @param string $blob
	 * @return array
	 * @since 1.26
	 */
	public static function extractParams( $blob ) {
		return unserialize( $blob );
	}
}

/**
 * This class wraps around database result row.
 * @since 1.19
 */
class DatabaseLogEntry extends LogEntryBase {
	// Static->

	/**
	 * Returns array of information that is needed for querying
	 * log entries. Array contains the following keys:
	 * tables, fields, conds, options and join_conds
	 * @return array
	 */
	public static function getSelectQueryData() {
		$tables = array( 'logging', 'user' );
		$fields = array(
			'log_id', 'log_type', 'log_action', 'log_timestamp',
			'log_user', 'log_user_text',
			'log_namespace', 'log_title', // unused log_page
			'log_comment', 'log_params', 'log_deleted',
			'user_id', 'user_name', 'user_editcount',
		);

		$joins = array(
			// IP's don't have an entry in user table
			'user' => array( 'LEFT JOIN', 'log_user=user_id' ),
		);

		return array(
			'tables' => $tables,
			'fields' => $fields,
			'conds' => array(),
			'options' => array(),
			'join_conds' => $joins,
		);
	}

	/**
	 * Constructs new LogEntry from database result row.
	 * Supports rows from both logging and recentchanges table.
	 * @param stdClass|array $row
	 * @return DatabaseLogEntry
	 */
	public static function newFromRow( $row ) {
		$row = (object)$row;
		if ( isset( $row->rc_logid ) ) {
			return new RCDatabaseLogEntry( $row );
		} else {
			return new self( $row );
		}
	}

	// Non-static->

	/** @var stdClass Database result row. */
	protected $row;

	/** @var User */
	protected $performer;

	/** @var bool Whether the parameters for this log entry are stored in new
	 *    or old format.
	 */
	protected $legacy;

	protected function __construct( $row ) {
		$this->row = $row;
	}

	/**
	 * Returns the unique database id.
	 * @return int
	 */
	public function getId() {
		return (int)$this->row->log_id;
	}

	/**
	 * Returns whatever is stored in the database field.
	 * @return string
	 */
	protected function getRawParameters() {
		return $this->row->log_params;
	}

	// LogEntryBase->

	public function isLegacy() {
		// This does the check
		$this->getParameters();

		return $this->legacy;
	}

	// LogEntry->

	public function getType() {
		return $this->row->log_type;
	}

	public function getSubtype() {
		return $this->row->log_action;
	}

	public function getParameters() {
		if ( !isset( $this->params ) ) {
			$blob = $this->getRawParameters();
			MediaWiki\suppressWarnings();
			$params = LogEntryBase::extractParams( $blob );
			MediaWiki\restoreWarnings();
			if ( $params !== false ) {
				$this->params = $params;
				$this->legacy = false;
			} else {
				$this->params = LogPage::extractParams( $blob );
				$this->legacy = true;
			}
		}

		return $this->params;
	}

	public function getPerformer() {
		if ( !$this->performer ) {
			$userId = (int)$this->row->log_user;
			if ( $userId !== 0 ) { // logged-in users
				if ( isset( $this->row->user_name ) ) {
					$this->performer = User::newFromRow( $this->row );
				} else {
					$this->performer = User::newFromId( $userId );
				}
			} else { // IP users
				$userText = $this->row->log_user_text;
				$this->performer = User::newFromName( $userText, false );
			}
		}

		return $this->performer;
	}

	public function getTarget() {
		$namespace = $this->row->log_namespace;
		$page = $this->row->log_title;
		$title = Title::makeTitle( $namespace, $page );

		return $title;
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->log_timestamp );
	}

	public function getComment() {
		return $this->row->log_comment;
	}

	public function getDeleted() {
		return $this->row->log_deleted;
	}
}

class RCDatabaseLogEntry extends DatabaseLogEntry {

	public function getId() {
		return $this->row->rc_logid;
	}

	protected function getRawParameters() {
		return $this->row->rc_params;
	}

	// LogEntry->

	public function getType() {
		return $this->row->rc_log_type;
	}

	public function getSubtype() {
		return $this->row->rc_log_action;
	}

	public function getPerformer() {
		if ( !$this->performer ) {
			$userId = (int)$this->row->rc_user;
			if ( $userId !== 0 ) {
				$this->performer = User::newFromId( $userId );
			} else {
				$userText = $this->row->rc_user_text;
				// Might be an IP, don't validate the username
				$this->performer = User::newFromName( $userText, false );
			}
		}

		return $this->performer;
	}

	public function getTarget() {
		$namespace = $this->row->rc_namespace;
		$page = $this->row->rc_title;
		$title = Title::makeTitle( $namespace, $page );

		return $title;
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->rc_timestamp );
	}

	public function getComment() {
		return $this->row->rc_comment;
	}

	public function getDeleted() {
		return $this->row->rc_deleted;
	}
}

/**
 * Class for creating log entries manually, for
 * example to inject them into the database.
 * @since 1.19
 */
class ManualLogEntry extends LogEntryBase {
	/** @var string Type of log entry */
	protected $type;

	/** @var string Sub type of log entry */
	protected $subtype;

	/** @var array Parameters for log entry */
	protected $parameters = array();

	/** @var array */
	protected $relations = array();

	/** @var User Performer of the action for the log entry */
	protected $performer;

	/** @var Title Target title for the log entry */
	protected $target;

	/** @var string Timestamp of creation of the log entry */
	protected $timestamp;

	/** @var string Comment for the log entry */
	protected $comment = '';

	/** @var int Deletion state of the log entry */
	protected $deleted;

	/** @var int ID of the log entry */
	protected $id;

	/** @var bool Whether this is a legacy log entry */
	protected $legacy = false;

	/**
	 * Constructor.
	 *
	 * @since 1.19
	 *
	 * @param string $type
	 * @param string $subtype
	 */
	public function __construct( $type, $subtype ) {
		$this->type = $type;
		$this->subtype = $subtype;
	}

	/**
	 * Set extra log parameters.
	 *
	 * You can pass params to the log action message by prefixing the keys with
	 * a number and optional type, using colons to separate the fields. The
	 * numbering should start with number 4, the first three parameters are
	 * hardcoded for every message. Example:
	 * $entry->setParameters(
	 *   '4::color' => 'blue',
	 *   '5:number:count' => 3000,
	 *   'animal' => 'dog'
	 * );
	 *
	 * @since 1.19
	 *
	 * @param array $parameters Associative array
	 */
	public function setParameters( $parameters ) {
		$this->parameters = $parameters;
	}

	/**
	 * Declare arbitrary tag/value relations to this log entry.
	 * These can be used to filter log entries later on.
	 *
	 * @param array $relations Map of (tag => (list of values|value))
	 * @since 1.22
	 */
	public function setRelations( array $relations ) {
		$this->relations = $relations;
	}

	/**
	 * Set the user that performed the action being logged.
	 *
	 * @since 1.19
	 *
	 * @param User $performer
	 */
	public function setPerformer( User $performer ) {
		$this->performer = $performer;
	}

	/**
	 * Set the title of the object changed.
	 *
	 * @since 1.19
	 *
	 * @param Title $target
	 */
	public function setTarget( Title $target ) {
		$this->target = $target;
	}

	/**
	 * Set the timestamp of when the logged action took place.
	 *
	 * @since 1.19
	 *
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * Set a comment associated with the action being logged.
	 *
	 * @since 1.19
	 *
	 * @param string $comment
	 */
	public function setComment( $comment ) {
		$this->comment = $comment;
	}

	/**
	 * Set the 'legacy' flag
	 *
	 * @since 1.25
	 * @param bool $legacy
	 */
	public function setLegacy( $legacy ) {
		$this->legacy = $legacy;
	}

	/**
	 * TODO: document
	 *
	 * @since 1.19
	 *
	 * @param int $deleted
	 */
	public function setDeleted( $deleted ) {
		$this->deleted = $deleted;
	}

	/**
	 * Inserts the entry into the logging table.
	 * @param IDatabase $dbw
	 * @return int ID of the log entry
	 * @throws MWException
	 */
	public function insert( IDatabase $dbw = null ) {
		global $wgContLang;

		$dbw = $dbw ?: wfGetDB( DB_MASTER );
		$id = $dbw->nextSequenceValue( 'logging_log_id_seq' );

		if ( $this->timestamp === null ) {
			$this->timestamp = wfTimestampNow();
		}

		# Trim spaces on user supplied text
		$comment = trim( $this->getComment() );

		# Truncate for whole multibyte characters.
		$comment = $wgContLang->truncate( $comment, 255 );

		$data = array(
			'log_id' => $id,
			'log_type' => $this->getType(),
			'log_action' => $this->getSubtype(),
			'log_timestamp' => $dbw->timestamp( $this->getTimestamp() ),
			'log_user' => $this->getPerformer()->getId(),
			'log_user_text' => $this->getPerformer()->getName(),
			'log_namespace' => $this->getTarget()->getNamespace(),
			'log_title' => $this->getTarget()->getDBkey(),
			'log_page' => $this->getTarget()->getArticleID(),
			'log_comment' => $comment,
			'log_params' => LogEntryBase::makeParamBlob( $this->getParameters() ),
		);
		if ( isset( $this->deleted ) ) {
			$data['log_deleted'] = $this->deleted;
		}

		$dbw->insert( 'logging', $data, __METHOD__ );
		$this->id = !is_null( $id ) ? $id : $dbw->insertId();

		$rows = array();
		foreach ( $this->relations as $tag => $values ) {
			if ( !strlen( $tag ) ) {
				throw new MWException( "Got empty log search tag." );
			}

			if ( !is_array( $values ) ) {
				$values = array( $values );
			}

			foreach ( $values as $value ) {
				$rows[] = array(
					'ls_field' => $tag,
					'ls_value' => $value,
					'ls_log_id' => $this->id
				);
			}
		}
		if ( count( $rows ) ) {
			$dbw->insert( 'log_search', $rows, __METHOD__, 'IGNORE' );
		}

		return $this->id;
	}

	/**
	 * Get a RecentChanges object for the log entry
	 * @param int $newId
	 * @return RecentChange
	 * @since 1.23
	 */
	public function getRecentChange( $newId = 0 ) {
		$formatter = LogFormatter::newFromEntry( $this );
		$context = RequestContext::newExtraneousContext( $this->getTarget() );
		$formatter->setContext( $context );

		$logpage = SpecialPage::getTitleFor( 'Log', $this->getType() );
		$user = $this->getPerformer();
		$ip = "";
		if ( $user->isAnon() ) {
			/*
			 * "MediaWiki default" and friends may have
			 * no IP address in their name
			 */
			if ( IP::isIPAddress( $user->getName() ) ) {
				$ip = $user->getName();
			}
		}

		return RecentChange::newLogEntry(
			$this->getTimestamp(),
			$logpage,
			$user,
			$formatter->getPlainActionText(),
			$ip,
			$this->getType(),
			$this->getSubtype(),
			$this->getTarget(),
			$this->getComment(),
			LogEntryBase::makeParamBlob( $this->getParameters() ),
			$newId,
			$formatter->getIRCActionComment() // Used for IRC feeds
		);
	}

	/**
	 * Publishes the log entry.
	 * @param int $newId Id of the log entry.
	 * @param string $to One of: rcandudp (default), rc, udp
	 */
	public function publish( $newId, $to = 'rcandudp' ) {
		$log = new LogPage( $this->getType() );
		if ( $log->isRestricted() ) {
			return;
		}

		$rc = $this->getRecentChange( $newId );

		if ( $to === 'rc' || $to === 'rcandudp' ) {
			$rc->save( 'pleasedontudp' );
		}

		if ( $to === 'udp' || $to === 'rcandudp' ) {
			$rc->notifyRCFeeds();
		}
	}

	// LogEntry->

	public function getType() {
		return $this->type;
	}

	public function getSubtype() {
		return $this->subtype;
	}

	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @return User
	 */
	public function getPerformer() {
		return $this->performer;
	}

	/**
	 * @return Title
	 */
	public function getTarget() {
		return $this->target;
	}

	public function getTimestamp() {
		$ts = $this->timestamp !== null ? $this->timestamp : wfTimestampNow();

		return wfTimestamp( TS_MW, $ts );
	}

	public function getComment() {
		return $this->comment;
	}

	/**
	 * @since 1.25
	 * @return bool
	 */
	public function isLegacy() {
		return $this->legacy;
	}

	public function getDeleted() {
		return (int)$this->deleted;
	}
}
