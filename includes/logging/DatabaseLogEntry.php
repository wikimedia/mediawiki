<?php
/**
 * Contains a class for dealing with database log entries
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
 * @license GPL-2.0-or-later
 * @since 1.19
 */

use Wikimedia\Rdbms\IDatabase;

/**
 * A value class to process existing log entries. In other words, this class caches a log
 * entry from the database and provides an immutable object-oriented representation of it.
 *
 * @since 1.19
 */
class DatabaseLogEntry extends LogEntryBase {

	/**
	 * Returns array of information that is needed for querying
	 * log entries. Array contains the following keys:
	 * tables, fields, conds, options and join_conds
	 *
	 * @return array
	 */
	public static function getSelectQueryData() {
		$commentQuery = CommentStore::getStore()->getJoin( 'log_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );

		$tables = array_merge(
			[ 'logging' ], $commentQuery['tables'], $actorQuery['tables'], [ 'user' ]
		);
		$fields = [
			'log_id', 'log_type', 'log_action', 'log_timestamp',
			'log_namespace', 'log_title', // unused log_page
			'log_params', 'log_deleted',
			'user_id', 'user_name', 'user_editcount',
		] + $commentQuery['fields'] + $actorQuery['fields'];

		$joins = [
			// IPs don't have an entry in user table
			'user' => [ 'LEFT JOIN', 'user_id=' . $actorQuery['fields']['log_user'] ],
		] + $commentQuery['joins'] + $actorQuery['joins'];

		return [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => [],
			'options' => [],
			'join_conds' => $joins,
		];
	}

	/**
	 * Constructs new LogEntry from database result row.
	 * Supports rows from both logging and recentchanges table.
	 *
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

	/**
	 * Loads a LogEntry with the given id from database
	 *
	 * @param int $id
	 * @param IDatabase $db
	 * @return DatabaseLogEntry|null
	 */
	public static function newFromId( $id, IDatabase $db ) {
		$queryInfo = self::getSelectQueryData();
		$queryInfo['conds'] += [ 'log_id' => $id ];
		$row = $db->selectRow(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			__METHOD__,
			$queryInfo['options'],
			$queryInfo['join_conds']
		);
		if ( !$row ) {
			return null;
		}
		return self::newFromRow( $row );
	}

	/** @var stdClass Database result row. */
	protected $row;

	/** @var User */
	protected $performer;

	/** @var array Parameters for log entry */
	protected $params;

	/** @var int A rev id associated to the log entry */
	protected $revId = null;

	/** @var bool Whether the parameters for this log entry are stored in new or old format. */
	protected $legacy;

	protected function __construct( $row ) {
		$this->row = $row;
	}

	/**
	 * Returns the unique database id.
	 *
	 * @return int
	 */
	public function getId() {
		return (int)$this->row->log_id;
	}

	/**
	 * Returns whatever is stored in the database field.
	 *
	 * @return string
	 */
	protected function getRawParameters() {
		return $this->row->log_params;
	}

	public function isLegacy() {
		// This extracts the property
		$this->getParameters();
		return $this->legacy;
	}

	public function getType() {
		return $this->row->log_type;
	}

	public function getSubtype() {
		return $this->row->log_action;
	}

	public function getParameters() {
		if ( !isset( $this->params ) ) {
			$blob = $this->getRawParameters();
			Wikimedia\suppressWarnings();
			$params = LogEntryBase::extractParams( $blob );
			Wikimedia\restoreWarnings();
			if ( $params !== false ) {
				$this->params = $params;
				$this->legacy = false;
			} else {
				$this->params = LogPage::extractParams( $blob );
				$this->legacy = true;
			}

			if ( isset( $this->params['associated_rev_id'] ) ) {
				$this->revId = $this->params['associated_rev_id'];
				unset( $this->params['associated_rev_id'] );
			}
		}

		return $this->params;
	}

	public function getAssociatedRevId() {
		// This extracts the property
		$this->getParameters();
		return $this->revId;
	}

	public function getPerformer() {
		if ( !$this->performer ) {
			$actorId = isset( $this->row->log_actor ) ? (int)$this->row->log_actor : 0;
			$userId = (int)$this->row->log_user;
			if ( $userId !== 0 || $actorId !== 0 ) {
				// logged-in users
				if ( isset( $this->row->user_name ) ) {
					$this->performer = User::newFromRow( $this->row );
				} elseif ( $actorId !== 0 ) {
					$this->performer = User::newFromActorId( $actorId );
				} else {
					$this->performer = User::newFromId( $userId );
				}
			} else {
				// IP users
				$userText = $this->row->log_user_text;
				$this->performer = User::newFromName( $userText, false );
			}
		}

		return $this->performer;
	}

	public function getTarget() {
		$namespace = $this->row->log_namespace;
		$page = $this->row->log_title;
		return Title::makeTitle( $namespace, $page );
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->log_timestamp );
	}

	public function getComment() {
		return CommentStore::getStore()->getComment( 'log_comment', $this->row )->text;
	}

	public function getDeleted() {
		return $this->row->log_deleted;
	}
}
