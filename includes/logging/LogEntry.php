<?php
/**
 * Contain classes for dealing with individual log entries
 *
 * This is how I see the log system history:
 * - appending to plain wiki pages
 * - formatting log entries based on database fields
 * - user is now part of the action message
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
	 * @param $field Integer: one of LogPage::DELETED_* bitfield constants
	 * @return Boolean
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
	 */
	public function isLegacy() {
		return false;
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

		$conds = array();

		$joins = array(
			// IP's don't have an entry in user table
			'user' => array( 'LEFT JOIN', 'log_user=user_id' ),
		);

		return array(
			'tables' => $tables,
			'fields' => $fields,
			'conds'  => array(),
			'options' => array(),
			'join_conds' => $joins,
		);
	}

	/**
	 * Constructs new LogEntry from database result row.
	 * Supports rows from both logging and recentchanges table.
	 * @param $row
	 * @return DatabaseLogEntry
	 */
	public static function newFromRow( $row ) {
		if ( is_array( $row ) && isset( $row['rc_logid'] ) ) {
			return new RCDatabaseLogEntry( (object) $row );
		} else {
			return new self( $row );
		}
	}

	// Non-static->

	/// Database result row.
	protected $row;

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
			wfSuppressWarnings();
			$params = unserialize( $blob );
			wfRestoreWarnings();
			if ( $params !== false ) {
				$this->params = $params;
				$this->legacy = false;
			} else {
				$params = FormatJson::decode( $blob, true /* array */ );
				if ( $params !== null ) {
					$this->params = $params;
					$this->legacy = false;
				} else {
					$this->params = $blob === '' ? array() : explode( "\n", $blob );
					$this->legacy = true;
				}
			}
		}
		return $this->params;
	}

	public function getPerformer() {
		$userId = (int) $this->row->log_user;
		if ( $userId !== 0 ) {
			return User::newFromRow( $this->row );
		} else {
			$userText = $this->row->log_user_text;
			return User::newFromName( $userText, false );
		}
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
		$userId = (int) $this->row->rc_user;
		if ( $userId !== 0 ) {
			return User::newFromId( $userId );
		} else {
			$userText = $this->row->rc_user_text;
			// Might be an IP, don't validate the username
			return User::newFromName( $userText, false );
		}
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
	protected $type; ///!< @var string
	protected $subtype; ///!< @var string
	protected $parameters = array(); ///!< @var array
	protected $performer; ///!< @var User
	protected $target; ///!< @var Title
	protected $timestamp; ///!< @var string
	protected $comment; ///!< @var string
	protected $deleted; ///!< @var int

	public function __construct( $type, $subtype ) {
		$this->type = $type;
		$this->subtype = $subtype;
	}

	/**
	 * Set extra log parameters. 
	 * You can pass params to the log action message
	 * by prefixing the keys with a number and colon.
	 * The numbering should start with number 4, the
	 * first three parameters are hardcoded for every
	 * message. Example:
	 * $entry->setParameters(
	 *   '4:color' => 'blue',
	 *   'animal' => 'dog'
	 * );
	 * @param $parameters Associative array
	 */
	public function setParameters( $parameters ) {
		$this->parameters = $parameters;
	}

	public function setPerformer( User $performer ) {
		$this->performer = $performer;
	}

	public function setTarget( Title $target ) {
		$this->target = $target;
	}

	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	public function setComment( $comment ) {
		$this->comment = $comment;
	}

	public function setDeleted( $deleted ) {
		$this->deleted = $deleted;
	}

	/**
	 * Inserts the entry into the logging table.
	 * @return int If of the log entry
	 */
	public function insert() {
		global $wgLogRestrictions;

		$dbw = wfGetDB( DB_MASTER );
		$id = $dbw->nextSequenceValue( 'logging_log_id_seq' );

		if ( $this->timestamp === null ) {
			$this->timestamp = wfTimestampNow();
		}

		$data = array(
			'log_id' => $id,
			'log_type' => $this->getType(),
			'log_action' => $this->getSubtype(),
			'log_timestamp' => $dbw->timestamp( $this->getTimestamp() ),
			'log_user' => $this->getPerformer()->getId(),
			'log_user_text' => $this->getPerformer()->getName(),
			'log_namespace' => $this->getTarget()->getNamespace(),
			'log_title' => $this->getTarget()->getDBkey(),
			'log_page' => $this->getTarget()->getArticleId(),
			'log_comment' => $this->getComment(),
			'log_params' => serialize( (array) $this->getParameters() ),
		);
		$dbw->insert( 'logging', $data, __METHOD__ );
		$this->id = !is_null( $id ) ? $id : $dbw->insertId();
		return $this->id;
	}

	/**
	 * Publishes the log entry.
	 * @param $newId int id of the log entry.
	 * @param $to string: rcandudp (default), rc, udp
	 */
	public function publish( $newId, $to = 'rcandudp' ) {
		$log = new LogPage( $this->getType() );
		if ( $log->isRestricted() ) {
			return;
		}

		$formatter = LogFormatter::newFromEntry( $this );
		$context = RequestContext::newExtraneousContext( $this->getTarget() );
		$formatter->setContext( $context );

		$logpage = SpecialPage::getTitleFor( 'Log', $this->getType() );
		$user = $this->getPerformer();
		$rc = RecentChange::newLogEntry(
			$this->getTimestamp(),
			$logpage,
			$user,
			$formatter->getPlainActionText(), // Used for IRC feeds
			$user->isAnon() ? $user->getName() : '',
			$this->getType(),
			$this->getSubtype(),
			$this->getTarget(),
			$this->getComment(),
			serialize( (array) $this->getParameters() ),
			$newId
		);

		if ( $to === 'rc' || $to === 'rcandudp' ) {
			$rc->save();
		}

		if ( $to === 'udp' || $to === 'rcandudp' ) {
			$rc->notifyRC2UDP();
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

	public function getPerformer() {
		return $this->performer;
	}

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

	public function getDeleted() {
		return (int) $this->deleted;
	}

}